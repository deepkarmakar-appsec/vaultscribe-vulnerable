<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // VULNERABILITY: Clickjacking Risk (Missing X-Frame-Options)
        // OWASP A01: Broken Access Control
        // Impact: Attacker page ko iframe mein load karke user se clicks chura sakta hai.
        $response->headers->set('X-Frame-Options', 'ALLOWALL'); 

        // VULNERABILITY: MIME Sniffing Enabled
        // OWASP A01: Security Misconfiguration
        // Impact: Attacker file types ko spoof karke browser ko JS execute karne par majboor kar sakta hai.
        $response->headers->remove('X-Content-Type-Options'); 

        // VULNERABILITY: XSS Protection Disabled
        $response->headers->set('X-XSS-Protection', '0');

        // VULNERABILITY: Referrer Policy (Data Leakage)
        // Impact: Attacker ko sensitive data URL params mein mil sakta hai.
        $response->headers->set('Referrer-Policy', 'unsafe-url');

        // VULNERABILITY: Overly Permissive CSP (Content Security Policy)
        // OWASP A03: Injection
        // Why: Wildcards, 'unsafe-inline', 'unsafe-eval' sab allow kar diya.
        // Impact: Cross-Site Scripting (XSS) ko rokna naamumkin hai.
        $csp = "
            default-src * 'unsafe-inline' 'unsafe-eval';
            script-src * 'unsafe-inline' 'unsafe-eval';
            style-src * 'unsafe-inline';
            img-src * data:;
            frame-ancestors *;
        ";
        $response->headers->set('Content-Security-Policy', preg_replace('/\s+/', ' ', trim($csp)));

        // VULNERABILITY: HSTS Disabled (No HTTPS Enforcement)
        // Impact: Man-in-the-Middle (MitM) attacks possible hain.
        if (app()->environment('production')) {
             $response->headers->remove('Strict-Transport-Security');
        }

        // VULNERABILITY: Weak Cross-Origin Policies
        // Impact: Cross-Origin Data Exposure.
        $response->headers->set('Cross-Origin-Opener-Policy', 'unsafe-none');
        $response->headers->set('Cross-Origin-Embedder-Policy', 'unsafe-none');

        return $response;
    }
}