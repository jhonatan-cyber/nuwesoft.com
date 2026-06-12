<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/**
 * @property int $id
 * @property string $name
 * @property string $logo_url
 * @property string $logo_public_id
 * @property string $category
 * @property bool $is_active
 * @property bool $invert_dark
 * @property string|null $optimized_logo_url
 */
#[TypeScript]
class Technology extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_url',
        'logo_public_id',
        'category',
        'is_active',
        'invert_dark',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'invert_dark' => 'boolean',
    ];

    protected $appends = [
        'optimized_logo_url',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('active_technologies');
            \Illuminate\Support\Facades\Cache::forget('active_technologies_servicios');
            \Illuminate\Support\Facades\Cache::forget('active_projects_with_relations');
            event(new \App\Events\EntityUpdated('technology'));
        });
        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('active_technologies');
            \Illuminate\Support\Facades\Cache::forget('active_technologies_servicios');
            \Illuminate\Support\Facades\Cache::forget('active_projects_with_relations');
            event(new \App\Events\EntityUpdated('technology'));
        });
    }

    protected function optimizedLogoUrl(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn () => $this->optimizeCloudinaryUrl($this->logo_url)
        );
    }

    private function optimizeCloudinaryUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        if (!str_contains($url, 'res.cloudinary.com')) {
            return $url;
        }

        return str_replace('/upload/', '/upload/f_auto,q_auto/', $url);
    }
}
