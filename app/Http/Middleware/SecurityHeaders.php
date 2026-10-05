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

        // Cache control headers for performance
        if ($request->isMethodSafe() && !$request->routeIs('*.show', '*.index')) {
            // Cache static assets for 1 year
            $path = $request->path();
            if ($path !== '/' && (str_contains($path, 'build/') || str_contains($path, 'storage/') || str_contains($path, 'images/'))) {
                $response->header('Cache-Control', 'public, max-age=31536000, immutable');
            }
        }

        // Cache homepage and article lists for 1 hour (only if not authenticated)
        if ($request->routeIs('home', 'blog.index', 'blog.category', 'blog.tag')) {
            if (!$request->user() && !$response->headers->hasCookie('XSRF-TOKEN')) {
                $response->header('Cache-Control', 'public, max-age=3600, s-maxage=3600');
            } else {
                $response->header('Cache-Control', 'private, max-age=3600');
            }
            $response->header('Vary', 'Accept-Encoding, Cookie');
        }

        // Cache individual articles for 24 hours (only if not authenticated)
        if ($request->routeIs('blog.show')) {
            if (!$request->user() && !$response->headers->hasCookie('XSRF-TOKEN')) {
                $response->header('Cache-Control', 'public, max-age=86400, s-maxage=86400');
            } else {
                $response->header('Cache-Control', 'private, max-age=86400');
            }
            $response->header('Vary', 'Accept-Encoding, Cookie');
        }

        // Don't cache dynamic/personalized pages
        if ($request->routeIs('admin.*', '*.edit', '*.create', '*.store', '*.update', '*.destroy')) {
            $response->header('Cache-Control', 'no-cache, no-store, must-revalidate, private');
            $response->header('Pragma', 'no-cache');
        }

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
