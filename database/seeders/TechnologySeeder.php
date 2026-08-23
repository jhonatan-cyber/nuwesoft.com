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
            ['name' => 'PHP', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg', 'category' => 'languages', 'invert_dark' => false],
            ['name' => 'JavaScript', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg', 'category' => 'languages', 'invert_dark' => false],
            ['name' => 'TypeScript', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/typescript/typescript-original.svg', 'category' => 'languages', 'invert_dark' => false],
            ['name' => 'Go', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/go/go-original.svg', 'category' => 'languages', 'invert_dark' => false],
            ['name' => 'Python', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg', 'category' => 'languages', 'invert_dark' => false],
            ['name' => 'Java', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg', 'category' => 'languages', 'invert_dark' => false],
            ['name' => 'C#', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/csharp/csharp-original.svg', 'category' => 'languages', 'invert_dark' => false],
            ['name' => 'Dart', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/dart/dart-original.svg', 'category' => 'languages', 'invert_dark' => false],
            ['name' => 'Node.js', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nodejs/nodejs-original.svg', 'category' => 'languages', 'invert_dark' => false],
            ['name' => 'Bun', 'logo_url' => 'https://bun.sh/logo.svg', 'category' => 'languages', 'invert_dark' => false],
            ['name' => 'Deno', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/denojs/denojs-original.svg', 'category' => 'languages', 'invert_dark' => false],

            // Frontend Frameworks
            ['name' => 'React', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/react/react-original.svg', 'category' => 'frontend', 'invert_dark' => false],
            ['name' => 'Vue.js', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vuejs/vuejs-original.svg', 'category' => 'frontend', 'invert_dark' => false],
            ['name' => 'Next.js', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nextjs/nextjs-original.svg', 'category' => 'frontend', 'invert_dark' => true],
            ['name' => 'Nuxt', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nuxtjs/nuxtjs-original.svg', 'category' => 'frontend', 'invert_dark' => false],
            ['name' => 'Angular', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/angularjs/angularjs-original.svg', 'category' => 'frontend', 'invert_dark' => false],
            ['name' => 'Svelte', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/svelte/svelte-original.svg', 'category' => 'frontend', 'invert_dark' => false],
            ['name' => 'Astro', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/astro/astro-original.svg', 'category' => 'frontend', 'invert_dark' => false],
            ['name' => 'Solid', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/solidjs/solidjs-original.svg', 'category' => 'frontend', 'invert_dark' => false],
            ['name' => 'Qwik', 'logo_url' => 'https://raw.githubusercontent.com/BuilderIO/qwik/main/packages/docs/public/logos/qwik-logo.svg', 'category' => 'frontend', 'invert_dark' => false],
            ['name' => 'Fresh', 'logo_url' => 'https://fresh.deno.dev/logo.svg', 'category' => 'frontend', 'invert_dark' => false],
            ['name' => 'jQuery', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/jquery/jquery-original.svg', 'category' => 'frontend', 'invert_dark' => false],

            // Backend Frameworks
            ['name' => 'Laravel', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/laravel/laravel-original.svg', 'category' => 'backend', 'invert_dark' => false],
            ['name' => 'NestJS', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nestjs/nestjs-original.svg', 'category' => 'backend', 'invert_dark' => false],
            ['name' => 'Spring Boot', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/spring/spring-original.svg', 'category' => 'backend', 'invert_dark' => false],
            ['name' => 'Django', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/django/django-plain.svg', 'category' => 'backend', 'invert_dark' => true],
            ['name' => 'Flask', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/flask/flask-original.svg', 'category' => 'backend', 'invert_dark' => true],
            ['name' => 'FastAPI', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/fastapi/fastapi-original.svg', 'category' => 'backend', 'invert_dark' => false],
            ['name' => '.NET Core', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/dotnetcore/dotnetcore-original.svg', 'category' => 'backend', 'invert_dark' => false],
            ['name' => 'Express', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/express/express-original.svg', 'category' => 'backend', 'invert_dark' => true],
            ['name' => 'Hono', 'logo_url' => 'https://hono.dev/images/logo.png', 'category' => 'backend', 'invert_dark' => false],
            ['name' => 'AdonisJS', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/adonisjs/adonisjs-original.svg', 'category' => 'backend', 'invert_dark' => false],
            ['name' => 'CodeIgniter', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/codeigniter/codeigniter-plain.svg', 'category' => 'backend', 'invert_dark' => false],

            // Mobile & Multiplatform
            ['name' => 'Flutter', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/flutter/flutter-original.svg', 'category' => 'mobile', 'invert_dark' => false],
            ['name' => 'React Native', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/react/react-original.svg', 'category' => 'mobile', 'invert_dark' => false],
            ['name' => 'Expo', 'logo_url' => 'https://www.vectorlogo.zone/logos/expoio/expoio-icon.svg', 'category' => 'mobile', 'invert_dark' => false],

            // Database & ORMs
            ['name' => 'PostgreSQL', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/postgresql/postgresql-original.svg', 'category' => 'database', 'invert_dark' => false],
            ['name' => 'MySQL', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg', 'category' => 'database', 'invert_dark' => false],
            ['name' => 'MongoDB', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mongodb/mongodb-original.svg', 'category' => 'database', 'invert_dark' => false],
            ['name' => 'SQLite', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/sqlite/sqlite-original.svg', 'category' => 'database', 'invert_dark' => false],
            ['name' => 'Redis', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/redis/redis-original.svg', 'category' => 'database', 'invert_dark' => false],
            ['name' => 'Prisma', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/prisma/prisma-original.svg', 'category' => 'database', 'invert_dark' => false],
            ['name' => 'Sequelize', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/sequelize/sequelize-original.svg', 'category' => 'database', 'invert_dark' => false],

            // Infrastructure & Cloud
            ['name' => 'Kubernetes', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/kubernetes/kubernetes-plain.svg', 'category' => 'infrastructure', 'invert_dark' => false],
            ['name' => 'Terraform', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/terraform/terraform-original.svg', 'category' => 'infrastructure', 'invert_dark' => false],
            ['name' => 'AWS', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/amazonwebservices/amazonwebservices-plain-wordmark.svg', 'category' => 'infrastructure', 'invert_dark' => false],
            ['name' => 'Google Cloud', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/googlecloud/googlecloud-original.svg', 'category' => 'infrastructure', 'invert_dark' => false],
            ['name' => 'Azure', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/azure/azure-original.svg', 'category' => 'infrastructure', 'invert_dark' => false],
            ['name' => 'Cloudflare', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/cloudflare/cloudflare-original.svg', 'category' => 'infrastructure', 'invert_dark' => false],

            // Automation & AI
            ['name' => 'n8n', 'logo_url' => 'https://icons.lobehub.com/n8n.svg', 'category' => 'automation', 'invert_dark' => false],
            ['name' => 'Zapier', 'logo_url' => 'https://www.vectorlogo.zone/logos/zapier/zapier-icon.svg', 'category' => 'automation', 'invert_dark' => false],
            ['name' => 'AIAGENT', 'logo_url' => 'https://icons.lobehub.com/openai-color.svg', 'category' => 'automation', 'invert_dark' => false],

            // UI & Styling
            ['name' => 'Tailwind', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tailwindcss/tailwindcss-original.svg', 'category' => 'ui', 'invert_dark' => false],
            ['name' => 'Bootstrap', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/bootstrap/bootstrap-original.svg', 'category' => 'ui', 'invert_dark' => false],
            ['name' => 'SCSS', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/sass/sass-original.svg', 'category' => 'ui', 'invert_dark' => false],
            ['name' => 'WordPress', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/wordpress/wordpress-plain.svg', 'category' => 'ui', 'invert_dark' => false],
            ['name' => 'PostCSS', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/postcss/postcss-original.svg', 'category' => 'ui', 'invert_dark' => false],
            ['name' => 'shadcn/ui', 'logo_url' => 'https://avatars.githubusercontent.com/u/139895814?s=64&v=4', 'category' => 'ui', 'invert_dark' => false],
            ['name' => 'Radix UI', 'logo_url' => 'https://raw.githubusercontent.com/radix-ui/logo/master/radix-ui-logo.svg', 'category' => 'ui', 'invert_dark' => false],
            ['name' => 'Sonner', 'logo_url' => 'https://sonner.emilkowalski.dev/favicon.svg', 'category' => 'ui', 'invert_dark' => false],
            ['name' => 'SweetAlert2', 'logo_url' => 'https://sweetalert2.github.io/images/SweetAlert2.png', 'category' => 'ui', 'invert_dark' => false],
            ['name' => 'React Hook Form', 'logo_url' => 'https://react-hook-form.com/images/logo-icon.svg', 'category' => 'ui', 'invert_dark' => false],
            ['name' => 'Zod', 'logo_url' => 'https://zod.dev/favicon.svg', 'category' => 'ui', 'invert_dark' => false],
            ['name' => 'TanStack Query', 'logo_url' => 'https://raw.githubusercontent.com/TanStack/query/main/media/logo.png', 'category' => 'frontend', 'invert_dark' => false],
            ['name' => 'Bcryptjs', 'logo_url' => '', 'category' => 'backend', 'invert_dark' => false],
            ['name' => 'JWT', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/jsonwebtokens/jsonwebtokens-original.svg', 'category' => 'backend', 'invert_dark' => false],
        ];

        foreach ($technologies as $tech) {
            Technology::updateOrCreate(
                ['name' => $tech['name']],
                [
                    'logo_url' => $tech['logo_url'],
                    'category' => $tech['category'],
                    'is_active' => true,
                    'invert_dark' => $tech['invert_dark'],
                ]
            );
        }
    }
}
