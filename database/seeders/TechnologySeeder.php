<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Seeder;

class TechnologySeeder extends Seeder
{
    public function run(): void
    {
        $technologies = [
            // Languages & Runtimes
            ['name' => 'PHP', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg', 'category' => 'languages'],
            ['name' => 'JavaScript', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg', 'category' => 'languages'],
            ['name' => 'TypeScript', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/typescript/typescript-original.svg', 'category' => 'languages'],
            ['name' => 'Go', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/go/go-original.svg', 'category' => 'languages'],
            ['name' => 'Python', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg', 'category' => 'languages'],
            ['name' => 'Java', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg', 'category' => 'languages'],
            ['name' => 'C#', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/csharp/csharp-original.svg', 'category' => 'languages'],
            ['name' => 'Dart', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/dart/dart-original.svg', 'category' => 'languages'],
            ['name' => 'Node.js', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nodejs/nodejs-original.svg', 'category' => 'languages'],
            ['name' => 'Bun', 'logo_url' => 'https://bun.sh/logo.svg', 'category' => 'languages'],
            ['name' => 'Deno', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/denojs/denojs-original.svg', 'category' => 'languages'],

            // Frontend Frameworks
            ['name' => 'React', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/react/react-original.svg', 'category' => 'frontend'],
            ['name' => 'Vue.js', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vuejs/vuejs-original.svg', 'category' => 'frontend'],
            ['name' => 'Next.js', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nextjs/nextjs-original.svg', 'category' => 'frontend'],
            ['name' => 'Nuxt', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nuxtjs/nuxtjs-original.svg', 'category' => 'frontend'],
            ['name' => 'Angular', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/angularjs/angularjs-original.svg', 'category' => 'frontend'],
            ['name' => 'Svelte', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/svelte/svelte-original.svg', 'category' => 'frontend'],
            ['name' => 'Astro', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/astro/astro-original.svg', 'category' => 'frontend'],
            ['name' => 'Solid', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/solidjs/solidjs-original.svg', 'category' => 'frontend'],
            ['name' => 'Qwik', 'logo_url' => 'https://raw.githubusercontent.com/BuilderIO/qwik/main/packages/docs/public/logos/qwik-logo.svg', 'category' => 'frontend'],
            ['name' => 'Fresh', 'logo_url' => 'https://fresh.deno.dev/logo.svg', 'category' => 'frontend'],
            ['name' => 'jQuery', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/jquery/jquery-original.svg', 'category' => 'frontend'],

            // Backend Frameworks
            ['name' => 'Laravel', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/laravel/laravel-original.svg', 'category' => 'backend'],
            ['name' => 'NestJS', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nestjs/nestjs-original.svg', 'category' => 'backend'],
            ['name' => 'Spring Boot', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/spring/spring-original.svg', 'category' => 'backend'],
            ['name' => 'Django', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/django/django-plain.svg', 'category' => 'backend'],
            ['name' => 'Flask', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/flask/flask-original.svg', 'category' => 'backend'],
            ['name' => 'FastAPI', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/fastapi/fastapi-original.svg', 'category' => 'backend'],
            ['name' => '.NET Core', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/dotnetcore/dotnetcore-original.svg', 'category' => 'backend'],
            ['name' => 'Express', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/express/express-original.svg', 'category' => 'backend'],
            ['name' => 'Hono', 'logo_url' => 'https://hono.dev/images/logo.png', 'category' => 'backend'],
            ['name' => 'AdonisJS', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/adonisjs/adonisjs-original.svg', 'category' => 'backend'],
            ['name' => 'CodeIgniter', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/codeigniter/codeigniter-plain.svg', 'category' => 'backend'],

            // Mobile & Multiplatform
            ['name' => 'Flutter', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/flutter/flutter-original.svg', 'category' => 'mobile'],
            ['name' => 'React Native', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/react/react-original.svg', 'category' => 'mobile'],
            ['name' => 'Expo', 'logo_url' => 'https://www.vectorlogo.zone/logos/expoio/expoio-icon.svg', 'category' => 'mobile'],

            // Database & ORMs
            ['name' => 'PostgreSQL', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/postgresql/postgresql-original.svg', 'category' => 'database'],
            ['name' => 'MySQL', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg', 'category' => 'database'],
            ['name' => 'MongoDB', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mongodb/mongodb-original.svg', 'category' => 'database'],
            ['name' => 'SQLite', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/sqlite/sqlite-original.svg', 'category' => 'database'],
            ['name' => 'Redis', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/redis/redis-original.svg', 'category' => 'database'],
            ['name' => 'Prisma', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/prisma/prisma-original.svg', 'category' => 'database'],
            ['name' => 'Sequelize', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/sequelize/sequelize-original.svg', 'category' => 'database'],

            // Infrastructure & Cloud
            ['name' => 'Docker', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/docker/docker-original.svg', 'category' => 'infrastructure'],
            ['name' => 'Kubernetes', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/kubernetes/kubernetes-plain.svg', 'category' => 'infrastructure'],
            ['name' => 'Terraform', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/terraform/terraform-original.svg', 'category' => 'infrastructure'],
            ['name' => 'AWS', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/amazonwebservices/amazonwebservices-plain-wordmark.svg', 'category' => 'infrastructure'],
            ['name' => 'Google Cloud', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/googlecloud/googlecloud-original.svg', 'category' => 'infrastructure'],
            ['name' => 'Azure', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/azure/azure-original.svg', 'category' => 'infrastructure'],
            ['name' => 'Cloudflare', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/cloudflare/cloudflare-original.svg', 'category' => 'infrastructure'],

            // Automation & AI
            ['name' => 'n8n', 'logo_url' => 'https://icons.lobehub.com/n8n.svg', 'category' => 'automation'],
            ['name' => 'Zapier', 'logo_url' => 'https://www.vectorlogo.zone/logos/zapier/zapier-icon.svg', 'category' => 'automation'],
            ['name' => 'AIAGENT', 'logo_url' => 'https://icons.lobehub.com/openai-color.svg', 'category' => 'automation'],

            // UI & Styling
            ['name' => 'Tailwind', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tailwindcss/tailwindcss-original.svg', 'category' => 'ui'],
            ['name' => 'Bootstrap', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/bootstrap/bootstrap-original.svg', 'category' => 'ui'],
            ['name' => 'SCSS', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/sass/sass-original.svg', 'category' => 'ui'],
            ['name' => 'WordPress', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/wordpress/wordpress-plain.svg', 'category' => 'ui'],
        ];

        foreach ($technologies as $tech) {
            Technology::updateOrCreate(
                ['name' => $tech['name']],
                [
                    'logo_url' => $tech['logo_url'],
                    'category' => $tech['category'],
                    'is_active' => true,
                ]
            );
        }
    }
}
