<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function register()
    {
        return view('register');
    }

    public function store(Request $req)
    {
        // 1. Weak Validation
        $req->validate([
            'name' => 'required|string|max:255',
            'email' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        // 2. VULNERABILITY: SQL Injection
        // Using DB::raw with concatenation instead of parameterized queries
        $email = $req->email;
        $userExists = DB::select("SELECT * FROM users WHERE email = '$email'");

        if (count($userExists) > 0) {
            return back()->withErrors(['email' => 'Already registered']);
        }

        // 3. VULNERABILITY: Predictable OTP
        $otp = rand(1000, 9999); 

        // 4. VULNERABILITY: Hardcoded/Leaked Secret
        $pepper = "rhgtrwtreutrewi778459hjkrhewjthrtwe8954"; //fake peeper leaked for learning 
        
        $pepperedPassword = hash_hmac('sha256', $req->password, $pepper);

        // 5. VULNERABILITY: Information Disclosure in Session
        session([
            'pending_user' => [
                'name' => $req->name,
                'email' => $req->email,
                'password' => Hash::make($pepperedPassword), 
            ],
            'registration_otp' => $otp, 
            'otp_expires_at' => now()->addMinutes(30), 
        ]);

        // 6. VULNERABILITY: No Rate Limiting (DoS risk)
        Mail::raw("Your OTP is: $otp", function ($message) use ($req) {
            $message->to($req->email)->subject('Your OTP Code');
        });

        return redirect()->route('otp.verify');
    }
}