<?php

namespace Tests\Unit;

use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriberModelTest extends TestCase
{
    use RefreshDatabase;

    // ── Active Scope ──

    public function test_active_scope_returns_only_active_subscribers(): void
    {
        Subscriber::create([
            'email' => 'active@test.com',
            'status' => 'active',
            'source' => 'website',
            'subscribed_at' => now(),
        ]);

        Subscriber::create([
            'email' => 'unsubscribed@test.com',
            'status' => 'unsubscribed',
            'source' => 'website',
            'subscribed_at' => now(),
            'unsubscribed_at' => now(),
        ]);

        $active = Subscriber::active()->get();
        $this->assertCount(1, $active);
        $this->assertEquals('active@test.com', $active->first()->email);
    }

    // ── Unsubscribe ──

    public function test_unsubscribe_sets_status_and_timestamp(): void
    {
        $subscriber = Subscriber::create([
            'email' => 'test@test.com',
            'status' => 'active',
            'source' => 'website',
            'subscribed_at' => now(),
        ]);

        $this->assertNull($subscriber->unsubscribed_at);

        $subscriber->unsubscribe();

        $subscriber->refresh();
        $this->assertEquals('unsubscribed', $subscriber->status);
        $this->assertNotNull($subscriber->unsubscribed_at);
    }

    public function test_unsubscribe_is_idempotent(): void
    {
        $subscriber = Subscriber::create([
            'email' => 'test@test.com',
            'status' => 'active',
            'source' => 'website',
            'subscribed_at' => now(),
        ]);

        $subscriber->unsubscribe();
        $firstUnsubscribedAt = $subscriber->fresh()->unsubscribed_at;

        // Unsubscribing again should not change the timestamp
        $subscriber->unsubscribe();
        $subscriber->refresh();

        $this->assertEquals('unsubscribed', $subscriber->status);
        $this->assertEquals($firstUnsubscribedAt->timestamp, $subscriber->unsubscribed_at->timestamp);
    }

    // ── Casting ──

    public function test_subscribed_at_is_cast_to_datetime(): void
    {
        $subscriber = Subscriber::create([
            'email' => 'test@test.com',
            'status' => 'active',
            'source' => 'website',
            'subscribed_at' => now(),
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $subscriber->subscribed_at);
    }

    public function test_unsubscribed_at_is_cast_to_datetime(): void
    {
        $subscriber = Subscriber::create([
            'email' => 'test@test.com',
            'status' => 'unsubscribed',
            'source' => 'website',
            'subscribed_at' => now()->subDays(5),
            'unsubscribed_at' => now(),
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $subscriber->unsubscribed_at);
    }
}
