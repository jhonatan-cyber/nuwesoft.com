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
        $stack = ['Next.js', 'MySQL', 'Tailwind', 'PostCSS', 'shadcn/ui', 'React', 'TypeScript', 'Radix UI', 'Sonner', 'SweetAlert2', 'React Hook Form', 'Zod', 'TanStack Query', 'Bcryptjs', 'JWT', 'Redis'];

        $project = Project::create([
            'name' => 'Las Muñecas de Ramón',
            'category' => 'web',
            'desc' => 'Sitio web institucional para nightclub exclusivo en Linares, Chile. Incluye información sobre servicios, eventos, ubicación y sección de empleo.',
            'icon' => 'Briefcase',
            'project_url' => 'https://xn--lasmuecasderamon-bub.com/',
            'is_active' => true,
        ]);

        // Sync technologies that match stack names — so logos show up
        $matchingTechIds = Technology::whereIn('name', $stack)->pluck('id')->toArray();
        if (!empty($matchingTechIds)) {
            $project->technologies()->sync($matchingTechIds);
        }
    }
}
