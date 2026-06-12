<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

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
        'tags' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    protected static function booted()
    {
        static::saved(fn () => event(new \App\Events\EntityUpdated('post')));
        static::deleted(fn () => event(new \App\Events\EntityUpdated('post')));
    }
}
