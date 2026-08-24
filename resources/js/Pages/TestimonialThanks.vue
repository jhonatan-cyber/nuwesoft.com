<script setup>
import { Head } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { CheckCircle, ArrowLeft } from 'lucide-vue-next'
import GuestLayout from '@/Layouts/GuestLayout.vue'

const { t } = useI18n()
</script>

<template>
    <GuestLayout>
        <Head :title="t('testimonial_thanks.title', '¡Gracias!')" />

        <section class="relative bg-black px-6 py-24 md:py-32 min-h-[60vh] flex items-center">
            <div class="pointer-events-none absolute -left-20 top-1/4 h-64 w-64 rounded-full bg-brutalist-yellow/10 blur-3xl"></div>

            <div class="relative z-10 mx-auto max-w-xl text-center">
                <CheckCircle class="w-24 h-24 text-brutalist-yellow mx-auto mb-8" />

                <h1 class="text-4xl font-black uppercase italic text-white md:text-6xl mb-6">
                    {{ t('testimonial_thanks.title', '¡Gracias!') }}
                </h1>

                <p class="text-lg text-white/60 mb-10 leading-relaxed">
                    {{ t('testimonial_thanks.message', 'Tu reseña fue recibida correctamente. Nuestro equipo la revisará y la publicaremos pronto. ¡Muchas gracias por confiar en nosotros!') }}
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="/"
                        class="inline-flex items-center gap-2 bg-brutalist-yellow text-black px-8 py-4 font-black uppercase text-sm tracking-wider hover:bg-brutalist-yellow/90 transition-colors">
                        <ArrowLeft class="w-4 h-4" />
                        {{ t('testimonial_thanks.back_home', 'Volver al inicio') }}
                    </a>
                </div>
            </div>
        </section>
    </GuestLayout>
</template>
