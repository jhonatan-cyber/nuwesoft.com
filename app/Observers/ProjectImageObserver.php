<?php

namespace App\Observers;

use App\Events\EntityUpdated;
use App\Models\ProjectImage;
use Illuminate\Support\Facades\Cache;

class ProjectImageObserver
{
    /**
     * Flush project caches and broadcast entity update after save.
     */
    public function saved(ProjectImage $image): void
    {
        Cache::forget('active_projects_with_relations');
        event(new EntityUpdated('project'));
    }

    /**
     * Flush project caches and broadcast entity update after delete.
     */
    public function deleted(ProjectImage $image): void
    {
        Cache::forget('active_projects_with_relations');
        event(new EntityUpdated('project'));
    }
}
