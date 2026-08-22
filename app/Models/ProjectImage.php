<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectImage extends Model
{
    protected $fillable = [
        'project_id',
        'image_url',
        'public_id',
        'order_index',
    ];

    protected $appends = [
        'optimized_image_url',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // boot() logic moved to ProjectImageObserver

    protected function optimizedImageUrl(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
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
