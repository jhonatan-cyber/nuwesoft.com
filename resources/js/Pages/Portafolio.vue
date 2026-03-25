<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref, computed } from 'vue';
import { useI18n } from 'vue-i18n';
import PublicGridBackground from '@/Components/PublicGridBackground.vue';
import PublicSiteHeader from '@/Components/PublicSiteHeader.vue';
import PublicSiteFooter from '@/Components/PublicSiteFooter.vue';
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
    ChevronLeft,
    Terminal,
    Cpu,
    Database,
    Globe,
    X,
} from 'lucide-vue-next';

const props = defineProps({
    projects: {
        type: Array,
        default: () => []
    }
});

const { t } = useI18n();

const categories = [
    { key: 'all', icon: Globe },
    { key: 'web', icon: Code },
    { key: 'mobile', icon: Smartphone },
    { key: 'cloud', icon: Cloud },
    { key: 'automation', icon: Zap },
];

const categoryHighlights = [
    { key: 'web', accent: 'bg-brutalist-pink' },
    { key: 'cloud', accent: 'bg-brutalist-blue' },
    { key: 'automation', accent: 'bg-brutalist-yellow' },
];

const activeCategory = ref('all');
const lightboxProject = ref(null);
const lightboxIndex = ref(0);

const displayProjects = computed(() => {
    const list = props.projects && props.projects.length > 0 ? props.projects : [
        { 
            id: 'p1',
            name: t('portafolio.projects.p1.name'), 
            category: 'web',
            stack: ['Laravel', 'Vue.js', 'Redis'], 
            desc: t('portafolio.projects.p1.desc'), 
            icon: 'Terminal'
        },
        { 
            id: 'p2',
            name: t('portafolio.projects.p2.name'), 
            category: 'web',
            stack: ['Inertia', 'Tailwind', 'MySQL'], 
            desc: t('portafolio.projects.p2.desc'), 
            icon: 'Code'
        },
        { 
            id: 'p3',
            name: t('portafolio.projects.p3.name'), 
            category: 'automation',
            stack: ['n8n', 'Python', 'AWS'], 
            desc: t('portafolio.projects.p3.desc'), 
            icon: 'Zap'
        }
    ];
    
    if (activeCategory.value === 'all') return list;
    return list.filter(p => p.category === activeCategory.value);
});

const getIcon = (iconName) => {
    const icons = { Terminal, Code, Zap, Smartphone, Cloud, Globe, Cpu, Database };
    return icons[iconName] || Globe;
};

const stats = [
    { key: 'projects', icon: Cpu },
    { key: 'uptime', icon: Zap },
    { key: 'commits', icon: Terminal },
    { key: 'coffee', icon: Database },
];

const heroMetrics = [
    { value: 'B2B', label: 'portafolio.metrics.m1' },
    { value: 'UI + OPS', label: 'portafolio.metrics.m2' },
    { value: 'REAL CASES', label: 'portafolio.metrics.m3' },
];

const isVisible = ref(false);
onMounted(() => {
    isVisible.value = true;
});

const openLightbox = (project, index = 0) => {
    const images = project.images?.length
        ? project.images
        : project.image_url
            ? [{ id: `${project.id}-cover`, image_url: project.image_url }]
            : [];

    if (!images.length) {
        return;
    }

    lightboxProject.value = {
        ...project,
        images,
    };
    lightboxIndex.value = index;
    document.body.style.overflow = 'hidden';
};

const closeLightbox = () => {
    lightboxProject.value = null;
    lightboxIndex.value = 0;
    document.body.style.overflow = '';
};

const showNextImage = () => {
    if (!lightboxProject.value) {
        return;
    }

    lightboxIndex.value = (lightboxIndex.value + 1) % lightboxProject.value.images.length;
};

const showPrevImage = () => {
    if (!lightboxProject.value) {
        return;
    }

    lightboxIndex.value = (lightboxIndex.value - 1 + lightboxProject.value.images.length) % lightboxProject.value.images.length;
};

const handleKeydown = (event) => {
    if (!lightboxProject.value) {
        return;
    }

    if (event.key === 'Escape') {
        closeLightbox();
    }

    if (event.key === 'ArrowRight') {
        showNextImage();
    }

    if (event.key === 'ArrowLeft') {
        showPrevImage();
    }
};

onMounted(() => {
    window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
    document.body.style.overflow = '';
});
</script>

<template>
    <Head :title="t('portafolio.head_title')" />
    <div class="min-h-screen overflow-x-hidden bg-white font-sans text-black selection:bg-brutalist-yellow selection:text-black dark:bg-black dark:text-white">
        <PublicGridBackground />
        <PublicSiteHeader />

        <main class="pt-32 pb-24 relative z-10">
            <!-- Hero Section -->
            <section class="px-6 mb-24">
                <div class="max-w-[1400px] mx-auto">
                    <div class="mb-16 grid gap-12 xl:grid-cols-[1.05fr_0.95fr] xl:items-end">
                        <div>
                            <Badge class="bg-brutalist-pink text-white font-black border-4 border-black dark:border-white px-4 py-2 mb-8 text-xl rotate-1 inline-block uppercase shadow-brutalist dark:shadow-brutalist-white">{{ t('portafolio.badge') }}</Badge>
                            <h1 class="text-[clamp(2.5rem,8vw,6rem)] font-display font-black leading-[0.9] tracking-tighter mb-8 uppercase italic text-black dark:text-white">
                                {{ t('portafolio.title1') }} <br/>
                                <span class="bg-brutalist-blue text-black px-4 ml-[-0.5rem]">{{ t('portafolio.title2') }}</span> <br/>
                                <span class="relative inline-block mt-2 text-white">
                                    <span class="absolute inset-0 bg-black dark:bg-white -rotate-1 z-0"></span>
                                    <span class="relative z-10 px-4 dark:text-black">{{ t('portafolio.title3') }}</span>
                                </span>
                            </h1>
                            <p class="max-w-3xl border-l-8 border-black pl-6 text-xl font-black leading-tight italic uppercase opacity-90 dark:border-white md:text-2xl">
                                {{ t('portafolio.subtitle') }}
                            </p>
                        </div>

                        <div class="space-y-6">
                            <div class="border-4 border-black bg-white p-8 shadow-brutalist dark:border-white dark:bg-zinc-950 dark:shadow-brutalist-white">
                                <p class="text-sm font-black uppercase tracking-[0.28em] text-brutalist-blue">{{ t('portafolio.lead_label') }}</p>
                                <p class="mt-4 text-xl font-black uppercase leading-tight md:text-2xl">
                                    {{ t('portafolio.lead_text') }}
                                </p>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-3">
                                <div
                                    v-for="metric in heroMetrics"
                                    :key="metric.label"
                                    class="border-4 border-black bg-brutalist-yellow p-5 text-black shadow-brutalist dark:border-white dark:shadow-brutalist-white"
                                >
                                    <div class="text-2xl font-display font-black italic leading-none md:text-3xl">{{ metric.value }}</div>
                                    <div class="mt-3 text-[11px] font-black uppercase tracking-[0.24em]">{{ t(metric.label) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-16 grid gap-4 md:grid-cols-3">
                        <div
                            v-for="highlight in categoryHighlights"
                            :key="highlight.key"
                            class="border-4 border-black bg-white p-5 shadow-brutalist dark:border-white dark:bg-zinc-950 dark:shadow-brutalist-white"
                        >
                            <div :class="['mb-4 h-3 w-16', highlight.accent]"></div>
                            <div class="text-[11px] font-black uppercase tracking-[0.28em] opacity-55">{{ t(`portafolio.${highlight.key}`) }}</div>
                            <p class="mt-4 text-lg font-black uppercase leading-tight">{{ t(`portafolio.highlights.${highlight.key}`) }}</p>
                        </div>
                    </div>

                    <!-- Filter Categories -->
                    <div class="mb-16 flex flex-wrap gap-4">
                        <button v-for="cat in categories" :key="cat.key"
                            @click="activeCategory = cat.key"
                            class="flex items-center space-x-3 px-6 py-3 border-4 border-black dark:border-white font-black uppercase italic transition-all"
                            :class="[activeCategory === cat.key ? 'bg-brutalist-yellow text-black -translate-y-1 shadow-brutalist dark:shadow-brutalist-white' : 'bg-white dark:bg-black text-black dark:text-white hover:bg-gray-100 dark:hover:bg-zinc-900']">
                            <component :is="cat.icon" class="w-5 h-5" />
                            <span>{{ t(`portafolio.${cat.key}`) }}</span>
                        </button>
                    </div>

                    <!-- Projects Grid -->
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3">
                        <div v-for="(project, i) in displayProjects" :key="project.id" 
                            class="group relative"
                            v-show="isVisible">
                            <!-- Background Layer for Offset Effect -->
                            <div class="absolute inset-0 bg-black dark:bg-white translate-x-2 translate-y-2 group-hover:translate-x-3 group-hover:translate-y-3 transition-transform"></div>
                            
                            <Card class="relative z-10 rounded-none border-4 border-black dark:border-white bg-white dark:bg-black h-full flex flex-col overflow-hidden transition-colors">
                                <button
                                    type="button"
                                    :class="['min-h-[18rem] w-full border-b-4 border-black dark:border-white relative flex items-center justify-center overflow-hidden text-left', project.color || 'bg-gray-200']"
                                    @click="openLightbox(project, 0)"
                                >
                                    <img v-if="project.images && project.images.length > 0" :src="project.images[0].image_url" class="w-full h-full object-cover grayscale-0 transition-all duration-500 group-hover:scale-[1.03]" />
                                    <img v-else-if="project.image_url" :src="project.image_url" class="w-full h-full object-cover grayscale-0 transition-all duration-500 group-hover:scale-[1.03]" />
                                    <div v-else class="w-full h-full flex items-center justify-center bg-slate-50 dark:bg-zinc-900 opacity-20">
                                        <svg viewBox="0 0 400 300" class="w-full h-full text-black dark:text-white">
                                            <defs>
                                                <pattern id="grid-brutalist" width="20" height="20" patternUnits="userSpaceOnUse">
                                                    <path d="M 20 0 L 0 0 0 20" fill="none" stroke="currentColor" stroke-width="0.5"/>
                                                </pattern>
                                            </defs>
                                            <rect width="100%" height="100%" fill="url(#grid-brutalist)" />
                                            <path d="M 0 0 L 400 300 M 400 0 L 0 300" stroke="currentColor" stroke-width="1" />
                                        </svg>
                                        <component :is="getIcon(project.icon)" class="w-20 h-20 text-black dark:text-white absolute m-auto" />
                                    </div>
                                    <div class="relative z-10 transform group-hover:scale-110 transition-transform duration-500">
                                        <span class="text-black dark:text-white font-display font-black text-4xl italic select-none uppercase opacity-10">
                                            #{{ (i + 1).toString().padStart(2, '0') }}
                                        </span>
                                    </div>
                                    <!-- Dynamic Badge -->
                                    <div class="absolute top-4 right-4 bg-black text-white dark:bg-white dark:text-black px-3 py-1 font-black text-xs uppercase italic border-2 border-current">
                                        {{ project.category }}
                                    </div>
                                    <div v-if="project.images && project.images.length > 1" class="absolute top-4 left-4 border-2 border-black bg-brutalist-yellow px-3 py-1 text-[11px] font-black uppercase tracking-[0.24em] text-black dark:border-white">
                                        {{ project.images.length }} {{ t('portafolio.images_label') }}
                                    </div>
                                    <div class="absolute bottom-4 left-4 border-2 border-black bg-white/90 px-3 py-1 text-[11px] font-black uppercase tracking-[0.24em] text-black backdrop-blur-sm dark:border-white dark:bg-black/80 dark:text-white">
                                        {{ t('portafolio.case_label') }} {{ (i + 1).toString().padStart(2, '0') }}
                                    </div>
                                </button>

                                <div v-if="project.images && project.images.length > 1" class="grid grid-cols-4 gap-0 border-b-4 border-black dark:border-white">
                                    <div
                                        v-for="(image, imageIndex) in project.images.slice(0, 4)"
                                        :key="image.id"
                                        class="h-20 border-r-2 border-black bg-zinc-100 last:border-r-0 dark:border-white dark:bg-zinc-900"
                                    >
                                        <button type="button" class="h-full w-full" @click="openLightbox(project, imageIndex)">
                                            <img :src="image.image_url" :alt="project.name" class="h-full w-full object-cover" />
                                        </button>
                                    </div>
                                </div>
                                
                                <CardHeader class="p-6 pb-2">
                                    <div v-if="(project.stack && project.stack.length > 0) || (project.technologies && project.technologies.length > 0)" class="flex flex-wrap gap-1.5 mb-4">
                                        <Badge v-for="tag in project.stack" :key="tag" 
                                            class="bg-white dark:bg-black text-black dark:text-white font-black border-2 border-black dark:border-white uppercase italic tracking-widest text-[8px] rounded-none px-1.5 py-0 shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] dark:shadow-[1px_1px_0px_0px_rgba(255,255,255,1)]">
                                            {{ tag }}
                                        </Badge>
                                        <Badge v-for="tech in project.technologies" :key="tech.id" 
                                            class="bg-brutalist-yellow text-black font-black border-2 border-black dark:border-white uppercase italic tracking-widest text-[8px] rounded-none px-1.5 py-0 shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] dark:shadow-[1px_1px_0px_0px_rgba(255,255,255,1)] flex items-center gap-1">
                                            <img v-if="tech.logo_url" :src="tech.logo_url" class="w-2.5 h-2.5 object-contain" />
                                            {{ tech.name }}
                                        </Badge>
                                    </div>
                                    <div class="mb-3 text-[10px] font-black uppercase tracking-[0.28em] opacity-55">
                                        {{ t('portafolio.project_label') }}
                                    </div>
                                    <CardTitle class="font-display font-black text-2xl uppercase italic leading-none group-hover:text-brutalist-pink transition-colors">
                                        {{ project.name }}
                                    </CardTitle>
                                </CardHeader>
                                <CardContent class="p-6 pt-2 flex-grow">
                                    <CardDescription class="text-base font-black uppercase leading-tight italic text-black dark:text-white opacity-100 mb-6">
                                        {{ project.desc }}
                                    </CardDescription>
                                    <a v-if="project.project_url" :href="project.project_url" target="_blank">
                                        <Button variant="outline" class="w-full h-auto rounded-none border-4 border-black dark:border-white font-black uppercase italic hover:bg-brutalist-pink hover:text-white transition-all group/btn text-sm py-3">
                                            {{ t('portafolio.view_project') }}
                                            <ExternalLink class="ml-2 w-4 h-4 group-hover/btn:translate-x-1 transition-transform" />
                                        </Button>
                                    </a>
                                    <Button v-else variant="outline" class="w-full h-auto rounded-none border-4 border-black dark:border-white font-black uppercase italic hover:bg-brutalist-pink hover:text-white transition-all group/btn text-sm py-3">
                                        {{ t('portafolio.view_project') }}
                                        <ExternalLink class="ml-2 w-4 h-4 group-hover/btn:translate-x-1 transition-transform" />
                                    </Button>
                                </CardContent>
                            </Card>
                        </div>
                    </div>

                    <div v-if="displayProjects.length === 0" class="border-4 border-black bg-white p-10 text-center shadow-brutalist dark:border-white dark:bg-zinc-950 dark:shadow-brutalist-white">
                        <p class="text-sm font-black uppercase tracking-[0.28em] text-brutalist-pink">{{ t('portafolio.empty_label') }}</p>
                        <h3 class="mt-4 text-4xl font-display font-black uppercase italic leading-none">{{ t('portafolio.empty_title') }}</h3>
                        <p class="mx-auto mt-6 max-w-2xl text-lg font-black uppercase leading-tight opacity-75">
                            {{ t('portafolio.empty_text') }}
                        </p>
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

        <PublicSiteFooter />

        <transition name="lightbox-fade">
            <div
                v-if="lightboxProject"
                class="fixed inset-0 z-[80] flex items-center justify-center bg-black/90 p-4 backdrop-blur-sm"
                @click.self="closeLightbox"
            >
                <div class="relative w-full max-w-6xl border-4 border-white bg-black text-white shadow-[10px_10px_0px_rgba(255,255,255,0.3)]">
                    <button
                        type="button"
                        class="absolute right-4 top-4 z-20 border-2 border-white bg-black p-2 text-white transition-colors hover:bg-white hover:text-black"
                        @click="closeLightbox"
                    >
                        <X class="h-6 w-6" />
                    </button>

                    <div class="grid lg:grid-cols-[1.2fr_0.8fr]">
                        <div class="relative min-h-[22rem] bg-zinc-950">
                            <img
                                :src="lightboxProject.images[lightboxIndex].image_url"
                                :alt="lightboxProject.name"
                                class="h-full w-full object-contain"
                            />

                            <button
                                v-if="lightboxProject.images.length > 1"
                                type="button"
                                class="absolute left-4 top-1/2 -translate-y-1/2 border-2 border-white bg-black/80 p-3 text-white transition-colors hover:bg-brutalist-yellow hover:text-black"
                                @click.stop="showPrevImage"
                            >
                                <ChevronLeft class="h-6 w-6" />
                            </button>
                            <button
                                v-if="lightboxProject.images.length > 1"
                                type="button"
                                class="absolute right-4 top-1/2 -translate-y-1/2 border-2 border-white bg-black/80 p-3 text-white transition-colors hover:bg-brutalist-yellow hover:text-black"
                                @click.stop="showNextImage"
                            >
                                <ChevronRight class="h-6 w-6" />
                            </button>
                        </div>

                        <div class="border-t-4 border-white p-6 lg:border-l-4 lg:border-t-0">
                            <div class="mb-3 text-[11px] font-black uppercase tracking-[0.28em] text-brutalist-yellow">
                                {{ t('portafolio.project_label') }}
                            </div>
                            <h3 class="text-3xl font-display font-black uppercase italic leading-none">
                                {{ lightboxProject.name }}
                            </h3>
                            <p class="mt-6 text-sm font-black uppercase leading-relaxed text-white/75">
                                {{ lightboxProject.desc }}
                            </p>

                            <div class="mt-6 flex flex-wrap gap-2">
                                <span
                                    v-for="tag in lightboxProject.stack || []"
                                    :key="tag"
                                    class="border-2 border-white px-2 py-1 text-[10px] font-black uppercase tracking-[0.2em]"
                                >
                                    {{ tag }}
                                </span>
                                <span
                                    v-for="tech in lightboxProject.technologies || []"
                                    :key="tech.id"
                                    class="border-2 border-brutalist-yellow bg-brutalist-yellow px-2 py-1 text-[10px] font-black uppercase tracking-[0.2em] text-black"
                                >
                                    {{ tech.name }}
                                </span>
                            </div>

                            <div class="mt-8 flex items-center justify-between border-t-4 border-white/20 pt-5 text-xs font-black uppercase tracking-[0.24em]">
                                <span>{{ lightboxIndex + 1 }} / {{ lightboxProject.images.length }}</span>
                                <span>{{ t('portafolio.gallery_label') }}</span>
                            </div>

                            <div v-if="lightboxProject.images.length > 1" class="mt-5 grid grid-cols-4 gap-3">
                                <button
                                    v-for="(image, imageIndex) in lightboxProject.images"
                                    :key="image.id"
                                    type="button"
                                    class="overflow-hidden border-2 transition-all"
                                    :class="imageIndex === lightboxIndex ? 'border-brutalist-yellow' : 'border-white/25'"
                                    @click="lightboxIndex = imageIndex"
                                >
                                    <img :src="image.image_url" :alt="lightboxProject.name" class="h-16 w-full object-cover" />
                                </button>
                            </div>

                            <a v-if="lightboxProject.project_url" :href="lightboxProject.project_url" target="_blank" class="mt-8 block">
                                <Button class="h-auto w-full rounded-none border-4 border-white bg-white py-4 font-black uppercase italic text-black transition-all hover:bg-brutalist-pink hover:text-white">
                                    {{ t('portafolio.view_project') }}
                                    <ExternalLink class="ml-2 h-4 w-4" />
                                </Button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<style>
.font-display { font-family: 'Space Grotesk', sans-serif; }
body { 
    @apply bg-white dark:bg-black transition-colors duration-500;
}

.lightbox-fade-enter-active,
.lightbox-fade-leave-active {
    transition: opacity 0.2s ease;
}

.lightbox-fade-enter-from,
.lightbox-fade-leave-to {
    opacity: 0;
}
</style>
