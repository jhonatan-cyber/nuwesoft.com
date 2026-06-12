<script setup>
import { useI18n } from 'vue-i18n'
import { Link } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import { ArrowRight } from 'lucide-vue-next'
import { useInView } from '@/composables/useInView'
import { usePostHog } from '@/composables/usePostHog'

const { t } = useI18n()
const { el, isVisible } = useInView(0.2)
const { capture } = usePostHog()

const sectionDelay = (index, step = 100) => ({ transitionDelay: `${index * step}ms` })

const trackCTAClick = (label) => {
    capture('cta_click', { label, section: 'hero_cta' })
}
</script>

<template>
    <section
        ref="el"
        class="relative overflow-hidden bg-white px-6 py-24 dark:bg-black md:py-48"
    >
        <!-- Background decoration -->
        <div class="absolute -left-32 top-1/2 h-96 w-96 -translate-y-1/2 rounded-full bg-brutalist-yellow opacity-5 blur-3xl"></div>
        <div class="absolute -right-32 bottom-0 h-80 w-80 rounded-full bg-brutalist-pink opacity-5 blur-3xl"></div>
        <div class="absolute left-1/3 top-0 h-48 w-48 rounded-full bg-brutalist-purple opacity-5 blur-3xl"></div>
        <div class="absolute right-1/4 top-[30%] h-32 w-32 rounded-full bg-brutalist-lime opacity-5 blur-2xl"></div>

        <div class="relative mx-auto max-w-5xl">
            <div
                :class="[
                    'relative overflow-hidden border-8 border-brutalist-yellow bg-black p-8 shadow-brutalist-lg transition-all duration-700 dark:shadow-brutalist-white-lg md:p-14',
                    isVisible ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'
                ]"
            >
                <!-- Massive "?" watermark -->
                <div class="pointer-events-none absolute -right-8 -top-12 select-none text-[14rem] font-display font-black italic leading-none text-white/5 md:text-[20rem]">?</div>

                <!-- Floating decorative diamonds -->
                <div class="absolute left-6 top-6 hidden h-3 w-3 rotate-45 bg-brutalist-yellow opacity-30 md:block"></div>
                <div class="absolute right-12 top-10 hidden h-2 w-2 rotate-45 bg-brutalist-pink opacity-25 md:block"></div>
                <div class="absolute bottom-8 left-1/4 hidden h-2 w-2 rotate-45 bg-brutalist-lime opacity-40 md:block"></div>
                <div class="absolute right-[20%] bottom-4 hidden h-3 w-3 rotate-12 bg-brutalist-purple opacity-30 md:block"></div>
                <div class="absolute bottom-8 left-1/4 hidden h-2 w-2 rotate-45 border-2 border-white/20 opacity-30 md:block"></div>

                <div class="relative z-10 mx-auto max-w-3xl text-center">
                    <!-- Title -->
                    <h2
                        :style="sectionDelay(0, 100)"
                        :class="[
                            'mb-6 font-display text-5xl font-black uppercase italic leading-[0.85] text-white transition-all duration-700 sm:text-6xl md:text-7xl xl:text-8xl',
                            isVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'
                        ]"
                    >
                        {{ t('cta.title1') }} <br/>
                        <span class="relative mt-2 inline-block">
                            <span class="text-brutalist-yellow">{{ t('cta.title2') }}</span>
                            <span class="absolute -bottom-2 left-0 right-0 h-2 bg-brutalist-yellow/40"></span>
                        </span>
                    </h2>

                    <!-- Subtitle -->
                    <p
                        :style="sectionDelay(1, 100)"
                        :class="[
                            'mx-auto mb-10 max-w-3xl text-base font-black uppercase leading-relaxed text-white/60 transition-all duration-700 delay-100 md:text-xl',
                            isVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'
                        ]"
                    >
                        {{ t('cta.subtitle') }}
                    </p>

                    <!-- Buttons -->
                    <div
                        :style="sectionDelay(2, 100)"
                        :class="[
                            'flex flex-col items-center justify-center gap-5 transition-all duration-700 delay-200 sm:flex-row',
                            isVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'
                        ]"
                    >
                        <Button as-child class="group relative overflow-hidden rounded-none border-4 border-black bg-brutalist-pink px-8 py-5 text-xl font-black tracking-wider text-white shadow-brutalist transition-all hover:translate-x-[4px] hover:translate-y-[4px] hover:bg-brutalist-yellow hover:text-black hover:shadow-brutalist-hover md:px-12 md:py-7 md:text-2xl">
                            <Link :href="route('contacto')" @click="trackCTAClick('contacto')">
                                <span class="relative z-10 flex items-center gap-4">
                                    <span>{{ t('cta.button') }}</span>
                                    <ArrowRight class="h-6 w-6 transition-transform group-hover:translate-x-2 group-hover:-translate-y-1" />
                                </span>
                            </Link>
                        </Button>
                        <Button as-child variant="outline" class="group rounded-none border-4 border-white/60 bg-transparent px-8 py-5 text-xl font-black tracking-wider text-white shadow-none transition-all hover:border-white hover:bg-white hover:text-black md:px-12 md:py-7 md:text-2xl">
                            <Link :href="route('portafolio')" @click="trackCTAClick('portfolio')">
                                <span class="flex items-center gap-4">
                                    {{ t('portfolio') }}
                                    <ArrowRight class="h-6 w-6 transition-transform group-hover:translate-x-2" />
                                </span>
                            </Link>
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
