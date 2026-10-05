<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent MIME type sniffing
        $response->header('X-Content-Type-Options', 'nosniff');

        // Clickjacking protection
        $response->header('X-Frame-Options', 'DENY');

        // XSS Protection
        $response->header('X-XSS-Protection', '1; mode=block');

        // Referrer Policy - don't send referrer info to external sites
        $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy - restrict browser features
        $response->header('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Content Security Policy - prevent XSS by restricting script sources
        // Only apply strict CSP in production, disable in development for Vite dev server
        if (app()->environment('production')) {
            $csp = "default-src 'self'; "
                . "script-src 'self' 'unsafe-inline'; "  // Bundled scripts only
                . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; "
                . "font-src 'self' data: https://fonts.gstatic.com; "
                . "img-src 'self' data: https:; "
                . "connect-src 'self'; "
                . "frame-ancestors 'none'; "
                . "base-uri 'self'; "
                . "form-action 'self';";

            $response->header('Content-Security-Policy', $csp);
        }
        // In development, CSP is disabled to allow Vite dev server and hot module reloading

        return $response;
    }
}
