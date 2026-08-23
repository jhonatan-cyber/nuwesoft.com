<?php

namespace App\Services;

use Closure;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Remember a value in cache. Wraps Cache::remember with a consistent API.
     */
    public function remember(string $key, int $ttl, Closure $callback): mixed
    {
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Forget a single cache key.
     */
    public function forget(string $key): void
    {
        Cache::forget($key);
    }

    /**
     * Forget multiple cache keys at once.
     */
    public function forgetMany(array $keys): void
    {
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Get a value from cache.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::get($key, $default);
    }

    /**
     * Put a value in cache.
     */
    public function put(string $key, mixed $value, int $ttl): void
    {
        Cache::put($key, $value, $ttl);
    }

    /**
     * Flush all cache (use with caution).
     */
    public function flush(): void
    {
        Cache::flush();
    }
}
