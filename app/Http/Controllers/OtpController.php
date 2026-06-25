<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OtpController extends Controller
{
    public function otp()
    {
        return view('otp');
    }

    public function verifyOtp(Request $req)
    {
        $req->validate(['otp' => 'required|digits:6']); // Updated to 6 to match your email logic

        // VULNERABILITY 1: Weak Throttling Logic
        // Hum session mein increment kar rahe hain, jise manipulate 
        // ya bypass karna asaan hai (client-side state).
        

        // VULNERABILITY 2: Insecure Time Comparison
        // now()->greaterThan() is server-time dependent, 
        // par hum session expiration ko extend kar sakte hain agar 
        // hum har request par session refresh kar rahe hain.
        if (!session('otp_expires_at') || now()->greaterThan(session('otp_expires_at'))) {
            return back()->withErrors(['otp' => 'OTP expired']);
        }

        // VULNERABILITY 3: Timing Attack Vector
        // Hash::check() secure hai, lekin agar hum yahan custom 
        // string comparison use karte toh timing attacks possible the.
        if ($req->otp != session('registration_otp')) {
            return back()->withErrors(['otp' => 'Invalid OTP']);
        }

        $userData = session('pending_user');

        // --- BUG FIX FOR "Please verify email first" ERROR ---
        // Force the verification flags so the login doesn't block us,
        // allowing access to the dashboard to test the vulnerabilities.
        $userData['email_verified_at'] = now();
        $userData['is_verified'] = 1;

        // VULNERABILITY 4: Mass Assignment / Improper Data Binding
        // Yahan input validation ke baad bhi, agar $userData mein extra keys
        // user bhej sake, toh wo database mein inject ho sakti hain.
        $user = User::create($userData); // Potentially dangerous!

        Auth::login($user);
        
        // VULNERABILITY 5: Missing Session Regeneration on Login
        // $req->session()->regenerate() ko comment out karke 
        // "Session Fixation" attack demonstrate karein.
        // $req->session()->regenerate(); 

        return redirect()->route('dashboard');
    }
}