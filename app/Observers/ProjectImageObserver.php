<?php

namespace App\Observers;

use App\Events\EntityUpdated;
use App\Models\ProjectImage;

class ProjectImageObserver
{
    /**
     * Flush project caches and broadcast entity update after save.
     */
    public function saved(ProjectImage $image): void
    {
        ProjectObserver::flushCache();
        event(new EntityUpdated('project'));
    }

    /**
     * Flush project caches and broadcast entity update after delete.
     */
    public function deleted(ProjectImage $image): void
    {
        ProjectObserver::flushCache();
        event(new EntityUpdated('project'));
    }
}
