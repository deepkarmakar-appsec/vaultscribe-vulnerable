<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Raw DB query ke liye
use App\Models\User;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    public function log()
    {
        return view('login');
    }
    
    public function logstore(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        /*
        |--------------------------------------------------------------------------
        | VULNERABILITY: SQL INJECTION (SQLi)
        | OWASP A03: Injection
        | Why: User input '$req->email' ko directly SQL query string mein concatenate 
        | kiya gaya hai bina parameter binding ke.
        | Impact: Attacker email field mein payload jaise `' OR '1'='1` daal kar 
        | bina password ke login kar sakta hai (Authentication Bypass).
        |--------------------------------------------------------------------------
        */
        $email = $req->email;
        $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $results = DB::select($query);
        $user = !empty($results) ? (object) $results[0] : null;

        // VULNERABILITY: Username Enumeration
        if (!$user) {
            return back()->withErrors(['email' => 'No account found with this email.']);
        }

        // VULNERABILITY: Missing/Weak Brute Force Protection
        if (!Hash::check($req->password, $user->password)) {
           
            return back()->withErrors(['email' => 'Incorrect password.']);
        }

        if (!$user->email_verified_at) {
            return back()->withErrors(['email' => 'Please verify email first']);
        }

        // VULNERABILITY: Session Fixation
        // OWASP A01: Broken Access Control
        // Why: Login ke baad $req->session()->regenerate() call nahi kiya gaya.
        Auth::loginUsingId($user->id);

        if ($user->google2fa_enabled) {
            session(['2fa_user_id' => $user->id]);
            return redirect()->route('2fa.challenge');
        }

       
        return redirect()->route('dashboard');
    }
}