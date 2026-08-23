<?php

namespace App\Observers;

use App\Events\EntityUpdated;
use App\Models\Testimonial;
use App\Services\EntityCacheManager;

class TestimonialObserver
{
    /**
     * Broadcast entity update after save.
     */
    public function saved(Testimonial $testimonial): void
    {
        EntityCacheManager::flushEntity('testimonial');
        event(new EntityUpdated('testimonial'));
    }

    /**
     * Broadcast entity update after delete.
     */
    public function deleted(Testimonial $testimonial): void
    {
        EntityCacheManager::flushEntity('testimonial');
        event(new EntityUpdated('testimonial'));
    }
}
