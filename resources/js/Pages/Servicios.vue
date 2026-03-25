<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import PublicGridBackground from '@/Components/PublicGridBackground.vue';
import PublicSiteHeader from '@/Components/PublicSiteHeader.vue';
import PublicSiteFooter from '@/Components/PublicSiteFooter.vue';
import n8nLogo from '@/Assets/n8n.svg';
import encoreLogo from '@/Assets/encore-logo-full.svg';
import n8nColorLogo from '@/Assets/n8n-color.svg';
import axiosLogo from '@/Assets/axios.png';
import daisyuiLogo from '@/Assets/daisyui.png';
import drizzleLogo from '@/Assets/drizzleorm.png';
import edgedbLogo from '@/Assets/edgedb.png';
import aiagentLogo from '@/Assets/iaagent.png';
import makeLogo from '@/Assets/make.png';
import pnpmLogo from '@/Assets/pnpm.png';
import prismaLogo from '@/Assets/primaorm.png';
import radixLogo from '@/Assets/radix.png';
import railwayLogo from '@/Assets/railway.png';
import redwoodLogo from '@/Assets/redwoodjs.png';
import shadcnLogo from '@/Assets/shadcn.png';
import solidLogo from '@/Assets/solid.png';
import kyselyLogo from '@/Assets/kysely.jpg';
import mauiLogo from '@/Assets/maui.jpg';
import mongooseLogo from '@/Assets/mongoose.jpg';
import tanstackLogo from '@/Assets/tanstack-start.webp';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import {
  Card,
  CardHeader,
  CardTitle,
  CardDescription,
  CardContent,
  CardFooter,
} from '@/Components/ui/card';
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/Components/ui/tooltip'
import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from '@/Components/ui/accordion'

const { t } = useI18n();

const serviceStats = [
    { value: 'END-TO-END', label: t('servicios.stats.s1') },
    { value: 'MULTISTACK', label: t('servicios.stats.s2') },
    { value: 'SHIP FAST', label: t('servicios.stats.s3') },
];

const services = [
    {
        slug: 'software',
        eyebrow: t('servicios.items.software.eyebrow'),
        title: t('servicios.items.software.title'),
        description: t('servicios.items.software.desc'),
        stack_desc: t('servicios.items.software.stack_desc'),
        outcome: t('servicios.items.software.outcome'),
        bullets: [
            t('servicios.items.software.b1'),
            t('servicios.items.software.b2'),
            t('servicios.items.software.b3'),
        ],
        icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/laravel/laravel-original.svg',
        color: 'bg-brutalist-pink',
        items: [
            { name: 'Laravel', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/laravel/laravel-original.svg' },
            { name: 'PHP', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg' },
            { name: 'NestJS', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nestjs/nestjs-original.svg' },
            { name: 'Spring Boot', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/spring/spring-original.svg' },
            { name: 'Django', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/django/django-plain.svg' },
            { name: 'Node.js', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nodejs/nodejs-original.svg' },
            { name: 'Go', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/go/go-original.svg' }
        ]
    },
    {
        slug: 'cloud',
        eyebrow: t('servicios.items.cloud.eyebrow'),
        title: t('servicios.items.cloud.title'),
        description: t('servicios.items.cloud.desc'),
        stack_desc: t('servicios.items.cloud.stack_desc'),
        outcome: t('servicios.items.cloud.outcome'),
        bullets: [
            t('servicios.items.cloud.b1'),
            t('servicios.items.cloud.b2'),
            t('servicios.items.cloud.b3'),
        ],
        icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/docker/docker-original.svg',
        color: 'bg-brutalist-blue',
        items: [
            { name: 'Docker', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/docker/docker-original.svg' },
            { name: 'Kubernetes', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/kubernetes/kubernetes-plain.svg' },
            { name: 'Terraform', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/terraform/terraform-original.svg' },
            { name: 'AWS', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/amazonwebservices/amazonwebservices-plain-wordmark.svg' },
            { name: 'Google Cloud', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/googlecloud/googlecloud-original.svg' },
            { name: 'Azure', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/azure/azure-original.svg' },
            { name: 'Helm', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/helm/helm-original.svg' }
        ]
    },
    {
        slug: 'automation',
        eyebrow: t('servicios.items.automation.eyebrow'),
        title: t('servicios.items.automation.title'),
        description: t('servicios.items.automation.desc'),
        stack_desc: t('servicios.items.automation.stack_desc'),
        outcome: t('servicios.items.automation.outcome'),
        bullets: [
            t('servicios.items.automation.b1'),
            t('servicios.items.automation.b2'),
            t('servicios.items.automation.b3'),
        ],
        icon: n8nLogo,
        color: 'bg-brutalist-yellow',
        items: [
            { name: 'n8n', icon: n8nColorLogo },
            { name: 'Make.com', icon: makeLogo },
            { name: 'Python', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg' },
            { name: 'OpenAI / AI Agent', icon: aiagentLogo },
            { name: 'Zapier', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg' },
            { name: 'Playwright', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/playwright/playwright-original.svg' }
        ]
    },
    {
        slug: 'frontend',
        eyebrow: t('servicios.items.frontend.eyebrow'),
        title: t('servicios.items.frontend.title'),
        description: t('servicios.items.frontend.desc'),
        stack_desc: t('servicios.items.frontend.stack_desc'),
        outcome: t('servicios.items.frontend.outcome'),
        bullets: [
            t('servicios.items.frontend.b1'),
            t('servicios.items.frontend.b2'),
            t('servicios.items.frontend.b3'),
        ],
        icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vuejs/vuejs-original.svg',
        color: 'bg-brutalist-pink',
        items: [
            { name: 'Vue.js', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vuejs/vuejs-original.svg' },
            { name: 'React', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/react/react-original.svg' },
            { name: 'Next.js', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nextjs/nextjs-original.svg' },
            { name: 'Astro', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/astro/astro-original.svg' },
            { name: 'TypeScript', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/typescript/typescript-original.svg' },
            { name: 'Tailwind CSS', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tailwindcss/tailwindcss-original.svg' },
            { name: 'Vite', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vite/vite-original.svg' }
        ]
    }
];

const techCategories = [
    {
        name: 'Languages & Runtimes',
        key: 'languages',
        items: [
            { name: 'PHP', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg' },
            { name: 'JavaScript', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg' },
            { name: 'TypeScript', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/typescript/typescript-original.svg' },
            { name: 'Go', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/go/go-original.svg' },
            { name: 'Python', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg' },
            { name: 'Java', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg' },
            { name: 'C#', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/csharp/csharp-original.svg' },
            { name: 'Dart', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/dart/dart-original.svg' },
            { name: 'Node.js', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nodejs/nodejs-original.svg' },
            { name: 'Bun', icon: 'https://bun.sh/logo.svg' },
            { name: 'Deno', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/denojs/denojs-original.svg' },
        ]
    },
    {
        name: 'Frontend Frameworks',
        key: 'frontend',
        items: [
            { name: 'React', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/react/react-original.svg' },
            { name: 'Vue.js', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vuejs/vuejs-original.svg' },
            { name: 'Next.js', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nextjs/nextjs-original.svg' },
            { name: 'Nuxt', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nuxtjs/nuxtjs-original.svg' },
            { name: 'Angular', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/angularjs/angularjs-original.svg' },
            { name: 'Svelte', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/svelte/svelte-original.svg' },
            { name: 'Astro', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/astro/astro-original.svg' },
            { name: 'Solid', icon: solidLogo },
            { name: 'Qwik', icon: 'https://raw.githubusercontent.com/BuilderIO/qwik/main/packages/docs/public/logos/qwik-logo.svg' },
            { name: 'Fresh', icon: 'https://fresh.deno.dev/logo.svg' },
            { name: 'jQuery', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/jquery/jquery-original.svg' },
            { name: 'TanStack Start', icon: tanstackLogo },
        ]
    },
    {
        name: 'Backend Frameworks',
        key: 'backend',
        items: [
            { name: 'Laravel', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/laravel/laravel-original.svg' },
            { name: 'NestJS', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nestjs/nestjs-original.svg' },
            { name: 'Spring Boot', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/spring/spring-original.svg' },
            { name: 'Django', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/django/django-plain.svg' },
            { name: 'Flask', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/flask/flask-original.svg' },
            { name: 'FastAPI', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/fastapi/fastapi-original.svg' },
            { name: '.NET Core', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/dotnetcore/dotnetcore-original.svg' },
            { name: 'Express', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/express/express-original.svg' },
            { name: 'Hono', icon: 'https://hono.dev/images/logo.png' },
            { name: 'Elysia', icon: 'https://elysiajs.com/assets/elysia.svg' },
            { name: 'Fastify', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/fastify/fastify-original.svg' },
            { name: 'AdonisJS', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/adonisjs/adonisjs-original.svg' },
            { name: 'Nitro', icon: 'https://nitro.unjs.io/icon.svg' },
            { name: 'CodeIgniter', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/codeigniter/codeigniter-plain.svg' },
            { name: 'RedwoodJS', icon: redwoodLogo },
            { name: 'Encore.ts', icon: encoreLogo },
        ]
    },
    {
        name: 'Mobile & Multiplatform',
        key: 'mobile',
        items: [
            { name: 'Flutter', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/flutter/flutter-original.svg' },
            { name: 'React Native', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/react/react-original.svg' },
            { name: 'Expo', icon: 'https://www.vectorlogo.zone/logos/expoio/expoio-icon.svg' },
            { name: 'Maui', icon: mauiLogo },
        ]
    },
    {
        name: 'Database & ORMs',
        key: 'database',
        items: [
            { name: 'PostgreSQL', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/postgresql/postgresql-original.svg' },
            { name: 'MySQL', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg' },
            { name: 'MongoDB', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mongodb/mongodb-original.svg' },
            { name: 'SQLite', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/sqlite/sqlite-original.svg' },
            { name: 'Redis', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/redis/redis-original.svg' },
            { name: 'EdgeDB', icon: edgedbLogo },
            { name: 'Drizzle', icon: drizzleLogo },
            { name: 'Prisma', icon: prismaLogo },
            { name: 'Mongoose', icon: mongooseLogo },
            { name: 'TypeORM', icon: 'https://raw.githubusercontent.com/typeorm/typeorm/master/resources/logo_big.png' },
            { name: 'Sequelize', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/sequelize/sequelize-original.svg' },
            { name: 'Kysely', icon: kyselyLogo },
            { name: 'MikroORM', icon: 'https://mikro-orm.io/img/logo.svg' },
        ]
    },
    {
        name: 'Infrastructure & Cloud',
        key: 'infrastructure',
        items: [
            { name: 'Docker', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/docker/docker-original.svg' },
            { name: 'Kubernetes', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/kubernetes/kubernetes-plain.svg' },
            { name: 'Terraform', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/terraform/terraform-original.svg' },
            { name: 'AWS', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/amazonwebservices/amazonwebservices-plain-wordmark.svg' },
            { name: 'Google Cloud', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/googlecloud/googlecloud-original.svg' },
            { name: 'Azure', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/azure/azure-original.svg' },
            { name: 'Cloudflare', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/cloudflare/cloudflare-original.svg' },
            { name: 'Fly.io', icon: 'https://www.vectorlogo.zone/logos/flyio/flyio-icon.svg' },
            { name: 'Railway', icon: railwayLogo },
            { name: 'Helm', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/helm/helm-original.svg' },
            { name: 'Nitro', icon: 'https://nitro.unjs.io/icon.svg' },
        ]
    },
    {
        name: 'Automation & AI',
        key: 'automation',
        items: [
            { name: 'n8n', icon: n8nColorLogo },
            { name: 'Zapier', icon: 'https://www.vectorlogo.zone/logos/zapier/zapier-icon.svg' },
            { name: 'Make.com', icon: makeLogo },
            { name: 'AI Agent', icon: 'https://www.vectorlogo.zone/logos/openai/openai-icon.svg' },
        ]
    },
    {
        name: 'Tools & Ecosystem',
        key: 'tools',
        items: [
            { name: 'Git', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/git/git-original.svg' },
            { name: 'Vite', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vite/vite-original.svg' },
            { name: 'npm', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/npm/npm-original-wordmark.svg' },
            { name: 'pnpm', icon: pnpmLogo },
            { name: 'yarn', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/yarn/yarn-original.svg' },
            { name: 'Axios', icon: axiosLogo },
            { name: 'WordPress', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/wordpress/wordpress-plain.svg' },
        ]
    },
    {
        name: 'UI & Styling',
        key: 'ui',
        items: [
            { name: 'Tailwind', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tailwindcss/tailwindcss-original.svg' },
            { name: 'Bootstrap', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/bootstrap/bootstrap-original.svg' },
            { name: 'SCSS', icon: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/sass/sass-original.svg' },
            { name: 'shadcn/ui', icon: shadcnLogo },
            { name: 'daisyUI', icon: daisyuiLogo },
            { name: 'Radix UI', icon: radixLogo },
            { name: 'Base UI', icon: 'https://raw.githubusercontent.com/mui/base-ui/master/docs/public/static/logo.svg' },
        ]
    }
];

const isVisible = ref(false);
onMounted(() => {
    isVisible.value = true;
});

const processSteps = [
    { name: t('servicios.steps.discovery.name'), desc: t('servicios.steps.discovery.desc'), icon: 'discovery' },
    { name: t('servicios.steps.architect.name'), desc: t('servicios.steps.architect.desc'), icon: 'architect' },
    { name: t('servicios.steps.develop.name'), desc: t('servicios.steps.develop.desc'), icon: 'develop' },
    { name: t('servicios.steps.deploy.name'), desc: t('servicios.steps.deploy.desc'), icon: 'deploy' },
];
</script>

<template>
    <Head title="Nuestros Servicios | NUWESOFT Engineering" />
    
    <div class="min-h-screen overflow-x-hidden bg-white font-sans text-black selection:bg-brutalist-yellow selection:text-black dark:bg-black dark:text-white">
        <PublicGridBackground />
        <PublicSiteHeader />

        <main class="relative pt-48 pb-32 px-6">
            <div class="max-w-[1400px] mx-auto relative z-10">
                <!-- Header -->
                <div :class="['transition-all duration-700 transform mb-24', isVisible ? 'translate-x-0 opacity-100' : '-translate-x-12 opacity-0']">
                    <div class="grid gap-12 xl:grid-cols-[1.1fr_0.9fr] xl:items-end">
                        <div>
                            <Badge class="mb-8 inline-block rotate-1 border-4 border-black bg-brutalist-pink px-4 py-2 text-xl font-black text-white">{{ t('servicios.badge') }}</Badge>
                            <h1 class="mb-10 text-[clamp(3rem,8vw,6.3rem)] font-display font-black uppercase italic leading-[0.8] tracking-tighter">
                                {{ t('servicios.title1') }} <br/>
                                <span class="ml-[-1rem] bg-brutalist-yellow px-4 text-black">{{ t('servicios.title2') }}</span> <br/>
                                <span class="relative mt-4 inline-block text-white">
                                    <span class="absolute inset-0 -rotate-1 bg-black z-0"></span>
                                    <span class="relative z-10 px-4">{{ t('servicios.title3') }}</span>
                                </span>
                            </h1>
                            <p class="max-w-3xl border-l-8 border-black pl-6 text-2xl font-black uppercase italic leading-tight dark:border-white">
                                {{ t('servicios.subtitle') }}
                            </p>
                        </div>

                        <div class="space-y-6">
                            <div class="border-4 border-black bg-white p-8 shadow-brutalist dark:border-white dark:bg-zinc-950 dark:shadow-brutalist-white">
                                <p class="text-sm font-black uppercase tracking-[0.28em] text-brutalist-pink">{{ t('servicios.lead_label') }}</p>
                                <p class="mt-4 text-xl font-black uppercase leading-tight md:text-2xl">
                                    {{ t('servicios.lead_text') }}
                                </p>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-3">
                                <div
                                    v-for="stat in serviceStats"
                                    :key="stat.label"
                                    class="border-4 border-black bg-brutalist-yellow p-5 text-black shadow-brutalist dark:border-white dark:shadow-brutalist-white"
                                >
                                    <div class="text-2xl font-display font-black italic leading-none md:text-3xl">{{ stat.value }}</div>
                                    <div class="mt-3 text-[11px] font-black uppercase tracking-[0.24em]">{{ stat.label }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services Grid -->
                <div class="mb-16 grid gap-8 lg:grid-cols-[0.75fr_1.25fr]">
                    <div class="border-4 border-black bg-black p-8 text-white shadow-brutalist dark:border-white dark:shadow-brutalist-white">
                        <p class="text-sm font-black uppercase tracking-[0.28em] text-brutalist-yellow">{{ t('servicios.map_label') }}</p>
                        <h2 class="mt-4 text-4xl font-display font-black uppercase italic leading-none md:text-5xl">{{ t('servicios.map_title') }}</h2>
                        <p class="mt-6 text-lg font-black uppercase leading-tight text-white/75">
                            {{ t('servicios.map_text') }}
                        </p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <a
                            v-for="(service, index) in services"
                            :key="`anchor-${service.slug}`"
                            :href="`#${service.slug}`"
                            class="group border-4 border-black bg-white p-5 text-black shadow-brutalist transition-all hover:-translate-y-1 hover:bg-brutalist-yellow hover:text-black dark:border-white dark:bg-zinc-950 dark:text-white dark:shadow-brutalist-white dark:hover:bg-brutalist-yellow dark:hover:text-black"
                        >
                            <div class="text-[11px] font-black uppercase tracking-[0.28em] opacity-55 transition-colors group-hover:text-black dark:group-hover:text-black">0{{ index + 1 }}</div>
                            <div class="mt-4 min-h-[4.5rem] text-xl font-black uppercase leading-tight break-words transition-colors group-hover:text-black dark:group-hover:text-black">
                                {{ service.title }}
                            </div>
                            <div class="mt-5 flex items-center justify-between gap-3 text-[11px] font-black uppercase tracking-[0.24em] transition-colors group-hover:text-black dark:group-hover:text-black">
                                <span>{{ service.eyebrow }}</span>
                                <span class="shrink-0 transition-transform group-hover:translate-x-1">-></span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-12 mb-40 md:grid-cols-2">
                    <Card v-for="(service, index) in services" :key="index"
                        :id="service.slug"
                        class="group rounded-none border-4 border-black dark:border-white bg-white dark:bg-black shadow-brutalist dark:shadow-brutalist-white hover:shadow-brutalist-hover-lg dark:hover:shadow-brutalist-white-lg hover:-translate-x-2 hover:-translate-y-2 transition-all duration-300 overflow-hidden flex flex-col h-full relative">
                        
                        <!-- Decorative element -->
                        <div :class="['absolute top-0 right-0 w-32 h-32 opacity-10 -mr-16 -mt-16 rounded-full transition-transform duration-700 group-hover:scale-150', service.color]"></div>

                        <CardHeader class="relative z-10 flex flex-row items-start justify-between p-10 pb-0">
                            <TooltipProvider :delay-duration="0">
                                <div class="flex -space-x-3 overflow-hidden group-hover:space-x-1 transition-all duration-500">
                                    <div v-for="item in service.items" :key="item.name" class="inline-block">
                                        <Tooltip>
                                            <TooltipTrigger as-child>
                                                <div class="w-16 h-16 border-4 border-black dark:border-white bg-white dark:bg-zinc-800 p-3 flex items-center justify-center cursor-help group-hover:border-brutalist-yellow transition-all hover:scale-125 z-10 hover:rotate-6">
                                                    <img :src="item.icon" :alt="item.name" class="w-full h-full object-contain grayscale group-hover:grayscale-0 transition-all dark:invert dark:group-hover:invert-0" />
                                                </div>
                                            </TooltipTrigger>
                                            <TooltipContent class="bg-black dark:bg-white text-white dark:text-black border-2 border-white dark:border-black rounded-none font-black uppercase">
                                                {{ item.name }}
                                            </TooltipContent>
                                        </Tooltip>
                                    </div>
                                </div>
                            </TooltipProvider>
                            <span class="text-7xl font-display font-black opacity-10 leading-none text-black dark:text-white group-hover:opacity-30 transition-opacity">0{{ index + 1 }}</span>
                        </CardHeader>

                        <CardContent class="p-10 pt-12 flex-grow relative z-10">
                            <div class="mb-5 inline-flex border-2 border-current px-3 py-1 text-[11px] font-black uppercase tracking-[0.28em]">
                                {{ service.eyebrow }}
                            </div>
                            <CardTitle class="text-4xl md:text-5xl font-display font-black uppercase italic mb-8 group-hover:bg-brutalist-yellow group-hover:text-black px-2 inline-block transition-colors text-black dark:text-white">
                                {{ service.title }}
                            </CardTitle>
                            
                            <CardDescription class="text-xl font-black uppercase leading-[1.1] mb-10 text-black dark:text-white/90 opacity-100 italic">
                                {{ service.description }}
                            </CardDescription>

                            <div class="mb-8 border-l-8 border-black pl-5 dark:border-white">
                                <p class="text-[11px] font-black uppercase tracking-[0.28em] opacity-55">{{ t('servicios.outcome_label') }}</p>
                                <p class="mt-3 text-base font-black uppercase leading-tight">
                                    {{ service.outcome }}
                                </p>
                            </div>

                            <div class="mb-10 space-y-3">
                                <div
                                    v-for="bullet in service.bullets"
                                    :key="bullet"
                                    class="flex items-start gap-3 border-2 border-black/15 px-4 py-3 dark:border-white/20"
                                >
                                    <span class="mt-1 h-3 w-3 shrink-0 bg-current"></span>
                                    <span class="text-xs font-black uppercase leading-relaxed">{{ bullet }}</span>
                                </div>
                            </div>

                            <Accordion type="single" collapsible class="w-full border-4 border-black dark:border-white bg-gray-50 dark:bg-zinc-900 shadow-brutalist group-hover:shadow-none transition-all">
                                <AccordionItem value="tech-details" class="border-none">
                                    <AccordionTrigger class="px-6 py-5 text-xl font-black uppercase tracking-widest hover:bg-brutalist-pink hover:text-white hover:no-underline transition-colors text-black dark:text-white">
                                        {{ t('servicios.tech_details') }}
                                    </AccordionTrigger>
                                    <AccordionContent class="px-6 pb-8">
                                        <div class="pt-6 border-t-4 border-black dark:border-white">
                                            <p class="text-lg font-black uppercase leading-tight italic text-black dark:text-white">
                                                {{ service.stack_desc }}
                                            </p>
                                        </div>
                                    </AccordionContent>
                                </AccordionItem>
                            </Accordion>
                        </CardContent>

                        <CardFooter class="p-10 pt-0 mt-auto relative z-10">
                            <Button as-child class="w-full bg-black text-white dark:bg-white dark:text-black font-black border-4 border-black dark:border-white rounded-none h-auto py-6 text-2xl hover:bg-brutalist-blue hover:text-white transition-colors shadow-brutalist hover:shadow-none">
                                <Link :href="route('contacto')">
                                    {{ t('servicios.consultancy') }}
                                </Link>
                            </Button>
                        </CardFooter>
                    </Card>
                </div>

                <!-- Full Tech Stack Section -->
                <div class="mt-40 mb-20 overflow-hidden">
                    <div class="mb-24">
                        <Badge class="bg-brutalist-yellow text-black font-black border-4 border-black px-4 py-2 mb-8 text-xl -rotate-1 inline-block uppercase">{{ t('skills.badge') }}</Badge>
                        <h2 class="text-[clamp(3rem,8vw,6rem)] font-display font-black uppercase italic leading-[0.8] tracking-tighter">
                            {{ t('skills.title1') }} <br/>
                            <span class="text-brutalist-pink">{{ t('skills.title2') }}</span>
                        </h2>
                    </div>

                    <div v-for="category in techCategories" :key="category.key" class="mb-20">
                        <h3 class="text-3xl font-display font-black uppercase italic mb-8 border-b-4 border-black dark:border-white inline-block text-black dark:text-white">
                            {{ category.name }}
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4">
                            <TooltipProvider v-for="tech in category.items" :key="tech.name" :delay-duration="0">
                                <Tooltip>
                                    <TooltipTrigger as-child>
                                        <div class="group relative bg-white dark:bg-black border-4 border-black dark:border-white p-4 aspect-square flex flex-col items-center justify-center hover:bg-brutalist-yellow dark:hover:bg-brutalist-yellow transition-all hover:scale-105 hover:-rotate-2 cursor-help shadow-brutalist dark:shadow-brutalist-white">
                                            <img :src="tech.icon" :alt="tech.name" class="w-12 h-12 object-contain grayscale group-hover:grayscale-0 transition-all dark:invert dark:group-hover:invert-0" />
                                            <span class="mt-2 text-[10px] font-black uppercase tracking-tighter text-center leading-tight group-hover:text-black dark:text-white dark:group-hover:text-black">
                                                {{ tech.name }}
                                            </span>
                                        </div>
                                    </TooltipTrigger>
                                    <TooltipContent class="bg-black dark:bg-white text-white dark:text-black border-2 border-white dark:border-black rounded-none font-black uppercase">
                                        {{ tech.name }}
                                    </TooltipContent>
                                </Tooltip>
                            </TooltipProvider>
                        </div>
                    </div>
                </div>

                <!-- Process Section -->
                <div class="mt-40 mb-20">
                    <div class="flex flex-col md:flex-row items-start justify-between mb-24 gap-12">
                        <div class="max-w-3xl">
                            <Badge class="bg-brutalist-yellow text-black font-black border-4 border-black px-4 py-2 mb-8 text-xl -rotate-1 inline-block uppercase">{{ t('servicios.workflow_badge') }}</Badge>
                            <h2 class="text-[clamp(3rem,8vw,6rem)] font-display font-black uppercase italic leading-[0.8] tracking-tighter">
                                {{ t('servicios.workflow_title1') }} <br/>
                                <span class="text-brutalist-blue">{{ t('servicios.workflow_title2') }}</span>
                            </h2>
                        </div>
                        <div class="max-w-sm border-l-8 border-brutalist-pink pl-6">
                            <p class="font-black text-2xl italic uppercase leading-none">
                                {{ t('servicios.workflow_quote') }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                        <Card v-for="(step, idx) in processSteps" :key="idx"
                            class="group relative flex flex-col overflow-hidden rounded-none border-4 border-black bg-white p-8 shadow-brutalist transition-all hover:translate-x-[4px] hover:translate-y-[4px] hover:shadow-brutalist-hover dark:border-white dark:bg-black dark:shadow-brutalist-white">
                            
                            <span class="absolute -bottom-8 -right-4 text-[10rem] font-display font-black opacity-5 leading-none select-none italic">{{ idx + 1 }}</span>
                            
                            <CardContent class="p-0 relative z-10">
                                <div class="mb-6 text-[11px] font-black uppercase tracking-[0.28em] opacity-50">PHASE 0{{ idx + 1 }}</div>
                                <div class="w-16 h-16 bg-black text-white border-4 border-black mb-8 flex items-center justify-center group-hover:bg-brutalist-yellow group-hover:text-black transition-colors dark:border-white">
                                    <svg v-if="step.icon==='discovery'" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    <svg v-if="step.icon==='architect'" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    <svg v-if="step.icon==='develop'" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    <svg v-if="step.icon==='deploy'" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                </div>
                                <CardTitle class="text-3xl font-black uppercase italic mb-6 leading-none">{{ step.name }}</CardTitle>
                                <CardDescription class="text-lg font-black uppercase italic leading-none text-black dark:text-white/80 opacity-100">
                                    {{ step.desc }}
                                </CardDescription>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </main>

        <!-- CTA Section -->
        <section class="py-48 px-6 bg-black border-y-8 border-brutalist-yellow overflow-hidden relative">
             <div class="flex whitespace-nowrap animate-[marquee_15s_linear_infinite] opacity-20 absolute top-0 w-full">
                <div v-for="n in 5" :key="n" class="flex items-center space-x-12 px-6">
                    <span class="text-[10rem] font-display font-black text-white italic">START NOW START NOW START NOW</span>
                </div>
            </div>
            <div class="max-w-5xl mx-auto text-center relative z-10">
                <h2 class="text-5xl md:text-8xl font-display font-black text-white uppercase italic leading-[0.8] mb-12">
                    {{ t('servicios.cta_title1') }} <br/> 
                    <span class="text-brutalist-yellow italic">{{ t('servicios.cta_title2') }}</span>
                </h2>
                <Button as-child class="bg-brutalist-pink text-white font-black border-4 border-black h-auto py-8 px-16 text-3xl shadow-brutalist hover:shadow-brutalist-hover hover:translate-x-[4px] hover:translate-y-[4px] transition-all rounded-none">
                    <Link :href="route('contacto')">
                        {{ t('servicios.cta_button') }}
                    </Link>
                </Button>
            </div>
        </section>

        <PublicSiteFooter />
    </div>
</template>

<style>
@keyframes marquee {
    from { transform: translateX(0); }
    to { transform: translateX(-50%); }
}

.font-display { font-family: 'Space Grotesk', sans-serif; }

body { 
    @apply bg-white dark:bg-black transition-colors duration-500;
}
</style>
