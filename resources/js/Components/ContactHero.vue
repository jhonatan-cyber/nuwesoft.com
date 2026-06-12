<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n'
import { useInView } from '@/composables/useInView'

const { t } = useI18n()
const { el: heroRef, isVisible: heroVisible } = useInView(0.1)

const page = usePage();
const settings = computed(() => page.props.settings || {});
const siteName = computed(() => settings.value.site_name || 'NUWESOFT');
const siteTagline = computed(() => settings.value.tagline || '');

const heroDelay = (index, step = 120) => `${index * step}ms`
</script>

<template>
    <section
        ref="heroRef"
        class="relative overflow-hidden border-b-8 border-black bg-brutalist-yellow px-6 pt-20 pb-10 text-black dark:border-white dark:bg-brutalist-blue dark:text-white"
    >
        <!-- Floating blobs -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
            <div class="absolute -left-16 top-32 h-72 w-72 rounded-full bg-black/5 blur-3xl dark:bg-white/10"></div>
            <div class="absolute -right-16 top-64 h-96 w-96 rounded-full bg-brutalist-pink/20 blur-3xl"></div>
            <div class="absolute left-1/3 bottom-0 h-56 w-56 rounded-full bg-brutalist-blue/20 blur-3xl"></div>
            <div class="absolute right-[12%] top-24 h-24 w-24 rotate-12 bg-brutalist-purple/40 blur-sm"></div>
            <div class="absolute left-[8%] top-[55%] h-40 w-40 rounded-full bg-brutalist-lime/15 blur-3xl"></div>
        </div>

        <div class="relative z-10 mx-auto max-w-[1400px]">
            <!-- Brand badge -->
            <div
                :style="{ transitionDelay: heroDelay(0) }"
                :class="[
                    'mb-4 mt-4 md:mt-6 transition-all duration-700',
                    heroVisible ? 'translate-y-0 opacity-100' : '-translate-y-8 opacity-0'
                ]"
            >
                <span class="inline-block border-4 border-black bg-white px-4 py-1.5 sm:px-5 sm:py-2 text-[10px] sm:text-xs font-black uppercase tracking-[0.28em] text-black shadow-brutalist dark:border-white dark:bg-black dark:text-white">
                    {{ siteName }}
                </span>
                <span v-if="siteTagline" class="mt-2 sm:mt-0 sm:ml-3 inline-block text-[10px] sm:text-xs font-black uppercase tracking-[0.28em] text-black/50 dark:text-white/50 break-words max-w-full">
                    {{ siteTagline }}
                </span>
            </div>

            <!-- Title -->
            <h1
                class="font-display text-[7vw] font-black uppercase italic leading-[0.8] tracking-tighter md:text-[8rem]"
            >
                <span
                    :style="{ transitionDelay: heroDelay(0) }"
                    :class="[
                        'block transition-all duration-700',
                        heroVisible ? 'translate-x-0 opacity-100' : '-translate-x-16 opacity-0'
                    ]"
                >
                    {{ t('contacto.title1') }}
                </span>
                <span
                    :style="{ transitionDelay: heroDelay(1) }"
                    :class="[
                        'block text-black drop-shadow-[8px_8px_0px_rgba(255,255,255,0.35)] transition-all duration-700 dark:text-white dark:drop-shadow-[8px_8px_0px_rgba(0,0,0,0.45)]',
                        heroVisible ? 'translate-x-0 opacity-100' : 'translate-x-16 opacity-0'
                    ]"
                >
                    {{ t('contacto.title2') }}
                </span>
                <span
                    :style="{ transitionDelay: heroDelay(2) }"
                    :class="[
                        'block transition-all duration-700',
                        heroVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'
                    ]"
                >
                    {{ t('contacto.title3') }}
                </span>
            </h1>

            <!-- Subtitle -->
            <p
                :style="{ transitionDelay: heroDelay(3) }"
                :class="[
                    'max-w-4xl text-2xl font-black uppercase italic leading-none tracking-tighter transition-all duration-700 md:text-4xl',
                    heroVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'
                ]"
            >
                {{ t('contacto.subtitle') }}
            </p>
        </div>
    </section>
</template>
