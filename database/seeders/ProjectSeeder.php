<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Project 1: Las Muñecas de Ramón
        $stack1 = ['Next.js', 'MySQL', 'Tailwind', 'PostCSS', 'shadcn/ui', 'React', 'TypeScript', 'Radix UI', 'Sonner', 'SweetAlert2', 'React Hook Form', 'Zod', 'TanStack Query', 'Bcryptjs', 'JWT', 'Redis'];

        $project1 = Project::firstOrCreate(
            ['name' => 'Las Muñecas de Ramón'],
            [
                'slug' => Str::slug('Las Muñecas de Ramón'),
                'category' => 'web',
                'desc' => 'Sitio web institucional para nightclub exclusivo en Linares, Chile. Incluye información sobre servicios, eventos, ubicación y sección de empleo.',
                'icon' => 'Briefcase',
                'project_url' => 'https://xn--lasmuecasderamon-bub.com/',
                'is_active' => true,
            ]
        );
        $this->syncTech($project1, $stack1);

        // Project 2: LogiTech Platform
        $stack2 = ['Laravel', 'Vue.js', 'PostgreSQL', 'Redis', 'Tailwind'];

        $project2 = Project::firstOrCreate(
            ['name' => 'LogiTech Platform'],
            [
                'slug' => Str::slug('LogiTech Platform'),
                'category' => 'web',
                'desc' => 'Plataforma de gestión logística con tracking en tiempo real, dashboard analítico e integración con APIs de transporte.',
                'icon' => 'Truck',
                'project_url' => null,
                'is_active' => true,
            ]
        );
        $this->syncTech($project2, $stack2);

        // Project 3: HealthData App
        $stack3 = ['Flutter', 'Firebase', 'Node.js', 'PostgreSQL'];

        $project3 = Project::firstOrCreate(
            ['name' => 'HealthData App'],
            [
                'slug' => Str::slug('HealthData App'),
                'category' => 'mobile',
                'desc' => 'App móvil multiplataforma para gestión de datos de salud con integración de wearables y alertas inteligentes.',
                'icon' => 'Heart',
                'project_url' => null,
                'is_active' => true,
            ]
        );
        $this->syncTech($project3, $stack3);

        // Project 4: Cloud Migration
        $stack4 = ['AWS', 'Terraform', 'Kubernetes', 'GitHub Actions'];

        $project4 = Project::firstOrCreate(
            ['name' => 'Cloud Migration'],
            [
                'slug' => Str::slug('Cloud Migration'),
                'category' => 'cloud',
                'desc' => 'Migración de infraestructura on-premise a AWS con containerización, IaC y pipeline CI/CD automatizado.',
                'icon' => 'Cloud',
                'project_url' => null,
                'is_active' => true,
            ]
        );
        $this->syncTech($project4, $stack4);

        // Project 5: Fintech Automation
        $stack5 = ['n8n', 'Python', 'PostgreSQL', 'Redis', 'AWS'];

        $project5 = Project::firstOrCreate(
            ['name' => 'Fintech Automation'],
            [
                'slug' => Str::slug('Fintech Automation'),
                'category' => 'automation',
                'desc' => 'Sistema de automatización para fintech con conciliación bancaria, clasificación inteligente de tickets y alertas proactivas.',
                'icon' => 'Zap',
                'project_url' => null,
                'is_active' => true,
            ]
        );
        $this->syncTech($project5, $stack5);

        // Imágenes demo se crean en ProjectImageSeeder (idempotente, con fallback picsum/demo)
        // Mantener compatibilidad si se ejecuta solo ProjectSeeder:
        $this->call(ProjectImageSeeder::class);
    }

    private function syncTech(Project $project, array $stack): void
    {
        $matchingTechIds = Technology::whereIn('name', $stack)->pluck('id')->toArray();
        if (! empty($matchingTechIds)) {
            $project->technologies()->sync($matchingTechIds);
        }
    }
}
