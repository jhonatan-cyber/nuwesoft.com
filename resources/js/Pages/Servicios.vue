<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import ThemeSwitcher from '@/Components/ThemeSwitcher.vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
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

const navigation = [
    { name: 'Services', href: 'servicios', key: 'services' },
    { name: 'Portfolio', href: 'portafolio', key: 'portfolio' },
    { name: 'Contact', href: 'contacto', key: 'contact' },
];

const { t } = useI18n();

const services = [
    {
        title: t('servicios.items.software.title'),
        description: t('servicios.items.software.desc'),
        stack_desc: t('servicios.items.software.stack_desc'),
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
        title: t('servicios.items.cloud.title'),
        description: t('servicios.items.cloud.desc'),
        stack_desc: t('servicios.items.cloud.stack_desc'),
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
        title: t('servicios.items.automation.title'),
        description: t('servicios.items.automation.desc'),
        stack_desc: t('servicios.items.automation.stack_desc'),
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
        title: t('servicios.items.frontend.title'),
        description: t('servicios.items.frontend.desc'),
        stack_desc: t('servicios.items.frontend.stack_desc'),
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
</script>

<template>
    <Head title="Nuestros Servicios | NUWESOFT Engineering" />
    
    <div class="min-h-screen bg-white dark:bg-black text-black dark:text-white font-sans selection:bg-brutalist-yellow selection:text-black overflow-x-hidden">
        <!-- Grid Background -->
        <div class="fixed inset-0 pointer-events-none opacity-[0.05] bg-[linear-gradient(to_right,#808080_1px,transparent_1px),linear-gradient(to_bottom,#808080_1px,transparent_1px)] bg-[size:40px_40px] z-0"></div>

        <!-- Navigation -->
        <nav class="fixed top-0 w-full z-40 bg-white dark:bg-black border-b-4 border-black dark:border-white">
            <div class="max-w-[1400px] mx-auto px-6">
                <div class="flex justify-between h-20 items-center">
                    <Link href="/" class="group flex items-center space-x-3">
                        <div class="w-12 h-12 bg-brutalist-yellow border-4 border-black flex items-center justify-center transform group-hover:-rotate-6 transition-transform">
                            <span class="text-2xl font-black text-black">N</span>
                        </div>
                        <span class="text-3xl font-display font-black tracking-tighter uppercase italic group-hover:bg-brutalist-pink group-hover:text-white px-2 transition-colors text-black dark:text-white">NUWESOFT</span>
                    </Link>
                    
                    <div class="hidden md:flex items-center space-x-8 text-black dark:text-white">
                        <Link v-for="item in navigation" :key="item.key" :href="route(item.href)" 
                            class="text-sm font-black uppercase tracking-widest hover:underline decoration-brutalist-pink decoration-4 underline-offset-8 transition-all">
                            {{ t(item.key) }}
                        </Link>
                        
                        <ThemeSwitcher />
                        <LanguageSwitcher />
                        
                        <Button as-child class="bg-brutalist-blue text-black font-black border-4 border-black dark:border-white shadow-brutalist dark:shadow-brutalist-white hover:shadow-brutalist-hover hover:translate-x-[4px] hover:translate-y-[4px] transition-all rounded-none">
                            <Link :href="route('login')">
                                {{ t('dashboard') }}
                            </Link>
                        </Button>
                    </div>

                    <div class="flex items-center space-x-4 md:hidden">
                        <ThemeSwitcher />
                        <LanguageSwitcher />
                        <button class="text-black dark:text-white p-2 border-4 border-black dark:border-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <main class="relative pt-48 pb-32 px-6">
            <div class="max-w-[1400px] mx-auto relative z-10">
                <!-- Header -->
                <div :class="['transition-all duration-700 transform mb-24', isVisible ? 'translate-x-0 opacity-100' : '-translate-x-12 opacity-0']">
                    <Badge class="bg-brutalist-pink text-white font-black border-4 border-black px-4 py-2 mb-8 text-xl rotate-1 inline-block">{{ t('servicios.badge') }}</Badge>
                    <h1 class="text-[clamp(3rem,8vw,6rem)] font-display font-black leading-[0.8] tracking-tighter mb-10 uppercase italic">
                        {{ t('servicios.title1') }} <br/>
                        <span class="bg-brutalist-yellow text-black px-4 ml-[-1rem]">{{ t('servicios.title2') }}</span> <br/>
                        <span class="relative inline-block mt-4 text-white">
                            <span class="absolute inset-0 bg-black -rotate-1 z-0"></span>
                            <span class="relative z-10 px-4">{{ t('servicios.title3') }}</span>
                        </span>
                    </h1>
                    <p class="max-w-2xl text-2xl font-black leading-none italic border-l-8 border-black dark:border-white pl-6 uppercase">
                        {{ t('servicios.subtitle') }}
                    </p>
                </div>

                <!-- Services Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-40">
                    <Card v-for="(service, index) in services" :key="index"
                        class="group rounded-none border-4 border-black dark:border-white bg-white dark:bg-black shadow-brutalist dark:shadow-brutalist-white hover:shadow-brutalist-hover-lg dark:hover:shadow-brutalist-white-lg hover:-translate-x-2 hover:-translate-y-2 transition-all duration-300 overflow-hidden flex flex-col h-full relative">
                        
                        <!-- Decorative element -->
                        <div :class="['absolute top-0 right-0 w-32 h-32 opacity-10 -mr-16 -mt-16 rounded-full transition-transform duration-700 group-hover:scale-150', service.color]"></div>

                        <CardHeader class="flex flex-row justify-between items-start p-10 pb-0 relative z-10">
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
                            <CardTitle class="text-4xl md:text-5xl font-display font-black uppercase italic mb-8 group-hover:bg-brutalist-yellow group-hover:text-black px-2 inline-block transition-colors text-black dark:text-white">
                                {{ service.title }}
                            </CardTitle>
                            
                            <CardDescription class="text-xl font-black uppercase leading-[1.1] mb-10 text-black dark:text-white/90 opacity-100 italic">
                                {{ service.description }}
                            </CardDescription>

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

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <Card v-for="(step, idx) in [
                            { name: t('servicios.steps.discovery.name'), desc: t('servicios.steps.discovery.desc'), icon: 'discovery' },
                            { name: t('servicios.steps.architect.name'), desc: t('servicios.steps.architect.desc'), icon: 'architect' },
                            { name: t('servicios.steps.develop.name'), desc: t('servicios.steps.develop.desc'), icon: 'develop' },
                            { name: t('servicios.steps.deploy.name'), desc: t('servicios.steps.deploy.desc'), icon: 'deploy' }
                        ]" :key="idx"
                            class="group rounded-none border-4 border-black bg-white dark:bg-black shadow-brutalist hover:shadow-brutalist-hover hover:translate-x-[4px] hover:translate-y-[4px] transition-all p-8 flex flex-col relative overflow-hidden">
                            
                            <span class="absolute -bottom-8 -right-4 text-[10rem] font-display font-black opacity-5 leading-none select-none italic">{{ idx + 1 }}</span>
                            
                            <CardContent class="p-0 relative z-10">
                                <div class="w-16 h-16 bg-black text-white border-4 border-black mb-8 flex items-center justify-center group-hover:bg-brutalist-yellow group-hover:text-black transition-colors">
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

        <!-- Footer -->
        <footer class="bg-black text-white border-t-8 border-brutalist-blue py-24">
            <div class="max-w-[1400px] mx-auto px-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-16">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-brutalist-yellow border-4 border-white flex items-center justify-center -rotate-6">
                            <span class="text-3xl font-black text-black">N</span>
                        </div>
                        <span class="text-5xl font-display font-black italic">NUWESOFT</span>
                    </div>
                    
                    <div class="flex space-x-12 text-2xl font-black italic uppercase text-black dark:text-white">
                        <Link href="#" class="hover:text-brutalist-pink transition-colors">{{ t('footer.twitter') }}</Link>
                        <Link href="#" class="hover:text-brutalist-blue transition-colors">{{ t('footer.linkedin') }}</Link>
                        <Link href="#" class="hover:text-brutalist-yellow transition-colors">{{ t('footer.github') }}</Link>
                    </div>
                    
                    <p class="text-xl font-black italic uppercase text-right leading-none text-black dark:text-white">
                        {{ t('footer.rights') }} <br/>
                        <span class="text-brutalist-pink">{{ t('footer.coding_future') }}</span>
                    </p>
                </div>
            </div>
        </footer>
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
