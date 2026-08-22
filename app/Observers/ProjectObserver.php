<?php

namespace App\Observers;

use App\Events\EntityUpdated;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ProjectObserver
{
    /**
     * Auto-generate slug before creating a project.
     */
    public function creating(Project $project): void
    {
        if (empty($project->slug)) {
            $project->slug = self::generateUniqueSlug($project->name);
        }
    }

    /**
     * Regenerate slug when name changes (unless slug was explicitly set).
     */
    public function updating(Project $project): void
    {
        if ($project->isDirty('name') && ! $project->isDirty('slug')) {
            $project->slug = self::generateUniqueSlug($project->name, $project->id);
        }
    }

    /**
     * Flush project caches and broadcast entity update after save.
     */
    public function saved(Project $project): void
    {
        self::flushCache();
        event(new EntityUpdated('project'));
    }

    /**
     * Flush project caches and broadcast entity update after delete.
     */
    public function deleted(Project $project): void
    {
        self::flushCache();
        event(new EntityUpdated('project'));
    }

    /**
     * Flush all cache keys that depend on project data.
     */
    public static function flushCache(): void
    {
        $keys = [
            'active_projects_with_relations',
            'dashboard.active_projects',
            'dashboard.total_projects',
            'dashboard.projects_by_category',
            'dashboard.recent_projects',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Generate a unique slug from the given name.
     */
    public static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $baseSlug = $slug;
        $counter = 1;

        $query = Project::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
            $query = Project::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }
}
