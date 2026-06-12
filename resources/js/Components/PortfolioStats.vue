<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const props = defineProps({
    stats: { type: Array, default: () => [] },
    animatedStats: { type: Array, default: () => [] },
    visibleStats: { type: Boolean, default: false },
})

const statsSection = ref(null)
defineExpose({ statsSection })
</script>

<template>
    <section
        ref="statsSection"
        class="bg-brutalist-yellow dark:bg-brutalist-yellow border-y-8 border-black dark:border-white py-20 px-6 relative overflow-hidden"
    >
        <!-- Decorative Background -->
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <div class="absolute top-0 left-0 w-64 h-64 bg-white/20 rounded-full -translate-x-1/2 -translate-y-1/2 blur-2xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-black/5 rounded-full translate-x-1/3 translate-y-1/3 blur-3xl"></div>
        </div>

        <div class="max-w-[1400px] mx-auto relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-12">
                <div
                    v-for="(stat, idx) in stats"
                    :key="stat.key"
                    :style="{ transitionDelay: `${idx * 100}ms` }"
                    :class="[
                        'text-center group transition-all duration-700',
                        visibleStats ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'
                    ]"
                >
                    <div class="w-16 h-16 bg-white border-4 border-black mx-auto mb-6 flex items-center justify-center transform group-hover:rotate-12 group-hover:scale-110 transition-all duration-300 shadow-brutalist dark:border-white">
                        <component :is="stat.icon" class="w-8 h-8 text-black" />
                    </div>

                    <div class="text-5xl font-display font-black text-black mb-2">
                        <template v-if="stat.key === 'coffee'">
                            {{ stat.displayValue }}
                        </template>
                        <template v-else-if="stat.key === 'uptime'">
                            {{ animatedStats[idx]?.toFixed(1) || '0.0' }}<span class="text-3xl">%</span>
                        </template>
                        <template v-else-if="stat.key === 'commits'">
                            {{ Math.round(animatedStats[idx] || 0) }}<span class="text-3xl">+</span>
                        </template>
                        <template v-else>
                            {{ Math.round(animatedStats[idx] || 0) }}<span class="text-3xl">+</span>
                        </template>
                    </div>

                    <div class="text-sm font-black uppercase italic text-black opacity-70 tracking-widest">{{ t(`portafolio.stats.${stat.key}.label`) }}</div>
                </div>
            </div>
        </div>
    </section>
</template>
