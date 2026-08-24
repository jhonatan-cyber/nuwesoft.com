<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Observers\ProjectObserver;
use App\Services\EntityCacheManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ProjectObserverTest extends TestCase
{
    use RefreshDatabase;

    // ── Slug Generation (via static method) ──

    public function test_generate_unique_slug_from_name(): void
    {
        $slug = ProjectObserver::generateUniqueSlug('My Awesome Project');
        $this->assertEquals('my-awesome-project', $slug);
    }

    public function test_generate_unique_slug_handles_special_characters(): void
    {
        $slug = ProjectObserver::generateUniqueSlug('Project #1: ¡Hola Mundo!');
        $this->assertEquals('project-1-hola-mundo', $slug);
    }

    public function test_generate_unique_slug_avoids_existing(): void
    {
        // Insert a project directly with a known slug to simulate an existing one
        Project::create([
            'name' => 'Existing',
            'slug' => 'existing',
            'category' => 'web',
            'desc' => 'Description',
        ]);

        $slug = ProjectObserver::generateUniqueSlug('Existing');
        $this->assertEquals('existing-1', $slug);
    }

    public function test_generate_unique_slug_handles_multiple_duplicates(): void
    {
        Project::create(['name' => 'Triple', 'slug' => 'triple', 'category' => 'web', 'desc' => '1']);
        Project::create(['name' => 'Triple', 'slug' => 'triple-1', 'category' => 'web', 'desc' => '2']);

        $slug = ProjectObserver::generateUniqueSlug('Triple');
        $this->assertEquals('triple-2', $slug);
    }

    public function test_generate_unique_slug_ignores_specific_id(): void
    {
        $project = Project::create([
            'name' => 'Existing',
            'slug' => 'existing',
            'category' => 'web',
            'desc' => 'Description',
        ]);

        // When ignoring the same project's ID, the slug is unique
        $slug = ProjectObserver::generateUniqueSlug('Existing', $project->id);
        $this->assertEquals('existing', $slug);
    }

    public function test_generate_unique_slug_returns_empty_for_empty_name(): void
    {
        $slug = ProjectObserver::generateUniqueSlug('');
        $this->assertEquals('', $slug);
    }

    // ── Cache Flush ──

    public function test_flush_cache_clears_registered_keys(): void
    {
        EntityCacheManager::register('project', ['test_flush_key']);

        Cache::put('test_flush_key', 'test_value', 60);
        $this->assertEquals('test_value', Cache::get('test_flush_key'));

        ProjectObserver::flushCache();

        $this->assertNull(Cache::get('test_flush_key'));
    }

    public function test_flush_cache_clears_multiple_registered_keys(): void
    {
        EntityCacheManager::register('project', ['flush_key_a', 'flush_key_b', 'flush_key_c']);

        Cache::put('flush_key_a', 'a', 60);
        Cache::put('flush_key_b', 'b', 60);
        Cache::put('flush_key_c', 'c', 60);

        ProjectObserver::flushCache();

        $this->assertNull(Cache::get('flush_key_a'));
        $this->assertNull(Cache::get('flush_key_b'));
        $this->assertNull(Cache::get('flush_key_c'));
    }

    // ── Model Behavior ──

    public function test_project_uses_slug_for_route_binding(): void
    {
        $project = Project::create([
            'name' => 'Route Binding Test',
            'slug' => 'route-binding-test',
            'category' => 'web',
            'desc' => 'Description',
        ]);

        $this->assertEquals('slug', (new Project)->getRouteKeyName());
    }

    public function test_project_category_is_lowercased(): void
    {
        $project = Project::create([
            'name' => 'Category Test',
            'slug' => 'category-test',
            'category' => 'WEB',
            'desc' => 'Description',
        ]);

        $this->assertEquals('web', $project->category->value);
    }

    public function test_project_is_active_is_cast_to_boolean(): void
    {
        $project = Project::create([
            'name' => 'Bool Test',
            'slug' => 'bool-test',
            'category' => 'web',
            'desc' => 'Description',
            'is_active' => 1,
        ]);

        $this->assertIsBool($project->is_active);
        $this->assertTrue($project->is_active);
    }

    public function test_project_images_relationship(): void
    {
        $project = Project::create([
            'name' => 'Has Images',
            'slug' => 'has-images',
            'category' => 'web',
            'desc' => 'Description',
        ]);

        $this->assertEmpty($project->images);
    }

    public function test_project_technologies_relationship(): void
    {
        $project = Project::create([
            'name' => 'Has Tech',
            'slug' => 'has-tech',
            'category' => 'web',
            'desc' => 'Description',
        ]);

        $this->assertEmpty($project->technologies);
    }
}
