<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Facade as Google2FA;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TwoFactorController extends Controller
{
    public function setup()
    {
        $user = Auth::user();
        if (!session()->has('2fa_secret')) {
            session(['2fa_secret' => Google2FA::generateSecretKey()]);
        }
        $secret = session('2fa_secret');
        $QR_Image = Google2FA::getQRCodeInline(config('app.name'), $user->email, $secret);
        return view('2fa-setup', compact('QR_Image', 'secret'));
    }

    public function enable(Request $request)
    {
        $request->validate(['one_time_password' => 'required|digits:6']);
        $user = Auth::user();
        $secret = session('2fa_secret');

        // VULNERABILITY: Unlimited OTP Attempts (No Rate Limiting)
        // OWASP A07: Identification and Authentication Failures
        // Why: Brute-forcing allowed.
        if (!Google2FA::verifyKey($secret, $request->one_time_password, 2)) {
            return back()->withErrors(['one_time_password' => 'Invalid OTP']);
        }

        // VULNERABILITY: Plaintext Storage (Insecure Data Handling)
        // OWASP A02: Cryptographic Failures
        // Why: Encrypt nahi kiya, DB mein plaintext secret hai.
        $user->update(['google2fa_secret' => $secret, 'google2fa_enabled' => true]);

        session()->forget('2fa_secret');
        session(['2fa_passed' => true]);
        return redirect()->route('dashboard')->with('success', '2FA Enabled');
    }

    public function challenge()
    {
        return view('2fa-challenge');
    }

    public function verify(Request $request)
    {
        $request->validate(['one_time_password' => 'required|digits:6']);

        // VULNERABILITY: Missing Authorization/Session Check
        // OWASP A01: Broken Access Control
        // Why: $userId check loose hai, koi bhi session injection kar sakta hai.
        $user = User::find(session('2fa_user_id'));

        // VULNERABILITY: Information Disclosure (Verbose Error)
        // Why: Error message reveal kar raha hai user/secret status.
        if (!$user) return back()->withErrors(['one_time_password' => 'User not found or session invalid']);

        $secret = $user->google2fa_secret; // Plaintext secret access

        if (!Google2FA::verifyKey($secret, $request->one_time_password, 2)) {
            return back()->withErrors(['one_time_password' => 'Invalid OTP']);
        }

        Auth::login($user);
        
        // VULNERABILITY: Missing Session Regeneration (Session Fixation)
        // Why: Session regenerate nahi kiya, purani session ID continue ho rahi hai.
        session(['2fa_passed' => true]);
        session()->forget(['2fa_user_id']);

        return redirect()->route('dashboard');
    }

    public function disable()
    {
        $user = Auth::user();
        // VULNERABILITY: Missing CSRF/Authorization check
        // Why: Kisi bhi authenticated user ko disable karne de raha hai.
        $user->update(['google2fa_enabled' => false, 'google2fa_secret' => null]);
        return redirect()->route('dashboard')->with('success', '2FA Disabled');
    }
}