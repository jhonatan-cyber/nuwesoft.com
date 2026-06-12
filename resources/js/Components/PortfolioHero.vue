<script setup>
import { useI18n } from 'vue-i18n'
import { Badge } from '@/Components/ui/badge'
import { useInView } from '@/composables/useInView'
import { Globe, Code, Smartphone, Cloud, Zap } from 'lucide-vue-next'

const { t } = useI18n()

const props = defineProps({
    categories: { type: Array, default: () => [] },
    activeCategory: { type: String, default: 'all' },
    categoryHighlights: { type: Array, default: () => [] },
})

const emit = defineEmits(['update:activeCategory'])

const { el: heroRef, isVisible: heroVisible } = useInView(0.05)

const heroDelay = (index, step = 120) => `${index * step}ms`

const metrics = [
    { value: 'B2B', label: 'portafolio.metrics.m1' },
    { value: 'UI + OPS', label: 'portafolio.metrics.m2' },
    { value: 'REAL CASES', label: 'portafolio.metrics.m3' },
]
</script>

<template>
    <section ref="heroRef" class="px-6 mb-24 relative overflow-hidden">
        <!-- Floating decorative blobs -->
        <div class="absolute pointer-events-none inset-0 overflow-hidden" aria-hidden="true">
            <div class="absolute -left-16 top-32 w-72 h-72 rounded-full bg-brutalist-yellow/10 blur-3xl float-slower"></div>
            <div class="absolute -right-16 top-64 w-96 h-96 rounded-full bg-brutalist-pink/10 blur-3xl float-slow"></div>
            <div class="absolute left-1/3 bottom-0 w-56 h-56 rounded-full bg-brutalist-blue/10 blur-3xl float-slow-reverse"></div>
            <div class="absolute left-[35%] top-10 w-16 h-16 rotate-45 bg-brutalist-purple/30 blur-sm float-slow"></div>
            <div class="absolute right-[5%] top-[40%] w-28 h-28 rounded-full bg-brutalist-lime/8 blur-2xl float-slow-reverse"></div>
            <div class="absolute left-[8%] top-48 w-20 h-20 border-4 border-black/10 rotate-12 dark:border-white/10 float-slow"></div>
            <div class="absolute right-[12%] top-24 w-12 h-12 bg-brutalist-yellow/30 rotate-45 float-slow-reverse"></div>
        </div>

        <div class="max-w-[1400px] mx-auto relative z-10">
            <!-- Header row: badge + title + lead panel -->
            <div class="mb-16 grid gap-12 xl:grid-cols-[1.05fr_0.95fr] xl:items-end">
                <div>
                    <div
                        :class="['transition-all duration-700 transform', heroVisible ? 'translate-x-0 opacity-100' : '-translate-x-12 opacity-0']"
                    >
                        <!-- Decorative diamond + badge row -->
                        <div class="mb-6 flex items-center gap-4">
                            <span class="inline-flex h-5 w-5 rotate-45 border-2 border-black bg-brutalist-blue dark:border-white"></span>
                            <Badge
                                class="bg-brutalist-pink text-white font-black border-4 border-black dark:border-white px-4 py-2 text-xl rotate-1 inline-block uppercase shadow-brutalist dark:shadow-brutalist-white"
                            >
                                {{ t('portafolio.badge') }}
                            </Badge>
                            <span class="h-px flex-1 bg-black/20 dark:bg-white/20"></span>
                        </div>

                        <h1
                            class="text-[clamp(2.5rem,8vw,6rem)] font-display font-black leading-[0.9] tracking-tighter mb-8 uppercase italic text-black dark:text-white"
                        >
                            {{ t('portafolio.title1') }} <br/>
                            <span class="bg-brutalist-blue text-black px-4 ml-[-0.5rem] inline-block skew-x-[-2deg]">
                                {{ t('portafolio.title2') }}
                            </span>
                            <br/>
                            <span class="relative inline-block mt-2 text-white">
                                <span class="absolute inset-0 bg-black dark:bg-white -rotate-1 scale-x-[1.02] z-0"></span>
                                <span class="relative z-10 px-4 dark:text-black">{{ t('portafolio.title3') }}</span>
                            </span>
                        </h1>

                        <p
                            class="max-w-3xl border-l-8 border-black pl-6 text-xl font-black leading-tight italic uppercase opacity-90 dark:border-white md:text-2xl"
                        >
                            {{ t('portafolio.subtitle') }}
                        </p>
                    </div>

                    <!-- Hero Metric Cards -->
                    <div class="mt-12 grid gap-4 sm:grid-cols-3">
                        <div
                            v-for="(metric, idx) in metrics"
                            :key="metric.label"
                            :style="{ transitionDelay: heroDelay(idx, 150) }"
                            :class="[
                                'transition-all duration-700 transform',
                                heroVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'
                            ]"
                            class="group relative border-4 border-black bg-brutalist-yellow p-5 text-black shadow-brutalist dark:border-white dark:shadow-brutalist-white hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all cursor-default overflow-hidden"
                        >
                            <!-- Hover accent bar -->
                            <div class="absolute top-0 left-0 h-1 w-0 bg-black transition-all duration-300 group-hover:w-full"></div>
                            <div class="text-2xl font-display font-black italic leading-none md:text-3xl">{{ metric.value }}</div>
                            <div class="mt-3 text-[11px] font-black uppercase tracking-[0.24em] text-black/75">{{ t(metric.label) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Lead Panel -->
                <div
                    :class="['transition-all duration-700 delay-200 transform', heroVisible ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0']"
                >
                    <div class="relative">
                        <div class="absolute -left-4 top-10 hidden h-[85%] w-full border-4 border-black bg-brutalist-yellow lg:block dark:border-white"></div>
                        <div class="relative border-4 border-black bg-white p-8 shadow-brutalist dark:border-white dark:bg-zinc-950 dark:shadow-brutalist-white">
                            <!-- Diamond + label -->
                            <div class="flex items-center gap-3 mb-4">
                                <span class="inline-flex h-3 w-3 rotate-45 border-2 border-brutalist-pink bg-brutalist-pink/30"></span>
                                <p class="text-sm font-black uppercase tracking-[0.28em] text-brutalist-blue">{{ t('portafolio.lead_label') }}</p>
                            </div>
                            <p class="mt-4 text-xl font-black uppercase leading-tight md:text-2xl">
                                {{ t('portafolio.lead_text') }}
                            </p>
                            <div class="mt-8 flex items-center gap-3 border-t-4 border-black/10 pt-4 dark:border-white/10">
                                <span class="text-[10px] font-black uppercase tracking-[0.24em] opacity-50">→ {{ t('cta.subtitle')?.substring(0, 30) || 'REAL ENGINEERING' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Highlights -->
            <div class="mb-16 grid gap-4 md:grid-cols-3">
                <div
                    v-for="(highlight, idx) in categoryHighlights"
                    :key="highlight.key"
                    :style="{ transitionDelay: heroDelay(idx, 130) }"
                    :class="[
                        'transition-all duration-700 transform',
                        heroVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'
                    ]"
                    class="relative border-4 border-black bg-white p-6 shadow-brutalist dark:border-white dark:bg-zinc-950 dark:shadow-brutalist-white hover:-translate-y-1 hover:shadow-brutalist-hover dark:hover:shadow-brutalist-white transition-all group overflow-hidden"
                >
                    <!-- Top accent bar -->
                    <div :class="['absolute top-0 left-0 h-1.5 w-0 transition-all duration-500 group-hover:w-full', highlight.accent]"></div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-[11px] font-black uppercase tracking-[0.28em] opacity-55">{{ t(`portafolio.${highlight.key}`) }}</div>
                        <span class="text-lg font-black italic opacity-20 group-hover:opacity-60 transition-opacity">{{ highlight.metric }}</span>
                    </div>
                    <p class="text-lg font-black uppercase leading-tight">{{ t(`portafolio.highlights.${highlight.key}`) }}</p>
                </div>
            </div>

            <!-- Filter Categories -->
            <div class="mb-16 flex flex-wrap gap-3 sm:gap-4">
                <button
                    v-for="cat in categories"
                    :key="cat.key"
                    @click="emit('update:activeCategory', cat.key)"
                    class="relative flex items-center space-x-2 sm:space-x-3 px-4 sm:px-6 py-2.5 sm:py-3 border-4 border-black dark:border-white font-black uppercase italic text-sm sm:text-base transition-all duration-300 overflow-hidden group focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2"
                    :class="[activeCategory === cat.key ? 'text-black' : 'bg-white dark:bg-black text-black dark:text-white hover:bg-gray-100 dark:hover:bg-zinc-900']"
                >
                    <!-- Active fill background -->
                    <span
                        class="absolute inset-0 bg-brutalist-yellow transition-transform duration-300 ease-out"
                        :class="activeCategory === cat.key ? 'translate-y-0' : 'translate-y-full'"
                    ></span>
                    <!-- Content -->
                    <span class="relative z-10 flex items-center space-x-2 sm:space-x-3">
                        <component :is="cat.icon" class="w-4 h-4 sm:w-5 sm:h-5" :class="activeCategory === cat.key ? 'animate-bounce-small' : ''" />
                        <span class="break-words">{{ t(`portafolio.${cat.key}`) }}</span>
                    </span>
                </button>
            </div>
        </div>
    </section>
</template>
