<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight } from 'lucide-vue-next';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import PublicGridBackground from '@/Components/PublicGridBackground.vue';
import PublicSiteFooter from '@/Components/PublicSiteFooter.vue';
import PublicSiteHeader from '@/Components/PublicSiteHeader.vue';
import ServiceCard from '@/Components/ServiceCard.vue';
import TechStackSection from '@/Components/TechStackSection.vue';
import WorkflowSteps from '@/Components/WorkflowSteps.vue';
import { useServicesPage } from './useServicesPage';

defineProps({
    technologies: { type: Array, default: () => [] },
});

const {
    t, pageTitle, pageUrl, pageDesc, skeletonReady, ctaRef, ctaVisible,
    isVisible, serviciosJsonLd, serviceStats, sectionDelay, services,
} = useServicesPage();
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
        <link rel="canonical" :href="pageUrl" />    </Head>

    <Teleport to="head">
        <component
            v-for="(schema, idx) in serviciosJsonLd"
            :key="idx"
            :is="'script'"
            type="application/ld+json"
            v-html="JSON.stringify(schema)"
        />
    </Teleport>

    <div
        class="min-h-screen overflow-x-hidden bg-white font-sans text-black selection:bg-brutalist-yellow selection:text-black dark:bg-black dark:text-white"
    >
        <PublicGridBackground />
        <PublicSiteHeader />

        <Transition name="fade" mode="out-in">
            <div v-if="!skeletonReady" key="skeleton" class="relative overflow-hidden pointer-events-none select-none">
                <header class="relative overflow-hidden pb-24 pt-48 md:pb-32 md:pt-56">
                    <div class="mx-auto max-w-[1400px] px-6">
                        <div class="grid items-start gap-12 xl:grid-cols-[1.1fr_0.9fr] xl:gap-20">
                            <div class="space-y-6">
                                <div class="h-12 w-48 skeleton-bg"></div>
                                <div class="h-24 w-full skeleton-bg"></div>
                                <div class="h-24 w-4/5 skeleton-bg"></div>
                                <div class="h-6 w-3/4 skeleton-bg"></div>
                                <div class="h-14 w-48 skeleton-bg"></div>
                            </div>
                            <div class="space-y-6">
                                <div class="h-48 w-full skeleton-bg"></div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div v-for="i in 3" :key="'stat-' + i" class="h-28 skeleton-bg"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <div class="absolute inset-0 shimmer-sweep z-10"></div>
            </div>

            <div v-else key="content">
                <header class="relative overflow-hidden pb-24 pt-48 md:pb-32 md:pt-56">
                    <!-- Decorative floating elements -->
                    <div class="pointer-events-none absolute inset-0">
                        <div class="float-slow absolute left-[6%] top-32 h-28 w-28 rotate-12 border-4 border-black/15 bg-brutalist-yellow/40 blur-[2px] dark:border-white/10"></div>
                        <div class="float-slower absolute right-[12%] top-44 h-80 w-80 rounded-full bg-brutalist-pink/15 blur-3xl"></div>
                        <div class="float-slow-reverse absolute bottom-8 left-1/3 h-56 w-56 rounded-full bg-brutalist-blue/15 blur-3xl"></div>
                        <div class="float-slow absolute left-[40%] top-56 h-16 w-16 rotate-45 bg-brutalist-purple/40 blur-sm"></div>
                        <div class="float-slower absolute right-[5%] top-[50%] h-32 w-32 rounded-full bg-brutalist-lime/10 blur-3xl"></div>
                    </div>

                    <div class="relative z-10 mx-auto max-w-[1400px] px-6">
                        <div class="grid items-start gap-12 xl:grid-cols-[1.1fr_0.9fr] xl:gap-20">
                            <!-- Left Column -->
                            <div
                                :class="[
                                    'transform transition-all duration-700',
                                    isVisible ? 'translate-x-0 opacity-100' : '-translate-x-12 opacity-0'
                                ]"
                            >
                                <Badge class="mb-8 inline-block rotate-1 border-4 border-black bg-brutalist-pink px-4 py-2 text-xl font-black text-white">
                                    {{ t('servicios.badge') }}
                                </Badge>

                                <h1 class="mb-10 text-[clamp(3rem,8vw,6.3rem)] font-display font-black uppercase italic leading-[0.8] tracking-tighter">
                                    {{ t('servicios.title1') }} <br/>
                                    <span class="relative ml-[-1rem] inline-block bg-brutalist-yellow px-4 text-black shadow-[6px_6px_0px_rgba(0,0,0,1)] dark:shadow-[6px_6px_0px_rgba(255,255,255,1)]">
                                        {{ t('servicios.title2') }}
                                    </span> <br/>
                                    <span class="relative mt-4 inline-block text-white">
                                        <span class="absolute inset-0 -rotate-1 bg-black dark:bg-zinc-800"></span>
                                        <span class="relative z-10 px-4">{{ t('servicios.title3') }}</span>
                                    </span>
                                </h1>

                                <div class="max-w-3xl border-l-8 border-black pl-6 dark:border-white">
                                    <p class="text-2xl font-black uppercase italic leading-tight text-black/80 dark:text-zinc-300">
                                        {{ t('servicios.subtitle') }}
                                    </p>
                                </div>

                                <!-- CTA -->
                                <div class="mt-10">
                                    <Button as-child class="group h-auto rounded-none border-4 border-black bg-black px-8 py-5 text-xl font-black text-white shadow-brutalist transition-all hover:translate-x-[4px] hover:translate-y-[4px] hover:bg-brutalist-yellow hover:text-black hover:shadow-brutalist-hover dark:border-white dark:bg-brutalist-yellow dark:text-black dark:shadow-brutalist-white dark:hover:bg-brutalist-pink dark:hover:text-white md:px-10 md:py-6 md:text-2xl">
                                        <Link :href="route('contacto')">
                                            <span>{{ t('servicios.cta_button') }}</span>
                                            <ArrowRight class="ml-4 h-7 w-7 transition-transform group-hover:translate-x-2 group-hover:-translate-y-1 md:h-8 md:w-8" />
                                        </Link>
                                    </Button>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div
                                :class="[
                                    'transform transition-all duration-700 delay-200',
                                    isVisible ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'
                                ]"
                            >
                                <!-- Lead block -->
                                <div class="relative overflow-hidden border-4 border-black bg-white shadow-brutalist dark:border-white dark:bg-zinc-950 dark:shadow-brutalist-white">
                                    <div class="pointer-events-none absolute -right-4 -top-6 select-none text-[8rem] font-display font-black italic leading-none text-black/5 dark:text-white/5">//</div>
                                    <div class="absolute left-0 top-0 h-full w-2 bg-brutalist-yellow"></div>
                                    <div class="p-8 pl-10">
                                        <p class="mb-4 text-sm font-black uppercase tracking-[0.28em] text-brutalist-pink">{{ t('servicios.lead_label') }}</p>
                                        <p class="text-xl font-black uppercase leading-tight text-black dark:text-white md:text-2xl">
                                            {{ t('servicios.lead_text') }}
                                        </p>
                                        <div class="mt-6 flex items-center gap-3 border-t-4 border-black/10 pt-6 dark:border-white/10">
                                            <span class="flex h-3 w-3 animate-pulse rounded-full bg-brutalist-yellow"></span>
                                            <span class="text-[10px] font-black uppercase tracking-[0.28em] text-zinc-500">{{ t('servicios.stats.s1') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stats -->
                                <div class="mt-6 grid grid-cols-3 gap-4">
                                    <div
                                        v-for="(stat, index) in serviceStats"
                                        :key="stat.labelKey"
                                        :style="sectionDelay(index, 120)"
                                        :class="[
                                            'border-4 border-black bg-brutalist-yellow p-5 text-black shadow-brutalist transition-all duration-500 dark:border-white dark:shadow-brutalist-white',
                                            isVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'
                                        ]"
                                    >
                                        <div class="text-lg font-display font-black italic leading-none md:text-xl">{{ stat.value }}</div>
                                        <div class="mt-3 text-[10px] font-black uppercase leading-tight tracking-[0.1em] break-words">{{ t(stat.labelKey) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
            </div>
        </Transition>

        <main id="main-content" class="relative px-6 pb-32">
            <div class="relative z-10 mx-auto max-w-[1400px]">
                <!-- Intro: Mapa de Capacidades -->
                <div class="relative mb-16 overflow-hidden border-4 border-black bg-black p-8 text-white shadow-brutalist dark:border-white dark:shadow-brutalist-white md:p-12">
                    <div class="pointer-events-none absolute -right-4 -top-4 select-none text-[8rem] font-display font-black italic leading-none text-white/5 md:text-[12rem]">///</div>
                    <div class="relative z-10 max-w-3xl">
                        <p class="mb-2 text-sm font-black uppercase tracking-[0.28em] text-brutalist-yellow">{{ t('servicios.map_label') }}</p>
                        <h2 class="text-4xl font-display font-black uppercase italic leading-none md:text-5xl">{{ t('servicios.map_title') }}</h2>
                        <p class="mt-6 text-base font-black uppercase leading-relaxed text-white/65 md:text-lg">
                            {{ t('servicios.map_text') }}
                        </p>
                    </div>
                    <div class="relative z-10 mt-8 flex items-center gap-4 border-t-4 border-white/15 pt-6">
                        <span class="flex h-2 w-2 animate-pulse rounded-full bg-brutalist-yellow"></span>
                        <span class="text-[10px] font-black uppercase tracking-[0.28em] text-white/40">4 SERVICIOS · 1 EJECUCION</span>
                    </div>
                </div>

                <!-- Service Cards -->
                <div class="mb-24 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <ServiceCard
                        v-for="(service, index) in services"
                        :key="index"
                        :service="service"
                        :index="index"
                    />
                </div>

                <!-- Full Tech Stack Section -->
                <TechStackSection :technologies="technologies" />

                <!-- Process Section -->
                <WorkflowSteps />
            </div>
        </main>

        <!-- CTA Section -->
        <section ref="ctaRef" class="relative overflow-hidden border-y-8 border-brutalist-yellow bg-black py-48 px-6">
            <!-- Decorative brutalist shapes -->
            <div class="pointer-events-none absolute inset-0 opacity-[0.04]"
                style="background-image: repeating-linear-gradient(0deg, transparent, transparent 2px, #fff 2px, #fff 3px),
                        repeating-linear-gradient(90deg, transparent, transparent 2px, #fff 2px, #fff 3px);
                        background-size: 40px 40px;">
            </div>
            <div class="pointer-events-none absolute -left-16 top-12 h-32 w-32 rotate-12 border-4 border-white/15"></div>
            <div class="pointer-events-none absolute -right-20 bottom-20 h-40 w-40 -rotate-6 border-4 border-white/10"></div>
            <div class="pointer-events-none absolute left-1/4 top-1/3 h-6 w-6 rotate-45 border-2 border-brutalist-pink/30"></div>
            <div class="pointer-events-none absolute right-1/3 bottom-1/4 h-4 w-4 rotate-45 border-2 border-brutalist-yellow/40"></div>

            <!-- Marquee background -->
            <div class="pointer-events-none absolute inset-0 flex items-center overflow-hidden">
                <div class="flex animate-[marquee_25s_linear_infinite] whitespace-nowrap opacity-[0.06]">
                    <div v-for="n in 4" :key="n" class="flex items-center gap-16 px-8">
                        <span v-for="i in 6" :key="'m-' + n + '-' + i" class="text-[12rem] font-display font-black text-white italic leading-none select-none">START</span>
                        <span class="inline-block h-10 w-10 rotate-45 border-2 border-white"></span>
                        <span v-for="i in 6" :key="'n-' + n + '-' + i" class="text-[12rem] font-display font-black text-white italic leading-none select-none">NOW</span>
                        <span class="inline-block h-10 w-10 rotate-45 border-2 border-white"></span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="relative z-10 mx-auto max-w-5xl text-center">
                <!-- Pre-heading diamond line -->
                <div
                    :class="[
                        'mb-8 flex items-center justify-center gap-4 transition-all duration-700 delay-300',
                        ctaVisible ? 'translate-y-0 opacity-100' : 'translate-y-6 opacity-0'
                    ]"
                >
                    <span class="inline-block h-px w-16 bg-brutalist-yellow"></span>
                    <span class="inline-flex h-3 w-3 rotate-45 border-2 border-brutalist-yellow bg-brutalist-yellow/30"></span>
                    <span class="inline-block h-px w-16 bg-brutalist-yellow"></span>
                </div>

                <!-- Title -->
                <h2
                    :class="[
                        'text-5xl font-display font-black uppercase italic leading-[0.8] text-white transition-all duration-700 delay-500 md:text-8xl',
                        ctaVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'
                    ]"
                >
                    {{ t('servicios.cta_title1') }} <br/>
                    <span class="text-brutalist-yellow italic">{{ t('servicios.cta_title2') }}</span>
                </h2>

                <!-- Subtitle line -->
                <p
                    :class="[
                        'mx-auto mt-8 max-w-xl text-base font-black uppercase italic tracking-wider text-white/40 transition-all duration-700 delay-700',
                        ctaVisible ? 'translate-y-0 opacity-100' : 'translate-y-6 opacity-0'
                    ]"
                >
                    SIN COMPROMISOS. SIN RODEOS. SOLO INGENIERÍA QUE IMPORTA.
                </p>

                <!-- Button -->
                <div
                    :class="[
                        'mt-12 transition-all duration-700 delay-900',
                        ctaVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'
                    ]"
                >
                    <Button
                        as-child
                        class="group relative overflow-hidden border-4 border-brutalist-yellow bg-transparent px-16 py-8 text-3xl font-black uppercase italic text-white shadow-brutalist transition-all duration-300 hover:translate-x-[4px] hover:translate-y-[4px] hover:shadow-brutalist-hover"
                    >
                        <Link :href="route('contacto')" class="flex items-center gap-4">
                            <span class="relative z-10 transition-transform duration-300 group-hover:-translate-x-2">{{ t('servicios.cta_button') }}</span>
                            <ArrowRight class="relative z-10 h-8 w-8 transition-all duration-300 group-hover:translate-x-2 group-hover:text-brutalist-yellow" />
                            <span class="absolute inset-0 -translate-x-full skew-x-12 bg-brutalist-yellow transition-transform duration-300 group-hover:translate-x-0"></span>
                        </Link>
                    </Button>
                </div>

                <!-- Bottom decorative line -->
                <div
                    :class="[
                        'mx-auto mt-16 flex items-center justify-center gap-4 transition-all duration-700 delay-[1100ms]',
                        ctaVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'
                    ]"
                >
                    <span class="inline-block h-px w-12 bg-white/20"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-white/20">BUILD BOLD</span>
                    <span class="inline-block h-px w-12 bg-white/20"></span>
                </div>
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
