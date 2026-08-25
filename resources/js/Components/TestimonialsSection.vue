<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useInView } from '@/composables/useInView'
import { Star, Quote } from 'lucide-vue-next'

const props = defineProps({
    testimonials: { type: Array, default: () => [
        { client_name: 'Cliente Demo', client_role: 'CEO', client_company: 'Empresa Demo', content: 'NUWESOFT transformó nuestra infraestructura digital. Resultados excepcionales.', rating: 5 },
    ]},
})

const { el, isVisible } = useInView(0.15)
const { t } = useI18n()
const activeIndex = ref(0)
const isPaused = ref(false)
let rotationInterval

onMounted(() => {
    // Auto-rotate every 6 seconds
    rotationInterval = setInterval(() => {
        if (!isPaused.value && props.testimonials.length > 1) {
            activeIndex.value = (activeIndex.value + 1) % props.testimonials.length
        }
    }, 6000)
})

onUnmounted(() => clearInterval(rotationInterval))
</script>

<template>
    <section v-if="testimonials.length" ref="el" class="relative overflow-hidden bg-black px-6 py-24 md:py-32">
        <!-- Decorative -->
        <div class="pointer-events-none absolute -left-20 top-1/4 h-64 w-64 rounded-full bg-brutalist-yellow/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-20 bottom-1/4 h-64 w-64 rounded-full bg-brutalist-pink/10 blur-3xl"></div>

        <div class="relative z-10 mx-auto max-w-5xl">
            <div
                :class="[
                    'transition-all duration-700',
                    isVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'
                ]"
            >
                <!-- Label -->
                <div class="flex items-center gap-4 mb-12">
                    <span class="inline-flex h-4 w-4 rotate-45 border-2 border-brutalist-yellow bg-brutalist-yellow/30"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.28em] text-brutalist-yellow">LO QUE DICEN</span>
                    <span class="flex-1 h-px bg-white/20"></span>
                </div>

                <!-- Carousel -->
                <div class="relative min-h-[16rem]" @mouseenter="isPaused = true" @mouseleave="isPaused = false">
                    <transition name="testimonial-fade" mode="out-in">
                        <div :key="activeIndex" class="flex flex-col items-center text-center">
                            <!-- Quote icon -->
                            <Quote class="w-12 h-12 text-brutalist-yellow/30 mb-8" />

                            <!-- Content -->
                            <p class="text-2xl font-black uppercase italic leading-relaxed text-white md:text-3xl lg:text-4xl">
                                "{{ testimonials[activeIndex].content }}"
                            </p>

                            <!-- Rating -->
                            <div class="flex items-center gap-1 mt-8">
                                <Star v-for="i in (testimonials[activeIndex].rating || 5)" :key="i"
                                    class="w-5 h-5 text-brutalist-yellow fill-brutalist-yellow" />
                            </div>

                            <!-- Author -->
                            <div class="mt-6 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full border-2 border-white/30 bg-white/10 flex items-center justify-center">
                                    <span class="text-lg font-black uppercase italic text-white">{{ testimonials[activeIndex].client_name.charAt(0) }}</span>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-black uppercase tracking-wider text-white">
                                        {{ testimonials[activeIndex].client_name }}
                                    </p>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-white/50">
                                        {{ testimonials[activeIndex].client_role }}
                                        <span v-if="testimonials[activeIndex].client_company">, {{ testimonials[activeIndex].client_company }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>

                <!-- CTA -->
                <div class="mt-10 text-center">
                    <a href="/reseñas"
                        class="inline-flex items-center gap-2 border-2 border-brutalist-yellow/30 text-brutalist-yellow px-6 py-3 text-[10px] font-black uppercase tracking-[0.28em] hover:bg-brutalist-yellow hover:text-black transition-all">
                        {{ t('testimonials.cta', 'Dejanos tu reseña') }}
                    </a>
                </div>

                <!-- Dots -->
                <div v-if="testimonials.length > 1" class="flex items-center justify-center gap-3 mt-6">
                    <button v-for="(_, idx) in testimonials" :key="idx"
                        @click="activeIndex = idx"
                        :class="[
                            'h-3 transition-all duration-300',
                            idx === activeIndex ? 'w-8 bg-brutalist-yellow' : 'w-3 bg-white/30 hover:bg-white/50'
                        ]">
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.testimonial-fade-enter-active,
.testimonial-fade-leave-active {
    transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}
.testimonial-fade-enter-from {
    opacity: 0;
    transform: translateY(20px);
}
.testimonial-fade-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}
</style>
