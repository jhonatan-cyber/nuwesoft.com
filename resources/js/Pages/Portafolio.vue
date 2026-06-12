<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref, computed, watch } from 'vue';
import { useSkeletonLoader } from '@/composables/useSkeletonLoader';
import { useI18n } from 'vue-i18n';
import { usePageTracking } from '@/composables/usePageTracking';
import PublicGridBackground from '@/Components/PublicGridBackground.vue';
import PublicSiteHeader from '@/Components/PublicSiteHeader.vue';
import PublicSiteFooter from '@/Components/PublicSiteFooter.vue';
import PortfolioHero from '@/Components/PortfolioHero.vue';
import PortfolioProjectCard from '@/Components/PortfolioProjectCard.vue';
import PortfolioStats from '@/Components/PortfolioStats.vue';
import PortfolioCta from '@/Components/PortfolioCta.vue';
import PortfolioLightbox from '@/Components/PortfolioLightbox.vue';
import SkeletonCard from '@/Components/SkeletonCard.vue';
import {
    Code,
    Smartphone,
    Cloud,
    Zap,
    Terminal,
    Cpu,
    Database,
    Globe,
    Layers,
    Eye,
} from 'lucide-vue-next';

const props = defineProps({
    projects: { type: Array, default: () => [] },
    technologies: { type: Array, default: () => [] }
});

const { t } = useI18n();

usePageTracking()

const page = usePage();
const settings = computed(() => page.props.settings || {});
const siteName = computed(() => settings.value.site_name || 'NUWESOFT');
const pageTitle = computed(() => t('portafolio.head_title').replace('NUWESOFT', siteName.value));
const pageUrl = computed(() => window.location.href);
const pageDesc = computed(() => t('portafolio.subtitle'));
const isVisible = ref(false);
const visibleStats = ref(false);

// ── Skeleton Loading ──
const { skeletonReady } = useSkeletonLoader();

// Show enough skeletons to fill complete rows in the 3-column grid
const skeletonCount = computed(() => {
    const count = props.projects.length;
    if (count === 0) return 6;
    return Math.ceil(count / 3) * 3;
});

// ── Tech Logo Lookup ──
const getTechLogo = (techName) => {
    if (!props.technologies || props.technologies.length === 0) return null;
    const exactMatch = props.technologies.find(
        tech => tech.name.toLowerCase() === techName.toLowerCase()
    );
    if (exactMatch && exactMatch.logo_url) return exactMatch.logo_url;
    const partialMatch = props.technologies.find(
        tech => techName.toLowerCase().includes(tech.name.toLowerCase()) ||
                tech.name.toLowerCase().includes(techName.toLowerCase())
    );
    if (partialMatch && partialMatch.logo_url) return partialMatch.logo_url;
    return null;
};

// ── Categories ──
const categories = [
    { key: 'all', icon: Globe, accent: 'bg-white' },
    { key: 'web', icon: Code, accent: 'bg-brutalist-pink' },
    { key: 'mobile', icon: Smartphone, accent: 'bg-brutalist-blue' },
    { key: 'cloud', icon: Cloud, accent: 'bg-brutalist-yellow' },
    { key: 'automation', icon: Zap, accent: 'bg-brutalist-pink' },
];

const categoryHighlights = [
    { key: 'web', accent: 'bg-brutalist-pink', metric: '+12' },
    { key: 'cloud', accent: 'bg-brutalist-blue', metric: '+8' },
    { key: 'automation', accent: 'bg-brutalist-yellow', metric: '+15' },
];

const activeCategory = ref('all');
const lightboxProject = ref(null);
const lightboxIndex = ref(0);

// ── Projects ──
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

// ── Icon Resolver ──
const getIcon = (iconName) => {
    const icons = { Terminal, Code, Zap, Smartphone, Cloud, Globe, Cpu, Database, Layers, Eye };
    return icons[iconName] || Globe;
};

// ── Stats ──
const stats = computed(() => [
    { key: 'projects', icon: Cpu, displayValue: `${props.projects.length}+` },
    { key: 'uptime', icon: Zap, displayValue: '99.9%' },
    { key: 'commits', icon: Terminal, displayValue: '15K+' },
    { key: 'coffee', icon: Database, displayValue: 'NONSTOP' },
]);

const animatedStats = ref(stats.value.map(() => 0));
const statTargets = computed(() => [props.projects.length, 99.9, 15000, 0]);

// Recompute animatedStats when stats change (e.g. projects data loads)
watch(() => props.projects, (newProjects) => {
    if (newProjects?.length) {
        animatedStats.value = stats.value.map(() => 0);
        statTargets.value = [newProjects.length, 99.9, 15000, 0];
    }
}, { immediate: true });

const animateCounters = () => {
    visibleStats.value = true;
    stats.forEach((_, idx) => {
        if (idx === 3) { animatedStats.value[3] = 1; return; }
        const target = statTargets[idx];
        const duration = 2000;
        const steps = 60;
        const increment = target / steps;
        let current = 0;
        let step = 0;
        const interval = setInterval(() => {
            step++;
            current = Math.min(current + increment, target);
            animatedStats.value[idx] = current;
            if (step >= steps) {
                clearInterval(interval);
                animatedStats.value[idx] = target;
            }
        }, duration / steps);
    });
};

// ── Scroll-sync Counter Trigger ──
const statsObserver = ref(null);
const statsComponentRef = ref(null);

onMounted(() => {
    isVisible.value = true;
    window.addEventListener('keydown', handleKeydown);

    const observer = new IntersectionObserver(
        ([entry]) => {
            if (entry.isIntersecting && !visibleStats.value) {
                animateCounters();
            }
        },
        { threshold: 0.3 }
    );
    if (statsComponentRef.value?.statsSection) {
        observer.observe(statsComponentRef.value.statsSection);
    }
    statsObserver.value = observer;
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
    if (statsObserver.value) statsObserver.value.disconnect();
});

// ── Lightbox ──
const openLightbox = (project, index = 0) => {
    const images = project.images?.length                ? project.images
                : [];
    if (!images.length) return;
    lightboxProject.value = { ...project, images };
    lightboxIndex.value = index;
};

const closeLightbox = () => {
    lightboxProject.value = null;
    lightboxIndex.value = 0;
};

const showNextImage = () => {
    if (!lightboxProject.value) return;
    lightboxIndex.value = (lightboxIndex.value + 1) % lightboxProject.value.images.length;
};

const showPrevImage = () => {
    if (!lightboxProject.value) return;
    lightboxIndex.value = (lightboxIndex.value - 1 + lightboxProject.value.images.length) % lightboxProject.value.images.length;
};

const handleKeydown = (event) => {
    if (!lightboxProject.value) return;
    if (event.key === 'Escape') closeLightbox();
    if (event.key === 'ArrowRight') showNextImage();
    if (event.key === 'ArrowLeft') showPrevImage();
};

// ── Stagger Delays ──
const staggerDelay = (index, step = 80) => `${index * step}ms`;
</script>

<template>
    <Head :title="pageTitle">
        <meta name="description" :content="pageDesc" />
        <meta property="og:title" :content="pageTitle" />
        <meta property="og:description" :content="pageDesc" />
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="pageUrl" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="pageTitle" />
        <meta name="twitter:description" :content="pageDesc" />
        <link rel="canonical" :href="pageUrl" />
    </Head>
    <div class="min-h-screen overflow-x-hidden bg-white font-sans text-black selection:bg-brutalist-yellow selection:text-black dark:bg-black dark:text-white">
        <PublicGridBackground />
        <PublicSiteHeader />

        <main id="main-content" class="pt-32 pb-24 relative z-10">
            <PortfolioHero
                :categories="categories"
                :active-category="activeCategory"
                :category-highlights="categoryHighlights"
                @update:active-category="activeCategory = $event"
            />

            <!-- ═══ Projects Grid ═══ -->
            <section class="px-6 mb-24">
                <div class="max-w-[1400px] mx-auto">
                    <Transition name="fade" mode="out-in">
                        <!-- Skeleton Grid (while loading) -->
                        <div
                            v-if="!skeletonReady"
                            key="skeleton"
                            class="grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3"
                        >
                            <SkeletonCard v-for="i in skeletonCount" :key="'skel-' + i" />
                        </div>

                        <!-- Cards Grid (loaded) -->
                        <div v-else key="cards">
                            <TransitionGroup
                                name="project-grid"
                                tag="div"
                                class="grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3"
                            >
                                <PortfolioProjectCard
                                    v-for="(project, i) in displayProjects"
                                    :key="project.id"
                                    :project="project"
                                    :index="i"
                                    :get-tech-logo="getTechLogo"
                                    :get-icon="getIcon"
                                    @open-lightbox="(project, index) => openLightbox(project, index)"
                                />
                            </TransitionGroup>

                            <!-- Empty State -->
                            <Transition name="fade">
                                <div
                                    v-if="displayProjects.length === 0"
                                    class="border-4 border-black bg-white p-10 text-center shadow-brutalist dark:border-white dark:bg-zinc-950 dark:shadow-brutalist-white"
                                >
                                    <div class="mb-6 inline-flex items-center justify-center w-20 h-20 border-4 border-black bg-brutalist-yellow">
                                        <Zap class="w-10 h-10 text-black" />
                                    </div>
                                    <p class="text-sm font-black uppercase tracking-[0.28em] text-brutalist-pink">{{ t('portafolio.empty_label') }}</p>
                                    <h3 class="mt-4 text-4xl font-display font-black uppercase italic leading-none">{{ t('portafolio.empty_title') }}</h3>
                                    <p class="mx-auto mt-6 max-w-2xl text-lg font-black uppercase leading-tight opacity-75">
                                        {{ t('portafolio.empty_text') }}
                                    </p>
                                </div>
                            </Transition>
                        </div>
                    </Transition>
                </div>
            </section>

            <PortfolioStats
                ref="statsComponentRef"
                :stats="stats"
            :animated-stats="animatedStats"
            :visible-stats="visibleStats"
            :key="stats[0].displayValue"
            />

            <PortfolioCta :is-visible="isVisible" />
        </main>

        <PublicSiteFooter />

        <PortfolioLightbox
            :project="lightboxProject"
            :current-index="lightboxIndex"
            :get-tech-logo="getTechLogo"
            @close="closeLightbox"
            @prev="showPrevImage"
            @next="showNextImage"
            @update:current-index="lightboxIndex = $event"
        />
    </div>
</template>

<style>
.font-display { font-family: 'Space Grotesk', sans-serif; }

body {
    @apply bg-white dark:bg-black transition-colors duration-500;
}

/* ── Floating Animations ── */
@keyframes float-slow {
    0%, 100% { transform: translate3d(0, 0, 0) rotate(12deg); }
    50% { transform: translate3d(0, -16px, 0) rotate(14deg); }
}

@keyframes float-slower {
    0%, 100% { transform: translate3d(0, 0, 0); }
    50% { transform: translate3d(-20px, 12px, 0); }
}

@keyframes float-slow-reverse {
    0%, 100% { transform: translate3d(0, 0, 0); }
    50% { transform: translate3d(16px, -12px, 0); }
}

.float-slow { animation: float-slow 10s ease-in-out infinite; }
.float-slower { animation: float-slower 14s ease-in-out infinite; }
.float-slow-reverse { animation: float-slow-reverse 12s ease-in-out infinite; }

/* ── Badge Breathe ── */
@keyframes badge-breathe {
    0%, 100% { transform: translateY(0) scale(1); }
    50% { transform: translateY(-2px) scale(1.02); }
}

.badge-breathe { animation: badge-breathe 5s ease-in-out infinite; }

/* ── Bounce small for filter icons ── */
@keyframes bounce-small {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-3px); }
}

.animate-bounce-small { animation: bounce-small 0.6s ease-in-out 2; }

/* ── Card Tilt ── */
.card-tilt {
    will-change: transform;
    transition: transform 0.1s ease-out;
}

/* ── Project Grid Transitions ── */
.project-grid-enter-active {
    transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

.project-grid-leave-active {
    transition: all 0.3s ease-in;
    position: absolute;
}

.project-grid-enter-from {
    opacity: 0;
    transform: translateY(30px) scale(0.95);
}

.project-grid-leave-to {
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
}

.project-grid-move {
    transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

/* ── Lightbox Transitions ── */
.lightbox-enter-active {
    transition: opacity 0.25s ease;
}

.lightbox-leave-active {
    transition: opacity 0.2s ease;
}

.lightbox-enter-from,
.lightbox-leave-to {
    opacity: 0;
}

.lightbox-enter-active > div,
.lightbox-leave-active > div {
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.lightbox-enter-from > div {
    transform: scale(0.92) translateY(20px);
}

.lightbox-leave-to > div {
    transform: scale(0.95) translateY(10px);
}

/* ── Lightbox Image Transition ── */
.lightbox-image-enter-active {
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

.lightbox-image-leave-active {
    transition: all 0.15s ease-in;
}

.lightbox-image-enter-from {
    opacity: 0;
    transform: scale(0.95);
}

.lightbox-image-leave-to {
    opacity: 0;
    transform: scale(1.05);
}

/* ── Scrollbar ── */
::-webkit-scrollbar {
    width: 12px;
}

::-webkit-scrollbar-track {
    @apply bg-black;
}

::-webkit-scrollbar-thumb {
    @apply bg-brutalist-pink border-4 border-black;
}

::-webkit-scrollbar-thumb:hover {
    @apply bg-brutalist-yellow;
}
</style>
