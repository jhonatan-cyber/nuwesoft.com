<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class AddRateLimitHeaders
{
    /**
     * Re-add rate limit headers to every response.
     *
     * Laravel's ThrottleRequests already sets these on every response,
     * but Cloudflare strips them from non-429 responses. This middleware
     * runs after the route middleware and re-adds them so clients can
     * see their remaining quota and self-throttle.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // If ThrottleRequests already added headers (e.g. 429), don't override
        if ($response->headers->has('X-RateLimit-Limit')) {
            $this->exposeHeaders($response);
            return $response;
        }

        // Find the active throttle limiter for this route
        $limiterName = $this->resolveLimiterName($request);

        if (! $limiterName) {
            return $response;
        }

        $maxAttempts = $this->getMaxAttempts($limiterName, $request);
        $key = $this->resolveKey($limiterName, $request);
        $remaining = RateLimiter::remaining($key, $maxAttempts);

        $response->headers->set('X-RateLimit-Limit', (string) $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', (string) max(0, $remaining));

        if ($remaining === 0) {
            $retryAfter = RateLimiter::availableIn($key, $request);
            $response->headers->set('X-RateLimit-Reset', (string) now()->addSeconds($retryAfter)->timestamp);
            $response->headers->set('Retry-After', (string) $retryAfter);
        }

        $this->exposeHeaders($response);

        return $response;
    }

    /**
     * Add CORS expose headers so browser JS can read rate limit headers.
     */
    private function exposeHeaders(Response $response): void
    {
        $existing = $response->headers->get('Access-Control-Expose-Headers', '');
        $needed = ['X-RateLimit-Limit', 'X-RateLimit-Remaining', 'X-RateLimit-Reset', 'Retry-After'];
        $toExpose = array_filter($needed, fn ($h) => ! str_contains($existing, $h));

        if ($toExpose) {
            $all = trim($existing . ', ' . implode(', ', $toExpose), ', ');
            $response->headers->set('Access-Control-Expose-Headers', $all);
        }
    }

    /**
     * Resolve the rate limiter name from the route's middleware.
     */
    private function resolveLimiterName(Request $request): ?string
    {
        $route = $request->route();

        if (! $route) {
            return null;
        }

        foreach ($route->gatherMiddleware() as $middleware) {
            if (is_string($middleware) && str_starts_with($middleware, 'throttle:')) {
                $param = substr($middleware, strlen('throttle:'));

                // Skip raw numeric limits like "throttle:3,1" or "throttle:60,1"
                // These use inline limits, not named limiters
                if (preg_match('/^\d+(,\d+)?$/', $param)) {
                    return null;
                }

                return $param;
            }
        }

        return null;
    }

    /**
     * Get max attempts from the limiter definition.
     */
    private function getMaxAttempts(string $limiterName, Request $request): int
    {
        $limiter = RateLimiter::limiter($limiterName);

        if (! $limiter) {
            return 60;
        }

        $limit = $limiter($request);

        if ($limit instanceof \Illuminate\Cache\RateLimiting\Limit) {
            return $limit->maxAttempts;
        }

        return 60;
    }

    /**
     * Resolve the rate limiter key from the limiter definition.
     */
    private function resolveKey(string $limiterName, Request $request): string
    {
        $limiter = RateLimiter::limiter($limiterName);

        if (! $limiter) {
            return $request->ip();
        }

        $limit = $limiter($request);

        if ($limit instanceof \Illuminate\Cache\RateLimiting\Limit) {
            return $limit->key;
        }

        return $request->ip();
    }
}
