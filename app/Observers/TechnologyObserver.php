<?php

namespace App\Observers;

use App\Events\EntityUpdated;
use App\Models\Technology;
use Illuminate\Support\Facades\Cache;

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
        $keys = [
            'active_technologies',
            'active_technologies_servicios',
            'active_projects_with_relations',
            'dashboard.active_technologies',
            'dashboard.total_technologies',
            'dashboard.tech_by_category',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}
