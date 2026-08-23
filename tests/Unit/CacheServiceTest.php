<?php

namespace Tests\Unit;

use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CacheServiceTest extends TestCase
{
    private CacheService $cache;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cache = new CacheService;
    }

    // ── remember ──

    public function test_remember_returns_callback_result_and_caches(): void
    {
        $result = $this->cache->remember('test_key', 60, fn () => 'computed_value');

        $this->assertEquals('computed_value', $result);
        $this->assertEquals('computed_value', Cache::get('test_key'));
    }

    public function test_remember_returns_cached_value_on_subsequent_calls(): void
    {
        $callCount = 0;

        $this->cache->remember('test_key', 60, fn () => ++$callCount);
        $this->cache->remember('test_key', 60, fn () => ++$callCount);

        // Callback should only be called once
        $this->assertEquals(1, Cache::get('test_key'));
    }

    // ── forget ──

    public function test_forget_removes_key_from_cache(): void
    {
        Cache::put('to_forget', 'value', 60);
        $this->assertEquals('value', Cache::get('to_forget'));

        $this->cache->forget('to_forget');

        $this->assertNull(Cache::get('to_forget'));
    }

    // ── forgetMany ──

    public function test_forget_many_removes_multiple_keys(): void
    {
        Cache::put('key_a', 'a', 60);
        Cache::put('key_b', 'b', 60);
        Cache::put('key_c', 'c', 60);

        $this->cache->forgetMany(['key_a', 'key_c']);

        $this->assertNull(Cache::get('key_a'));
        $this->assertEquals('b', Cache::get('key_b'));
        $this->assertNull(Cache::get('key_c'));
    }

    // ── get ──

    public function test_get_returns_stored_value(): void
    {
        Cache::put('stored', 'hello', 60);

        $this->assertEquals('hello', $this->cache->get('stored'));
    }

    public function test_get_returns_default_for_missing_key(): void
    {
        $this->assertEquals('fallback', $this->cache->get('missing', 'fallback'));
    }

    public function test_get_returns_null_for_missing_key_without_default(): void
    {
        $this->assertNull($this->cache->get('missing'));
    }

    // ── put ──

    public function test_put_stores_value_in_cache(): void
    {
        $this->cache->put('new_key', 'new_value', 60);

        $this->assertEquals('new_value', Cache::get('new_key'));
    }

    // ── flush ──

    public function test_flush_clears_all_cache(): void
    {
        Cache::put('a', 1, 60);
        Cache::put('b', 2, 60);

        $this->cache->flush();

        $this->assertNull(Cache::get('a'));
        $this->assertNull(Cache::get('b'));
    }
}
