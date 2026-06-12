<?php

namespace Tests\Feature;

use App\Models\Technology;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TechnologyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest cannot access dashboard technologies list.
     */
    public function test_guests_cannot_access_technologies_dashboard(): void
    {
        $this->get('/dashboard/technologies')
            ->assertRedirect('/login');
    }

    /**
     * Test authenticated admin can create a technology.
     */
    public function test_authenticated_user_can_create_technology(): void
    {
        $user = User::factory()->create();

        $techData = [
            'name' => 'Vue 3',
            'category' => 'frontend',
            'invert_dark' => false,
            'is_active' => true,
        ];

        $response = $this->actingAs($user)
            ->post('/dashboard/technologies', $techData);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('technologies', [
            'name' => 'Vue 3',
            'is_active' => true,
        ]);
    }

    /**
     * Test invalid file upload for logo fails validation.
     */
    public function test_invalid_logo_file_fails_validation(): void
    {
        $user = User::factory()->create();

        // Upload a non-image text file disguised as logo
        $invalidLogo = UploadedFile::fake()->create('document.txt', 100, 'text/plain');

        $techData = [
            'name' => 'Invalid Tech',
            'category' => 'frontend',
            'invert_dark' => false,
            'logo' => $invalidLogo,
            'is_active' => true,
        ];

        $response = $this->actingAs($user)
            ->post('/dashboard/technologies', $techData);

        $response->assertSessionHasErrors(['logo']);
    }
}
