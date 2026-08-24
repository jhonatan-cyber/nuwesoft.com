<?php

namespace Tests\Unit;

use App\Services\EntityCacheManager;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class EntityCacheManagerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        EntityCacheManager::reset();
    }

    // ── Registry ──

    public function test_register_adds_keys_for_entity(): void
    {
        EntityCacheManager::register('project', ['key_a', 'key_b']);

        $keys = EntityCacheManager::getKeys('project');
        $this->assertEquals(['key_a', 'key_b'], $keys);
    }

    public function test_register_merges_keys_for_same_entity(): void
    {
        EntityCacheManager::register('project', ['key_a']);
        EntityCacheManager::register('project', ['key_b', 'key_c']);

        $keys = EntityCacheManager::getKeys('project');
        $this->assertEquals(['key_a', 'key_b', 'key_c'], $keys);
    }

    public function test_register_deduplicates_keys(): void
    {
        EntityCacheManager::register('project', ['key_a', 'key_a', 'key_b']);
        EntityCacheManager::register('project', ['key_b', 'key_c']);

        $keys = EntityCacheManager::getKeys('project');
        $this->assertCount(3, $keys);
        $this->assertEqualsCanonicalizing(['key_a', 'key_b', 'key_c'], $keys);
    }

    public function test_get_keys_returns_empty_array_for_unregistered_entity(): void
    {
        $keys = EntityCacheManager::getKeys('nonexistent');
        $this->assertIsArray($keys);
        $this->assertEmpty($keys);
    }

    public function test_get_registry_returns_all_entities(): void
    {
        EntityCacheManager::register('project', ['key_a']);
        EntityCacheManager::register('technology', ['key_b']);

        $registry = EntityCacheManager::getRegistry();
        $this->assertArrayHasKey('project', $registry);
        $this->assertArrayHasKey('technology', $registry);
    }

    // ── Flush ──

    public function test_flush_entity_forgets_registered_keys(): void
    {
        EntityCacheManager::register('project', ['cache_key_a', 'cache_key_b']);

        Cache::put('cache_key_a', 'value_a', 60);
        Cache::put('cache_key_b', 'value_b', 60);

        $this->assertEquals('value_a', Cache::get('cache_key_a'));
        $this->assertEquals('value_b', Cache::get('cache_key_b'));

        EntityCacheManager::flushEntity('project');

        $this->assertNull(Cache::get('cache_key_a'));
        $this->assertNull(Cache::get('cache_key_b'));
    }

    public function test_flush_entity_does_not_affect_other_entities(): void
    {
        EntityCacheManager::register('project', ['project_key']);
        EntityCacheManager::register('technology', ['tech_key']);

        Cache::put('project_key', 'project_value', 60);
        Cache::put('tech_key', 'tech_value', 60);

        EntityCacheManager::flushEntity('project');

        $this->assertNull(Cache::get('project_key'));
        $this->assertEquals('tech_value', Cache::get('tech_key'));
    }

    public function test_flush_entity_does_nothing_for_unregistered_entity(): void
    {
        Cache::put('some_key', 'some_value', 60);

        EntityCacheManager::flushEntity('nonexistent');

        $this->assertEquals('some_value', Cache::get('some_key'));
    }

    public function test_flush_entities_flushes_multiple(): void
    {
        EntityCacheManager::register('project', ['p_key']);
        EntityCacheManager::register('technology', ['t_key']);

        Cache::put('p_key', 'p_value', 60);
        Cache::put('t_key', 't_value', 60);

        EntityCacheManager::flushEntities(['project', 'technology']);

        $this->assertNull(Cache::get('p_key'));
        $this->assertNull(Cache::get('t_key'));
    }

    // ── Reset ──

    public function test_reset_clears_entire_registry(): void
    {
        EntityCacheManager::register('project', ['key_a']);
        EntityCacheManager::register('technology', ['key_b']);

        $this->assertNotEmpty(EntityCacheManager::getRegistry());

        EntityCacheManager::reset();

        $this->assertEmpty(EntityCacheManager::getRegistry());
        $this->assertEmpty(EntityCacheManager::getKeys('project'));
    }
}
