<?php

namespace App\Observers;

use App\Events\EntityUpdated;
use App\Models\Technology;
use App\Services\EntityCacheManager;

class TechnologyObserver
{
    /**
     * Flush technology caches and broadcast entity update after save.
     */
    public function saved(Technology $technology): void
    {
        self::flushCache();
        event(new EntityUpdated('technology'));
    }

    /**
     * Flush technology caches and broadcast entity update after delete.
     */
    public function deleted(Technology $technology): void
    {
        self::flushCache();
        event(new EntityUpdated('technology'));
    }

    /**
     * Flush all cache keys that depend on technology data.
     */
    public static function flushCache(): void
    {
        EntityCacheManager::flushEntity('technology');
    }
}
