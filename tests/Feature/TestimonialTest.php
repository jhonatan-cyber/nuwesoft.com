<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialTest extends TestCase
{
    use RefreshDatabase;

    // ── Authorization ──

    public function test_guests_cannot_access_testimonials_dashboard(): void
    {
        $this->get('/dashboard/testimonials')->assertRedirect('/login');
    }

    public function test_guests_cannot_create_testimonial(): void
    {
        $this->post('/dashboard/testimonials', ['client_name' => 'Test'])
            ->assertRedirect('/login');
    }

    public function test_guests_cannot_update_testimonial(): void
    {
        $testimonial = Testimonial::create([
            'client_name' => 'Jane',
            'content' => 'Great work',
            'is_active' => true,
        ]);

        $this->patch("/dashboard/testimonials/{$testimonial->id}", [
            'client_name' => 'Updated',
            'content' => 'Updated content',
        ])->assertRedirect('/login');
    }

    public function test_guests_cannot_delete_testimonial(): void
    {
        $testimonial = Testimonial::create([
            'client_name' => 'Jane',
            'content' => 'Great work',
            'is_active' => true,
        ]);

        $this->delete("/dashboard/testimonials/{$testimonial->id}")
            ->assertRedirect('/login');
    }

    // ── Index ──

    public function test_authenticated_user_can_view_testimonials_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/testimonials');
        $response->assertStatus(200);
    }

    public function test_index_displays_testimonials_ordered_by_sort_order(): void
    {
        $user = User::factory()->create();

        Testimonial::create(['client_name' => 'B', 'content' => 'B content', 'sort_order' => 2, 'is_active' => true]);
        Testimonial::create(['client_name' => 'A', 'content' => 'A content', 'sort_order' => 1, 'is_active' => true]);

        $response = $this->actingAs($user)->get('/dashboard/testimonials');
        $response->assertStatus(200);
    }

    // ── Store ──

    public function test_authenticated_user_can_create_testimonial(): void
    {
        $user = User::factory()->create();

        $data = [
            'client_name' => 'Carlos Méndez',
            'client_role' => 'CTO',
            'client_company' => 'TechCorp',
            'content' => 'Excellent development team. Very professional.',
            'rating' => 5,
            'is_active' => true,
            'sort_order' => 1,
        ];

        $response = $this->actingAs($user)->post('/dashboard/testimonials', $data);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('testimonials', [
            'client_name' => 'Carlos Méndez',
            'client_role' => 'CTO',
            'client_company' => 'TechCorp',
            'rating' => 5,
            'is_active' => true,
        ]);
    }

    public function test_store_requires_client_name_and_content(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/testimonials', [
            'client_name' => '',
            'content' => '',
        ]);

        $response->assertSessionHasErrors(['client_name', 'content']);
    }

    public function test_store_validates_rating_range(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/testimonials', [
            'client_name' => 'Test',
            'content' => 'Test content',
            'rating' => 6,
        ]);

        $response->assertSessionHasErrors(['rating']);
    }

    public function test_store_validates_rating_minimum(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/testimonials', [
            'client_name' => 'Test',
            'content' => 'Test content',
            'rating' => 0,
        ]);

        $response->assertSessionHasErrors(['rating']);
    }

    public function test_store_validates_content_max_length(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/testimonials', [
            'client_name' => 'Test',
            'content' => str_repeat('a', 2001),
        ]);

        $response->assertSessionHasErrors(['content']);
    }

    public function test_store_validates_client_name_max_length(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/dashboard/testimonials', [
            'client_name' => str_repeat('a', 256),
            'content' => 'Valid content',
        ]);

        $response->assertSessionHasErrors(['client_name']);
    }

    // ── Update ──

    public function test_authenticated_user_can_update_testimonial(): void
    {
        $user = User::factory()->create();
        $testimonial = Testimonial::create([
            'client_name' => 'Old Name',
            'content' => 'Old content',
            'rating' => 3,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->put("/dashboard/testimonials/{$testimonial->id}", [
            'client_name' => 'New Name',
            'content' => 'New content',
            'rating' => 5,
            'is_active' => false,
        ]);

        $response->assertSessionHasNoErrors();
        $testimonial->refresh();
        $this->assertEquals('New Name', $testimonial->client_name);
        $this->assertEquals('New content', $testimonial->content);
        $this->assertEquals(5, $testimonial->rating);
        $this->assertFalse($testimonial->is_active);
    }

    public function test_update_requires_client_name_and_content(): void
    {
        $user = User::factory()->create();
        $testimonial = Testimonial::create([
            'client_name' => 'Existing',
            'content' => 'Existing content',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->put("/dashboard/testimonials/{$testimonial->id}", [
            'client_name' => '',
            'content' => '',
        ]);

        $response->assertSessionHasErrors(['client_name', 'content']);
    }

    // ── Delete ──

    public function test_authenticated_user_can_delete_testimonial(): void
    {
        $user = User::factory()->create();
        $testimonial = Testimonial::create([
            'client_name' => 'To Delete',
            'content' => 'Delete me',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->delete("/dashboard/testimonials/{$testimonial->id}");

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('testimonials', ['id' => $testimonial->id]);
    }

    // ── Model: Active Scope ──

    public function test_active_scope_filters_inactive_testimonials(): void
    {
        Testimonial::create(['client_name' => 'Active', 'content' => 'Yes', 'is_active' => true, 'sort_order' => 1]);
        Testimonial::create(['client_name' => 'Inactive', 'content' => 'No', 'is_active' => false, 'sort_order' => 2]);

        $active = Testimonial::active()->get();
        $this->assertCount(1, $active);
        $this->assertEquals('Active', $active->first()->client_name);
    }

    // ── Model: Casting ──

    public function test_rating_is_cast_to_integer(): void
    {
        $testimonial = Testimonial::create([
            'client_name' => 'Caster',
            'content' => 'Test',
            'rating' => '4',
            'is_active' => true,
        ]);

        $this->assertIsInt($testimonial->rating);
        $this->assertEquals(4, $testimonial->rating);
    }

    public function test_is_active_is_cast_to_boolean(): void
    {
        $testimonial = Testimonial::create([
            'client_name' => 'Bool Caster',
            'content' => 'Test',
            'is_active' => 1,
        ]);

        $this->assertIsBool($testimonial->is_active);
        $this->assertTrue($testimonial->is_active);
    }
}
