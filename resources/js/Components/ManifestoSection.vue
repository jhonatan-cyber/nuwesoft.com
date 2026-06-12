<script setup>
import { useI18n } from 'vue-i18n'
import { Badge } from '@/Components/ui/badge'
import { Zap, Shield, TrendingUp, Sparkles } from 'lucide-vue-next'
import { useInView } from '@/composables/useInView'

const { t } = useI18n()
const { el, isVisible } = useInView(0.08)

const sectionDelay = (index, step = 170) => ({ transitionDelay: `${index * step}ms` })

const manifestoCards = [
    { key: 'p1', icon: Zap, accent: 'bg-brutalist-yellow text-black', barClass: 'bg-brutalist-yellow', stat: '+3X', hoverShadow: 'hover:shadow-[4px_4px_0px_0px_#FF4400]' },
    { key: 'p2', icon: Shield, accent: 'bg-brutalist-pink text-white', barClass: 'bg-brutalist-pink', stat: '-40%', hoverShadow: 'hover:shadow-[4px_4px_0px_0px_#FF2E63]' },
    { key: 'p3', icon: TrendingUp, accent: 'bg-brutalist-purple text-white', barClass: 'bg-brutalist-purple', stat: 'LONG', hoverShadow: 'hover:shadow-[4px_4px_0px_0px_#B026FF] dark:hover:shadow-[4px_4px_0px_0px_#39FF14]' },
]
</script>

<template>
    <section ref="el" class="relative overflow-hidden bg-white py-20 dark:bg-black md:py-32">
        <!-- Decorative Elements -->
        <div class="absolute right-0 top-0 h-64 w-64 -translate-y-1/2 translate-x-1/2 rounded-full bg-brutalist-yellow opacity-10 blur-2xl md:h-96 md:w-96 md:blur-3xl"></div>
        <div class="absolute bottom-0 left-0 h-64 w-64 translate-y-1/2 -translate-x-1/2 rounded-full bg-brutalist-pink opacity-10 blur-2xl md:h-96 md:w-96 md:blur-3xl"></div>
        <div class="absolute left-1/4 top-1/4 h-48 w-48 rounded-full bg-brutalist-blue opacity-5 blur-3xl"></div>
        <div class="float-slow absolute right-[20%] top-[20%] h-32 w-32 rounded-full bg-brutalist-lime opacity-5 blur-3xl"></div>
        <div class="float-slower absolute left-[10%] bottom-[10%] h-28 w-28 bg-brutalist-purple/10 blur-2xl rotate-12"></div>

        <div class="relative z-10 mx-auto max-w-[1400px] px-6">
            <!-- Header: Title + Quote side by side -->
            <div class="mb-16 flex flex-col items-start justify-between gap-12 lg:flex-row md:mb-24">
                <div class="max-w-4xl">
                    <Badge class="mb-6 border-2 border-black bg-black px-4 py-1 font-black uppercase tracking-[0.3em] text-white dark:border-white dark:bg-white dark:text-black">{{ t('manifesto.badge') }}</Badge>
                    <h2 class="mb-8 text-5xl font-display font-black uppercase italic leading-[0.82] tracking-tighter text-black dark:text-white sm:text-7xl md:text-8xl">
                        {{ t('manifesto.title1') }} <br/>
                        <span class="relative inline-block bg-brutalist-yellow px-4 text-black shadow-[6px_6px_0px_rgba(0,0,0,1)] dark:shadow-[6px_6px_0px_rgba(255,255,255,1)]">{{ t('manifesto.title2') }}</span> <br/>
                        <span>{{ t('manifesto.title3') }}</span>
                    </h2>
                    <p class="max-w-3xl text-lg font-black uppercase leading-relaxed text-black/75 dark:text-zinc-300 md:text-2xl">
                        {{ t('manifesto.intro') }}
                    </p>
                </div>
                <div class="w-full space-y-6 lg:max-w-md">
                    <div class="relative -rotate-2 overflow-hidden border-4 border-black bg-brutalist-yellow p-6 shadow-brutalist transition-transform duration-500 hover:rotate-0 dark:border-white dark:shadow-brutalist-white md:-rotate-3 md:border-8 md:p-10">
                        <div class="pointer-events-none absolute -right-6 -top-6 select-none text-[8rem] font-display font-black leading-none text-black/10 md:text-[10rem]">“</div>
                        <p class="relative z-10 text-xl font-black italic uppercase leading-tight text-black md:text-3xl md:leading-none">
                            {{ t('manifesto.quote') }}
                        </p>
                        <div class="relative z-10 mt-6 flex justify-end md:mt-8">
                            <Sparkles class="h-10 w-10 animate-pulse text-black md:h-12 md:w-12" />
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

            <!-- Bridge + Pillars -->
            <div class="mb-12 md:mb-16">
                <div class="relative overflow-hidden border-4 border-black bg-black p-8 text-white shadow-brutalist-lg dark:border-white dark:bg-white dark:text-black dark:shadow-brutalist-white-lg md:p-12">
                    <div class="pointer-events-none absolute -right-4 -top-4 select-none text-[10rem] font-display font-black leading-none text-white/5 dark:text-black/5 md:text-[14rem]">///</div>
                    <p class="relative z-10 text-xs font-black uppercase tracking-[0.32em] opacity-70">{{ t('manifesto.bridge_label') }}</p>
                    <p class="relative z-10 mt-5 max-w-4xl text-2xl font-black uppercase leading-tight md:text-4xl">
                        {{ t('manifesto.bridge_text') }}
                    </p>
                    <div class="relative z-10 mt-8 flex flex-wrap items-center gap-3 border-t-4 border-white/20 pt-6 dark:border-black/20 md:mt-10">
                        <span class="mr-1 text-[10px] font-black uppercase tracking-[0.28em] text-white/50 dark:text-black/50">PILARES:</span>
                        <span v-for="(text, pillarKey) in {
                            a: t('manifesto.pillars.a'),
                            b: t('manifesto.pillars.b'),
                            c: t('manifesto.pillars.c')
                        }" :key="pillarKey"
                            class="group/pillar border-2 border-white/30 px-3 py-1.5 text-xs font-black uppercase tracking-wider text-white/80 transition-all hover:border-brutalist-yellow hover:bg-brutalist-yellow hover:text-black dark:border-black/30 dark:text-black/80 dark:hover:border-brutalist-pink dark:hover:bg-brutalist-pink dark:hover:text-white"
                        >
                            {{ text }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- 3 Principles Cards -->
            <div class="grid gap-6 md:grid-cols-3">
                <div
                    v-for="(card, index) in manifestoCards"
                    :key="card.key"
                    class="group relative"
                    :style="sectionDelay(index, 170)"
                    :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'"
                >
                    <div :class="['relative h-full border-4 border-black bg-white transition-all duration-300 group-hover:-translate-y-2 dark:border-white dark:bg-zinc-900', card.hoverShadow]">
                        <!-- Top accent bar -->
                        <div :class="['h-2 w-full border-b-4 border-black dark:border-white', card.barClass]"></div>

                        <div class="p-8">
                            <!-- Stat + Icon row -->
                            <div class="mb-8 flex items-start justify-between">
                                <div class="select-none text-5xl font-display font-black italic leading-none opacity-20">{{ card.stat }}</div>
                                <div :class="['flex h-14 w-14 items-center justify-center border-4 border-black transition-all duration-300 group-hover:scale-110 group-hover:-rotate-6 dark:border-white', card.accent]">
                                    <component :is="card.icon" class="h-7 w-7" />
                                </div>
                            </div>

                            <!-- Content -->
                            <h4 class="text-2xl md:text-3xl font-black uppercase leading-none tracking-tight text-black dark:text-white break-words">
                                {{ t(`manifesto.points.${card.key}.name`) }}
                            </h4>
                            <p class="mt-4 text-base font-black uppercase leading-relaxed text-black/75 dark:text-zinc-300">
                                {{ t(`manifesto.points.${card.key}.desc`) }}
                            </p>

                            <!-- Separator + support -->
                            <div class="mt-8 border-t-4 border-black/10 pt-6 dark:border-white/10">
                                <p class="flex items-start gap-3 text-sm font-black uppercase leading-relaxed text-black/50 dark:text-zinc-500">
                                    <span class="mt-1 block h-2 w-2 shrink-0 rotate-45 bg-current"></span>
                                    {{ t(`manifesto.points.${card.key}.support`) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
