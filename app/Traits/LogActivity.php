<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogActivity
{
    public static function bootLogActivity(): void
    {
        static::created(function ($model) {
            ActivityLog::logCreated($model);
        });

        static::updated(function ($model) {
            ActivityLog::logUpdated($model);
        });

        static::deleted(function ($model) {
            ActivityLog::logDeleted($model);
        });
    }
}
