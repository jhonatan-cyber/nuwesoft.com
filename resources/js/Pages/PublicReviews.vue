<script setup>
import { computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { Star, Quote, ArrowLeft, MessageSquareQuote, ChevronLeft, ChevronRight } from 'lucide-vue-next'
import GuestLayout from '@/Layouts/GuestLayout.vue'

const { t } = useI18n()

const props = defineProps({
    testimonials: { type: Object, default: () => ({ data: [], links: [] }) },
    stats: { type: Object, default: () => ({ total: 0, avg_rating: 0, 5_star: 0, 4_star: 0, 3_star: 0 }) },
})

const paginatedLinks = computed(() => {
    return props.testimonials.links?.filter(l => l.label !== '...') || []
})

const goToPage = (url) => {
    if (url) {
        router.get(url, {}, { preserveState: true, replace: true })
    }
}

const formatDate = (dateStr) => {
    return new Date(dateStr).toLocaleDateString('es-AR', {
        year: 'numeric',
        month: 'long',
    })
}

const starPercentage = (count) => {
    if (!props.stats.total) return 0
    return Math.round((count / props.stats.total) * 100)
}
</script>

<template>
    <GuestLayout>
        <Head :title="t('public_reviews.title', 'Reseñas de Clientes')" />

        <section class="relative bg-black px-6 py-24 md:py-32">
            <!-- Decorative -->
            <div class="pointer-events-none absolute -left-20 top-1/4 h-64 w-64 rounded-full bg-brutalist-yellow/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -right-20 bottom-1/4 h-64 w-64 rounded-full bg-brutalist-pink/10 blur-3xl"></div>

            <div class="relative z-10 mx-auto max-w-6xl">
                <!-- Back link -->
                <a href="/" class="inline-flex items-center gap-2 text-sm font-black uppercase tracking-wider text-white/50 hover:text-brutalist-yellow transition-colors mb-12">
                    <ArrowLeft class="w-4 h-4" />
                    {{ t('public_reviews.back', 'Volver') }}
                </a>

                <!-- Header -->
                <div class="flex items-center gap-4 mb-8">
                    <span class="inline-flex h-4 w-4 rotate-45 border-2 border-brutalist-yellow bg-brutalist-yellow/30"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.28em] text-brutalist-yellow">
                        {{ t('public_reviews.eyebrow', 'OPINIONES') }}
                    </span>
                    <span class="flex-1 h-px bg-white/20"></span>
                </div>

                <h1 class="text-3xl font-black uppercase italic text-white md:text-5xl mb-4">
                    {{ t('public_reviews.title', 'Lo que dicen nuestros clientes') }}
                </h1>
                <p class="text-lg text-white/60 mb-12">
                    {{ t('public_reviews.subtitle', 'Reseñas verificadas de empresas que confiaron en nosotros.') }}
                </p>

                <!-- Stats summary -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-16">
                    <!-- Avg rating -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 text-center">
                        <div class="text-5xl font-black text-brutalist-yellow mb-2">{{ stats.avg_rating }}</div>
                        <div class="flex items-center justify-center gap-1 mb-2">
                            <Star v-for="i in 5" :key="i"
                                :class="i <= Math.round(stats.avg_rating) ? 'text-brutalist-yellow fill-brutalist-yellow' : 'text-white/20'"
                                class="w-4 h-4" />
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-white/40">
                            {{ stats.total }} {{ stats.total === 1 ? 'reseña' : 'reseñas' }}
                        </p>
                    </div>

                    <!-- Star breakdown -->
                    <div class="sm:col-span-3 bg-white/5 border border-white/10 rounded-2xl p-6">
                        <div v-for="stars in [5, 4, 3]" :key="stars" class="flex items-center gap-3 mb-3 last:mb-0">
                            <span class="text-sm font-black text-white w-8">{{ stars }}<Star class="inline w-3 h-3 text-brutalist-yellow fill-brutalist-yellow" /></span>
                            <div class="flex-1 h-3 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-brutalist-yellow rounded-full transition-all duration-500"
                                    :style="{ width: starPercentage(stats[`${stars}_star`]) + '%' }"></div>
                            </div>
                            <span class="text-xs font-black text-white/40 w-10 text-right">{{ stats[`${stars}_star`] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonials grid -->
                <div v-if="testimonials.data?.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    <div v-for="item in testimonials.data" :key="item.id"
                        class="bg-white/5 border border-white/10 rounded-2xl p-6 hover:border-brutalist-yellow/30 transition-all group">
                        <!-- Rating -->
                        <div class="flex items-center gap-0.5 mb-4">
                            <Star v-for="i in 5" :key="i"
                                :class="i <= item.rating ? 'text-brutalist-yellow fill-brutalist-yellow' : 'text-white/10'"
                                class="w-4 h-4" />
                        </div>

                        <!-- Content -->
                        <Quote class="w-6 h-6 text-brutalist-yellow/20 mb-3" />
                        <p class="text-white/80 leading-relaxed mb-6 line-clamp-5 group-hover:line-clamp-none transition-all">
                            "{{ item.content }}"
                        </p>

                        <!-- Author -->
                        <div class="flex items-center gap-3 mt-auto pt-4 border-t border-white/10">
                            <div class="w-10 h-10 rounded-full border border-white/20 bg-white/10 flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-black uppercase text-white">{{ item.client_name.charAt(0) }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-black uppercase tracking-wider text-white truncate">{{ item.client_name }}</p>
                                <p class="text-[10px] text-white/40 truncate">
                                    <span v-if="item.client_role">{{ item.client_role }}</span>
                                    <span v-if="item.client_role && item.client_company">, </span>
                                    <span v-if="item.client_company">{{ item.client_company }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Date -->
                        <p class="text-[9px] text-white/20 mt-3">{{ formatDate(item.created_at) }}</p>
                    </div>
                </div>

                <!-- Empty state -->
                <div v-else class="text-center py-20">
                    <MessageSquareQuote class="w-16 h-16 text-white/20 mx-auto mb-6" />
                    <p class="text-xl font-black uppercase text-white/40">
                        {{ t('public_reviews.empty', 'Aún no hay reseñas publicadas') }}
                    </p>
                    <p class="text-sm text-white/30 mt-2">
                        {{ t('public_reviews.empty_sub', 'Sé el primero en dejarnos tu opinión') }}
                    </p>
                </div>

                <!-- Pagination -->
                <div v-if="testimonials.last_page > 1" class="flex items-center justify-center gap-2">
                    <button
                        @click="goToPage(testimonials.prev_page_url)"
                        :disabled="!testimonials.prev_page_url"
                        class="p-3 border border-white/20 rounded-xl hover:border-brutalist-yellow hover:text-brutalist-yellow transition-colors disabled:opacity-20 disabled:cursor-not-allowed">
                        <ChevronLeft class="w-4 h-4" />
                    </button>

                    <template v-for="link in paginatedLinks" :key="link.label">
                        <button
                            v-if="link.url"
                            @click="goToPage(link.url)"
                            :class="[
                                'w-10 h-10 rounded-xl font-black text-xs transition-all',
                                link.active
                                    ? 'bg-brutalist-yellow text-black'
                                    : 'border border-white/20 text-white/60 hover:border-brutalist-yellow hover:text-brutalist-yellow'
                            ]">
                            {{ link.label }}
                        </button>
                        <span v-else class="w-10 h-10 flex items-center justify-center text-white/20">…</span>
                    </template>

                    <button
                        @click="goToPage(testimonials.next_page_url)"
                        :disabled="!testimonials.next_page_url"
                        class="p-3 border border-white/20 rounded-xl hover:border-brutalist-yellow hover:text-brutalist-yellow transition-colors disabled:opacity-20 disabled:cursor-not-allowed">
                        <ChevronRight class="w-4 h-4" />
                    </button>
                </div>

                <!-- CTA -->
                <div class="mt-16 text-center">
                    <a href="/reseñas"
                        class="inline-flex items-center gap-2 bg-brutalist-yellow text-black px-8 py-4 font-black uppercase text-sm tracking-wider hover:bg-brutalist-yellow/90 transition-colors">
                        <MessageSquareQuote class="w-4 h-4" />
                        {{ t('public_reviews.cta', 'Dejanos tu reseña') }}
                    </a>
                </div>
            </div>
        </section>
    </GuestLayout>
</template>
