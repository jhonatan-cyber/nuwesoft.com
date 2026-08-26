<?php

namespace App\Observers;

use App\Events\EntityUpdated;
use App\Models\Project;
use App\Services\EntityCacheManager;
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
        if (empty($project->slug) || ($project->isDirty('name') && ! $project->isDirty('slug'))) {
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
     * Delegates to EntityCacheManager which owns the central key registry.
     */
    public static function flushCache(): void
    {
        EntityCacheManager::flushEntity('project');
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
