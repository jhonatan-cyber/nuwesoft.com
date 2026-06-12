<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useI18n } from 'vue-i18n';
import PublicGridBackground from '@/Components/PublicGridBackground.vue';
import PublicSiteHeader from '@/Components/PublicSiteHeader.vue';
import PublicSiteFooter from '@/Components/PublicSiteFooter.vue';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/Components/ui/tooltip';
import { Dialog, DialogContent } from '@/Components/ui/dialog';
import { useRekaCleanup } from '@/composables/useRekaCleanup';
import { useInView } from '@/composables/useInView';
import { useSkeletonLoader } from '@/composables/useSkeletonLoader';
import {
    ArrowLeft, ArrowUpRight, ChevronLeft, ChevronRight, ExternalLink, X, Eye, Layers,
} from 'lucide-vue-next';
import BlurImage from '@/Components/BlurImage.vue'

const { skeletonReady } = useSkeletonLoader();

const props = defineProps({
    project: { type: Object, required: true },
});

const { t } = useI18n();

const page = usePage();
const settings = computed(() => page.props.settings || {});
const siteName = computed(() => settings.value.site_name || 'NUWESOFT');
const pageUrl = computed(() => window.location.href);

const projectJsonLd = computed(() => ({
    '@context': 'https://schema.org',
    '@type': 'CreativeWork',
    name: props.project.name,
    description: props.project.desc,
    url: pageUrl.value,
    dateCreated: props.project.created_at,
    dateModified: props.project.updated_at,
    keywords: props.project.technologies?.map(t => t.name).join(', ') || '',
    about: {
        '@type': 'Thing',
        name: props.project.category,
    },
    author: {
        '@type': 'Organization',
        name: siteName.value,
    },
    image: allImages.value.length > 0 ? allImages.value[0].image_url : undefined,
}))
const { el: heroRef, isVisible: heroVisible } = useInView(0.05);
const { el: galleryRef, isVisible: galleryVisible } = useInView(0.05);
const { el: contentRef, isVisible: contentVisible } = useInView(0.05);

// ── Tech Logo Lookup ──
const getTechLogo = (techName) => {
    if (!props.project.technologies || props.project.technologies.length === 0) return null;
    const exactMatch = props.project.technologies.find(
        tech => tech.name.toLowerCase() === techName.toLowerCase()
    );
    if (exactMatch && exactMatch.logo_url) return exactMatch.logo_url;
    return null;
};

// ── Lightbox ──
const lightboxOpen = ref(false);
const lightboxIndex = ref(0);

useRekaCleanup(lightboxOpen);

const openLightbox = (index = 0) => {
    lightboxIndex.value = index;
    lightboxOpen.value = true;
};

const closeLightbox = () => {
    lightboxOpen.value = false;
};

const showNext = () => {
    const images = props.project.images || [];
    lightboxIndex.value = (lightboxIndex.value + 1) % images.length;
};

const showPrev = () => {
    const images = props.project.images || [];
    lightboxIndex.value = (lightboxIndex.value - 1 + images.length) % images.length;
};

const handleKeydown = (e) => {
    if (!lightboxOpen.value) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowRight') showNext();
    if (e.key === 'ArrowLeft') showPrev();
};

onMounted(() => window.addEventListener('keydown', handleKeydown));
onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
});

const allImages = computed(() => {
    if (props.project.images?.length) return props.project.images;
    return [];
});

const categoryColors = {
    web: 'bg-brutalist-pink',
    mobile: 'bg-brutalist-blue',
    cloud: 'bg-brutalist-yellow',
    automation: 'bg-brutalist-pink',
};
</script>

<template>
    <Head :title="`${project.name} | ${siteName} Engineering`">
        <meta name="description" :content="project.desc" />
        <meta property="og:title" :content="`${project.name} | ${siteName} Engineering`" />
        <meta property="og:description" :content="project.desc" />
        <meta property="og:type" content="article" />
        <meta property="og:url" :content="pageUrl" />
        <meta v-if="allImages.length > 0" property="og:image" :content="allImages[0].image_url" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="`${project.name} | ${siteName} Engineering`" />
        <meta name="twitter:description" :content="project.desc" />
        <meta v-if="allImages.length > 0" name="twitter:image" :content="allImages[0].image_url" />
        <link rel="canonical" :href="pageUrl" />
    </Head>

    <Teleport to="head">
        <component :is="'script'" type="application/ld+json" v-html="JSON.stringify(projectJsonLd)" />
    </Teleport>
    <div class="min-h-screen overflow-x-hidden bg-white font-sans text-black selection:bg-brutalist-yellow selection:text-black dark:bg-black dark:text-white portfolio-detail">
        <PublicGridBackground />
        <PublicSiteHeader />

        <main id="main-content" class="relative z-10 pt-32">
            <!-- ═══ Skeleton / Content Transition ═══ -->
            <Transition name="fade" mode="out-in">
                <div v-if="!skeletonReady" key="skeleton" class="relative overflow-hidden px-6 pointer-events-none select-none">
                    <div class="max-w-[1400px] mx-auto relative z-10">
                        <!-- Back link skeleton -->
                        <div class="flex justify-end">
                            <div class="h-10 w-56 skeleton-bg border-2 border-black dark:border-white"></div>
                        </div>

                        <div class="mt-8 grid gap-12 lg:grid-cols-[1.1fr_0.9fr] lg:items-start">
                            <!-- Left: Image skeleton -->
                            <div>
                                <div class="relative border-4 border-black dark:border-white shadow-brutalist dark:shadow-brutalist-white overflow-hidden">
                                    <div class="w-full h-[24rem] md:h-[32rem] skeleton-bg"></div>
                                    <!-- Image count badge skeleton -->
                                    <div class="absolute bottom-4 left-4 border-2 border-black bg-white/90 px-3 py-1.5">
                                        <div class="h-3 w-24 skeleton-bg"></div>
                                    </div>
                                </div>

                                <!-- Thumbnail strip skeleton -->
                                <div class="mt-4 grid grid-cols-5 gap-3">
                                    <div v-for="j in 5" :key="'thumb-' + j" class="border-4 border-black/30 dark:border-white/30 h-20 skeleton-bg"></div>
                                </div>
                            </div>

                            <!-- Right: Info skeleton -->
                            <div class="space-y-6">
                                <!-- Category badge -->
                                <div class="flex items-center gap-4">
                                    <div class="h-4 w-4 rotate-45 skeleton-bg border-2 border-black dark:border-white"></div>
                                    <div class="h-10 w-32 skeleton-bg border-4 border-black dark:border-white"></div>
                                    <div class="h-px flex-1 skeleton-bg"></div>
                                </div>

                                <!-- Title -->
                                <div class="space-y-3">
                                    <div class="h-16 w-full skeleton-bg"></div>
                                    <div class="h-16 w-3/4 skeleton-bg"></div>
                                    <div class="h-16 w-5/6 skeleton-bg"></div>
                                </div>

                                <!-- Description -->
                                <div class="space-y-2">
                                    <div class="h-5 w-full skeleton-bg"></div>
                                    <div class="h-5 w-5/6 skeleton-bg"></div>
                                    <div class="h-5 w-4/6 skeleton-bg"></div>
                                </div>

                                <!-- Tech Stack -->
                                <div class="pt-4">
                                    <div class="mb-4 h-3 w-24 skeleton-bg"></div>
                                    <div class="flex flex-wrap gap-3">
                                        <div v-for="j in 5" :key="'tech-' + j" class="h-11 w-16 skeleton-bg border-2 border-black dark:border-white"></div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-wrap gap-4 pt-4">
                                    <div class="h-14 w-44 skeleton-bg border-4 border-black dark:border-white"></div>
                                    <div class="h-14 w-40 skeleton-bg border-4 border-black dark:border-white"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="absolute inset-0 shimmer-sweep z-10"></div>
                </div>

                <div v-else key="content">
                    <!-- ═══ Hero ═══ -->
                    <section ref="heroRef" class="px-6 pb-24 relative overflow-hidden">
                        <!-- Decorative blobs -->
                        <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
                            <div class="absolute -left-16 top-32 w-72 h-72 rounded-full bg-brutalist-yellow/10 blur-3xl"></div>
                            <div class="absolute -right-16 top-64 w-96 h-96 rounded-full bg-brutalist-pink/10 blur-3xl"></div>
                        </div>

                        <div class="max-w-[1400px] mx-auto relative z-10">
                            <!-- Back link -->
                            <div
                                :class="['flex justify-end transition-all duration-700', heroVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0']"
                            >
                                <Link :href="route('portafolio')" class="inline-flex items-center gap-2 border-2 border-black px-4 py-2 text-xs font-black uppercase tracking-[0.2em] transition-all hover:bg-black hover:text-white dark:border-white dark:hover:bg-white dark:hover:text-black">
                                    <ArrowLeft class="w-4 h-4" />
                                    {{ t('portafolio.back') || 'VOLVER AL PORTAFOLIO' }}
                                </Link>
                            </div>

                            <div class="mt-8 grid gap-12 lg:grid-cols-[1.1fr_0.9fr] lg:items-start">
                                <!-- Left: Main image -->
                                <div
                                    :class="['transition-all duration-700 delay-100', heroVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0']"
                                >
                                    <div class="relative border-4 border-black shadow-brutalist dark:border-white dark:shadow-brutalist-white overflow-hidden">
                                        <BlurImage
                                            v-if="allImages.length > 0"
                                            :src="allImages[0].image_url"
                                            :alt="project.name"
                                            fullRes
                                            class="w-full h-[24rem] md:h-[32rem] cursor-pointer"
                                            img-class="h-full w-full object-cover transition-transform duration-500 hover:scale-105"
                                            @click="openLightbox(0)"
                                        />
                                        <div
                                            v-else
                                            class="w-full h-[24rem] md:h-[32rem] flex items-center justify-center bg-zinc-100 dark:bg-zinc-900"
                                        >
                                            <Layers class="w-20 h-20 text-black/10 dark:text-white/10" />
                                        </div>

                                        <!-- Image count badge -->
                                        <div
                                            v-if="allImages.length > 1"
                                            class="absolute bottom-4 left-4 border-2 border-black bg-white/90 px-3 py-1.5 text-[11px] font-black uppercase tracking-[0.24em] backdrop-blur-sm"
                                            @click="openLightbox(0)"
                                        >
                                            <span class="flex items-center gap-1.5 cursor-pointer">
                                                <Eye class="w-3.5 h-3.5" />
                                                {{ allImages.length }} {{ t('portafolio.images_label') }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Thumbnail strip -->
                                    <div v-if="allImages.length > 1" class="mt-4 grid grid-cols-5 gap-3">
                                        <button
                                            v-for="(image, idx) in allImages"
                                            :key="image.id"
                                            @click="openLightbox(idx)"
                                            class="border-4 overflow-hidden transition-all duration-200 hover:-translate-y-1"
                                            :class="idx === 0 ? 'border-black dark:border-white' : 'border-black/30 dark:border-white/30 hover:border-black dark:hover:border-white'"
                                        >
                                            <BlurImage :src="image.image_url" :alt="project.name" :width="250" :height="180" class="h-20 w-full" />
                                        </button>
                                    </div>
                                </div>

                                <!-- Right: Project info -->
                                <div
                                    :class="['transition-all duration-700 delay-200', heroVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0']"
                                >
                                    <!-- Category badge -->
                                    <div class="mb-6 flex items-center gap-4">
                                        <span class="inline-flex h-4 w-4 rotate-45 border-2 border-black dark:border-white" :class="categoryColors[project.category] || 'bg-black'"></span>
                                        <Badge class="border-4 border-black bg-white px-4 py-2 text-xs font-black uppercase tracking-[0.24em] shadow-brutalist dark:border-white dark:bg-zinc-900 dark:text-white">
                                            {{ project.category }}
                                        </Badge>
                                        <span class="h-px flex-1 bg-black/20 dark:bg-white/20"></span>
                                    </div>

                                    <!-- Title -->
                                    <h1 class="text-[clamp(2.5rem,5vw,4.5rem)] font-display font-black uppercase italic leading-[0.9] tracking-tighter">
                                        {{ project.name }}
                                    </h1>

                                    <!-- Description -->
                                    <p class="mt-8 text-lg font-black uppercase italic leading-relaxed text-black/80 dark:text-white/80">
                                        {{ project.desc }}
                                    </p>

                                    <!-- Tech Stack -->
                                    <div class="mt-10">
                                        <div class="mb-4 text-[11px] font-black uppercase tracking-[0.28em] opacity-50 flex items-center gap-3">
                                            <span class="inline-block w-6 h-px bg-current"></span>
                                            {{ t('portafolio.tech_stack_label') || 'TECH STACK' }}
                                        </div>
                                        <div class="flex flex-wrap gap-3">
                                            <TooltipProvider :delay-duration="0">
                                                <Tooltip v-for="tech in project.technologies" :key="tech.id">
                                                    <TooltipTrigger as-child>
                                                        <span class="border-2 border-black dark:border-white bg-transparent px-3 py-1.5 text-[10px] font-black uppercase tracking-[0.2em] text-black dark:text-white flex items-center justify-center w-16 h-11">
                                                            <img v-if="tech.logo_url" :src="tech.logo_url" class="w-full h-full object-contain" />
                                                            <span v-else class="text-[9px] truncate max-w-[50px]">{{ tech.name }}</span>
                                                        </span>
                                                    </TooltipTrigger>
                                                    <TooltipContent side="top" :side-offset="8">
                                                        {{ tech.name }}
                                                    </TooltipContent>
                                                </Tooltip>
                                            </TooltipProvider>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="mt-10 flex flex-wrap gap-4">
                                        <a v-if="project.project_url" :href="project.project_url" target="_blank">
                                            <Button class="bg-black text-white font-black border-4 border-black px-8 py-4 text-base rounded-none shadow-brutalist hover:shadow-brutalist-hover hover:translate-x-[4px] hover:translate-y-[4px] transition-all dark:border-white dark:bg-white dark:text-black">
                                                <span class="flex items-center gap-2">
                                                    {{ t('portafolio.view_project') }}
                                                    <ArrowUpRight class="w-5 h-5" />
                                                </span>
                                            </Button>
                                        </a>
                                        <button
                                            v-if="allImages.length > 0"
                                            @click="openLightbox(0)"
                                            class="border-4 border-black px-8 py-3.5 text-base font-black uppercase italic transition-all hover:bg-black hover:text-white dark:border-white dark:hover:bg-white dark:hover:text-black"
                                        >
                                            <span class="flex items-center gap-2">
                                                <Eye class="w-5 h-5" />
                                                {{ t('portafolio.view_gallery') || 'VER GALERIA' }}
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- ═══ Full Gallery ═══ -->
                    <section
                        v-if="allImages.length > 1"
                        ref="galleryRef"
                        class="border-y-8 border-black bg-zinc-50 px-6 py-24 dark:border-white dark:bg-zinc-950"
                    >
                        <div class="max-w-[1400px] mx-auto">
                            <div
                                :class="['mb-12 flex items-center gap-4 transition-all duration-700', galleryVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0']"
                            >
                                <span class="inline-flex h-4 w-4 rotate-45 border-2 border-black bg-brutalist-pink"></span>
                                <span class="text-[11px] font-black uppercase tracking-[0.28em] opacity-50">{{ t('portafolio.gallery_label') }}</span>
                                <span class="h-px flex-1 bg-black/20 dark:bg-white/20"></span>
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">{{ allImages.length }} IMAGES</span>
                            </div>

                            <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
                                <div
                                    v-for="(image, idx) in allImages"
                                    :key="image.id"
                                    :style="{ transitionDelay: `${idx * 80}ms` }"
                                    :class="[
                                        'transition-all duration-700',
                                        galleryVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'
                                    ]"
                                >
                                    <button
                                        @click="openLightbox(idx)"
                                        class="group relative w-full overflow-hidden border-4 border-black shadow-brutalist transition-all duration-300 hover:-translate-y-1 hover:shadow-brutalist-hover"
                                    >
                                        <BlurImage :src="image.image_url" :alt="project.name" :width="600" :height="400" class="w-full h-48 md:h-64" img-class="transition-all duration-500 group-hover:scale-110" />
                                        <div class="absolute inset-0 bg-black/0 transition-all duration-300 group-hover:bg-black/10"></div>
                                        <div class="absolute bottom-2 right-2 border-2 border-black bg-white/90 px-2 py-1 text-[10px] font-black uppercase opacity-0 transition-all duration-300 group-hover:opacity-100">
                                            #{{ (idx + 1).toString().padStart(2, '0') }}
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- ═══ More Info ═══ -->
                    <section ref="contentRef" class="px-6 py-24">
                        <div class="max-w-[1400px] mx-auto">
                            <div
                                :class="['grid gap-16 lg:grid-cols-2 transition-all duration-700', contentVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0']"
                            >
                                <div>
                                    <div class="mb-6 flex items-center gap-4">
                                        <span class="inline-flex h-4 w-4 rotate-45 border-2 border-black bg-brutalist-blue"></span>
                                        <span class="text-[11px] font-black uppercase tracking-[0.28em] opacity-50">{{ t('portafolio.project_overview') || 'DESCRIPCION' }}</span>
                                        <span class="h-px flex-1 bg-black/20 dark:bg-white/20"></span>
                                    </div>
                                    <p class="text-xl font-black uppercase italic leading-relaxed">
                                        {{ project.desc }}
                                    </p>
                                </div>
                                <div>
                                    <div class="mb-6 flex items-center gap-4">
                                        <span class="inline-flex h-4 w-4 rotate-45 border-2 border-black bg-brutalist-yellow"></span>
                                        <span class="text-[11px] font-black uppercase tracking-[0.28em] opacity-50">{{ t('portafolio.project_details') || 'DETALLES' }}</span>
                                        <span class="h-px flex-1 bg-black/20 dark:bg-white/20"></span>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="flex items-center gap-4 border-2 border-black p-4">
                                            <span class="text-[10px] font-black uppercase tracking-[0.24em] opacity-50 w-24">{{ t('portafolio.category_label') || 'CATEGORIA' }}</span>
                                            <Badge class="border-2 border-black bg-white px-3 py-1 text-[10px] font-black uppercase dark:border-white dark:bg-zinc-900 dark:text-white">
                                                {{ project.category }}
                                            </Badge>
                                        </div>
                                        <div class="flex items-center gap-4 border-2 border-black p-4">
                                            <span class="text-[10px] font-black uppercase tracking-[0.24em] opacity-50 w-24">{{ t('portafolio.tech_count') || 'TECNOLOGIAS' }}</span>                                            <span class="text-sm font-black uppercase italic">{{ project.technologies?.length || 0 }}</span>
                                        </div>
                                        <div class="flex items-center gap-4 border-2 border-black p-4">
                                            <span class="text-[10px] font-black uppercase tracking-[0.24em] opacity-50 w-24">{{ t('portafolio.images_count') || 'IMAGENES' }}</span>
                                            <span class="text-sm font-black uppercase italic">{{ allImages.length }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- ═══ CTA ═══ -->
                    <section class="relative border-y-8 border-black bg-black py-20 text-white dark:border-white dark:bg-white dark:text-black">
                        <div class="absolute inset-0" style="background-image: repeating-linear-gradient(0deg, transparent, transparent 40px, rgba(255,255,255,0.02) 40px, rgba(255,255,255,0.02) 41px);" aria-hidden="true"></div>
                        <div class="max-w-[1400px] mx-auto px-6 relative z-10">
                            <div class="flex flex-col items-center text-center gap-8">
                                <span class="inline-flex h-5 w-5 rotate-45 border-2 border-white bg-brutalist-yellow dark:border-black"></span>
                                <h2 class="text-[clamp(2rem,4vw,3.5rem)] font-display font-black uppercase italic leading-[0.9]">
                                    {{ t('portafolio.cta_title') || 'EXPLORA MAS PROYECTOS' }}
                                </h2>
                                <p class="text-lg font-black uppercase italic text-white/70 dark:text-black/70 max-w-lg">
                                    {{ t('portafolio.cta_desc') || 'Cada proyecto cuenta una historia diferente. Volvé al portfolio y descubrí más trabajos.' }}
                                </p>
                                <Link :href="route('portafolio')">
                                    <span class="inline-flex items-center gap-3 border-4 border-white bg-white px-10 py-4 text-sm font-black uppercase italic tracking-[0.2em] text-black transition-all hover:translate-x-[4px] hover:translate-y-[4px] dark:border-black dark:bg-black dark:text-white">
                                        {{ t('portafolio.back') || 'VOLVER AL PORTAFOLIO' }}
                                        <ArrowLeft class="w-5 h-5" />
                                    </span>
                                </Link>
                            </div>
                        </div>
                    </section>
                </div>
            </Transition>
        </main>

        <PublicSiteFooter />

        <!-- ═══ Lightbox ═══ -->
        <Dialog v-model:open="lightboxOpen">
            <DialogContent
                class="!max-w-6xl !w-[calc(100%-2rem)] !rounded-none !border-4 border-white !bg-black !text-white !p-0 shadow-[10px_10px_0px_rgba(255,255,255,0.3)] lightbox-dialog-enter"
            >
                <div class="grid lg:grid-cols-[1.2fr_0.8fr]">
                    <div class="relative min-h-[22rem] bg-zinc-950 overflow-hidden">
                        <transition name="lightbox-image" mode="out-in">
                            <img
                                :key="lightboxIndex"
                                :src="allImages[lightboxIndex]?.image_url"
                                :alt="project.name"
                                class="h-full w-full object-contain"
                                loading="lazy"
                            />
                        </transition>

                        <button
                            v-if="allImages.length > 1"
                            type="button"
                            class="absolute left-4 top-1/2 -translate-y-1/2 border-2 border-white bg-black/80 p-3 text-white transition-all hover:bg-brutalist-yellow hover:text-black hover:scale-110"
                            @click.stop="showPrev"
                        >
                            <ChevronLeft class="h-6 w-6" />
                        </button>
                        <button
                            v-if="allImages.length > 1"
                            type="button"
                            class="absolute right-4 top-1/2 -translate-y-1/2 border-2 border-white bg-black/80 p-3 text-white transition-all hover:bg-brutalist-yellow hover:text-black hover:scale-110"
                            @click.stop="showNext"
                        >
                            <ChevronRight class="h-6 w-6" />
                        </button>

                        <div class="absolute bottom-4 left-4 border-2 border-white/30 bg-black/70 px-3 py-1.5 text-xs font-black uppercase tracking-wider">
                            {{ lightboxIndex + 1 }} / {{ allImages.length }}
                        </div>
                    </div>

                    <div class="border-t-4 border-white p-6 lg:border-l-4 lg:border-t-0 flex flex-col">
                        <div class="mb-3 text-[11px] font-black uppercase tracking-[0.28em] text-brutalist-yellow flex items-center gap-2">
                            <span class="inline-block w-6 h-px bg-brutalist-yellow"></span>
                            {{ t('portafolio.project_label') }}
                        </div>

                        <h3 class="text-3xl font-display font-black uppercase italic leading-none">{{ project.name }}</h3>
                        <p class="mt-6 text-sm font-black uppercase leading-relaxed text-white/75">{{ project.desc }}</p>

                        <div class="mt-6 flex flex-wrap gap-2">
                            <TooltipProvider :delay-duration="0">
                                <Tooltip
                                    v-for="tech in project.technologies"
                                    :key="'lightbox-' + tech.id"
                                >
                                    <TooltipTrigger as-child>
                                        <span
                                            class="border-2 border-white/50 bg-transparent px-2 py-1 text-[10px] font-black uppercase tracking-[0.2em] text-white flex items-center justify-center w-12 h-9"
                                        >
                                            <img v-if="tech.logo_url" :src="tech.logo_url" class="w-full h-full object-contain" />
                                            <span v-else>{{ tech.name }}</span>
                                        </span>
                                    </TooltipTrigger>
                                    <TooltipContent side="top" :side-offset="8">
                                        {{ tech.name }}
                                    </TooltipContent>
                                </Tooltip>
                            </TooltipProvider>
                        </div>

                        <div v-if="allImages.length > 1" class="mt-auto pt-6">
                            <div class="mb-3 text-[10px] font-black uppercase tracking-[0.24em] text-white/50">
                                {{ t('portafolio.gallery_label') }}
                            </div>
                            <div class="grid grid-cols-4 gap-2">
                                <button
                                    v-for="(image, idx) in allImages"
                                    :key="image.id"
                                    type="button"
                                    class="overflow-hidden border-2 transition-all duration-200 hover:scale-105"
                                    :class="idx === lightboxIndex ? 'border-brutalist-yellow' : 'border-white/20 hover:border-white/50'"
                                    @click="lightboxIndex = idx"
                                >
                                    <BlurImage :src="image.image_url" :alt="project.name" :width="150" :height="100" class="h-16 w-full" />
                                </button>
                            </div>
                        </div>

                        <a v-if="project.project_url" :href="project.project_url" target="_blank" class="mt-6 block">
                            <Button class="h-auto w-full rounded-none border-4 border-white bg-white py-4 font-black uppercase italic text-black transition-all hover:bg-brutalist-pink hover:text-white group/btn">
                                <span class="flex items-center justify-center gap-2">
                                    {{ t('portafolio.view_project') }}
                                    <ExternalLink class="h-4 w-4 group-hover/btn:translate-x-1 group-hover/btn:-translate-y-1 transition-transform" />
                                </span>
                            </Button>
                        </a>
                    </div>
                </div>

                <!-- DialogClose button (X) positioned by DialogContent -->
            </DialogContent>
        </Dialog>
    </div>
</template>

<style>
.font-display { font-family: 'Space Grotesk', sans-serif; }

body {
    @apply bg-white dark:bg-black transition-colors duration-500;
}

</style>
