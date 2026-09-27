<?php

namespace Tests\Feature;

use App\Contracts\StorageServiceInterface;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test public portfolio lists active projects.
     */
    public function test_portfolio_page_can_be_rendered(): void
    {
        $response = $this->get('/portafolio');
        $response->assertStatus(200);
    }

    /**
     * Test active project is accessible, inactive project returns 404.
     */
    public function test_inactive_project_returns_404_on_public_show(): void
    {
        // Active project
        $activeProject = Project::create([
            'name' => 'Active Project',
            'slug' => 'active-slug',
            'category' => 'web',
            'desc' => 'Active Project Desc',
            'is_active' => true,
        ]);

        // Inactive project
        $inactiveProject = Project::create([
            'name' => 'Inactive Project',
            'slug' => 'inactive-slug',
            'category' => 'web',
            'desc' => 'Inactive Project Desc',
            'is_active' => false,
        ]);

        $responseActive = $this->get('/portafolio/' . $activeProject->slug);
        $responseActive->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('PortfolioProjectDetail')
                ->where('project.id', $activeProject->id)
                ->where('project.name', 'Active Project')
                ->where('project.slug', 'active-slug')
                ->where('project.desc', 'Active Project Desc')
                ->has('project.images')
                ->has('project.technologies')
            );

        $responseInactive = $this->get('/portafolio/' . $inactiveProject->slug);
        $responseInactive->assertStatus(404);
    }

    /**
     * Test guest cannot access admin dashboard projects CRUD.
     */
    public function test_guests_cannot_access_projects_dashboard(): void
    {
        $this->get('/dashboard/projects')
            ->assertRedirect('/login');
    }

    /**
     * Test authenticated admin can create a project.
     */
    public function test_authenticated_user_can_create_project(): void
    {
        $user = User::factory()->create();

        $projectData = [
            'name' => 'New Awesome Project',
            'category' => 'web',
            'desc' => 'Descripción en español',
            'project_url' => 'https://awesome-project.com',
            'is_active' => true,
        ];

        $response = $this->actingAs($user)
            ->post('/dashboard/projects', $projectData);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('projects', [
            'name' => 'New Awesome Project',
            'is_active' => true,
        ]);
    }

    public function test_analyzer_reports_fields_from_a_login_form_returning_http_200(): void
    {
        Http::fake([
            'https://example.com/login' => Http::response(<<<'HTML'
                <html><head><meta name="description" content="Plataforma de gestión empresarial"></head><body><form>
                    <input type="email" placeholder="Correo" required>
                    <input type="password" placeholder="Contraseña" required>
                    <button type="submit">Ingresar</button>
                </form></body></html>
                HTML),
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->postJson(route('projects.analyze-technologies'), [
                'url' => 'https://example.com/login',
            ]);

        $response->assertOk()
            ->assertJsonPath('needs_credentials', true)
            ->assertJsonPath('authentication_type', 'form')
            ->assertJsonPath('page_description', 'Plataforma de gestión empresarial')
            ->assertJsonCount(2, 'authentication_fields')
            ->assertJsonPath('authentication_fields.0.type', 'email')
            ->assertJsonPath('authentication_fields.1.type', 'password');
    }

    public function test_authenticated_user_can_toggle_project_status(): void
    {
        $user = User::factory()->create();
        $project = Project::create([
            'name' => 'Toggle Project',
            'category' => 'web',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->patch(route('projects.status', $project->id), ['is_active' => false])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'is_active' => false,
        ]);
    }

    public function test_deleting_project_removes_cloudinary_images_first(): void
    {
        $user = User::factory()->create();
        $project = Project::create([
            'name' => 'Delete Project',
            'category' => 'web',
            'is_active' => true,
        ]);
        $image = ProjectImage::create([
            'project_id' => $project->id,
            'image_url' => 'https://res.cloudinary.com/test/image/upload/project.jpg',
            'public_id' => 'nuwesoft/projects/project',
            'order_index' => 0,
        ]);

        $storage = $this->mock(StorageServiceInterface::class);
        $storage->shouldReceive('delete')
            ->once()
            ->with('nuwesoft/projects/project');

        $this->actingAs($user)
            ->delete(route('projects.destroy', $project->id))
            ->assertRedirect(route('projects.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        $this->assertDatabaseMissing('project_images', ['id' => $image->id]);
    }

    public function test_project_is_preserved_when_cloudinary_delete_fails(): void
    {
        $user = User::factory()->create();
        $project = Project::create([
            'name' => 'Preserved Project',
            'category' => 'web',
            'is_active' => true,
        ]);
        $image = ProjectImage::create([
            'project_id' => $project->id,
            'image_url' => 'https://res.cloudinary.com/test/image/upload/project.jpg',
            'public_id' => 'nuwesoft/projects/project',
            'order_index' => 0,
        ]);

        $storage = $this->mock(StorageServiceInterface::class);
        $storage->shouldReceive('delete')
            ->once()
            ->andThrow(new \RuntimeException('Cloudinary unavailable'));

        $this->actingAs($user)
            ->from(route('projects.index'))
            ->delete(route('projects.destroy', $project->id))
            ->assertRedirect(route('projects.index'))
            ->assertSessionHasErrors('delete');

        $this->assertDatabaseHas('projects', ['id' => $project->id]);
        $this->assertDatabaseHas('project_images', ['id' => $image->id]);
    }

    public function test_partial_cloudinary_delete_does_not_leave_stale_image_rows(): void
    {
        $user = User::factory()->create();
        $project = Project::create(['name' => 'Partial Delete', 'category' => 'web', 'is_active' => true]);
        $first = ProjectImage::create([
            'project_id' => $project->id, 'image_url' => 'https://example.com/first.jpg',
            'public_id' => 'projects/first', 'order_index' => 0,
        ]);
        $second = ProjectImage::create([
            'project_id' => $project->id, 'image_url' => 'https://example.com/second.jpg',
            'public_id' => 'projects/second', 'order_index' => 1,
        ]);

        $storage = $this->mock(StorageServiceInterface::class);
        $storage->shouldReceive('delete')->once()->with('projects/first');
        $storage->shouldReceive('delete')->once()->with('projects/second')
            ->andThrow(new \RuntimeException('Cloudinary unavailable'));

        $this->actingAs($user)->from(route('projects.index'))
            ->delete(route('projects.destroy', $project->id))->assertSessionHasErrors('delete');

        $this->assertDatabaseHas('projects', ['id' => $project->id]);
        $this->assertDatabaseMissing('project_images', ['id' => $first->id]);
        $this->assertDatabaseHas('project_images', ['id' => $second->id]);
    }
}
