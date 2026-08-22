<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventHtmlCache
{
    /**
     * Prevent Cloudflare and browsers from caching HTML pages.
     * This ensures users always get fresh Vite chunk references after deploys.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Only apply to HTML responses (Inertia pages, not API/JSON/XML)
        $contentType = $response->headers->get('Content-Type', '');
        if (str_contains($contentType, 'text/html')) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
            // Tell Cloudflare specifically not to cache
            $response->headers->set('CDN-Cache-Control', 'no-store');
            $response->headers->set('Surrogate-Control', 'no-store');
        }

        return $response;
    }
}
