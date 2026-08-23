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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $isLocal = app()->environment('local');
        $isProduction = app()->environment('production');

        // ── Content Security Policy ──
        $response->headers->set('Content-Security-Policy', $this->buildCsp($isLocal, $isProduction, $request));

        // ── Prevent MIME-type sniffing ──
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // ── Prevent clickjacking ──
        $response->headers->set('X-Frame-Options', 'DENY');

        // ── Enable browser XSS filter (legacy, but still useful for older browsers) ──
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // ── Referrer policy ──
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // ── HTTP Strict Transport Security (production only) ──
        if ($isProduction) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // ── Permissions Policy ──
        $response->headers->set('Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=(), usb=()'
        );

        return $response;
    }

    /**
     * Build the Content-Security-Policy header value.
     *
     * Production policy is stricter: no unsafe-eval, upgrade-insecure-requests, reporting.
     * Development policy is relaxed to allow Vite HMR and local tools.
     */
    protected function buildCsp(bool $isLocal, bool $isProduction, Request $request): string
    {
        $devHosts = '';
        if ($isLocal) {
            $host = $request->getHost();
            $devHosts = " http://localhost:* ws://localhost:* http://{$host}:* ws://{$host}:*";
        }

        // ── Script sources ──
        // Production: no unsafe-eval (Vite prod builds don't need it)
        // Dev: unsafe-eval for Vue devtools and Vite HMR
        $scriptSrc = $isProduction
            ? "'self' 'unsafe-inline' https://cdn.jsdelivr.net https://static.cloudflareinsights.com https://us-assets.i.posthog.com https://eu-assets.i.posthog.com"
            : "'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://static.cloudflareinsights.com https://us-assets.i.posthog.com https://eu-assets.i.posthog.com{$devHosts}";

        // ── Style sources ──
        $styleSrc = "'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net";

        // ── Image sources ──
        $imgSrc = "'self' data: blob: https: https://res.cloudinary.com https://cdn.jsdelivr.net https://picsum.photos https://us-assets.i.posthog.com https://eu-assets.i.posthog.com";

        // ── Font sources ──
        $fontSrc = "'self' https://fonts.gstatic.com https://cdn.jsdelivr.net";

        // ── Connect sources (API, WebSocket, analytics) ──
        $connectSrc = "'self' wss: https://res.cloudinary.com https://us-assets.i.posthog.com https://us.i.posthog.com https://eu-assets.i.posthog.com https://eu.i.posthog.com{$devHosts}";

        // ── Directives ──
        $directives = [
            "default-src 'self'",
            "script-src {$scriptSrc}",
            "style-src {$styleSrc}",
            "img-src {$imgSrc}",
            "font-src {$fontSrc}",
            "connect-src {$connectSrc}",
            "frame-src 'self' https://challenges.cloudflare.com https://*.cloudflare.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ];

        // ── Production-only directives ──
        if ($isProduction) {
            // Force HTTPS for all sub-resources
            $directives[] = 'upgrade-insecure-requests';

            // Report CSP violations to our endpoint
            $reportUri = url('/csp-report');
            $directives[] = "report-uri {$reportUri}";
            $directives[] = 'report-to csp-endpoint';
        }

        return implode('; ', $directives) . ';';
    }
}
