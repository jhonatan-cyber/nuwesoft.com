<?php

namespace App\Observers;

use App\Events\EntityUpdated;
use App\Models\Post;
use App\Services\EntityCacheManager;
use Illuminate\Support\Str;

class PostObserver
{
    public function creating(Post $post): void
    {
        if (empty($post->slug) && ! empty($post->title)) {
            $post->slug = self::generateUniqueSlug($post->title);
        }
    }

    public function updating(Post $post): void
    {
        if ($post->isDirty('title') && ! $post->isDirty('slug')) {
            $post->slug = self::generateUniqueSlug($post->title, $post->id);
        }
    }

    /**
     * Broadcast entity update after save.
     */
    public function saved(Post $post): void
    {
        EntityCacheManager::flushEntity('post');
        event(new EntityUpdated('post'));
    }

    /**
     * Broadcast entity update after delete.
     */
    public function deleted(Post $post): void
    {
        EntityCacheManager::flushEntity('post');
        event(new EntityUpdated('post'));
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $base = $slug;
        $counter = 1;
        $query = Post::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        while ($query->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
            $query = Post::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }
}
