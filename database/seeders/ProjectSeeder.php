<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Project::create([
            'name' => 'Las Muñecas de Ramón',
            'category' => 'web',
            'stack' => ['Next.js', 'MySQL', 'Tailwind CSS', 'PostCSS', 'shadcn/ui', 'React', 'TypeScript', 'Radix UI', 'Sonner', 'SweetAlert2', 'React Hook Form', 'Zod', 'TanStack Query', 'Bcryptjs', 'JWT', 'Redis'],
            'desc' => 'Sitio web institucional para nightclub exclusivo en Linares, Chile. Incluye información sobre servicios, eventos, ubicación y sección de empleo.',
            'icon' => 'Briefcase',
            'image_url' => '',
            'project_url' => 'https://xn--lasmuecasderamon-bub.com/',
            'is_active' => true,
        ]);
    }
}
