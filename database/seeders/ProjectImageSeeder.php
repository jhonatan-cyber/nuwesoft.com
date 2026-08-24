<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectImageSeeder extends Seeder
{
    public function run(): void
    {
        $cloudName = config('cloudinary.cloud_name') ?: env('CLOUDINARY_CLOUD_NAME', 'dgzrhdysr');

        // En local permitimos fallback a demo cloud si no hay credencial real
        if (empty($cloudName) || $cloudName === 'test-cloud') {
            $cloudName = 'demo';
        }

        // URLs demo que sí existen (Cloudinary demo) — evitan 404 en dev
        $samples = [
            'https://res.cloudinary.com/' . $cloudName . '/image/upload/v1/nuwesoft/projects/las-munecas-cover.jpg',
            'https://res.cloudinary.com/' . $cloudName . '/image/upload/v1/nuwesoft/projects/logitech-dashboard.jpg',
            'https://res.cloudinary.com/' . $cloudName . '/image/upload/v1/nuwesoft/projects/healthdata-app.jpg',
            'https://res.cloudinary.com/' . $cloudName . '/image/upload/v1/nuwesoft/projects/cloud-migration.jpg',
            'https://res.cloudinary.com/' . $cloudName . '/image/upload/v1/nuwesoft/projects/fintech-automation.jpg',
        ];

        // Fallback picsum si cloud es demo y no tienes imágenes subidas
        $usePicsum = $cloudName === 'demo';

        $projects = Project::with('images')->get();
        foreach ($projects as $idx => $project) {
            if ($project->images->isNotEmpty()) {
                continue;
            }

            $slug = $project->slug ?: Str::slug($project->name);
            $baseUrl = $samples[$idx % count($samples)];

            if ($usePicsum) {
                // picsum no es Cloudinary, pero permite ver layout sin 404
                $baseUrl = 'https://picsum.photos/seed/' . $slug . '/800/600';
            }

            $project->images()->firstOrCreate(
                ['public_id' => 'nuwesoft/' . $project->getTable() . '/' . $slug . '-cover'],
                [
                    'image_url' => $baseUrl,
                    'order_index' => 0,
                    'alt' => $project->name . ' cover',
                ]
            );

            if ($idx === 0) {
                $galleryUrl = $usePicsum
                    ? 'https://picsum.photos/seed/' . $slug . '-gallery/800/600'
                    : 'https://res.cloudinary.com/' . $cloudName . '/image/upload/v1/nuwesoft/projects/las-munecas-gallery-2.jpg';

                $project->images()->firstOrCreate(
                    ['public_id' => 'nuwesoft/' . $project->getTable() . '/' . $slug . '-gallery-2'],
                    [
                        'image_url' => $galleryUrl,
                        'order_index' => 1,
                        'alt' => $project->name . ' gallery 2',
                    ]
                );
            }
        }
    }
}
