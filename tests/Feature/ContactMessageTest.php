<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test public contact form submits successfully.
     */
    public function test_contact_form_submits_successfully(): void
    {
        $messageData = [
            'nombre' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Inquiry',
            'mensaje' => 'Hello, I would like to get a quote.',
        ];

        $response = $this->post('/contacto', $messageData);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('contact_messages', [
            'nombre' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    /**
     * Test guest cannot view message inbox in dashboard.
     */
    public function test_guests_cannot_view_messages_dashboard(): void
    {
        $this->get('/dashboard/messages')
            ->assertRedirect('/login');
    }

    /**
     * Test admin can view and mark messages as read.
     */
    public function test_admin_can_view_and_read_messages(): void
    {
        $user = User::factory()->create();

        $message = ContactMessage::create([
            'nombre' => 'Jane Smith',
            'email' => 'jane@example.com',
            'subject' => 'Test Subject',
            'mensaje' => 'Test Message Body',
        ]);

        // Access index
        $responseIndex = $this->actingAs($user)
            ->get('/dashboard/messages');

        $responseIndex->assertStatus(200);

        // Mark as read
        $responseRead = $this->actingAs($user)
            ->post("/dashboard/messages/{$message->id}/read");

        $responseRead->assertSessionHasNoErrors();
        $this->assertNotNull($message->fresh()->read_at);
    }
}
