<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
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
import { Rocket, Shield, Zap, Sparkles, TrendingUp, Cpu, ArrowRight, Code, Layers, Globe } from 'lucide-vue-next';

const { t } = useI18n();

const isVisible = ref(false);
const heroStats = [
    { value: '48H', labelKey: 'hero.stats.m1' },
    { value: '99.9%', labelKey: 'hero.stats.m2' },
    { value: '24/7', labelKey: 'hero.stats.m3' },
];
const heroHighlights = [
    { icon: Layers, titleKey: 'hero.highlights.h1.title', descriptionKey: 'hero.highlights.h1.desc', tone: 'bg-brutalist-yellow text-black' },
    { icon: Zap, titleKey: 'hero.highlights.h2.title', descriptionKey: 'hero.highlights.h2.desc', tone: 'bg-brutalist-pink text-white' },
    { icon: Shield, titleKey: 'hero.highlights.h3.title', descriptionKey: 'hero.highlights.h3.desc', tone: 'bg-black text-white dark:bg-white dark:text-black' },
];
const servicesPreviewCards = [
    {
        titleKey: 'services_preview.card1.title',
        descKey: 'services_preview.card1.desc',
        eyebrowKey: 'services_preview.card1.eyebrow',
        detailKey: 'services_preview.card1.detail',
        outcomeKey: 'services_preview.card1.outcome',
        bullets: ['services_preview.card1.b1', 'services_preview.card1.b2', 'services_preview.card1.b3'],
        metric: '01',
        icon: Code,
        shell: 'bg-[#fff7e8] text-black',
        accent: 'bg-brutalist-yellow',
        iconWrap: 'bg-black text-white dark:bg-black dark:text-white',
        linkTone: 'hover:text-brutalist-pink',
    },
    {
        titleKey: 'services_preview.card2.title',
        descKey: 'services_preview.card2.desc',
        eyebrowKey: 'services_preview.card2.eyebrow',
        detailKey: 'services_preview.card2.detail',
        outcomeKey: 'services_preview.card2.outcome',
        bullets: ['services_preview.card2.b1', 'services_preview.card2.b2', 'services_preview.card2.b3'],
        metric: '02',
        icon: Layers,
        shell: 'bg-brutalist-yellow text-black',
        accent: 'bg-brutalist-pink',
        iconWrap: 'bg-white text-black',
        linkTone: 'hover:text-black/70',
    },
    {
        titleKey: 'services_preview.card3.title',
        descKey: 'services_preview.card3.desc',
        eyebrowKey: 'services_preview.card3.eyebrow',
        detailKey: 'services_preview.card3.detail',
        outcomeKey: 'services_preview.card3.outcome',
        bullets: ['services_preview.card3.b1', 'services_preview.card3.b2', 'services_preview.card3.b3'],
        metric: '03',
        icon: Globe,
        shell: 'bg-[#111111] text-white dark:bg-[#f5f1e8] dark:text-black',
        accent: 'bg-brutalist-blue',
        iconWrap: 'bg-brutalist-pink text-white dark:bg-black dark:text-white',
        linkTone: 'hover:text-brutalist-yellow',
    }
];
const manifestoCards = [
    { key: 'p1', icon: Zap, accent: 'bg-brutalist-yellow text-black', stat: '+3X' },
    { key: 'p2', icon: Shield, accent: 'bg-brutalist-pink text-white', stat: '-40%' },
    { key: 'p3', icon: TrendingUp, accent: 'bg-black text-white dark:bg-white dark:text-black', stat: 'LONG' },
];
onMounted(() => {
    isVisible.value = true;
});
</script>

<template>
    <Head title="NUWESOFT | Digital Master Engineering" />
    
    <div class="min-h-screen overflow-x-hidden bg-white font-sans text-black selection:bg-brutalist-yellow selection:text-black dark:bg-black dark:text-white">
        <PublicGridBackground />
        <PublicSiteHeader />

        <!-- Hero Section -->
        <header class="relative overflow-hidden pt-40 pb-24 md:pt-48 md:pb-32">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute left-[4%] top-24 h-32 w-32 rotate-12 border-4 border-black/15 bg-brutalist-yellow/50 blur-[2px] dark:border-white/10"></div>
                <div class="absolute right-[8%] top-36 h-72 w-72 rounded-full bg-brutalist-blue/15 blur-3xl"></div>
                <div class="absolute bottom-0 left-1/3 h-56 w-56 rounded-full bg-brutalist-pink/15 blur-3xl"></div>
            </div>
            <div class="max-w-[1400px] mx-auto px-6 relative z-10">
                <div class="grid lg:grid-cols-12 gap-12 xl:gap-16 items-start">
                    <div class="lg:col-span-7">
                        <div :class="['transition-all duration-700 transform', isVisible ? 'translate-x-0 opacity-100' : '-translate-x-12 opacity-0']">
                            <Badge class="mb-6 rounded-none border-2 border-black bg-brutalist-pink px-4 py-1 font-black uppercase tracking-[0.2em] text-white shadow-[6px_6px_0px_rgba(0,0,0,1)] dark:border-white dark:shadow-[6px_6px_0px_rgba(255,255,255,1)]">
                                {{ t('hero.badge') }}
                            </Badge>

                            <div class="mb-8 inline-flex items-center gap-3 border-4 border-black bg-white px-4 py-3 shadow-brutalist dark:border-white dark:bg-zinc-950 dark:shadow-brutalist-white">
                                <div class="h-3 w-3 animate-pulse bg-brutalist-yellow"></div>
                                <span class="text-xs font-black uppercase tracking-[0.28em] text-black dark:text-white">{{ t('hero.kicker') }}</span>
                            </div>

                            <h1 class="relative mb-8 text-4xl font-display font-black uppercase italic leading-[0.88] tracking-[-0.05em] text-black dark:text-white sm:text-6xl md:text-7xl xl:text-[8rem]">
                                <span class="block">{{ t('hero.title1') }}</span>
                                <span class="relative inline-block bg-brutalist-yellow px-3 text-black md:px-5">
                                    {{ t('hero.title2') }}
                                </span>
                                <span class="block">{{ t('hero.title3') }}</span>
                            </h1>

                            <p class="max-w-3xl text-lg font-black uppercase leading-tight text-black dark:text-zinc-300 md:text-2xl xl:text-3xl">
                                {{ t('hero.subtitle') }}
                            </p>

                            <div class="mt-10 flex flex-wrap gap-6">
                                <Button as-child class="group h-auto rounded-none border-4 border-black bg-black px-8 py-5 text-xl font-black text-white shadow-brutalist transition-all hover:translate-x-[4px] hover:translate-y-[4px] hover:shadow-brutalist-hover dark:border-white dark:bg-brutalist-yellow dark:text-black dark:shadow-brutalist-white dark:hover:bg-brutalist-pink dark:hover:text-white md:px-10 md:py-6 md:text-2xl">
                                    <Link :href="route('contacto')">
                                        {{ t('hero.cta_start') }}
                                        <Rocket class="ml-4 h-7 w-7 transition-transform group-hover:-translate-y-2 group-hover:translate-x-2 md:h-8 md:w-8" />
                                    </Link>
                                </Button>
                                <Button as-child variant="outline" class="h-auto rounded-none border-4 border-black bg-transparent px-8 py-5 text-xl font-black text-black shadow-brutalist transition-all hover:bg-brutalist-yellow hover:text-black dark:border-white dark:text-white dark:shadow-brutalist-white md:px-10 md:py-6 md:text-2xl">
                                    <Link :href="route('servicios')">
                                        {{ t('hero.cta_solutions') }}
                                    </Link>
                                </Button>
                            </div>

                            <div class="mt-12 grid gap-4 md:grid-cols-3">
                                <div v-for="stat in heroStats" :key="stat.label" class="border-4 border-black bg-white p-5 shadow-brutalist transition-transform hover:-translate-y-1 dark:border-white dark:bg-zinc-950 dark:shadow-brutalist-white">
                                    <div class="text-3xl font-black uppercase italic leading-none text-black dark:text-white md:text-4xl">{{ stat.value }}</div>
                                    <div class="mt-2 text-xs font-black uppercase tracking-[0.25em] text-zinc-600 dark:text-zinc-400">{{ t(stat.labelKey) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <div :class="['transition-all duration-700 delay-150 transform', isVisible ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0']">
                            <div class="relative">
                                <div class="absolute -left-4 top-10 hidden h-[82%] w-full border-4 border-black bg-brutalist-yellow lg:block dark:border-white"></div>
                                <Card class="relative overflow-hidden rounded-none border-4 border-black bg-white shadow-brutalist-lg dark:border-white dark:bg-zinc-950 dark:shadow-brutalist-white-lg">
                                    <CardHeader class="border-b-4 border-black bg-brutalist-blue p-6 dark:border-white">
                                        <div class="mb-4 flex items-center justify-between">
                                            <div class="inline-flex items-center gap-3 border-2 border-black bg-white px-3 py-2 text-xs font-black uppercase tracking-[0.28em] text-black">
                                                <Cpu class="h-4 w-4" />
                                                {{ t('hero.panel.badge') }}
                                            </div>
                                            <div class="inline-flex items-center gap-2 border-2 border-black bg-black px-3 py-2 text-xs font-black uppercase tracking-[0.24em] text-white dark:border-white dark:bg-white dark:text-black">
                                                <span class="h-2.5 w-2.5 animate-pulse bg-brutalist-yellow"></span>
                                                {{ t('hero.panel.live') }}
                                            </div>
                                        </div>
                                        <CardTitle class="text-4xl font-display font-black uppercase italic tracking-tight text-black md:text-5xl">
                                            {{ t('hero.panel.title') }}
                                        </CardTitle>
                                        <CardDescription class="mt-3 max-w-md text-sm font-black uppercase leading-relaxed tracking-[0.18em] text-black/70">
                                            {{ t('hero.panel.description') }}
                                        </CardDescription>
                                    </CardHeader>
                                    <CardContent class="space-y-5 p-6">
                                        <div class="grid gap-4 sm:grid-cols-2">
                                            <div class="border-4 border-black bg-brutalist-yellow p-5 dark:border-white">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs font-black uppercase tracking-[0.25em] text-black">{{ t('hero.panel.velocity_label') }}</span>
                                                    <TrendingUp class="h-5 w-5 text-black" />
                                                </div>
                                                <p class="mt-6 text-5xl font-black italic leading-none text-black">+220%</p>
                                                <p class="mt-2 text-sm font-black uppercase text-black/70">{{ t('hero.panel.velocity_text') }}</p>
                                            </div>
                                            <div class="border-4 border-black bg-black p-5 text-white dark:border-white dark:bg-white dark:text-black">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs font-black uppercase tracking-[0.25em]">{{ t('hero.panel.stack_label') }}</span>
                                                    <Globe class="h-5 w-5" />
                                                </div>
                                                <div class="mt-6 flex flex-wrap gap-2">
                                                    <span class="border-2 border-white px-3 py-1 text-xs font-black uppercase dark:border-black">{{ t('hero.panel.stack_items.s1') }}</span>
                                                    <span class="border-2 border-white px-3 py-1 text-xs font-black uppercase dark:border-black">{{ t('hero.panel.stack_items.s2') }}</span>
                                                    <span class="border-2 border-white px-3 py-1 text-xs font-black uppercase dark:border-black">{{ t('hero.panel.stack_items.s3') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid gap-4">
                                            <div v-for="highlight in heroHighlights" :key="highlight.titleKey" class="grid gap-4 border-4 border-black p-5 transition-transform hover:translate-x-2 dark:border-white md:grid-cols-[auto,1fr,auto]" :class="highlight.tone">
                                                <div class="flex h-14 w-14 items-center justify-center border-4 border-black bg-white text-black dark:border-white dark:bg-zinc-950 dark:text-white">
                                                    <component :is="highlight.icon" class="h-7 w-7" />
                                                </div>
                                                <div>
                                                    <h3 class="text-xl font-black uppercase leading-none">{{ t(highlight.titleKey) }}</h3>
                                                    <p class="mt-2 text-sm font-black uppercase leading-relaxed opacity-80">{{ t(highlight.descriptionKey) }}</p>
                                                </div>
                                                <div class="flex items-start justify-end">
                                                    <ArrowRight class="h-7 w-7" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between border-4 border-dashed border-black bg-white p-5 dark:border-white dark:bg-zinc-900">
                                            <div>
                                                <p class="text-xs font-black uppercase tracking-[0.25em] text-zinc-500 dark:text-zinc-400">{{ t('hero.panel.mission_label') }}</p>
                                                <p class="mt-2 text-2xl font-black uppercase italic text-black dark:text-white">{{ t('hero.panel.mission_text') }}</p>
                                            </div>
                                            <Sparkles class="h-10 w-10 text-brutalist-pink" />
                                        </div>
                                    </CardContent>
                                </Card>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Marquee / Stats -->
        <section class="py-8 bg-black dark:bg-white overflow-hidden border-y-4 border-black dark:border-white">
            <div class="flex whitespace-nowrap animate-[marquee_20s_linear_infinite]">
                <div v-for="n in 10" :key="n" class="flex items-center space-x-12 px-6">
                    <span class="text-2xl font-black text-white dark:text-black uppercase italic">{{ t('marquee.clean_code') }}</span>
                    <span class="text-3xl text-brutalist-yellow">★</span>
                    <span class="text-2xl font-black text-white dark:text-black uppercase italic">{{ t('marquee.scalable') }}</span>
                    <span class="text-3xl text-brutalist-pink">★</span>
                    <span class="text-2xl font-black text-white dark:text-black uppercase italic">{{ t('marquee.support') }}</span>
                    <span class="text-3xl text-brutalist-blue">★</span>
                </div>
            </div>
        </section>

        <!-- Manifesto Section -->
        <section class="py-20 md:py-32 relative overflow-hidden bg-white dark:bg-black">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-64 md:w-96 h-64 md:h-96 bg-brutalist-yellow opacity-10 rounded-full -translate-y-1/2 translate-x-1/2 blur-2xl md:blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-64 md:w-96 h-64 md:h-96 bg-brutalist-pink opacity-10 rounded-full translate-y-1/2 -translate-x-1/2 blur-2xl md:blur-3xl"></div>

            <div class="max-w-[1400px] mx-auto px-6 relative z-10">
                <div class="flex flex-col lg:flex-row items-start justify-between mb-16 md:mb-24 gap-12">
                    <div class="max-w-4xl">
                        <Badge class="bg-black dark:bg-white text-white dark:text-black font-black border-2 border-black dark:border-white mb-6 uppercase tracking-[0.3em] px-4 py-1">{{ t('manifesto.badge') }}</Badge>
                        <h2 class="text-5xl sm:text-7xl md:text-9xl font-display font-black uppercase italic leading-[0.8] tracking-tighter mb-8 text-black dark:text-white">
                            {{ t('manifesto.title1') }} <br/>
                            <span class="inline-block bg-brutalist-yellow px-4 text-black">{{ t('manifesto.title2') }}</span> <br/>
                            <span>{{ t('manifesto.title3') }}</span>
                        </h2>
                        <p class="max-w-3xl text-lg font-black uppercase leading-relaxed text-black/75 dark:text-zinc-300 md:text-2xl">
                            {{ t('manifesto.intro') }}
                        </p>
                    </div>
                    <div class="lg:max-w-md w-full space-y-6">
                        <div class="bg-brutalist-yellow border-4 md:border-8 border-black dark:border-white p-6 md:p-10 shadow-brutalist dark:shadow-brutalist-white -rotate-2 md:-rotate-3 hover:rotate-0 transition-transform duration-500">
                            <p class="font-black text-xl md:text-3xl italic uppercase leading-tight md:leading-none text-black">
                                {{ t('manifesto.quote') }}
                            </p>
                            <div class="mt-6 md:mt-8 flex justify-end">
                                <Sparkles class="w-10 h-10 md:w-12 md:h-12 text-black animate-pulse" />
                            </div>
                        </div>
                        <div class="border-4 border-black bg-white p-6 shadow-brutalist dark:border-white dark:bg-zinc-950 dark:shadow-brutalist-white">
                            <p class="text-xs font-black uppercase tracking-[0.28em] text-zinc-500 dark:text-zinc-400">{{ t('manifesto.focus_label') }}</p>
                            <p class="mt-3 text-2xl font-black uppercase italic leading-tight text-black dark:text-white">
                                {{ t('manifesto.focus_text') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-12 grid gap-6 lg:grid-cols-[1.2fr,0.8fr]">
                    <div class="border-4 border-black bg-black p-8 text-white shadow-brutalist-lg dark:border-white dark:bg-white dark:text-black dark:shadow-brutalist-white-lg md:p-10">
                        <p class="text-xs font-black uppercase tracking-[0.32em] opacity-70">{{ t('manifesto.bridge_label') }}</p>
                        <p class="mt-5 max-w-4xl text-2xl font-black uppercase leading-tight md:text-4xl">
                            {{ t('manifesto.bridge_text') }}
                        </p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                        <div v-for="item in [
                            { value: 'DISCOVERY', label: t('manifesto.pillars.a') },
                            { value: 'DELIVERY', label: t('manifesto.pillars.b') },
                            { value: 'EVOLUTION', label: t('manifesto.pillars.c') }
                        ]" :key="item.value" class="border-4 border-black bg-white p-5 text-black shadow-brutalist dark:border-white dark:bg-zinc-950 dark:text-white dark:shadow-brutalist-white">
                            <p class="text-xs font-black uppercase tracking-[0.28em] opacity-50">{{ item.value }}</p>
                            <p class="mt-3 text-lg font-black uppercase leading-tight">{{ item.label }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    <div v-for="card in manifestoCards" :key="card.key" class="group relative">
                        <div class="absolute inset-0 translate-x-3 translate-y-3 border-4 border-black bg-white transition-transform duration-300 group-hover:translate-x-5 group-hover:translate-y-5 dark:border-white dark:bg-zinc-950"></div>
                        <div class="relative h-full border-4 border-black bg-white p-8 transition-transform duration-300 group-hover:-translate-x-1 group-hover:-translate-y-1 dark:border-white dark:bg-zinc-900">
                            <div class="flex items-start justify-between gap-4">
                                <div :class="['flex h-16 w-16 items-center justify-center border-4 border-black transition-transform group-hover:rotate-6 group-hover:scale-105 dark:border-white', card.accent]">
                                    <component :is="card.icon" class="h-8 w-8" />
                                </div>
                                <div class="border-2 border-black px-3 py-1 text-sm font-black uppercase tracking-[0.24em] text-black dark:border-white dark:text-white">
                                    {{ card.stat }}
                                </div>
                            </div>
                            <h4 class="mt-8 text-3xl font-black uppercase leading-none text-black dark:text-white">
                                {{ t(`manifesto.points.${card.key}.name`) }}
                            </h4>
                            <p class="mt-4 text-lg font-black uppercase leading-tight text-black/80 dark:text-zinc-300">
                                {{ t(`manifesto.points.${card.key}.desc`) }}
                            </p>
                            <p class="mt-6 border-t-4 border-black/10 pt-6 text-sm font-black uppercase leading-relaxed text-black/55 dark:border-white/10 dark:text-zinc-500">
                                {{ t(`manifesto.points.${card.key}.support`) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Dynamic Trust Section (Logos Marquee) -->
        <section class="py-12 md:py-20 bg-brutalist-pink border-y-8 border-black">
            <div class="max-w-[1400px] mx-auto px-6 mb-8 md:mb-12 flex justify-between items-end">
                <h3 class="text-2xl md:text-4xl font-black uppercase italic text-white leading-none">{{ t('trust.title') }}</h3>
                <div class="hidden md:block w-48 h-1 bg-white"></div>
            </div>
            <div class="overflow-hidden flex space-x-12">
                <div class="flex animate-marquee space-x-12 whitespace-nowrap py-4">
                    <div v-for="n in 10" :key="n" class="flex items-center space-x-12">
                        <span class="text-4xl md:text-6xl font-display font-black text-black opacity-20 uppercase leading-none">{{ t('trust.words.w1') }}</span>
                        <div class="w-4 h-4 bg-white rotate-45"></div>
                        <span class="text-4xl md:text-6xl font-display font-black text-white uppercase italic leading-none">{{ t('trust.words.w2') }}</span>
                        <div class="w-3 h-3 md:w-4 md:h-4 bg-black rotate-45"></div>
                        <span class="text-4xl md:text-6xl font-display font-black text-black opacity-20 uppercase underline decoration-white leading-none">{{ t('trust.words.w3') }}</span>
                        <div class="w-4 h-4 bg-white rotate-45"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Preview -->
        <section class="border-y-8 border-black bg-brutalist-blue py-32">
            <div class="max-w-[1400px] mx-auto px-6">
                <div class="mb-20 grid gap-10 lg:grid-cols-[1.2fr_0.8fr] lg:items-end">
                    <div>
                        <div class="mb-6 inline-flex border-4 border-black bg-white px-4 py-3 text-xs font-black uppercase tracking-[0.28em] text-black shadow-brutalist">
                            {{ t('services_preview.kicker') }}
                        </div>
                        <h2 class="text-6xl font-display font-black uppercase italic tracking-tighter leading-[0.82] md:text-8xl xl:text-9xl">
                            {{ t('services_preview.title1') }} <br/> <span class="text-white">{{ t('services_preview.title2') }}</span>
                        </h2>
                    </div>
                    <div class="border-4 border-black bg-white p-6 text-left text-black shadow-brutalist">
                        <p class="text-lg font-black uppercase leading-tight md:text-2xl">
                            {{ t('services_preview.intro') }}
                        </p>
                        <div class="mt-6 flex flex-wrap gap-3 text-[11px] font-black uppercase tracking-[0.24em]">
                            <span class="border-2 border-black bg-brutalist-yellow px-3 py-2">{{ t('services_preview.tag1') }}</span>
                            <span class="border-2 border-black bg-brutalist-pink px-3 py-2 text-white">{{ t('services_preview.tag2') }}</span>
                            <span class="border-2 border-black bg-black px-3 py-2 text-white">{{ t('services_preview.tag3') }}</span>
                        </div>
                    </div>
                </div>

                <div class="grid gap-8 md:grid-cols-3 md:gap-12">
                    <div v-for="(service, i) in servicesPreviewCards" :key="service.titleKey" class="group relative"
                    :class="['transition-all duration-500 delay-[' + (i * 100) + 'ms]', isVisible ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0']">
                        <div :class="['absolute inset-0 translate-x-3 translate-y-3 border-4 border-black transition-transform duration-300 group-hover:translate-x-5 group-hover:translate-y-5', service.accent]"></div>
                        <div :class="['relative flex h-full flex-col border-4 border-black p-10 transition-transform duration-300 group-hover:-translate-x-1 group-hover:-translate-y-1', service.shell]">
                            <div class="mb-8 flex items-start justify-between gap-6">
                                <div class="space-y-3">
                                    <span class="inline-flex border-2 border-current px-3 py-1 text-[11px] font-black uppercase tracking-[0.28em]">
                                        {{ t(service.eyebrowKey) }}
                                    </span>
                                    <div class="h-2 w-20 bg-current opacity-25"></div>
                                </div>
                                <div class="text-right">
                                    <div :class="['mb-3 flex h-16 w-16 items-center justify-center border-4 border-black transition-transform group-hover:scale-110 group-hover:rotate-6', service.iconWrap]">
                                        <component :is="service.icon" class="w-8 h-8" />
                                    </div>
                                    <div class="text-4xl font-display font-black italic leading-none opacity-35">
                                        {{ service.metric }}
                                    </div>
                                </div>
                            </div>

                            <div class="mb-8 flex-1">
                                <h3 class="mb-4 text-3xl font-black uppercase leading-none">
                                    {{ t(service.titleKey) }}
                                </h3>
                                <p class="mb-6 text-lg font-black uppercase tracking-tight opacity-80">
                                    {{ t(service.descKey) }}
                                </p>
                                <p class="max-w-sm text-sm font-black uppercase leading-relaxed opacity-65">
                                    {{ t(service.detailKey) }}
                                </p>
                                <div class="mt-8 border-l-4 border-current pl-4">
                                    <p class="text-[11px] font-black uppercase tracking-[0.28em] opacity-55">
                                        {{ t('services_preview.outcome') }}
                                    </p>
                                    <p class="mt-2 text-base font-black uppercase leading-tight">
                                        {{ t(service.outcomeKey) }}
                                    </p>
                                </div>
                                <div class="mt-8 space-y-3">
                                    <div
                                        v-for="bullet in service.bullets"
                                        :key="bullet"
                                        class="flex items-start gap-3 border-2 border-current/15 px-3 py-3"
                                    >
                                        <span class="mt-1 h-3 w-3 shrink-0 bg-current"></span>
                                        <span class="text-xs font-black uppercase leading-relaxed">
                                            {{ t(bullet) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t-4 border-current/20 pt-6">
                                <Link :href="route('servicios')" :class="['flex items-center justify-between text-lg font-black uppercase tracking-widest transition-colors group/btn', service.linkTone]">
                                    <span>{{ t('services_preview.details') }}</span>
                                    <ArrowRight class="w-6 h-6 transition-transform group-hover/btn:translate-x-2" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 md:py-48 px-6">
            <div class="max-w-5xl mx-auto bg-black border-4 md:border-8 border-brutalist-yellow p-8 md:p-16 text-center shadow-brutalist rotate-1">
                <h2 class="text-4xl sm:text-5xl md:text-8xl font-display font-black text-white uppercase italic leading-[0.8] mb-12">
                    {{ t('cta.title1') }} <br/> <span class="text-brutalist-yellow">{{ t('cta.title2') }}</span>
                </h2>
                <p class="mx-auto mb-10 max-w-3xl text-lg font-black uppercase leading-relaxed text-white/70 md:text-2xl">
                    {{ t('cta.subtitle') }}
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-8">
                    <Button as-child class="group relative overflow-hidden rounded-none border-4 border-black bg-brutalist-pink px-8 py-6 text-2xl font-black text-white shadow-brutalist transition-all hover:translate-x-[4px] hover:translate-y-[4px] hover:bg-brutalist-yellow hover:text-black hover:shadow-brutalist-hover md:px-12 md:py-8 md:text-3xl">
                        <Link :href="route('contacto')">
                            <span class="relative z-10 flex items-center gap-4">
                                <span>{{ t('cta.button') }}</span>
                                <ArrowRight class="h-7 w-7 transition-transform group-hover:translate-x-2 group-hover:-translate-y-1" />
                            </span>
                        </Link>
                    </Button>
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

.animate-marquee {
    animation: marquee 20s linear infinite;
}

.stroke-text {
    -webkit-text-stroke: 2px currentColor;
    color: transparent;
}

.font-display {
    font-family: 'Space Grotesk', sans-serif;
}

body {
    @apply bg-white dark:bg-black transition-colors duration-500;
}

::selection {
    @apply bg-brutalist-yellow text-black;
}

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
