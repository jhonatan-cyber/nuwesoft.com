<script setup>
import { ref, computed } from 'vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { Star, Send, ArrowLeft, Quote, CheckCircle } from 'lucide-vue-next'
import GuestLayout from '@/Layouts/GuestLayout.vue'

const { t } = useI18n()
const { props } = usePage()
const existingTestimonials = computed(() => props.testimonials || [])

const form = useForm({
    client_name: '',
    client_role: '',
    client_company: '',
    content: '',
    rating: 5,
    form_token: '', // honeypot — must remain empty
})

const hoveredStar = ref(0)
const submitted = ref(false)

const submit = () => {
    form.post(route('review.store'), {
        onSuccess: () => {
            submitted.value = true
        },
    })
}

const setRating = (value) => {
    form.rating = value
}
</script>

<template>
    <GuestLayout>
        <Head :title="t('testimonial_form.title', 'Dejanos tu reseña')" />

        <section class="relative bg-black px-6 py-24 md:py-32">
            <!-- Decorative -->
            <div class="pointer-events-none absolute -left-20 top-1/4 h-64 w-64 rounded-full bg-brutalist-yellow/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -right-20 bottom-1/4 h-64 w-64 rounded-full bg-brutalist-pink/10 blur-3xl"></div>

            <div class="relative z-10 mx-auto max-w-2xl">
                <!-- Back link -->
                <a href="/" class="inline-flex items-center gap-2 text-sm font-black uppercase tracking-wider text-white/50 hover:text-brutalist-yellow transition-colors mb-12">
                    <ArrowLeft class="w-4 h-4" />
                    {{ t('testimonial_form.back', 'Volver') }}
                </a>

                <!-- Header -->
                <div class="flex items-center gap-4 mb-8">
                    <span class="inline-flex h-4 w-4 rotate-45 border-2 border-brutalist-yellow bg-brutalist-yellow/30"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.28em] text-brutalist-yellow">
                        {{ t('testimonial_form.eyebrow', 'TESTIMONIOS') }}
                    </span>
                    <span class="flex-1 h-px bg-white/20"></span>
                </div>

                <h1 class="text-3xl font-black uppercase italic text-white md:text-5xl mb-4">
                    {{ t('testimonial_form.title', '¿Trabajaste con nosotros?') }}
                </h1>
                <p class="text-lg text-white/60 mb-12">
                    {{ t('testimonial_form.subtitle', 'Tu opinión nos ayuda a crecer. Compartí tu experiencia con otros clientes.') }}
                </p>

                <!-- Success state -->
                <div v-if="submitted" class="text-center py-16">
                    <CheckCircle class="w-20 h-20 text-brutalist-yellow mx-auto mb-6" />
                    <h2 class="text-2xl font-black uppercase italic text-white mb-4">
                        {{ t('testimonial_form.success_title', '¡Gracias!') }}
                    </h2>
                    <p class="text-white/60 mb-8">
                        {{ t('testimonial_form.success_msg', 'Tu reseña fue enviada correctamente. Será publicada después de ser revisada por nuestro equipo.') }}
                    </p>
                    <a href="/" class="inline-flex items-center gap-2 bg-brutalist-yellow text-black px-6 py-3 font-black uppercase text-sm hover:bg-brutalist-yellow/90 transition-colors">
                        {{ t('testimonial_form.back_home', 'Volver al inicio') }}
                    </a>
                </div>

                <!-- Form -->
                <form v-else @submit.prevent="submit" class="space-y-6">
                    <!-- Honeypot — invisible to humans -->
                    <input v-model="form.form_token" type="text" name="form_token" class="hidden" tabindex="-1" autocomplete="off" />

                    <!-- Name -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-white/50 mb-2">
                            {{ t('testimonial_form.name', 'Tu nombre') }} *
                        </label>
                        <input
                            v-model="form.client_name"
                            type="text"
                            required
                            maxlength="255"
                            class="w-full bg-white/5 border border-white/20 rounded-lg px-4 py-3 text-white font-black uppercase tracking-wider placeholder:text-white/30 focus:border-brutalist-yellow focus:ring-1 focus:ring-brutalist-yellow outline-none transition-colors"
                            :placeholder="t('testimonial_form.name_placeholder', 'Juan Pérez')"
                        />
                        <p v-if="form.errors.client_name" class="text-red-400 text-sm mt-1">{{ form.errors.client_name }}</p>
                    </div>

                    <!-- Role + Company -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-white/50 mb-2">
                                {{ t('testimonial_form.role', 'Tu rol') }}
                            </label>
                            <input
                                v-model="form.client_role"
                                type="text"
                                maxlength="255"
                                class="w-full bg-white/5 border border-white/20 rounded-lg px-4 py-3 text-white font-black uppercase tracking-wider placeholder:text-white/30 focus:border-brutalist-yellow focus:ring-1 focus:ring-brutalist-yellow outline-none transition-colors"
                                :placeholder="t('testimonial_form.role_placeholder', 'CEO')"
                            />
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-white/50 mb-2">
                                {{ t('testimonial_form.company', 'Empresa') }}
                            </label>
                            <input
                                v-model="form.client_company"
                                type="text"
                                maxlength="255"
                                class="w-full bg-white/5 border border-white/20 rounded-lg px-4 py-3 text-white font-black uppercase tracking-wider placeholder:text-white/30 focus:border-brutalist-yellow focus:ring-1 focus:ring-brutalist-yellow outline-none transition-colors"
                                :placeholder="t('testimonial_form.company_placeholder', 'Mi Empresa S.A.')"
                            />
                        </div>
                    </div>

                    <!-- Rating -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-white/50 mb-3">
                            {{ t('testimonial_form.rating', 'Calificación') }} *
                        </label>
                        <div class="flex items-center gap-2">
                            <button
                                v-for="i in 5" :key="i"
                                type="button"
                                @click="setRating(i)"
                                @mouseenter="hoveredStar = i"
                                @mouseleave="hoveredStar = 0"
                                class="transition-transform hover:scale-110"
                            >
                                <Star
                                    class="w-8 h-8 transition-colors"
                                    :class="(hoveredStar || form.rating) >= i
                                        ? 'text-brutalist-yellow fill-brutalist-yellow'
                                        : 'text-white/20'"
                                />
                            </button>
                            <span class="text-sm text-white/40 ml-2">{{ form.rating }}/5</span>
                        </div>
                        <p v-if="form.errors.rating" class="text-red-400 text-sm mt-1">{{ form.errors.rating }}</p>
                    </div>

                    <!-- Content -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-white/50 mb-2">
                            {{ t('testimonial_form.content', 'Tu reseña') }} *
                        </label>
                        <textarea
                            v-model="form.content"
                            required
                            rows="5"
                            maxlength="2000"
                            class="w-full bg-white/5 border border-white/20 rounded-lg px-4 py-3 text-white font-medium placeholder:text-white/30 focus:border-brutalist-yellow focus:ring-1 focus:ring-brutalist-yellow outline-none transition-colors resize-none"
                            :placeholder="t('testimonial_form.content_placeholder', 'Contanos sobre tu experiencia trabajando con nosotros...')"
                        ></textarea>
                        <div class="flex justify-between items-center mt-1">
                            <p v-if="form.errors.content" class="text-red-400 text-sm">{{ form.errors.content }}</p>
                            <span class="text-xs text-white/30 ml-auto">{{ form.content.length }}/2000</span>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-brutalist-yellow text-black py-4 font-black uppercase text-sm tracking-wider hover:bg-brutalist-yellow/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                    >
                        <Send class="w-4 h-4" />
                        {{ form.processing ? t('testimonial_form.sending', 'Enviando...') : t('testimonial_form.submit', 'Enviar reseña') }}
                    </button>

                    <p class="text-center text-xs text-white/30">
                        {{ t('testimonial_form.notice', 'Tu reseña será revisada antes de ser publicada.') }}
                    </p>
                </form>

                <!-- Existing testimonials preview -->
                <div v-if="existingTestimonials.length" class="mt-20">
                    <div class="flex items-center gap-4 mb-8">
                        <span class="text-[10px] font-black uppercase tracking-[0.28em] text-white/40">
                            {{ t('testimonial_form.others_said', 'Lo que dicen otros clientes') }}
                        </span>
                        <span class="flex-1 h-px bg-white/10"></span>
                    </div>
                    <div class="space-y-6">
                        <div v-for="(testimonial, idx) in existingTestimonials" :key="idx"
                            class="bg-white/5 border border-white/10 rounded-lg p-6">
                            <Quote class="w-6 h-6 text-brutalist-yellow/30 mb-3" />
                            <p class="text-white/80 italic mb-4">"{{ testimonial.content }}"</p>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full border border-white/20 bg-white/10 flex items-center justify-center">
                                    <span class="text-xs font-black uppercase text-white">{{ testimonial.client_name.charAt(0) }}</span>
                                </div>
                                <div>
                                    <p class="text-xs font-black uppercase text-white">{{ testimonial.client_name }}</p>
                                    <p class="text-[10px] text-white/40">
                                        {{ testimonial.client_role }}<span v-if="testimonial.client_company">, {{ testimonial.client_company }}</span>
                                    </p>
                                </div>
                                <div class="flex items-center gap-0.5 ml-auto">
                                    <Star v-for="i in testimonial.rating" :key="i" class="w-3 h-3 text-brutalist-yellow fill-brutalist-yellow" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </GuestLayout>
</template>
