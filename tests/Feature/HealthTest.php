<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthTest extends TestCase
{
    use RefreshDatabase;

    public function test_ping_returns_pong(): void
    {
        $this->get('/ping')->assertOk()->assertJsonStructure(['status', 'timestamp'])
            ->assertJson(['status' => 'pong']);
    }

    public function test_public_health_only_returns_safe_status(): void
    {
        $response = $this->get('/health')->assertOk()->assertHeader('Content-Type', 'application/json');
        $response->assertExactJson(['status' => 'healthy', 'timestamp' => $response->json('timestamp')]);
    }

    public function test_non_admin_health_does_not_include_internal_metrics(): void
    {
        $response = $this->actingAs(User::factory()->nonAdmin()->create())->get('/health');
        $response->assertOk()->assertJsonMissingPath('metrics')
            ->assertJsonMissingPath('database')->assertJsonMissingPath('environment');
    }

    public function test_admin_health_includes_internal_status_and_metrics(): void
    {
        Project::create(['name' => 'Active', 'category' => 'web', 'is_active' => true]);
        Project::create(['name' => 'Inactive', 'category' => 'web', 'is_active' => false]);
        Technology::create(['name' => 'Vue', 'category' => 'frontend', 'is_active' => true]);
        ContactMessage::create(['nombre' => 'A', 'email' => 'a@test.com', 'mensaje' => 'Hi']);

        $response = $this->actingAs(User::factory()->create())->get('/health')->assertOk();
        $response->assertJsonPath('status', 'healthy')->assertJsonPath('database.status', 'connected')
            ->assertJsonPath('cache.status', 'operational')->assertJsonPath('metrics.projects_count', 1)
            ->assertJsonPath('metrics.technologies_count', 1)->assertJsonPath('metrics.contact_messages', 1)
            ->assertJsonStructure([
                'environment', 'debug', 'locale', 'response_time_ms',
                'metrics' => ['php_version', 'laravel_version', 'users_count', 'total_projects', 'total_technologies'],
                'system' => ['memory_usage', 'peak_memory', 'uptime'],
                'database' => ['connection', 'status'], 'cache' => ['driver', 'status'],
                'disk' => ['free', 'total'],
            ]);
    }

    public function test_admin_health_metrics_are_cached(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin)->get('/health');
        Project::create(['name' => 'New', 'category' => 'web', 'is_active' => true]);
        $this->actingAs($admin)->get('/health')->assertJsonPath('metrics.projects_count', 0);
    }
}
