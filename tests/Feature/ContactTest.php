<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_pagina_de_contacto_carga_exitosamente(): void
    {
        $response = $this->get('/contacto');
        $response->assertStatus(200);
    }

    private function validAntiSpamToken(): string
    {
        // Create a token with timestamp 10 seconds in the past to pass timing check
        return Crypt::encryptString(json_encode(['ts' => now()->subSeconds(10)->timestamp]));
    }

    public function test_el_formulario_de_contacto_requiere_nombre_email_y_mensaje(): void
    {
        $response = $this->post('/contacto', [
            'form_token' => $this->validAntiSpamToken(),
        ]);
        $response->assertSessionHasErrors(['nombre', 'email', 'mensaje']);
    }

    public function test_se_puede_enviar_un_mensaje_de_contacto_valido(): void
    {
        $data = [
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'mensaje' => 'Hola, me gustaría solicitar un presupuesto de desarrollo.',
            'form_token' => $this->validAntiSpamToken(),
        ];

        $response = $this->post('/contacto', $data);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('contact_messages', [
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
        ]);
    }

    // ── File Upload Tests ───────────────────────────────

    public function test_se_puede_enviar_mensaje_con_adjunto_pdf(): void
    {
        Queue::fake();
        $pdf = UploadedFile::fake()->create('proyecto.pdf', 100, 'application/pdf');

        $data = [
            'nombre' => 'María García',
            'email' => 'maria@example.com',
            'mensaje' => 'Adjunto el brief del proyecto.',
            'attachment' => $pdf,
            'form_token' => $this->validAntiSpamToken(),
        ];

        $response = $this->post('/contacto', $data);
        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('contact_messages', [
            'nombre' => 'María García',
            'email' => 'maria@example.com',
            'attachment_name' => 'proyecto.pdf',
        ]);
    }

    public function test_se_puede_enviar_mensaje_con_adjunto_docx(): void
    {
        Queue::fake();
        $docx = UploadedFile::fake()->create('brief.docx', 200, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        $data = [
            'nombre' => 'Carlos López',
            'email' => 'carlos@example.com',
            'mensaje' => '',
            'attachment' => $docx,
            'form_token' => $this->validAntiSpamToken(),
        ];

        $response = $this->post('/contacto', $data);
        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('contact_messages', [
            'nombre' => 'Carlos López',
            'attachment_name' => 'brief.docx',
        ]);
    }

    public function test_mensaje_es_requerido_cuando_no_hay_adjunto(): void
    {
        $response = $this->post('/contacto', [
            'nombre' => 'Test User',
            'email' => 'test@example.com',
            'mensaje' => '',
            'form_token' => $this->validAntiSpamToken(),
        ]);
        $response->assertSessionHasErrors(['mensaje']);
    }

    public function test_mensaje_no_es_requerido_cuando_hay_adjunto(): void
    {
        Queue::fake();
        $pdf = UploadedFile::fake()->create('docs.pdf', 50, 'application/pdf');

        $data = [
            'nombre' => 'Ana Martínez',
            'email' => 'ana@example.com',
            'mensaje' => '',
            'attachment' => $pdf,
            'form_token' => $this->validAntiSpamToken(),
        ];

        $response = $this->post('/contacto', $data);
        $response->assertSessionHasNoErrors();
    }

    public function test_adjunto_no_puede_superar_10mb(): void
    {
        $largePdf = UploadedFile::fake()->create('huge.pdf', 11000, 'application/pdf');

        $data = [
            'nombre' => 'Test User',
            'email' => 'test@example.com',
            'mensaje' => 'Mensaje con archivo grande.',
            'attachment' => $largePdf,
            'form_token' => $this->validAntiSpamToken(),
        ];

        $response = $this->post('/contacto', $data);
        $response->assertSessionHasErrors(['attachment']);
    }

    public function test_adjunto_solo_acepta_pdf_doc_docx(): void
    {
        $exe = UploadedFile::fake()->create('virus.exe', 10, 'application/x-msdownload');

        $data = [
            'nombre' => 'Test User',
            'email' => 'test@example.com',
            'mensaje' => 'Archivo no válido.',
            'attachment' => $exe,
            'form_token' => $this->validAntiSpamToken(),
        ];

        $response = $this->post('/contacto', $data);
        $response->assertSessionHasErrors(['attachment']);
    }

    public function test_mensaje_con_adjunto_guarda_attachment_name_y_dispatch_job(): void
    {
        Queue::fake();

        $pdf = UploadedFile::fake()->create('spec.pdf', 100, 'application/pdf');

        $data = [
            'nombre' => 'Test User',
            'email' => 'test@example.com',
            'mensaje' => 'Con archivo.',
            'attachment' => $pdf,
            'form_token' => $this->validAntiSpamToken(),
        ];

        $this->post('/contacto', $data);

        $message = ContactMessage::where('email', 'test@example.com')->first();
        $this->assertNotNull($message, 'Contact message should be created');
        $this->assertEquals('spec.pdf', $message->attachment_name);
        $this->assertNull($message->attachment_url, 'Cloud URL is set async by UploadToCloudinary job');

        // Verify the upload job was dispatched
        Queue::assertPushed(
            \App\Jobs\UploadToCloudinary::class,
            fn ($job) => $job->modelType === 'contact_attachment'
                && $job->modelId === $message->id
        );
    }
}
