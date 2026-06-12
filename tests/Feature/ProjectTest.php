<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $responseActive = $this->get('/portafolio/' . $activeProject->id);
        $responseActive->assertStatus(200);

        $responseInactive = $this->get('/portafolio/' . $inactiveProject->id);
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
}
