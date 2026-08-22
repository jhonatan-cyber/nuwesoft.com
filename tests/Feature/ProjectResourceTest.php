<?php

namespace Tests\Feature;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ProjectResourceTest extends TestCase
{
    use RefreshDatabase;

    private function createProject(array $overrides = []): Project
    {
        return Project::create(array_merge([
            'name' => 'Test Project',
            'slug' => 'test-project',
            'category' => 'WEB',
            'desc' => 'A test project description.',
            'icon' => 'Briefcase',
            'project_url' => 'https://example.com',
            'is_active' => true,
        ], $overrides));
    }

    // ── Field Presence ──────────────────────────────────

    public function test_resource_contains_all_expected_fields(): void
    {
        $project = $this->createProject();
        $resource = new ProjectResource($project);
        $array = $resource->toArray(new Request);

        $expectedKeys = [
            'id', 'name', 'slug', 'category', 'desc', 'icon',
            'project_url', 'is_active', 'technologies', 'images',
            'cover_image', 'url', 'created_at', 'updated_at',
        ];

        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $array, "Missing key: {$key}");
        }
    }

    public function test_resource_returns_correct_values(): void
    {
        $project = $this->createProject([
            'name' => 'E-Commerce Platform',
            'slug' => 'e-commerce-platform',
            'category' => 'WEB',
            'desc' => 'Full-stack e-commerce solution.',
            'icon' => 'ShoppingCart',
            'project_url' => 'https://shop.example.com',
        ]);

        $resource = new ProjectResource($project);
        $array = $resource->toArray(new Request);

        $this->assertEquals($project->id, $array['id']);
        $this->assertEquals('E-Commerce Platform', $array['name']);
        $this->assertEquals('e-commerce-platform', $array['slug']);
        $this->assertEquals('WEB', $array['category']);
        $this->assertEquals('Full-stack e-commerce solution.', $array['desc']);
        $this->assertEquals('ShoppingCart', $array['icon']);
        $this->assertEquals('https://shop.example.com', $array['project_url']);
        $this->assertTrue($array['is_active']);
    }

    // ── Slug-based URL ─────────────────────────────────

    public function test_resource_url_uses_slug_not_id(): void
    {
        $project = $this->createProject(['slug' => 'my-cool-project']);
        $resource = new ProjectResource($project);
        $array = $resource->toArray(new Request);

        $this->assertStringContainsString('/portafolio/my-cool-project', $array['url']);
        $this->assertStringNotContainsString('/portafolio/' . $project->id, $array['url']);
    }

    public function test_resource_url_matches_portafolio_show_route(): void
    {
        $project = $this->createProject(['slug' => 'portfolio-demo']);
        $resource = new ProjectResource($project);
        $array = $resource->toArray(new Request);

        $expectedUrl = route('portafolio.show', 'portfolio-demo');
        $this->assertEquals($expectedUrl, $array['url']);
    }

    // ── Nested Technologies ─────────────────────────────

    public function test_technologies_not_loaded_returns_empty(): void
    {
        $project = $this->createProject();
        // Don't load technologies
        $resource = new ProjectResource($project);
        $array = $resource->toArray(new Request);

        $this->assertEmpty($array['technologies']);
    }

    public function test_technologies_loaded_returns_technology_resources(): void
    {
        $project = $this->createProject();

        $tech1 = Technology::create([
            'name' => 'Laravel',
            'category' => 'BACKEND',
            'logo_url' => 'https://example.com/laravel.png',
            'is_active' => true,
        ]);

        $tech2 = Technology::create([
            'name' => 'Vue.js',
            'category' => 'FRONTEND',
            'logo_url' => 'https://example.com/vue.png',
            'is_active' => true,
        ]);

        $project->technologies()->sync([$tech1->id, $tech2->id]);
        $project->load('technologies');

        $resource = new ProjectResource($project);
        $array = $resource->toArray(new Request);

        $this->assertCount(2, $array['technologies']);
        $this->assertEquals('Laravel', $array['technologies'][0]['name']);
        $this->assertEquals('BACKEND', $array['technologies'][0]['category']);
        $this->assertEquals('Vue.js', $array['technologies'][1]['name']);
        $this->assertEquals('FRONTEND', $array['technologies'][1]['category']);
    }

    public function test_technology_resource_contains_all_fields(): void
    {
        $project = $this->createProject();

        $tech = Technology::create([
            'name' => 'React',
            'category' => 'FRONTEND',
            'logo_url' => 'https://example.com/react.png',
            'is_active' => true,
            'invert_dark' => false,
        ]);

        $project->technologies()->sync([$tech->id]);
        $project->load('technologies');

        $resource = new ProjectResource($project);
        $array = $resource->toArray(new Request);

        $techData = $array['technologies'][0];

        $this->assertEquals($tech->id, $techData['id']);
        $this->assertEquals('React', $techData['name']);
        $this->assertEquals('FRONTEND', $techData['category']);
        $this->assertEquals('https://example.com/react.png', $techData['logo_url']);
        $this->assertArrayHasKey('optimized_logo_url', $techData);
        $this->assertTrue($techData['is_active']);
        $this->assertFalse($techData['invert_dark']);
    }

    // ── Nested Images ───────────────────────────────────

    public function test_images_not_loaded_returns_empty(): void
    {
        $project = $this->createProject();
        // Don't load images
        $resource = new ProjectResource($project);
        $array = $resource->toArray(new Request);

        $this->assertEmpty($array['images']);
    }

    public function test_images_loaded_returns_project_image_resources(): void
    {
        $project = $this->createProject();

        $project->images()->create([
            'url' => 'https://res.cloudinary.com/demo/image1.jpg',
            'public_id' => 'projects/image1',
            'alt' => 'Screenshot 1',
            'order_index' => 0,
        ]);

        $project->images()->create([
            'url' => 'https://res.cloudinary.com/demo/image2.jpg',
            'public_id' => 'projects/image2',
            'alt' => 'Screenshot 2',
            'order_index' => 1,
        ]);

        $project->load('images');

        $resource = new ProjectResource($project);
        $array = $resource->toArray(new Request);

        $this->assertCount(2, $array['images']);
        $this->assertEquals('Screenshot 1', $array['images'][0]['alt']);
        $this->assertEquals('Screenshot 2', $array['images'][1]['alt']);
    }

    public function test_image_resource_contains_all_fields(): void
    {
        $project = $this->createProject();

        $image = $project->images()->create([
            'url' => 'https://res.cloudinary.com/demo/image.jpg',
            'public_id' => 'projects/test-image',
            'alt' => 'Project screenshot',
            'order_index' => 0,
        ]);

        $project->load('images');

        $resource = new ProjectResource($project);
        $array = $resource->toArray(new Request);

        $imageData = $array['images'][0];

        $this->assertEquals($image->id, $imageData['id']);
        $this->assertEquals('https://res.cloudinary.com/demo/image.jpg', $imageData['url']);
        $this->assertEquals('projects/test-image', $imageData['public_id']);
        $this->assertEquals('Project screenshot', $imageData['alt']);
        $this->assertEquals(0, $imageData['order_index']);
    }

    // ── Cover Image ─────────────────────────────────────

    public function test_cover_image_returns_first_image_url(): void
    {
        $project = $this->createProject();

        $project->images()->create([
            'url' => 'https://res.cloudinary.com/demo/first.jpg',
            'public_id' => 'projects/first',
            'order_index' => 0,
        ]);

        $project->images()->create([
            'url' => 'https://res.cloudinary.com/demo/second.jpg',
            'public_id' => 'projects/second',
            'order_index' => 1,
        ]);

        $project->load('images');

        $resource = new ProjectResource($project);
        $array = $resource->toArray(new Request);

        $this->assertEquals('https://res.cloudinary.com/demo/first.jpg', $array['cover_image']);
    }

    public function test_cover_image_returns_null_when_no_images(): void
    {
        $project = $this->createProject();
        $project->load('images');

        $resource = new ProjectResource($project);
        $array = $resource->toArray(new Request);

        $this->assertNull($array['cover_image']);
    }

    public function test_cover_image_returns_null_when_images_not_loaded(): void
    {
        $project = $this->createProject();
        // Don't load images

        $resource = new ProjectResource($project);
        $array = $resource->toArray(new Request);

        $this->assertNull($array['cover_image']);
    }

    // ── Date Formatting ─────────────────────────────────

    public function test_dates_are_iso8601_formatted(): void
    {
        $project = $this->createProject();
        $resource = new ProjectResource($project);
        $array = $resource->toArray(new Request);

        $this->assertMatchesRegularExpression('/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $array['created_at']);
        $this->assertMatchesRegularExpression('/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $array['updated_at']);
    }

    // ── Collection Response ─────────────────────────────

    public function test_collection_returns_array_of_resources(): void
    {
        $this->createProject(['name' => 'Project A', 'slug' => 'project-a']);
        $this->createProject(['name' => 'Project B', 'slug' => 'project-b']);

        $projects = Project::with(['images', 'technologies'])->get();
        $collection = ProjectResource::collection($projects);
        $response = $collection->toArray(new Request);

        $this->assertCount(2, $response);
        $this->assertEquals('Project A', $response[0]['name']);
        $this->assertEquals('Project B', $response[1]['name']);
    }

    // ── Full Integration ────────────────────────────────

    public function test_full_project_with_technologies_and_images(): void
    {
        $project = $this->createProject([
            'name' => 'Full Stack App',
            'slug' => 'full-stack-app',
        ]);

        $tech = Technology::create([
            'name' => 'TypeScript',
            'category' => 'LANGUAGE',
            'logo_url' => 'https://example.com/ts.png',
            'is_active' => true,
        ]);

        $project->technologies()->sync([$tech->id]);

        $project->images()->create([
            'url' => 'https://res.cloudinary.com/demo/hero.jpg',
            'public_id' => 'projects/hero',
            'alt' => 'Hero shot',
            'order_index' => 0,
        ]);

        $project->load(['images', 'technologies']);

        $resource = new ProjectResource($project);
        $array = $resource->toArray(new Request);

        // Top-level fields
        $this->assertEquals('Full Stack App', $array['name']);
        $this->assertStringContainsString('/portafolio/full-stack-app', $array['url']);

        // Nested technologies
        $this->assertCount(1, $array['technologies']);
        $this->assertEquals('TypeScript', $array['technologies'][0]['name']);
        $this->assertEquals('LANGUAGE', $array['technologies'][0]['category']);

        // Nested images
        $this->assertCount(1, $array['images']);
        $this->assertEquals('Hero shot', $array['images'][0]['alt']);

        // Cover image from first image
        $this->assertEquals('https://res.cloudinary.com/demo/hero.jpg', $array['cover_image']);
    }
}
