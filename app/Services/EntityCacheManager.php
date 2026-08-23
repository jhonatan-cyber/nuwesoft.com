<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Central registry mapping entities to their cache keys.
 *
 * Instead of each observer maintaining its own list of keys to forget,
 * this class owns ALL cache key registrations and provides a single
 * flushEntity() call that observers use.
 *
 * Usage in observers:
 *   EntityCacheManager::flushEntity('project');
 *   EntityCacheManager::flushEntity('technology');
 */
class EntityCacheManager
{
    /**
     * Map of entity name → array of cache keys that depend on that entity.
     *
     * @var array<string, string[]>
     */
    protected static array $registry = [];

    /**
     * Register cache keys that belong to a specific entity.
     *
     * Call this once (e.g. from a config file or boot method) to declare
     * which keys should be flushed when the entity changes.
     */
    public static function register(string $entity, array $keys): void
    {
        static::$registry[$entity] = array_unique(
            array_merge(static::$registry[$entity] ?? [], $keys)
        );
    }

    /**
     * Flush all cache keys registered for the given entity.
     *
     * Also attempts Redis tag-based flush if the cache store supports it.
     */
    public static function flushEntity(string $entity): void
    {
        // Try tag-based flush first (Redis supports tags, file/database don't)
        if (method_exists(Cache::getStore(), 'tags')) {
            try {
                Cache::tags([$entity])->flush();
            } catch (\Throwable) {
                // Silently ignore if tags are not supported
            }
        }

        $keys = static::$registry[$entity] ?? [];
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Flush multiple entities at once.
     */
    public static function flushEntities(array $entities): void
    {
        foreach ($entities as $entity) {
            static::flushEntity($entity);
        }
    }

    /**
     * Get all registered cache keys for an entity (useful for testing/debugging).
     *
     * @return string[]
     */
    public static function getKeys(string $entity): array
    {
        return static::$registry[$entity] ?? [];
    }

    /**
     * Get the full registry (useful for debugging).
     *
     * @return array<string, string[]>
     */
    public static function getRegistry(): array
    {
        return static::$registry;
    }

    /**
     * Reset the registry (useful for testing).
     */
    public static function reset(): void
    {
        static::$registry = [];
    }
}
