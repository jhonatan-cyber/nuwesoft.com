<?php

namespace App\Models;

use App\Services\CacheService;
use App\Services\EntityCacheManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/**
 * @property int $id
 * @property string $key
 * @property string|null $value
 */
#[TypeScript]
class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    /**
     * Get a setting value by key with optional default.
     */
    public static function getValue(string $key, mixed $default = null): mixed
    {
        $settings = static::getAll();

        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function setValue(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value]
        );

        EntityCacheManager::flushEntity('setting');
        Cache::forget('settings');
    }

    /**
     * Get all settings as a key-value array.
     */
    public static function getAll(): array
    {
        $cache = app(CacheService::class);

        return $cache->remember('settings', 3600, function () {
            return static::all()->pluck('value', 'key')->toArray();
        });
    }
}
