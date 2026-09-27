<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Vite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
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
        // Per-request nonce for inline scripts. It is shared with Blade
        // (app.blade.php), with Vite's generated tags and with Inertia pages
        // (see HandleInertiaRequests), so 'unsafe-inline' can be dropped.
        $nonce = base64_encode(random_bytes(16));
        $request->attributes->set('csp_nonce', $nonce);
        View::share('cspNonce', $nonce);
        Vite::useCspNonce($nonce);

        $response = $next($request);

        // Content Security Policy
        $isLocal = app()->environment('local');

        if ($isLocal) {
            $requestHost = $request->getHost();
            $devCsp = " http://localhost:* ws://localhost:* http://{$requestHost}:* ws://{$requestHost}:*";
        } else {
            $devCsp = '';
        }

        // 'unsafe-eval' only in local (vue-i18n compiler needs eval in dev mode)
        // Production uses runtime-only build (no eval needed)
        $evalSrc = $isLocal ? " 'unsafe-eval'" : '';

        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            // Script hosts are an explicit allowlist: Cloudflare's Web Analytics
            // beacon and PostHog's lazily loaded extensions/remote config. Nothing
            // loads scripts from cdn.jsdelivr.net (its devicon SVGs are images),
            // so it was dropped from script-src.
            "script-src 'self' 'nonce-{$nonce}'{$evalSrc} https://static.cloudflareinsights.com https://us-assets.i.posthog.com https://eu-assets.i.posthog.com{$devCsp}; " .
            "style-src 'self' 'nonce-{$nonce}' https://fonts.googleapis.com https://cdn.jsdelivr.net; " .
            // Style *attributes* stay allowed (Vue's static style="..." bindings and
            // third-party components), but <style> elements must now be first-party
            // or nonced: injected stylesheet blocks are blocked outright.
            "style-src-attr 'unsafe-inline'; " .
            "img-src 'self' data: blob: https: https://us-assets.i.posthog.com https://eu-assets.i.posthog.com; " .
            "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net; " .
            "connect-src 'self' wss: https://res.cloudinary.com https://us-assets.i.posthog.com https://us.i.posthog.com https://eu-assets.i.posthog.com https://eu.i.posthog.com{$devCsp}; " .
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
            'camera=(), microphone=(), geolocation=(), payment=(), usb=()'
        );

        return $response;
    }
}
