<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'client_name' => 'María Fernández',
                'client_role' => 'CTO',
                'client_company' => 'LogiTech SA',
                'content' => 'Nuwesoft transformó completamente nuestra plataforma logística. La migración fue impecable y el resultado superó nuestras expectativas. Redujimos los tiempos de procesamiento un 60% y el uptime ha sido del 99.9% desde el lanzamiento.',
                'rating' => 5,
                'status' => 'approved',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'client_name' => 'Carlos Mendoza',
                'client_role' => 'Director de Operaciones',
                'client_company' => 'Fintech Global',
                'content' => 'La automatización que implementaron con n8n e IA nos liberó 20 horas semanales de trabajo manual. El ROI fue positivo desde el primer mes. El equipo de Nuwesoft es excepcionalmente profesional y rápido.',
                'rating' => 5,
                'status' => 'approved',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'client_name' => 'Laura Gutiérrez',
                'client_role' => 'CEO',
                'client_company' => 'HealthData Corp',
                'content' => 'Migramos 15 años de infraestructura on-premise a AWS sin un solo minuto de downtime. Nuwesoft manejó toda la complejidad con una estrategia blue/green impecable. Los costos se redujeron un 50%.',
                'rating' => 5,
                'status' => 'approved',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'client_name' => 'Roberto Sánchez',
                'client_role' => 'Fundador',
                'client_company' => 'E-commerce Plus',
                'content' => 'Necesitábamos una plataforma web moderna y rápida. Nuwesoft entregó un sitio con React y Next.js que carga en menos de 2 segundos. Las ventas online crecieron un 40% en el primer trimestre.',
                'rating' => 4,
                'status' => 'approved',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
