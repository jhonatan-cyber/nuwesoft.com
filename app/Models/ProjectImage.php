<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectImage extends Model
{
    protected $fillable = [
        'project_id',
        'image_url',
        'url',
        'public_id',
        'order_index',
        'alt',
    ];

    protected $casts = [
        'order_index' => 'integer',
    ];

    protected $appends = [
        'optimized_image_url',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index');
    }

    /**
     * Blur placeholder URL para lazy loading (w_20 + e_blur) si es Cloudinary.
     */
    protected function blurImageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $url = $this->image_url;
                if (! $url || ! str_contains($url, 'res.cloudinary.com')) {
                    return $url;
                }

                return str_replace('/upload/', '/upload/w_20,e_blur:1000,q_auto,f_auto/', $url);
            }
        );
    }

    // boot() logic moved to ProjectImageObserver

    /**
     * Alias para compatibilidad con tests y frontend que usan `url`.
     * Mapea `url` <-> `image_url` sin requerir columna DB.
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image_url,
            set: fn (?string $value) => ['image_url' => $value],
        );
    }

    protected function optimizedImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->optimizeCloudinaryUrl($this->image_url)
        );
    }

    private function optimizeCloudinaryUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        if (! str_contains($url, 'res.cloudinary.com')) {
            return $url;
        }

        return str_replace('/upload/', '/upload/f_auto,q_auto/', $url);
    }
}
