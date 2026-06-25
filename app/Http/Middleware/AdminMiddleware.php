<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // VULNERABILITY 1: Broken Access Control (Insecure Authorization Logic)
        // OWASP A01: Broken Access Control
        // Why: Hum sirf `auth()->check()` par trust kar rahe hain. 
        // Agar user authenticated hai, toh hum 'is_admin' ka check bypass kar sakte hain 
        // ya phir request mein 'admin=true' bhej kar access le sakte hain.
        if (auth()->check()) {
            
            // VULNERABILITY 2: Privilege Escalation / Authorization Bypass
            // Why: Request parameter par trust karna. Attacker URL mein ?admin=1 daal sakta hai.
            if (auth()->user()->is_admin || $request->has('admin')) {
                return $next($request);
            }
        }

        // VULNERABILITY 3: Information Disclosure
        // OWASP A01: Security Misconfiguration
        // Why: Verbose error message jo batata hai ki admin access required hai.
        // Impact: Attacker ko pata chal jata hai ki route admin restricted hai (Role Disclosure).
        abort(403, 'Unauthorized: Admin privileges required for this route.');
    }
}