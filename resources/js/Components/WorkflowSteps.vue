<script setup>
import { useI18n } from 'vue-i18n'
import { Badge } from '@/Components/ui/badge'
import { useInView } from '@/composables/useInView'

const { t } = useI18n()
const { el, isVisible } = useInView(0.05)

const sectionDelay = (index, step = 120) => ({ transitionDelay: `${index * step}ms` })

const processSteps = [
    {
        name: t('servicios.steps.discovery.name'),
        desc: t('servicios.steps.discovery.desc'),
        icon: 'discovery',
        badgeColor: 'bg-brutalist-yellow',
        headerColor: 'border-brutalist-yellow/30',
        hoverColor: '#FF4400',
    },
    {
        name: t('servicios.steps.architect.name'),
        desc: t('servicios.steps.architect.desc'),
        icon: 'architect',
        badgeColor: 'bg-brutalist-pink',
        headerColor: 'border-brutalist-pink/30',
        hoverColor: '#FF2E63',
    },
    {
        name: t('servicios.steps.develop.name'),
        desc: t('servicios.steps.develop.desc'),
        icon: 'develop',
        badgeColor: 'bg-brutalist-blue',
        headerColor: 'border-brutalist-blue/30',
        hoverColor: '#00F0FF',
    },
    {
        name: t('servicios.steps.deploy.name'),
        desc: t('servicios.steps.deploy.desc'),
        icon: 'deploy',
        badgeColor: 'bg-brutalist-purple',
        headerColor: 'border-brutalist-purple/30',
        hoverColor: '#B026FF',
    },
]
</script>

<template>
    <section ref="el" class="relative mt-40 mb-20 overflow-hidden">
        <!-- Decorative blobs -->
        <div class="pointer-events-none absolute -left-32 top-1/3 h-72 w-72 rounded-full bg-brutalist-yellow/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-32 bottom-1/4 h-80 w-80 rounded-full bg-brutalist-pink/10 blur-3xl"></div>

        <div class="relative z-10">
            <!-- Header -->
            <div
                :class="[
                    'flex flex-col md:flex-row items-start justify-between mb-24 gap-12 transition-all duration-700',
                    isVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0',
                ]"
            >
                <div class="max-w-3xl">
                    <div class="mb-6 flex items-center gap-4">
                        <span class="inline-flex h-5 w-5 rotate-45 border-2 border-black bg-brutalist-blue dark:border-white"></span>
                        <Badge class="-rotate-1 border-4 border-black bg-brutalist-yellow px-4 py-2 text-xl font-black uppercase text-black">
                            {{ t('servicios.workflow_badge') }}
                        </Badge>
                    </div>
                    <h2 class="text-[clamp(3rem,8vw,6rem)] font-display font-black uppercase italic leading-[0.8] tracking-tighter">
                        {{ t('servicios.workflow_title1') }} <br/>
                        <span class="text-brutalist-blue">{{ t('servicios.workflow_title2') }}</span>
                    </h2>
                </div>
                <div class="max-w-sm border-l-8 border-brutalist-pink pl-6">
                    <p class="font-black text-2xl italic uppercase leading-none">
                        {{ t('servicios.workflow_quote') }}
                    </p>
                </div>
            </div>

            <!-- Desktop stepper line -->
            <div
                v-if="isVisible"
                class="relative mb-16 hidden md:block"
            >
                <div class="absolute left-0 right-0 top-1/2 h-1 -translate-y-1/2 bg-black/20 dark:bg-white/20"></div>
                <div class="relative flex justify-between">
                    <div
                        v-for="(step, idx) in processSteps"
                        :key="'stepper-' + idx"
                        :style="{ transitionDelay: `${idx * 150}ms` }"
                        class="flex flex-col items-center transition-all duration-500"
                        :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'"
                    >
                        <span
                            :class="[
                                'relative z-10 flex h-10 w-10 items-center justify-center border-4 border-black text-sm font-black text-black shadow-brutalist',
                                step.badgeColor,
                            ]"
                        >
                            {{ idx + 1 }}
                        </span>
                        <span class="mt-3 text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500 dark:text-zinc-400">{{ step.name }}</span>
                    </div>
                </div>
            </div>

            <!-- Steps cards -->
            <div class="grid grid-cols-1 gap-8 md:grid-cols-4 md:gap-6">
                <div
                    v-for="(step, idx) in processSteps"
                    :key="idx"
                    :style="{ transitionDelay: `${idx * 120}ms` }"
                    :class="[
                        'group relative flex flex-col overflow-hidden border-4 border-black bg-white shadow-brutalist transition-all duration-700 dark:border-white dark:bg-black dark:shadow-brutalist-white',
                        isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0',
                    ]"
                >
                    <!-- Monumental step number -->
                    <span class="absolute -bottom-6 -right-4 text-[8rem] font-display font-black italic leading-none select-none text-black/5 dark:text-white/5 md:text-[10rem]">
                        {{ idx + 1 }}
                    </span>

                    <!-- Phase label -->
                    <div
                        :class="[
                            'flex items-center gap-3 border-b-4 border-black bg-zinc-100 px-6 py-3 dark:border-white dark:bg-zinc-900',
                            step.headerColor,
                        ]"
                    >
                        <span
                            :class="[
                                'flex h-8 w-8 items-center justify-center border-2 border-black text-xs font-black text-black',
                                step.badgeColor,
                            ]"
                        >
                            {{ idx + 1 }}
                        </span>
                        <span class="text-[10px] font-black uppercase tracking-[0.28em] text-zinc-600 dark:text-zinc-400">PHASE 0{{ idx + 1 }}</span>
                    </div>

                    <!-- Card body -->
                    <div class="flex flex-1 flex-col p-6">
                        <!-- Hover accent bar -->
                        <div
                            class="mb-4 h-1.5 w-12 bg-black transition-all duration-300 group-hover:w-full dark:bg-white"
                            :style="{ backgroundColor: step.hoverColor }"
                        ></div>

                        <!-- Icon box -->
                        <div class="mb-6 flex h-16 w-16 items-center justify-center border-4 border-black bg-black text-white transition-all duration-300 group-hover:bg-brutalist-yellow group-hover:text-black dark:border-white">
                            <svg v-if="step.icon==='discovery'" class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            <svg v-if="step.icon==='architect'" class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            <svg v-if="step.icon==='develop'" class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            <svg v-if="step.icon==='deploy'" class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </div>

                        <!-- Title -->
                        <h3 class="mb-4 text-3xl font-black uppercase italic leading-none break-words">{{ step.name }}</h3>

                        <!-- Description -->
                        <p class="text-base font-black uppercase italic leading-tight text-black/70 dark:text-white/70 break-words">
                            {{ step.desc }}
                        </p>
                    </div>

                    <!-- Bottom connector / arrow -->
                    <div v-if="idx < processSteps.length - 1" class="hidden md:block">
                        <div class="absolute -right-4 top-1/2 z-20 flex h-8 w-8 -translate-y-1/2 translate-x-0 items-center justify-center border-4 border-black bg-brutalist-pink text-black opacity-0 transition-all duration-300 group-hover:translate-x-1 group-hover:opacity-100">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
