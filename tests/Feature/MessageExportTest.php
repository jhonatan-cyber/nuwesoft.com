<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageExportTest extends TestCase
{
    use RefreshDatabase;

    // ── Authentication ──────────────────────────────────

    public function test_guest_cannot_export_messages(): void
    {
        $response = $this->get(route('messages.export.csv'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_export_messages(): void
    {
        $user = User::factory()->create();

        ContactMessage::create([
            'nombre' => 'Juan',
            'email' => 'juan@test.com',
            'mensaje' => 'Hola',
        ]);

        $response = $this->actingAs($user)->get(route('messages.export.csv'));
        $response->assertStatus(200);
    }

    // ── CSV Content ─────────────────────────────────────

    public function test_export_contains_csv_headers(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('messages.export.csv'));
        $response->assertStatus(200);

        $content = $response->streamedContent();
        // Strip BOM
        $content = ltrim($content, "\xEF\xBB\xBF");

        $lines = explode("\n", trim($content));
        $this->assertStringContainsString('Nombre,Email,Mensaje,Leído,Fecha', $lines[0]);
    }

    public function test_export_contains_all_messages(): void
    {
        $user = User::factory()->create();

        ContactMessage::create([
            'nombre' => 'Alice',
            'email' => 'alice@test.com',
            'mensaje' => 'First message',
        ]);

        ContactMessage::create([
            'nombre' => 'Bob',
            'email' => 'bob@test.com',
            'mensaje' => 'Second message',
        ]);

        $response = $this->actingAs($user)->get(route('messages.export.csv'));
        $content = $response->streamedContent();
        $content = ltrim($content, "\xEF\xBB\xBF");

        $this->assertStringContainsString('Alice', $content);
        $this->assertStringContainsString('Bob', $content);
        $this->assertStringContainsString('First message', $content);
        $this->assertStringContainsString('Second message', $content);
    }

    public function test_export_shows_read_status(): void
    {
        $user = User::factory()->create();

        ContactMessage::create([
            'nombre' => 'Read User',
            'email' => 'read@test.com',
            'mensaje' => 'Read message',
            'read_at' => now(),
        ]);

        ContactMessage::create([
            'nombre' => 'Unread User',
            'email' => 'unread@test.com',
            'mensaje' => 'Unread message',
        ]);

        $response = $this->actingAs($user)->get(route('messages.export.csv'));
        $content = $response->streamedContent();
        $content = ltrim($content, "\xEF\xBB\xBF");

        $this->assertStringContainsString('Sí', $content);  // Read message
        $this->assertStringContainsString('No', $content);   // Unread message
    }

    public function test_export_with_no_messages_returns_only_headers(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('messages.export.csv'));
        $content = $response->streamedContent();
        $content = ltrim($content, "\xEF\xBB\xBF");

        $lines = explode("\n", trim($content));
        $this->assertCount(1, $lines); // Only the header row
    }

    // ── Filters ─────────────────────────────────────────

    public function test_export_unread_filter_returns_only_unread_messages(): void
    {
        $user = User::factory()->create();

        ContactMessage::create([
            'nombre' => 'Read',
            'email' => 'read@test.com',
            'mensaje' => 'Read',
            'read_at' => now(),
        ]);

        ContactMessage::create([
            'nombre' => 'Unread',
            'email' => 'unread@test.com',
            'mensaje' => 'Unread',
        ]);

        $response = $this->actingAs($user)->get(route('messages.export.csv', ['filter' => 'unread']));
        $content = $response->streamedContent();
        $content = ltrim($content, "\xEF\xBB\xBF");

        $this->assertStringContainsString('Unread', $content);
        $this->assertStringNotContainsString('Read', $content);
    }

    public function test_export_read_filter_returns_only_read_messages(): void
    {
        $user = User::factory()->create();

        ContactMessage::create([
            'nombre' => 'Read',
            'email' => 'read@test.com',
            'mensaje' => 'Read',
            'read_at' => now(),
        ]);

        ContactMessage::create([
            'nombre' => 'Unread',
            'email' => 'unread@test.com',
            'mensaje' => 'Unread',
        ]);

        $response = $this->actingAs($user)->get(route('messages.export.csv', ['filter' => 'read']));
        $content = $response->streamedContent();
        $content = ltrim($content, "\xEF\xBB\xBF");

        $this->assertStringContainsString('Read', $content);
        $this->assertStringNotContainsString('Unread', $content);
    }

    public function test_export_without_filter_returns_all_messages(): void
    {
        $user = User::factory()->create();

        ContactMessage::create([
            'nombre' => 'Read',
            'email' => 'read@test.com',
            'mensaje' => 'Read',
            'read_at' => now(),
        ]);

        ContactMessage::create([
            'nombre' => 'Unread',
            'email' => 'unread@test.com',
            'mensaje' => 'Unread',
        ]);

        $response = $this->actingAs($user)->get(route('messages.export.csv'));
        $content = $response->streamedContent();
        $content = ltrim($content, "\xEF\xBB\xBF");

        $this->assertStringContainsString('Read', $content);
        $this->assertStringContainsString('Unread', $content);
    }

    // ── Response Headers ────────────────────────────────

    public function test_export_returns_csv_content_type(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('messages.export.csv'));
        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }

    public function test_export_contains_utf8_bom_for_excel(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('messages.export.csv'));
        $content = $response->streamedContent();

        // UTF-8 BOM: 0xEF 0xBB 0xBF
        $this->assertStringStartsWith("\xEF\xBB\xBF", $content);
    }

    public function test_export_filename_contains_today_date(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('messages.export.csv'));
        $today = now()->format('Y-m-d');

        $response->assertHeader('Content-Disposition', "attachment; filename=mensajes-{$today}.csv");
    }

    // ── Edge Cases ──────────────────────────────────────

    public function test_export_handles_special_characters_in_messages(): void
    {
        $user = User::factory()->create();

        ContactMessage::create([
            'nombre' => 'María José',
            'email' => 'maria@test.com',
            'mensaje' => 'Mensaje con "comillas" y, comas y; puntos y coma',
        ]);

        $response = $this->actingAs($user)->get(route('messages.export.csv'));
        $content = $response->streamedContent();
        $content = ltrim($content, "\xEF\xBB\xBF");

        $this->assertStringContainsString('María José', $content);
        $this->assertStringContainsString('comillas', $content);
    }

    public function test_export_handles_empty_message_body(): void
    {
        $user = User::factory()->create();

        ContactMessage::create([
            'nombre' => 'Empty',
            'email' => 'empty@test.com',
            'mensaje' => '',
        ]);

        $response = $this->actingAs($user)->get(route('messages.export.csv'));
        $response->assertStatus(200);

        $content = $response->streamedContent();
        $content = ltrim($content, "\xEF\xBB\xBF");

        $this->assertStringContainsString('Empty', $content);
    }

    public function test_export_messages_ordered_by_created_at_desc(): void
    {
        $user = User::factory()->create();

        $old = ContactMessage::create([
            'nombre' => 'First',
            'email' => 'first@test.com',
            'mensaje' => 'First',
        ]);
        \Illuminate\Support\Facades\DB::table('contact_messages')->where('id', $old->id)->update(['created_at' => now()->subMinutes(5), 'updated_at' => now()->subMinutes(5)]);

        $new = ContactMessage::create([
            'nombre' => 'Last',
            'email' => 'last@test.com',
            'mensaje' => 'Last',
        ]);

        $response = $this->actingAs($user)->get(route('messages.export.csv'));
        $content = $response->streamedContent();
        $content = ltrim($content, "\xEF\xBB\xBF");

        $lines = explode("\n", trim($content));
        // First data row (index 1) should be "Last" (newest), second should be "First"
        $this->assertStringContainsString('Last', $lines[1]);
        $this->assertStringContainsString('First', $lines[2]);
    }
}
