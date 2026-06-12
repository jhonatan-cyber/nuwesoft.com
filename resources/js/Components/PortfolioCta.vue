<script setup>
import { useI18n } from 'vue-i18n'
import { Button } from '@/Components/ui/button'
import { Link } from '@inertiajs/vue3'
import { ChevronRight, Sparkles } from 'lucide-vue-next'

const { t } = useI18n()

defineProps({
    isVisible: { type: Boolean, default: false },
})
</script>

<template>
    <section class="py-32 px-6 relative overflow-hidden">
        <!-- Decorative background -->
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80vw] h-[50vh] bg-brutalist-yellow/5 blur-3xl rounded-full"></div>
        </div>

        <div class="max-w-[1400px] mx-auto text-center relative z-10">
            <div
                :class="['transition-all duration-1000 transform', isVisible ? 'translate-y-0 opacity-100' : 'translate-y-16 opacity-0']"
            >
                <div class="inline-flex items-center gap-3 border-4 border-black bg-white px-6 py-3 shadow-brutalist mb-12 dark:border-white dark:bg-zinc-950 dark:shadow-brutalist-white">
                    <Sparkles class="w-5 h-5 text-brutalist-pink" />
                    <span class="text-xs font-black uppercase tracking-[0.28em]">{{ t('cta.subtitle')?.substring(0, 20) || 'NEXT STEP' }}</span>
                </div>

                <h2 class="text-[clamp(2rem,6vw,4rem)] font-display font-black uppercase italic leading-none mb-12">
                    {{ t('cta.title1') }} <br/>
                    <span class="text-brutalist-blue relative inline-block">
                        {{ t('cta.title2') }}
                        <span class="absolute -bottom-2 left-0 right-0 h-2 bg-brutalist-yellow -skew-x-6"></span>
                    </span>
                </h2>

                <Button as-child size="lg" class="bg-black dark:bg-white text-white dark:text-black font-black text-2xl px-12 py-8 rounded-none border-4 border-black dark:border-white hover:bg-brutalist-pink hover:text-white dark:hover:bg-brutalist-pink dark:hover:text-white shadow-brutalist dark:shadow-brutalist-white hover:shadow-brutalist-hover transition-all duration-300 group/cta">
                    <Link :href="route('contacto')">
                        <span class="flex items-center gap-3">
                            {{ t('cta.button') }}
                            <ChevronRight class="w-8 h-8 transition-transform group-hover/cta:translate-x-1" />
                        </span>
                    </Link>
                </Button>
            </div>
        </div>
    </section>
</template>
