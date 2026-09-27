<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_direct_registration_is_closed_after_first_user(): void
    {
        User::factory()->create();

        $response = $this->post('/register', [
            'name' => 'Intruso',
            'email' => 'intruso@example.com',
            'password' => 'SecurePassword123!',
            'password_confirmation' => 'SecurePassword123!',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('users', ['email' => 'intruso@example.com']);
    }

    public function test_non_admin_user_cannot_access_dashboard(): void
    {
        $user = User::factory()->nonAdmin()->create();

        $this->actingAs($user)->get('/dashboard')->assertForbidden();
        $this->actingAs($user)->get('/dashboard/projects')->assertForbidden();
    }

    public function test_admin_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/dashboard')->assertOk();
    }
}
