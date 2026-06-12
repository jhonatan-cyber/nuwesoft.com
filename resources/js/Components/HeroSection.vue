<script setup>
import { onMounted, ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { Link, usePage } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import { Badge } from '@/Components/ui/badge'
import { Rocket, Sparkles, ArrowRight, Zap } from 'lucide-vue-next'
import SystemConsole from '@/Components/SystemConsole.vue'
import { usePostHog } from '@/composables/usePostHog'

const { t } = useI18n()
const page = usePage()
const { capture, getFeatureFlag } = usePostHog()
const settings = computed(() => page.props.settings || {})
const siteName = computed(() => settings.value.site_name || 'NUWESOFT')
const siteTagline = computed(() => settings.value.tagline || '')
const isVisible = ref(false)

// ── A/B Testing variants ──
const heroVariant = computed(() => {
    return getFeatureFlag('hero_variant') || 'control';
});

const trackHeroCTA = (ctaLabel = 'start_project') => {
    capture('hero_cta_click', {
        variant: heroVariant.value,
        cta: ctaLabel,
    })
}

const heroStats = [
    { value: '48H', labelKey: 'hero.stats.m1', dotClass: 'bg-brutalist-yellow', glow: 'rgba(255,68,0,0.6)' },
    { value: '99.9%', labelKey: 'hero.stats.m2', dotClass: 'bg-brutalist-pink', glow: 'rgba(255,46,99,0.6)' },
    { value: '24/7', labelKey: 'hero.stats.m3', dotClass: 'bg-brutalist-blue', glow: 'rgba(0,240,255,0.6)' },
]

const sectionDelay = (index, step = 120) => ({ transitionDelay: `${index * step}ms` })

onMounted(() => {
    // Track which variant is shown
    capture('hero_variant_impression', {
        variant: heroVariant.value,
    })
    isVisible.value = true
})
</script>

<template>
    <header class="relative overflow-hidden pb-24 pt-40 md:pb-32 md:pt-48">
        <!-- Decorative floating elements -->
        <div class="pointer-events-none absolute inset-0">
            <div class="float-slow absolute left-[4%] top-24 h-32 w-32 rotate-12 border-4 border-black/15 bg-brutalist-yellow/50 blur-[2px] dark:border-white/10"></div>
            <div class="float-slower absolute right-[8%] top-36 h-72 w-72 rounded-full bg-brutalist-blue/15 blur-3xl"></div>
            <div class="float-slow-reverse absolute bottom-0 left-1/3 h-56 w-56 rounded-full bg-brutalist-pink/15 blur-3xl"></div>
            <div class="float-slow absolute left-[20%] top-48 h-20 w-20 rotate-45 border-2 border-black/10 bg-brutalist-purple/60 blur-[1px] dark:border-white/10"></div>
            <div class="float-slower absolute right-[15%] top-[55%] h-36 w-36 rounded-full bg-brutalist-lime/10 blur-3xl"></div>
        </div>

        <div class="relative z-10 mx-auto max-w-[1400px] px-6">
            <div class="grid items-start gap-12 lg:grid-cols-12 xl:gap-16">
                <!-- Left Column: Copy -->
                <div class="lg:col-span-7">
                    <div
                        :class="[
                            'transform transition-all duration-700',
                            isVisible ? 'translate-x-0 opacity-100' : '-translate-x-12 opacity-0'
                        ]"
                    >
                        <!-- Badge -->
                        <Badge class="mb-6 rounded-none border-2 border-black bg-brutalist-pink px-4 py-1 font-black uppercase tracking-[0.2em] text-white shadow-[6px_6px_0px_rgba(0,0,0,1)] dark:border-white dark:shadow-[6px_6px_0px_rgba(255,255,255,1)]">
                            {{ siteName }} · {{ t('hero.badge') }}
                        </Badge>
                        <p v-if="siteTagline" class="-mt-4 mb-6 text-[10px] font-black uppercase tracking-[0.28em] text-neutral-500">
                            {{ siteTagline }}
                        </p>

                        <!-- Title -->
                        <h1 class="relative mb-6 max-w-5xl text-4xl font-display font-black uppercase italic leading-[0.9] tracking-[-0.04em] text-black dark:text-white sm:text-5xl md:text-6xl xl:text-7xl">
                            <span class="block">{{ t('hero.title1') }}</span>
                            <span class="relative inline-block bg-brutalist-yellow px-3 text-black shadow-[4px_4px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_rgba(255,255,255,1)] md:px-5">
                                {{ t('hero.title2') }}
                            </span>
                            <span class="mt-1 block">{{ t('hero.title3') }}</span>
                        </h1>

                        <!-- Subtitle -->
                        <p class="max-w-4xl text-lg font-black uppercase leading-tight text-black/80 dark:text-zinc-300 md:text-2xl xl:text-3xl">
                            {{ t('hero.subtitle') }}
                        </p>

                        <!-- CTA - Variant A (Control) -->
                        <div v-if="heroVariant === 'control'" class="mt-10 flex flex-wrap gap-6">
                            <Button as-child class="group h-auto rounded-none border-4 border-black bg-black px-8 py-5 text-xl font-black text-white shadow-brutalist transition-all hover:translate-x-[4px] hover:translate-y-[4px] hover:bg-brutalist-yellow hover:text-black hover:shadow-brutalist-hover dark:border-white dark:bg-brutalist-yellow dark:text-black dark:shadow-brutalist-white dark:hover:bg-brutalist-lime dark:hover:text-black md:px-10 md:py-6 md:text-2xl">
                                <Link :href="route('contacto')" @click="trackHeroCTA('start_project')">
                                    {{ t('hero.cta_start') }}
                                    <Rocket class="ml-4 h-7 w-7 transition-transform group-hover:-translate-y-2 group-hover:translate-x-2 md:h-8 md:w-8" />
                                </Link>
                            </Button>
                        </div>

                        <!-- CTA - Variant B (Test: Urgency + secondary link) -->
                        <div v-if="heroVariant === 'test'" class="mt-10 flex flex-wrap items-center gap-4">
                            <Button as-child class="group h-auto rounded-none border-4 border-brutalist-pink bg-brutalist-pink px-8 py-5 text-xl font-black text-white shadow-brutalist transition-all hover:translate-x-[4px] hover:translate-y-[4px] hover:bg-black hover:text-white hover:shadow-brutalist-hover dark:border-white dark:bg-brutalist-yellow dark:text-black dark:shadow-brutalist-white dark:hover:bg-white dark:hover:text-black md:px-10 md:py-6 md:text-2xl">
                                <Link :href="route('contacto')" @click="trackHeroCTA('start_project')">
                                    <Sparkles class="mr-3 h-6 w-6 transition-transform group-hover:rotate-12 md:h-7 md:w-7" />
                                    <span>{{ t('hero.cta_start') }}</span>
                                    <ArrowRight class="ml-4 h-7 w-7 transition-transform group-hover:translate-x-2 md:h-8 md:w-8" />
                                </Link>
                            </Button>
                            <Link :href="route('portafolio')" @click="trackHeroCTA('view_portfolio')" class="group flex items-center gap-2 px-6 py-4 text-base font-black uppercase tracking-widest text-black/60 transition-all hover:text-black dark:text-white/60 dark:hover:text-white">
                                <Zap class="h-4 w-4 transition-transform group-hover:scale-125" />
                                <span>{{ t('portfolio') }}</span>
                                <ArrowRight class="h-4 w-4 transition-transform group-hover:translate-x-1" />
                            </Link>
                        </div>

                        <!-- Stats -->
                        <div class="mt-12 flex max-w-5xl flex-wrap gap-x-10 gap-y-4">
                            <div
                                v-for="(stat, index) in heroStats"
                                :key="stat.labelKey"
                                class="flex items-center gap-3"
                                :style="sectionDelay(index, 120)"
                                :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'"
                            >
                                <span :class="['flex h-3 w-3 rounded-full', stat.dotClass]" :style="{ boxShadow: `0 0 8px ${stat.glow}` }"></span>
                                <span class="text-2xl font-black italic leading-none text-black dark:text-white">{{ stat.value }}</span>
                                <span class="text-[10px] font-black uppercase tracking-[0.22em] text-zinc-500 dark:text-zinc-500">{{ t(stat.labelKey) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: System Console -->
                <div class="lg:col-span-5">
                    <SystemConsole />
                </div>
            </div>
        </div>
    </header>
</template>
