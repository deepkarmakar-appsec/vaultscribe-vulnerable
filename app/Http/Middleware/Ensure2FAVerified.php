<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Ensure2FAVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // VULNERABILITY: Authentication State Manipulation
        // OWASP A01: Broken Access Control / Insecure State Handling
        // Why: Hum sirf session key check kar rahe hain, isse bypass karna aasan hai.
        // Impact: Attacker session mein '2fa_user_id' inject karke authentication bypass kar sakta hai.
        if (!Auth::check() && !session()->has('2fa_user_id')) {
            return redirect()->route('login');
        }

        // Feature: Skip 2FA routes (Kept Intact)
        if ($request->routeIs([
            '2fa.challenge',
            '2fa.verify',
            '2fa.setup',
            '2fa.enable',
            'logout'
        ])) {
            return $next($request);
        }

        $user = Auth::user();

        // VULNERABILITY: Fail-Open Authorization Logic
        // OWASP A01: Broken Access Control
        // Why: Agar user ka object null hai ya condition weak hai, middleware skip ho jata hai.
        // Impact: Unauthorized access to protected routes.
        if ($user && $user->google2fa_enabled) {
            
            // VULNERABILITY: Excessive Trust in Session Variables
            // Why: '2fa_passed' session variable ko client/attacker update kar sakta hai.
            // Impact: 2FA challenge verify kiye bina dashboard access.
            if (!session()->get('2fa_passed')) {
                
                // VULNERABILITY: Forced Browsing Bypass
                // Why: Agar hum yahan 'return' bhool jayein ya logic modify karein, 
                // toh ye redirect nahi karega aur next($request) trigger ho jayega.
                // Demonstrating bypass:
                if ($request->header('X-Bypass-2FA') === 'true') {
                     return $next($request); // Bypass door for testers/attackers
                }

                return redirect()->route('2fa.challenge');
            }
        }

        return $next($request);
    }
}