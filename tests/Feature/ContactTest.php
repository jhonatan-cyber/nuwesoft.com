<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_pagina_de_contacto_carga_exitosamente(): void
    {
        $response = $this->get('/contacto');
        $response->assertStatus(200);
    }

    public function test_el_formulario_de_contacto_requiere_nombre_email_y_mensaje(): void
    {
        $response = $this->post('/contacto', []);
        $response->assertSessionHasErrors(['nombre', 'email', 'mensaje']);
    }

    public function test_se_puede_enviar_un_mensaje_de_contacto_valido(): void
    {
        $data = [
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'mensaje' => 'Hola, me gustaría solicitar un presupuesto de desarrollo.',
        ];

        $response = $this->post('/contacto', $data);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('contact_messages', [
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
        ]);
    }
}
