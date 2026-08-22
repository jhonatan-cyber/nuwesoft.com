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

    // ── Ping ──

    public function test_ping_returns_pong(): void
    {
        $response = $this->get('/ping');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'pong',
        ]);
        $response->assertJsonStructure(['status', 'timestamp']);
    }

    // ── Health Check (Unauthenticated) ──

    public function test_health_returns_200(): void
    {
        $response = $this->get('/health');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }

    public function test_health_returns_healthy_status(): void
    {
        $response = $this->get('/health');
        $data = $response->json();

        $this->assertEquals('healthy', $data['status']);
    }

    public function test_health_contains_required_structure(): void
    {
        $response = $this->get('/health');

        $response->assertJsonStructure([
            'status',
            'timestamp',
            'environment',
            'debug',
            'locale',
            'metrics' => [
                'projects_count',
                'technologies_count',
                'contact_messages',
                'php_version',
                'laravel_version',
            ],
            'system' => [
                'memory_usage',
                'peak_memory',
                'uptime',
            ],
            'database' => [
                'connection',
                'status',
            ],
            'cache' => [
                'driver',
                'status',
            ],
            'response_time_ms',
        ]);
    }

    public function test_health_database_status_is_connected(): void
    {
        $response = $this->get('/health');
        $data = $response->json();

        $this->assertEquals('connected', $data['database']['status']);
    }

    public function test_health_cache_status_is_operational(): void
    {
        $response = $this->get('/health');
        $data = $response->json();

        $this->assertEquals('operational', $data['cache']['status']);
    }

    public function test_health_returns_response_time(): void
    {
        $response = $this->get('/health');
        $data = $response->json();

        $this->assertArrayHasKey('response_time_ms', $data);
        $this->assertIsNumeric($data['response_time_ms']);
        $this->assertGreaterThan(0, $data['response_time_ms']);
    }

    public function test_health_returns_php_version(): void
    {
        $response = $this->get('/health');
        $data = $response->json();

        $this->assertEquals(PHP_VERSION, $data['metrics']['php_version']);
    }

    // ── Health Check: Metrics reflect actual data ──

    public function test_health_metrics_count_active_projects(): void
    {
        Project::create(['name' => 'A', 'category' => 'web', 'is_active' => true]);
        Project::create(['name' => 'B', 'category' => 'web', 'is_active' => false]);

        $response = $this->get('/health');
        $data = $response->json();

        $this->assertEquals(1, $data['metrics']['projects_count']);
    }

    public function test_health_metrics_count_active_technologies(): void
    {
        Technology::create(['name' => 'Vue', 'category' => 'frontend', 'is_active' => true]);
        Technology::create(['name' => 'Legacy', 'category' => 'backend', 'is_active' => false]);

        $response = $this->get('/health');
        $data = $response->json();

        $this->assertEquals(1, $data['metrics']['technologies_count']);
    }

    public function test_health_metrics_count_contact_messages(): void
    {
        ContactMessage::create(['nombre' => 'A', 'email' => 'a@test.com', 'mensaje' => 'Hi']);
        ContactMessage::create(['nombre' => 'B', 'email' => 'b@test.com', 'mensaje' => 'Hello']);

        $response = $this->get('/health');
        $data = $response->json();

        $this->assertEquals(2, $data['metrics']['contact_messages']);
    }

    // ── Health Check (Authenticated) ──

    public function test_authenticated_health_includes_user_and_disk_metrics(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/health');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'metrics' => [
                'users_count',
                'total_projects',
                'total_technologies',
            ],
            'disk' => [
                'free',
                'total',
            ],
        ]);
    }

    public function test_authenticated_health_counts_users(): void
    {
        User::factory()->count(3)->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/health');
        $data = $response->json();

        $this->assertEquals(4, $data['metrics']['users_count']);
    }

    public function test_unauthenticated_health_does_not_include_disk_info(): void
    {
        $response = $this->get('/health');
        $data = $response->json();

        $this->assertArrayNotHasKey('disk', $data);
        $this->assertArrayNotHasKey('users_count', $data['metrics']);
    }

    // ── Health Check: Caching ──

    public function test_health_metrics_are_cached(): void
    {
        // First call populates cache
        $this->get('/health');

        // Create new project — should NOT affect cached count
        Project::create(['name' => 'New', 'category' => 'web', 'is_active' => true]);

        $response = $this->get('/health');
        $data = $response->json();

        // Count should still be 0 (cached from first request with no projects)
        $this->assertEquals(0, $data['metrics']['projects_count']);
    }
}
