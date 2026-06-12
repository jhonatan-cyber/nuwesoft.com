<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_page_can_be_rendered()
    {
        // Seed settings
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

    public function test_settings_can_be_updated()
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
}
