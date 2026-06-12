<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        Post::firstOrCreate(
            ['slug' => 'plataforma-gestion-logistica-laravel-vue'],
            [
            'title' => 'Plataforma de Gestión Logística con Laravel + Vue 3',
            'slug' => 'plataforma-gestion-logistica-laravel-vue',
            'excerpt' => 'Arquitectura escalable para una plataforma de gestión logística que procesa miles de transacciones por segundo con integridad total.',
            'content' => "CASO DE ESTUDIO: PLATAFORMA DE GESTIÓN LOGÍSTICA\n\n===============================================\n\nCLIENTE: Empresa líder en logística de Latinoamérica\nDURACIÓN: 6 meses\nSTACK: Laravel 13, Vue 3, PostgreSQL, Redis, Docker\n\n== DESAFÍO ==\n\nLa empresa operaba con un sistema legacy que no escalaba. Los procesos manuales generaban cuellos de botella y errores en la conciliación de inventario. Necesitaban una plataforma moderna que pudiera manejar el crecimiento proyectado.\n\n== SOLUCIÓN ==\n\nDiseñamos una arquitectura modular con Laravel en el backend y Vue 3 en el frontend. Implementamos:\n\n- Sistema de tracking en tiempo real con WebSockets\n- Panel de administración con roles y permisos\n- Integración con 3 APIs de transporte\n- Dashboard analítico con métricas clave\n\n== RESULTADOS ==\n\n- 40% reducción en errores de inventario\n- 60% más rápido en procesamiento de órdenes\n- 99.9% uptime desde el lanzamiento\n- Escalabilidad para 10x el volumen actual",
            'category' => 'case-study',
            'tags' => ['Laravel', 'Vue 3', 'PostgreSQL', 'Arquitectura'],
            'is_published' => true,
            'published_at' => now()->subDays(2),
            'author_name' => 'NUWESOFT',
        ]);

        Post::firstOrCreate(
            ['slug' => 'automatizacion-procesos-n8n-ia'],
            [
            'title' => 'Automatización de Procesos con n8n + IA',
            'slug' => 'automatizacion-procesos-n8n-ia',
            'excerpt' => 'Cómo eliminamos 20 horas semanales de trabajo manual mediante flujos automatizados con n8n e inteligencia artificial.',
            'content' => "CASO DE ESTUDIO: AUTOMATIZACIÓN DE PROCESOS\n\n===============================================\n\nCLIENTE: Fintech en etapa de crecimiento\nDURACIÓN: 3 meses\nSTACK: n8n, Python, OpenAI API, PostgreSQL, AWS\n\n== DESAFÍO ==\n\nEl equipo de operaciones dedicaba 20+ horas semanales a tareas repetitivas: conciliación de pagos, carga de datos en CRM y seguimiento de clientes. Necesitaban liberar capacidad para tareas de alto valor.\n\n== SOLUCIÓN ==\n\nImplementamos una arquitectura de automatización con n8n como orquestador central:\n\n- Flujos autónomos de conciliación bancaria\n- Integración bidireccional con CRM y ERP\n- Clasificación inteligente de tickets con IA\n- Alertas proactivas en Slack\n\n== RESULTADOS ==\n\n- 20h/semana recuperadas para el equipo\n- 95% de precisión en clasificación automática\n- Reducción del 70% en errores de carga de datos\n- ROI positivo desde el mes 1",
            'category' => 'technical',
            'tags' => ['n8n', 'IA', 'Automatización', 'Python'],
            'is_published' => true,
            'published_at' => now()->subDays(5),
            'author_name' => 'NUWESOFT',
        ]);

        Post::firstOrCreate(
            ['slug' => 'migracion-cloud-on-premise-aws'],
            [
            'title' => 'Migración a Cloud: De On-Premise a AWS sin Downtime',
            'slug' => 'migracion-cloud-on-premise-aws',
            'excerpt' => 'Estrategia de migración gradual que llevó 15 años de infraestructura on-premise a AWS con cero downtime y mejora del 50% en costos.',
            'content' => "CASO DE ESTUDIO: MIGRACIÓN A CLOUD\n\n===============================================\n\nCLIENTE: Empresa de salud con 15 años de operación\nDURACIÓN: 8 meses\nSTACK: AWS, Terraform, Docker, Kubernetes, GitHub Actions\n\n== DESAFÍO ==\n\nInfraestructura on-premise con 15 años de antigüedad. Costos operativos elevados, falta de escalabilidad y vulnerabilidades de seguridad críticas. El downtime no era una opción.\n\n== SOLUCIÓN ==\n\nDiseñamos una estrategia de migración en fases:\n\n- Containerización de aplicaciones legacy\n- Terraform para infraestructura como código\n- Estrategia blue/green para migración sin downtime\n- Pipeline CI/CD automatizado\n- Monitoreo 24/7 con alertas inteligentes\n\n== RESULTADOS ==\n\n- 50% reducción en costos de infraestructura\n- Cero downtime durante toda la migración\n- Deploy 5x más rápido\n- Cumplimiento normativo alcanzado",
            'category' => 'case-study',
            'tags' => ['AWS', 'Docker', 'Terraform', 'DevOps'],
            'is_published' => true,
            'published_at' => now()->subDays(10),
            'author_name' => 'NUWESOFT',
        ]);
    }
}
