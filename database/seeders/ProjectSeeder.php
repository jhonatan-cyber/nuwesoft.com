<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Technology;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Project 1: Las Muñecas de Ramón
        $stack1 = ['Next.js', 'MySQL', 'Tailwind', 'PostCSS', 'shadcn/ui', 'React', 'TypeScript', 'Radix UI', 'Sonner', 'SweetAlert2', 'React Hook Form', 'Zod', 'TanStack Query', 'Bcryptjs', 'JWT', 'Redis'];

        $project1 = Project::create([
            'name' => 'Las Muñecas de Ramón',
            'category' => 'web',
            'desc' => 'Sitio web institucional para nightclub exclusivo en Linares, Chile. Incluye información sobre servicios, eventos, ubicación y sección de empleo.',
            'icon' => 'Briefcase',
            'project_url' => 'https://xn--lasmuecasderamon-bub.com/',
            'is_active' => true,
        ]);
        $this->syncTech($project1, $stack1);

        // Project 2: LogiTech Platform
        $stack2 = ['Laravel', 'Vue.js', 'PostgreSQL', 'Redis', 'Docker', 'Tailwind'];

        $project2 = Project::create([
            'name' => 'LogiTech Platform',
            'category' => 'web',
            'desc' => 'Plataforma de gestión logística con tracking en tiempo real, dashboard analítico e integración con APIs de transporte.',
            'icon' => 'Truck',
            'project_url' => null,
            'is_active' => true,
        ]);
        $this->syncTech($project2, $stack2);

        // Project 3: HealthData App
        $stack3 = ['Flutter', 'Firebase', 'Node.js', 'PostgreSQL', 'Docker'];

        $project3 = Project::create([
            'name' => 'HealthData App',
            'category' => 'mobile',
            'desc' => 'App móvil multiplataforma para gestión de datos de salud con integración de wearables y alertas inteligentes.',
            'icon' => 'Heart',
            'project_url' => null,
            'is_active' => true,
        ]);
        $this->syncTech($project3, $stack3);

        // Project 4: Cloud Migration
        $stack4 = ['AWS', 'Terraform', 'Docker', 'Kubernetes', 'GitHub Actions'];

        $project4 = Project::create([
            'name' => 'Cloud Migration',
            'category' => 'cloud',
            'desc' => 'Migración de infraestructura on-premise a AWS con containerización, IaC y pipeline CI/CD automatizado.',
            'icon' => 'Cloud',
            'project_url' => null,
            'is_active' => true,
        ]);
        $this->syncTech($project4, $stack4);

        // Project 5: Fintech Automation
        $stack5 = ['n8n', 'Python', 'PostgreSQL', 'Redis', 'AWS'];

        $project5 = Project::create([
            'name' => 'Fintech Automation',
            'category' => 'automation',
            'desc' => 'Sistema de automatización para fintech con conciliación bancaria, clasificación inteligente de tickets y alertas proactivas.',
            'icon' => 'Zap',
            'project_url' => null,
            'is_active' => true,
        ]);
        $this->syncTech($project5, $stack5);
    }

    private function syncTech(Project $project, array $stack): void
    {
        $matchingTechIds = Technology::whereIn('name', $stack)->pluck('id')->toArray();
        if (! empty($matchingTechIds)) {
            $project->technologies()->sync($matchingTechIds);
        }
    }
}
