<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function forget()
    {
        // VULNERABILITY: No Rate Limiting/CAPTCHA. 
        // Attacker can spam unlimited emails (Mail Bombing).
        return view('forgetpassword');
    }

    public function forgetpass(Request $req)
    {
        // VULNERABILITY: Still no rate limiting.
        // BUG FIX: Removed "return" before Password::sendResetLink to stop the white page.
        Password::sendResetLink($req->only('email'));
        
        // BUG FIX: Now properly redirects to the UI to show the green alert box
        return back()->with('status', 'Reset link sent');
    }

    public function showResetForm(Request $request, $token)
    {
        return view('passwordreset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        // VULNERABILITY: Weak Validation. Password complexity (regex/min length) is missing.
        $request->validate(['token' => 'required', 'email' => 'required', 'password' => 'required']);

        // BUG FIX: Removed "return" to prevent the white page crash.
        Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), 
            function ($user, $password) {
                // VULNERABILITY: Insecure hashing. No Pepper is used here.
                $user->password = Hash::make($password);
                $user->save();
            }
        );
        
        // BUG FIX: Moved this block INSIDE the function so it actually executes and redirects.
        return redirect()
            ->route('login')
            ->with('status', 'Password Reset Successful');
    }
}