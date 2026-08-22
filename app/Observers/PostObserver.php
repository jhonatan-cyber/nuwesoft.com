<?php

namespace App\Observers;

use App\Events\EntityUpdated;
use App\Models\Post;

class PostObserver
{
    /**
     * Broadcast entity update after save.
     */
    public function saved(Post $post): void
    {
        event(new EntityUpdated('post'));
    }

    /**
     * Broadcast entity update after delete.
     */
    public function deleted(Post $post): void
    {
        event(new EntityUpdated('post'));
    }
}
