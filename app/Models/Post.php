<?php

namespace App\Models;

use App\Enums\PostCategory;
use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, LogActivity;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category',
        'cover_image',
        'tags',
        'is_published',
        'published_at',
        'author_name',
    ];

    protected $casts = [
        'category' => PostCategory::class,
        'tags' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function setAttribute($key, $value): static
    {
        if ($key === 'category') {
            if ($value instanceof PostCategory) {
                $value = $value->value;
            } elseif (is_string($value)) {
                $value = strtolower($value);
            }
        }

        return parent::setAttribute($key, $value);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    // boot() logic moved to PostObserver
}
