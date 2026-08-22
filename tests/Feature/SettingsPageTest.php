<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SettingsPageTest extends TestCase
{
    use RefreshDatabase;

    // ── Authorization ──

    public function test_guests_cannot_access_settings(): void
    {
        $this->get('/dashboard/settings')->assertRedirect('/login');
    }

    public function test_guests_cannot_update_settings(): void
    {
        $this->patch('/dashboard/settings', ['site_name' => 'Hacked'])
            ->assertRedirect('/login');
    }

    // ── Index ──

    public function test_settings_page_can_be_rendered(): void
    {
        Setting::setValue('site_name', 'NUWESOFT');
        Setting::setValue('email', 'test@test.com');

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)
            ->get(route('dashboard.settings.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Dashboard/Settings/Index')
            ->has('settings')
        );
    }

    public function test_index_passes_all_settings_to_view(): void
    {
        Setting::setValue('site_name', 'Test Site');
        Setting::setValue('tagline', 'Test Tagline');
        Setting::setValue('email', 'admin@test.com');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard.settings.index'));

        $response->assertInertia(fn ($page) => $page
            ->where('settings.site_name', 'Test Site')
            ->where('settings.tagline', 'Test Tagline')
            ->where('settings.email', 'admin@test.com')
        );
    }

    // ── Update: Text Fields ──

    public function test_settings_can_be_updated(): void
    {
        Setting::setValue('site_name', 'Old Name');

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('dashboard.settings.index'))
            ->patch(route('dashboard.settings.update'), [
                'site_name' => 'New Name',
                'email' => 'test@test.com',
            ]);

        $response->assertSessionHas('success');
        $this->assertEquals('New Name', Setting::getValue('site_name'));
    }

    public function test_update_saves_all_text_fields(): void
    {
        $user = User::factory()->create();

        $data = [
            'site_name' => 'My Site',
            'tagline' => 'My Tagline',
            'email' => 'contact@site.com',
            'phone' => '+54 11 1234-5678',
            'address' => 'Buenos Aires, Argentina',
        ];

        $this->actingAs($user)->patch(route('dashboard.settings.update'), $data);

        $this->assertEquals('My Site', Setting::getValue('site_name'));
        $this->assertEquals('My Tagline', Setting::getValue('tagline'));
        $this->assertEquals('contact@site.com', Setting::getValue('email'));
        $this->assertEquals('+54 11 1234-5678', Setting::getValue('phone'));
        $this->assertEquals('Buenos Aires, Argentina', Setting::getValue('address'));
    }

    public function test_update_saves_social_links(): void
    {
        $user = User::factory()->create();

        $data = [
            'social_facebook' => 'https://facebook.com/mypage',
            'social_twitter' => 'https://x.com/myhandle',
            'social_linkedin' => 'https://linkedin.com/company/mycompany',
            'social_github' => 'https://github.com/myorg',
            'social_youtube' => 'https://youtube.com/@mychannel',
            'social_tiktok' => 'https://tiktok.com/@myhandle',
        ];

        $this->actingAs($user)->patch(route('dashboard.settings.update'), $data);

        $this->assertEquals('https://facebook.com/mypage', Setting::getValue('social_facebook'));
        $this->assertEquals('https://x.com/myhandle', Setting::getValue('social_twitter'));
        $this->assertEquals('https://linkedin.com/company/mycompany', Setting::getValue('social_linkedin'));
        $this->assertEquals('https://github.com/myorg', Setting::getValue('social_github'));
        $this->assertEquals('https://youtube.com/@mychannel', Setting::getValue('social_youtube'));
        $this->assertEquals('https://tiktok.com/@myhandle', Setting::getValue('social_tiktok'));
    }

    // ── Validation ──

    public function test_update_validates_email_format(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('dashboard.settings.update'), [
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_update_validates_social_urls(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('dashboard.settings.update'), [
            'social_facebook' => 'not-a-url',
            'social_twitter' => 'also-not-a-url',
        ]);

        $response->assertSessionHasErrors(['social_facebook', 'social_twitter']);
    }

    public function test_update_validates_site_name_max_length(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('dashboard.settings.update'), [
            'site_name' => str_repeat('a', 256),
        ]);

        $response->assertSessionHasErrors(['site_name']);
    }

    public function test_update_validates_phone_max_length(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('dashboard.settings.update'), [
            'phone' => str_repeat('1', 51),
        ]);

        $response->assertSessionHasErrors(['phone']);
    }

    public function test_update_allows_nullable_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('dashboard.settings.update'), [
            'site_name' => null,
            'tagline' => null,
            'email' => null,
            'phone' => null,
            'address' => null,
        ]);

        $response->assertSessionHasNoErrors();
    }

    // ── Cache Behavior ──

    public function test_setting_getter_uses_cache(): void
    {
        Setting::setValue('cache_test_key', 'original');

        // First call should cache
        $value1 = Setting::getValue('cache_test_key');
        $this->assertEquals('original', $value1);

        // Update directly in DB (bypassing cache)
        DB::table('settings')->where('key', 'cache_test_key')->update(['value' => 'updated']);

        // Cached value should still be 'original'
        $value2 = Setting::getValue('cache_test_key');
        $this->assertEquals('original', $value2);

        // After forgetting cache, should get new value
        \Illuminate\Support\Facades\Cache::forget('settings');
        $value3 = Setting::getValue('cache_test_key');
        $this->assertEquals('updated', $value3);
    }

    public function test_set_value_clears_cache(): void
    {
        Setting::setValue('clear_test', 'before');

        // Populate cache
        Setting::getValue('clear_test');

        // Update — should clear cache
        Setting::setValue('clear_test', 'after');

        $this->assertEquals('after', Setting::getValue('clear_test'));
    }

    // ── Setting Model ──

    public function test_get_value_returns_default_for_missing_key(): void
    {
        $result = Setting::getValue('nonexistent_key', 'default_value');
        $this->assertEquals('default_value', $result);
    }

    public function test_get_value_returns_null_for_missing_key_without_default(): void
    {
        $result = Setting::getValue('nonexistent_key');
        $this->assertNull($result);
    }

    public function test_get_all_returns_array(): void
    {
        Setting::setValue('key1', 'value1');
        Setting::setValue('key2', 'value2');

        $all = Setting::getAll();

        $this->assertIsArray($all);
        $this->assertEquals('value1', $all['key1']);
        $this->assertEquals('value2', $all['key2']);
    }
}
