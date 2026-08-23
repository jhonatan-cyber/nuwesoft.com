<?php

namespace Tests\Feature;

use App\Models\FourOhFourLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogTest extends TestCase
{
    use RefreshDatabase;

    // ── Authentication ──────────────────────────────────

    public function test_guest_cannot_access_logs_page(): void
    {
        $response = $this->get(route('logs.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_logs_page(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('logs.index'));
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Dashboard/Logs/Index'));
    }

    // ── Page Rendering ──────────────────────────────────

    public function test_logs_page_returns_log_stats(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            FourOhFourLog::create(['url' => "/page-{$i}", 'logged_at' => now()->subDays(rand(1, 5))]);
        }
        for ($i = 0; $i < 3; $i++) {
            FourOhFourLog::create(['url' => "/today-{$i}", 'logged_at' => now()]);
        }

        $response = $this->actingAs($user)->get(route('logs.index'));
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->where('stats.total', 8)
            ->where('stats.today', 3)
        );
    }

    public function test_logs_page_returns_recent_logs(): void
    {
        $user = User::factory()->create();

        $log = FourOhFourLog::create([
            'url' => '/missing-page',
            'referer' => 'https://google.com',
            'ip' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0',
            'logged_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('logs.index'));
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->where('logs.0.url', '/missing-page')
        );
    }

    public function test_logs_page_returns_unique_url_count(): void
    {
        $user = User::factory()->create();

        FourOhFourLog::create(['url' => '/page-a', 'logged_at' => now()]);
        FourOhFourLog::create(['url' => '/page-a', 'logged_at' => now()]);
        FourOhFourLog::create(['url' => '/page-b', 'logged_at' => now()]);

        $response = $this->actingAs($user)->get(route('logs.index'));
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page->where('stats.unique_urls', 2)
        );
    }

    // ── Stats Accuracy ──────────────────────────────────

    public function test_stats_count_only_logs_from_today(): void
    {
        $user = User::factory()->create();

        // Yesterday's logs — should NOT count in "today"
        FourOhFourLog::create(['url' => '/old', 'logged_at' => now()->subDay()]);
        // Today's logs — should count
        FourOhFourLog::create(['url' => '/today', 'logged_at' => now()]);

        $response = $this->actingAs($user)->get(route('logs.index'));
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page->where('stats.today', 1)
        );
    }

    public function test_logs_page_limits_to_100_entries(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 120; $i++) {
            FourOhFourLog::create(['url' => "/page-{$i}", 'logged_at' => now()]);
        }

        $response = $this->actingAs($user)->get(route('logs.index'));
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->where('logs', fn ($logs) => count($logs) <= 100)
        );
    }

    public function test_logs_ordered_by_most_recent_first(): void
    {
        $user = User::factory()->create();

        $old = FourOhFourLog::create(['url' => '/old', 'logged_at' => now()->subHour()]);
        $new = FourOhFourLog::create(['url' => '/new', 'logged_at' => now()]);

        $response = $this->actingAs($user)->get(route('logs.index'));
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page->where('logs.0.url', '/new')
        );
    }

    public function test_logs_page_with_no_logs_returns_empty_stats(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('logs.index'));
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->where('stats.total', 0)
            ->where('stats.today', 0)
            ->where('stats.unique_urls', 0)
            ->where('logs', [])
        );
    }

    // ── Log Data Structure ──────────────────────────────

    public function test_log_entry_contains_required_fields(): void
    {
        $user = User::factory()->create();

        FourOhFourLog::create([
            'url' => '/test-404',
            'referer' => 'https://example.com',
            'ip' => '192.168.1.1',
            'user_agent' => 'TestBot/1.0',
            'logged_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('logs.index'));
        $response->assertStatus(200);

        $response->assertInertia(fn ($page) => $page
            ->where('logs.0.url', '/test-404')
            ->where('logs.0.referer', 'https://example.com')
            ->where('logs.0.ip', '192.168.1.1')
            ->where('logs.0.user_agent', 'TestBot/1.0')
            ->has('logs.0.created_at')
        );
    }
}
