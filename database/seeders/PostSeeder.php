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
                'content' => "CASO DE ESTUDIO: PLATAFORMA DE GESTIÓN LOGÍSTICA\n\n===============================================\n\nCLIENTE: Empresa líder en logística de Latinoamérica\nDURACIÓN: 6 meses\nSTACK: Laravel 13, Vue 3, PostgreSQL, Redis\n\n== DESAFÍO ==\n\nLa empresa operaba con un sistema legacy que no escalaba. Los procesos manuales generaban cuellos de botella y errores en la conciliación de inventario. Necesitaban una plataforma moderna que pudiera manejar el crecimiento proyectado.\n\n== SOLUCIÓN ==\n\nDiseñamos una arquitectura modular con Laravel en el backend y Vue 3 en el frontend. Implementamos:\n\n- Sistema de tracking en tiempo real con WebSockets\n- Panel de administración con roles y permisos\n- Integración con 3 APIs de transporte\n- Dashboard analítico con métricas clave\n\n== RESULTADOS ==\n\n- 40% reducción en errores de inventario\n- 60% más rápido en procesamiento de órdenes\n- 99.9% uptime desde el lanzamiento\n- Escalabilidad para 10x el volumen actual",
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
            ['slug' => 'desarrollo-app-movil-flutter'],
            [
                'title' => 'Desarrollo de App Móvil Multiplataforma con Flutter',
                'slug' => 'desarrollo-app-movil-flutter',
                'excerpt' => 'Cómo construimos una app de delivery que funciona en iOS y Android con un solo codebase, reduciendo tiempos de desarrollo un 50%.',
                'content' => "CASO DE ESTUDIO: APP DE DELIVERY\n\n===============================================\n\nCLIENTE: Startup de delivery en Buenos Aires\nDURACIÓN: 4 meses\nSTACK: Flutter, Dart, Firebase, Node.js, Stripe\n\n== DESAFÍO ==\n\nLa startup necesitaba lanzar rápido en iOS y Android sin duplicar esfuerzos. El presupuesto era limitado y el time-to-market crítico.\n\n== SOLUCIÓN ==\n\nDesarrollamos una app cross-platform con Flutter:\n\n- UI nativa en ambos sistemas operativos\n- Integración con Google Maps para tracking en tiempo real\n- Pagos con Stripe y Mercado Pago\n- Panel de administración web con Laravel\n- Notificaciones push con Firebase Cloud Messaging\n\n== RESULTADOS ==\n\n- 50% de reducción en tiempo de desarrollo\n- 4.7 estrellas en App Store y Google Play\n- 10,000 descargas en el primer mes\n- Costo de desarrollo 40% menor vs apps nativas",
                'category' => 'case-study',
                'tags' => ['Flutter', 'Firebase', 'Mobile', 'Startup'],
                'is_published' => true,
                'published_at' => now()->subDays(15),
                'author_name' => 'NUWESOFT',
            ]);

        Post::firstOrCreate(
            ['slug' => 'pipeline-ci-cd-github-actions'],
            [
                'title' => 'Pipeline CI/CD Completo con GitHub Actions',
                'slug' => 'pipeline-ci-cd-github-actions',
                'excerpt' => 'Guía práctica para configurar un pipeline de integración continua que ejecuta tests, code style, y despliega automáticamente.',
                'content' => "ARTÍTULO TÉCNICO: CI/CD CON GITHUB ACTIONS\n\n===============================================\n\n== INTRODUCCIÓN ==\n\nUn pipeline CI/CD bien configurado es la columna vertebral de cualquier equipo de desarrollo moderno. En este artículo compartimos cómo configuramos el pipeline de Nuwesoft.\n\n== STACK DEL PIPELINE ==\n\n- GitHub Actions como orquestador\n- PHPStan para análisis estático\n- Pint para code style\n- PHPUnit para tests\n- ESLint para frontend\n\n== FLUJO ==\n\n1. PR abierto → Tests + Lint + Build\n2. PR mergeado a main → Deploy automático a producción\n3. Backup de DB antes de cada deploy\n4. Healthcheck post-deploy\n5. Notificación a Discord\n\n== RESULTADOS ==\n\n- Deploy en 3 minutos vs 30 manuales\n- 0 errores de producción por código sin testear\n- Rollback automático si el healthcheck falla",
                'category' => 'technical',
                'tags' => ['CI/CD', 'GitHub Actions', 'DevOps'],
                'is_published' => true,
                'published_at' => now()->subDays(20),
                'author_name' => 'NUWESOFT',
            ]);

        Post::firstOrCreate(
            ['slug' => 'ia-para-atencion-al-cliente'],
            [
                'title' => 'Implementando IA para Atención al Cliente: Guía Práctica',
                'slug' => 'ia-para-atencion-al-cliente',
                'excerpt' => 'Cómo integrar modelos de lenguaje para automatizar respuestas frecuentes y mejorar la experiencia del cliente.',
                'content' => "ARTÍCULO: IA PARA ATENCIÓN AL CLIENTE\n\n===============================================\n\n== EL PROBLEMA ==\n\nLos equipos de soporte reciben cientos de preguntas repetitivas diariamente. Las respuestas tardan en llegar y la calidad es inconsistente.\n\n== NUESTRA SOLUCIÓN ==\n\nImplementamos un sistema de IA que:\n\n1. Analiza el historial de conversaciones\n2. Genera respuestas contextuales\n3. Aprende de los mejores agentes\n4. Escala a un humano cuando no tiene certeza\n\n== TECNOLOGÍA ==\n\n- OpenAI API (GPT-4)\n- Python + FastAPI\n- PostgreSQL para historial\n- Redis para cache de respuestas\n- n8n para orquestación\n\n== RESULTADOS ==\n\n- 70% de preguntas respondidas automáticamente\n- Tiempo de respuesta: 3 seg vs 45 min promedio\n- Satisfacción del cliente: +35%\n- Costo de soporte: -50%",
                'category' => 'insights',
                'tags' => ['IA', 'OpenAI', 'Automatización', 'Customer Success'],
                'is_published' => true,
                'published_at' => now()->subDays(25),
                'author_name' => 'NUWESOFT',
            ]);

        Post::firstOrCreate(
            ['slug' => 'migracion-cloud-on-premise-aws'],
            [
                'title' => 'Migración a Cloud: De On-Premise a AWS sin Downtime',
                'slug' => 'migracion-cloud-on-premise-aws',
                'excerpt' => 'Estrategia de migración gradual que llevó 15 años de infraestructura on-premise a AWS con cero downtime y mejora del 50% en costos.',
                'content' => "CASO DE ESTUDIO: MIGRACIÓN A CLOUD\n\n===============================================\n\nCLIENTE: Empresa de salud con 15 años de operación\nDURACIÓN: 8 meses\nSTACK: AWS, Terraform, Kubernetes, GitHub Actions\n\n== DESAFÍO ==\n\nInfraestructura on-premise con 15 años de antigüedad. Costos operativos elevados, falta de escalabilidad y vulnerabilidades de seguridad críticas. El downtime no era una opción.\n\n== SOLUCIÓN ==\n\nDiseñamos una estrategia de migración en fases:\n\n- Modernización gradual de aplicaciones legacy\n- Terraform para infraestructura como código\n- Estrategia blue/green para migración sin downtime\n- Pipeline CI/CD automatizado\n- Monitoreo 24/7 con alertas inteligentes\n\n== RESULTADOS ==\n\n- 50% reducción en costos de infraestructura\n- Cero downtime durante toda la migración\n- Deploy 5x más rápido\n- Cumplimiento normativo alcanzado",
                'category' => 'case-study',
                'tags' => ['AWS', 'Terraform', 'DevOps'],
                'is_published' => true,
                'published_at' => now()->subDays(10),
                'author_name' => 'NUWESOFT',
            ]);
    }
}
