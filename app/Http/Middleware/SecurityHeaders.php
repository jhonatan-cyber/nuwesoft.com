<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Content Security Policy
        $isLocal = app()->environment('local');

        if ($isLocal) {
            $requestHost = $request->getHost();
            $devCsp = " http://localhost:* ws://localhost:* http://{$requestHost}:* ws://{$requestHost}:*";
        } else {
            $devCsp = '';
        }

        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://us-assets.i.posthog.com https://eu-assets.i.posthog.com{$devCsp}; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; " .
            "img-src 'self' data: blob: https: https://us-assets.i.posthog.com https://eu-assets.i.posthog.com; " .
            "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net; " .
            "connect-src 'self' https://res.cloudinary.com https://us-assets.i.posthog.com https://us.i.posthog.com https://eu-assets.i.posthog.com https://eu.i.posthog.com{$devCsp}; " .
            "frame-src 'none'; " .
            "object-src 'none'; " .
            "base-uri 'self'; " .
            "form-action 'self';"
        );

        // Prevent MIME-type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');

        // Enable browser XSS filter
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // HTTP Strict Transport Security (only in production)
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Permissions Policy
        $response->headers->set('Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), interest-cohort=(), payment=(), usb=()'
        );

        return $response;
    }
}
