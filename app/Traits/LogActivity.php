<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogActivity
{
    public static function bootLogActivity(): void
    {
        static::created(function ($model) {
            ActivityLog::created($model);
        });

        static::updated(function ($model) {
            ActivityLog::updated($model);
        });

        static::deleted(function ($model) {
            ActivityLog::deleted($model);
        });
    }
}
