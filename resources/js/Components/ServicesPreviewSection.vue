<script setup>
import { useI18n } from 'vue-i18n'
import { Link } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import { Code, Layers, Globe, ArrowRight } from 'lucide-vue-next'
import { useInView } from '@/composables/useInView'
import { usePostHog } from '@/composables/usePostHog'

const { t } = useI18n()
const { el, isVisible } = useInView(0.1)
const { capture } = usePostHog()

const sectionDelay = (index, step = 160) => ({ transitionDelay: `${index * step}ms` })

const trackServiceClick = (titleKey) => {
    capture('service_card_click', {
        service: titleKey,
        section: 'services_preview',
    })
}

const servicesCards = [
    {
        titleKey: 'services_preview.card1.title',
        descKey: 'services_preview.card1.desc',
        eyebrowKey: 'services_preview.card1.eyebrow',
        detailKey: 'services_preview.card1.detail',
        outcomeKey: 'services_preview.card1.outcome',
        bullets: ['services_preview.card1.b1', 'services_preview.card1.b2', 'services_preview.card1.b3'],
        metric: '01',
        icon: Code,
        shell: 'bg-white text-black dark:bg-zinc-800 dark:text-white',
        accent: 'bg-brutalist-yellow',
        iconWrap: 'bg-brutalist-yellow text-black dark:border-white dark:bg-zinc-800',
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
        shell: 'bg-black text-white dark:bg-zinc-800 dark:text-white',
        accent: 'bg-brutalist-pink',
        iconWrap: 'bg-brutalist-pink text-white dark:bg-zinc-800 dark:border-white',
        linkTone: 'hover:text-white/70',
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
        shell: 'bg-brutalist-purple/10 text-black dark:bg-zinc-800 dark:text-white',
        accent: 'bg-brutalist-purple',
        iconWrap: 'bg-brutalist-purple text-white dark:bg-zinc-800 dark:border-white',
        linkTone: 'hover:text-brutalist-lime',
    },
]
</script>

<template>
    <section ref="el" class="relative overflow-hidden border-y-8 border-black bg-zinc-100 py-32 dark:bg-zinc-900">
        <!-- Decorative blobs -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
            <div class="absolute -left-16 top-32 h-64 w-64 rounded-full bg-brutalist-yellow/10 blur-3xl"></div>
            <div class="absolute -right-16 bottom-0 h-72 w-72 rounded-full bg-brutalist-pink/10 blur-3xl"></div>
            <div class="absolute left-1/3 top-1/2 h-40 w-40 rounded-full bg-brutalist-purple/10 blur-3xl"></div>
            <div class="absolute right-[10%] top-[20%] h-24 w-24 rotate-12 bg-brutalist-lime/20 blur-sm"></div>
        </div>
        <div class="relative z-10 mx-auto max-w-[1400px] px-6">
            <!-- Header -->
            <div class="mb-16 lg:mb-20">
                <div class="mb-8 inline-flex border-4 border-black bg-white px-4 py-3 text-xs font-black uppercase tracking-[0.28em] text-black shadow-brutalist">
                    {{ t('services_preview.kicker') }}
                </div>
                <div class="grid items-end gap-8 lg:grid-cols-[1.3fr_1fr]">
                    <h2 class="text-5xl font-display font-black uppercase italic leading-[0.85] tracking-tighter md:text-7xl xl:text-8xl">
                        {{ t('services_preview.title1') }} <br/> <span class="text-black dark:text-white">{{ t('services_preview.title2') }}</span>
                    </h2>
                    <div>
                        <p class="text-lg font-black uppercase leading-tight text-black/80 dark:text-white/80 md:text-2xl">
                            {{ t('services_preview.intro') }}
                        </p>
                        <div class="mt-6 flex flex-wrap gap-3">
                            <span class="border-2 border-black bg-brutalist-yellow px-3 py-2 text-xs font-black uppercase tracking-[0.24em] text-black">{{ t('services_preview.tag1') }}</span>
                            <span class="border-2 border-black bg-brutalist-pink px-3 py-2 text-xs font-black uppercase tracking-[0.24em] text-white">{{ t('services_preview.tag2') }}</span>
                            <span class="border-2 border-black bg-black px-3 py-2 text-xs font-black uppercase tracking-[0.24em] text-white">{{ t('services_preview.tag3') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cards -->
            <div class="grid gap-6 md:grid-cols-3">
                <div
                    v-for="(service, i) in servicesCards"
                    :key="service.titleKey"
                    class="group relative"
                    :style="sectionDelay(i, 160)"
                    :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'"
                >
                    <div :class="['relative h-full overflow-hidden border-4 border-black transition-all duration-300 group-hover:-translate-y-1.5 group-hover:shadow-brutalist-hover dark:border-white', service.shell]">
                        <!-- Left accent bar -->
                        <div :class="['absolute left-0 top-0 h-full w-2', service.accent]"></div>

                        <div class="p-8 pl-10">
                            <!-- Metric watermark + Icon -->
                            <div class="mb-6 flex items-start justify-between">
                                <div class="select-none text-6xl font-display font-black italic leading-none opacity-10">{{ service.metric }}</div>
                                <div :class="['flex h-14 w-14 items-center justify-center border-4 border-black transition-all duration-300 group-hover:scale-110 group-hover:-rotate-6 dark:border-white', service.iconWrap]">
                                    <component :is="service.icon" class="h-7 w-7" />
                                </div>
                            </div>

                            <!-- Eyebrow + Title + Desc -->
                            <span class="mb-3 inline-block text-[10px] font-black uppercase tracking-[0.3em] opacity-40">{{ t(service.eyebrowKey) }}</span>
                            <h3 class="mb-3 text-xl md:text-2xl font-black uppercase leading-none break-words">{{ t(service.titleKey) }}</h3>
                            <p class="max-w-xs text-sm font-black uppercase leading-relaxed opacity-70">{{ t(service.descKey) }}</p>

                            <!-- Bullets -->
                            <div class="mt-6 space-y-2.5">
                                <div
                                    v-for="bullet in service.bullets"
                                    :key="bullet"
                                    class="flex items-start gap-3"
                                >
                                    <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rotate-45 bg-current opacity-60"></span>
                                    <span class="text-[11px] font-black uppercase leading-relaxed opacity-60">{{ t(bullet) }}</span>
                                </div>
                            </div>

                            <!-- Link -->
                            <div class="mt-8 border-t-4 border-black/10 pt-5 dark:border-white/10">
                                <Link :href="route('servicios')" :class="['flex items-center justify-between text-sm font-black uppercase tracking-widest transition-colors group/btn', service.linkTone]" @click="trackServiceClick(service.titleKey)">
                                    <span>{{ t('services_preview.details') }}</span>
                                    <ArrowRight class="h-5 w-5 transition-transform group-hover/btn:translate-x-2" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
