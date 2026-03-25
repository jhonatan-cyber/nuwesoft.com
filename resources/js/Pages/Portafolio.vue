<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref, computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import {
  Card,
  CardHeader,
  CardTitle,
  CardDescription,
  CardContent,
} from '@/Components/ui/card';
import {
    Code,
    Smartphone,
    Cloud,
    Zap,
    ExternalLink,
    ChevronRight,
    Terminal,
    Cpu,
    Database,
    Globe
} from 'lucide-vue-next';
import ThemeSwitcher from '@/Components/ThemeSwitcher.vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';

const { t } = useI18n();

const navigation = [
    { name: 'Services', href: 'servicios', key: 'services' },
    { name: 'Portfolio', href: 'portafolio', key: 'portfolio' },
    { name: 'Contact', href: 'contacto', key: 'contact' },
];

const categories = [
    { key: 'all', icon: Globe },
    { key: 'web', icon: Code },
    { key: 'mobile', icon: Smartphone },
    { key: 'cloud', icon: Cloud },
    { key: 'automation', icon: Zap },
];

const activeCategory = ref('all');

const projects = computed(() => [
    { 
        id: 'p1',
        name: t('portafolio.projects.p1.name'), 
        category: 'web',
        stack: ['Laravel', 'Vue.js', 'Redis'], 
        desc: t('portafolio.projects.p1.desc'), 
        color: 'bg-brutalist-pink',
        icon: Terminal
    },
    { 
        id: 'p2',
        name: t('portafolio.projects.p2.name'), 
        category: 'web',
        stack: ['Inertia', 'Tailwind', 'MySQL'], 
        desc: t('portafolio.projects.p2.desc'), 
        color: 'bg-brutalist-yellow',
        icon: Code
    },
    { 
        id: 'p3',
        name: t('portafolio.projects.p3.name'), 
        category: 'automation',
        stack: ['n8n', 'Python', 'AWS'], 
        desc: t('portafolio.projects.p3.desc'), 
        color: 'bg-brutalist-blue',
        icon: Zap
    },
    { 
        id: 'p4',
        name: 'NUWESOFT MOBILE', 
        category: 'mobile',
        stack: ['Flutter', 'Firebase', 'Dart'], 
        desc: 'EXPERIENCIA MÓVIL DE ALTO RENDIMIENTO CON SINCRONIZACIÓN EN TIEMPO REAL.', 
        color: 'bg-brutalist-green',
        icon: Smartphone
    }
]);

const filteredProjects = computed(() => {
    if (activeCategory.value === 'all') return projects.value;
    return projects.value.filter(p => p.category === activeCategory.value);
});

const stats = [
    { key: 'projects', icon: Cpu },
    { key: 'uptime', icon: Zap },
    { key: 'commits', icon: Terminal },
    { key: 'coffee', icon: Database },
];

const isVisible = ref(false);
onMounted(() => {
    isVisible.value = true;
});
</script>

<template>
    <Head :title="t('portafolio.head_title')" />
    <div class="min-h-screen bg-white dark:bg-black text-black dark:text-white font-sans selection:bg-brutalist-yellow selection:text-black overflow-x-hidden">
        <!-- Grid Background -->
        <div class="fixed inset-0 pointer-events-none opacity-[0.05] bg-[linear-gradient(to_right,#808080_1px,transparent_1px),linear-gradient(to_bottom,#808080_1px,transparent_1px)] bg-[size:40px_40px] z-0"></div>

        <!-- Navigation -->
        <nav class="fixed top-0 w-full z-50 bg-white dark:bg-black border-b-4 border-black dark:border-white">
            <div class="max-w-[1400px] mx-auto px-6">
                <div class="flex justify-between h-20 items-center">
                    <Link href="/" class="group flex items-center space-x-3">
                        <div class="w-12 h-12 bg-brutalist-yellow border-4 border-black dark:border-white flex items-center justify-center transform group-hover:-rotate-6 transition-transform">
                            <span class="text-2xl font-black text-black">N</span>
                        </div>
                        <span class="text-xl md:text-3xl font-display font-black tracking-tighter uppercase italic group-hover:bg-brutalist-pink group-hover:text-white px-2 transition-colors text-black dark:text-white">NUWESOFT</span>
                    </Link>
                    
                    <div class="hidden md:flex items-center space-x-8 text-black dark:text-white">
                        <Link v-for="item in navigation" :key="item.key" :href="route(item.href)" 
                            class="text-sm font-black uppercase tracking-widest hover:underline decoration-brutalist-pink decoration-4 underline-offset-8 transition-all"
                            :class="[route().current(item.href) ? 'text-brutalist-pink' : '']">
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

                    <!-- Mobile Navigation Toggle (Simple for now, matching Services style) -->
                    <div class="md:hidden flex items-center space-x-4">
                        <ThemeSwitcher />
                        <LanguageSwitcher />
                    </div>
                </div>
            </div>
        </nav>

        <main class="pt-32 pb-24 relative z-10">
            <!-- Hero Section -->
            <section class="px-6 mb-24">
                <div class="max-w-[1400px] mx-auto">
                    <div class="text-center md:text-left mb-16">
                        <Badge class="bg-brutalist-pink text-white font-black border-4 border-black dark:border-white px-4 py-2 mb-8 text-xl rotate-1 inline-block uppercase shadow-brutalist dark:shadow-brutalist-white">{{ t('portafolio.badge') }}</Badge>
                        <h1 class="text-[clamp(2.5rem,8vw,6rem)] font-display font-black leading-[0.9] tracking-tighter mb-8 uppercase italic text-black dark:text-white">
                            {{ t('portafolio.title1') }} <br/>
                            <span class="bg-brutalist-blue text-black px-4 ml-[-0.5rem]">{{ t('portafolio.title2') }}</span> <br/>
                            <span class="relative inline-block mt-2 text-white">
                                <span class="absolute inset-0 bg-black dark:bg-white -rotate-1 z-0"></span>
                                <span class="relative z-10 px-4 dark:text-black">{{ t('portafolio.title3') }}</span>
                            </span>
                        </h1>
                        <p class="max-w-2xl text-xl md:text-2xl font-black leading-tight italic uppercase opacity-80">
                            {{ t('portafolio.subtitle') }}
                        </p>
                    </div>

                    <!-- Filter Categories -->
                    <div class="flex flex-wrap gap-4 mb-16">
                        <button v-for="cat in categories" :key="cat.key"
                            @click="activeCategory = cat.key"
                            class="flex items-center space-x-3 px-6 py-3 border-4 border-black dark:border-white font-black uppercase italic transition-all"
                            :class="[activeCategory === cat.key ? 'bg-brutalist-yellow text-black -translate-y-1 shadow-brutalist dark:shadow-brutalist-white' : 'bg-white dark:bg-black text-black dark:text-white hover:bg-gray-100 dark:hover:bg-zinc-900']">
                            <component :is="cat.icon" class="w-5 h-5" />
                            <span>{{ t(`portafolio.${cat.key}`) }}</span>
                        </button>
                    </div>

                    <!-- Projects Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-12">
                        <div v-for="(project, i) in filteredProjects" :key="project.id" 
                            class="group relative"
                            v-show="isVisible">
                            <!-- Background Layer for Offset Effect -->
                            <div class="absolute inset-0 bg-black dark:bg-white translate-x-4 translate-y-4 group-hover:translate-x-6 group-hover:translate-y-6 transition-transform"></div>
                            
                            <Card class="relative z-10 rounded-none border-4 border-black dark:border-white bg-white dark:bg-black h-full flex flex-col overflow-hidden">
                                <div :class="['h-64 border-b-4 border-black dark:border-white relative flex items-center justify-center overflow-hidden', project.color]">
                                    <component :is="project.icon" class="w-32 h-32 text-black opacity-20 absolute -right-4 -bottom-4 rotate-12" />
                                    <div class="relative z-10 transform group-hover:scale-110 transition-transform duration-500">
                                        <span class="text-black font-display font-black text-6xl italic select-none uppercase opacity-10">
                                            #{{ (i + 1).toString().padStart(2, '0') }}
                                        </span>
                                    </div>
                                    <!-- Dynamic Badge -->
                                    <div class="absolute top-4 right-4 bg-black text-white dark:bg-white dark:text-black px-3 py-1 font-black text-xs uppercase italic border-2 border-current">
                                        {{ project.category }}
                                    </div>
                                </div>
                                
                                <CardHeader class="p-8 pb-4">
                                    <div class="flex flex-wrap gap-2 mb-6">
                                        <Badge v-for="tag in project.stack" :key="tag" 
                                            class="bg-white dark:bg-black text-black dark:text-white font-black border-2 border-black dark:border-white uppercase italic tracking-widest text-[10px] rounded-none px-2 py-0.5 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] dark:shadow-[2px_2px_0px_0px_rgba(255,255,255,1)]">
                                            {{ tag }}
                                        </Badge>
                                    </div>
                                    <CardTitle class="font-display font-black text-4xl uppercase italic leading-none group-hover:text-brutalist-pink transition-colors">
                                        {{ project.name }}
                                    </CardTitle>
                                </CardHeader>
                                <CardContent class="p-8 pt-4 flex-grow">
                                    <CardDescription class="text-lg font-black uppercase leading-tight italic text-black dark:text-white opacity-100 mb-8">
                                        {{ project.desc }}
                                    </CardDescription>
                                    <Button variant="outline" class="w-full rounded-none border-4 border-black dark:border-white font-black uppercase italic hover:bg-brutalist-pink hover:text-white transition-all group/btn">
                                        {{ t('portafolio.view_project') }}
                                        <ExternalLink class="ml-2 w-5 h-5 group-hover/btn:translate-x-1 transition-transform" />
                                    </Button>
                                </CardContent>
                            </Card>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="bg-brutalist-yellow dark:bg-brutalist-yellow border-y-8 border-black dark:border-white py-20 px-6">
                <div class="max-w-[1400px] mx-auto">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-12">
                        <div v-for="stat in stats" :key="stat.key" class="text-center group">
                            <div class="w-16 h-16 bg-white border-4 border-black mx-auto mb-6 flex items-center justify-center transform group-hover:rotate-12 transition-transform shadow-brutalist">
                                <component :is="stat.icon" class="w-8 h-8 text-black" />
                            </div>
                            <div class="text-5xl font-display font-black text-black mb-2">{{ t(`portafolio.stats.${stat.key}.value`) }}</div>
                            <div class="text-sm font-black uppercase italic text-black opacity-70 tracking-widest">{{ t(`portafolio.stats.${stat.key}.label`) }}</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Final CTA -->
            <section class="py-32 px-6">
                <div class="max-w-[1400px] mx-auto text-center">
                    <h2 class="text-[clamp(2rem,6vw,4rem)] font-display font-black uppercase italic leading-none mb-12">
                        {{ t('cta.title1') }} <br/>
                        <span class="text-brutalist-blue">{{ t('cta.title2') }}</span>
                    </h2>
                    <Button as-child size="lg" class="bg-black dark:bg-white text-white dark:text-black font-black text-2xl px-12 py-8 rounded-none border-4 border-black dark:border-white hover:bg-brutalist-pink hover:text-white dark:hover:bg-brutalist-pink dark:hover:text-white shadow-brutalist dark:shadow-brutalist-white hover:shadow-brutalist-hover transition-all">
                        <Link :href="route('contacto')">
                            {{ t('cta.button') }}
                            <ChevronRight class="ml-2 w-8 h-8" />
                        </Link>
                    </Button>
                </div>
            </section>
        </main>

        <footer class="bg-black text-white border-t-8 border-brutalist-yellow py-24 px-6">
            <div class="max-w-[1400px] mx-auto text-center">
                 <p class="text-2xl font-black italic uppercase leading-none mb-10">
                    {{ t('footer.rights') }} <br/>
                    <span class="text-brutalist-yellow italic">{{ t('footer.engineering_no_borders') }}</span>
                </p>
                <div class="flex justify-center space-x-12 text-xl font-black italic uppercase">
                    <Link href="#" class="hover:text-brutalist-pink transition-colors">{{ t('footer.twitter') }}</Link>
                    <Link href="#" class="hover:text-brutalist-blue transition-colors">{{ t('footer.linkedin') }}</Link>
                    <Link href="#" class="hover:text-brutalist-yellow transition-colors">{{ t('footer.github') }}</Link>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
.font-display { font-family: 'Space Grotesk', sans-serif; }
body { 
    @apply bg-white dark:bg-black transition-colors duration-500;
}
</style>
