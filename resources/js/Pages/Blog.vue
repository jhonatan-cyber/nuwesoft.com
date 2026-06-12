<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { usePageTracking } from '@/composables/usePageTracking'
import { useSkeletonLoader } from '@/composables/useSkeletonLoader'
import PublicGridBackground from '@/Components/PublicGridBackground.vue'
import PublicSiteHeader from '@/Components/PublicSiteHeader.vue'
import PublicSiteFooter from '@/Components/PublicSiteFooter.vue'
import SkeletonPostCard from '@/Components/SkeletonPostCard.vue'
import { Badge } from '@/Components/ui/badge'
import { ArrowRight, Calendar, Clock, User } from 'lucide-vue-next'

const { t } = useI18n()
const page = usePage()
const settings = computed(() => page.props.settings || {})
const siteName = computed(() => settings.value.site_name || 'NUWESOFT')
const pageUrl = computed(() => window.location.href)

usePageTracking()

const { skeletonReady } = useSkeletonLoader()

defineProps({
    posts: { type: Array, default: () => [] },
})

const categories = ['case-study', 'technical', 'news', 'insights']
</script>

<template>
    <Head :title="`Blog | ${siteName}`">
        <meta name="description" content="Casos de estudio, artículos técnicos e insights de NUWESOFT Engineering" />
        <meta property="og:title" :content="`Blog | ${siteName}`" />
        <meta property="og:description" content="Casos de estudio, artículos técnicos e insights de NUWESOFT Engineering" />
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="pageUrl" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="`Blog | ${siteName}`" />
        <meta name="twitter:description" content="Casos de estudio, artículos técnicos e insights de NUWESOFT Engineering" />
        <link rel="canonical" :href="pageUrl" />
    </Head>

    <div class="min-h-screen overflow-x-hidden bg-white font-sans text-black selection:bg-brutalist-yellow selection:text-black dark:bg-black dark:text-white">
        <PublicGridBackground />
        <PublicSiteHeader />

        <main id="main-content" class="relative pt-40 pb-24">
            <div class="pointer-events-none absolute -left-32 top-1/4 h-72 w-72 rounded-full bg-brutalist-pink/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -right-32 bottom-1/4 h-80 w-80 rounded-full bg-brutalist-blue/10 blur-3xl"></div>

            <div class="relative z-10 mx-auto max-w-[1400px] px-6">
                <!-- Header -->
                <div class="mb-20">
                    <Badge class="-rotate-1 mb-6 inline-block border-4 border-black bg-brutalist-yellow px-4 py-2 text-xl font-black uppercase text-black">
                        BLOG
                    </Badge>
                    <h1 class="text-[clamp(3rem,8vw,6rem)] font-display font-black uppercase italic leading-[0.8] tracking-tighter">
                        CASOS DE <br/>
                        <span class="text-brutalist-pink">ESTUDIO</span>
                    </h1>
                    <p class="mt-6 max-w-2xl text-xl font-black uppercase leading-tight text-black/70 dark:text-zinc-300">
                        Proyectos reales, decisiones técnicas y resultados concretos. Sin marketing vacío.
                    </p>
                </div>

                <!-- Skeleton Grid -->
                <Transition name="fade" mode="out-in">
                    <div v-if="!skeletonReady" key="skeleton" class="grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3">
                        <SkeletonPostCard v-for="i in 6" :key="'skel-' + i" />
                    </div>

                    <div v-else key="content">
                        <!-- Posts Grid -->
                        <div v-if="posts.length" class="grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3">
                            <Link v-for="post in posts" :key="post.id"
                                :href="route('blog.show', post.slug)"
                                class="group relative bg-white dark:bg-black border-4 border-black dark:border-white shadow-brutalist dark:shadow-brutalist-white transition-all hover:-translate-x-2 hover:-translate-y-2 hover:shadow-brutalist-hover-lg dark:hover:shadow-brutalist-white-lg overflow-hidden flex flex-col">
                                
                                <!-- Category Ribbon -->
                                <div class="absolute top-4 right-4 z-10 bg-black dark:bg-white px-3 py-1">
                                    <span class="text-[9px] font-black uppercase tracking-widest text-white dark:text-black">{{ post.category }}</span>
                                </div>

                                <!-- Cover Gradient -->
                                <div class="h-48 bg-gradient-to-br from-brutalist-pink/20 via-brutalist-yellow/10 to-brutalist-blue/20 flex items-center justify-center relative overflow-hidden">
                                    <div class="absolute inset-0 opacity-[0.04]" style="background-image: repeating-linear-gradient(0deg, transparent, transparent 2px, #000 2px, #000 3px); background-size: 40px 40px;"></div>
                                    <span class="text-6xl font-display font-black italic text-black/10 dark:text-white/10 select-none">//</span>
                                </div>

                                <div class="p-6 flex flex-col flex-1">
                                    <!-- Meta -->
                                    <div class="flex items-center gap-4 text-[10px] font-black uppercase tracking-widest text-neutral-500 mb-4">
                                        <span class="flex items-center gap-1.5">
                                            <Calendar class="w-3 h-3" />
                                            {{ post.published_at }}
                                        </span>
                                        <span class="flex items-center gap-1.5">
                                            <User class="w-3 h-3" />
                                            {{ post.author_name }}
                                        </span>
                                    </div>

                                    <!-- Title -->
                                    <h2 class="text-lg md:text-xl font-display font-black uppercase italic leading-tight mb-3 group-hover:text-brutalist-pink transition-colors break-words">
                                        {{ post.title }}
                                    </h2>

                                    <!-- Excerpt -->
                                    <p v-if="post.excerpt" class="text-xs font-black uppercase leading-relaxed text-neutral-600 dark:text-neutral-400 mb-6 flex-1">
                                        {{ post.excerpt }}
                                    </p>

                                    <!-- Tags -->
                                    <div v-if="post.tags?.length" class="flex flex-wrap gap-2 mb-4">
                                        <span v-for="tag in post.tags" :key="tag"
                                            class="px-2 py-1 border-2 border-black/20 dark:border-white/20 text-[8px] font-black uppercase tracking-wider">
                                            {{ tag }}
                                        </span>
                                    </div>

                                    <!-- Read More -->
                                    <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest group-hover:text-brutalist-pink transition-colors mt-auto">
                                        LEER CASO
                                        <ArrowRight class="w-4 h-4 transition-transform group-hover:translate-x-1" />
                                    </div>
                                </div>
                            </Link>
                        </div>

                        <!-- Empty State -->
                        <div v-else class="border-4 border-black dark:border-white p-16 text-center shadow-brutalist dark:shadow-brutalist-white">
                            <p class="text-2xl font-display font-black uppercase italic">PRÓXIMAMENTE</p>
                            <p class="mt-4 text-sm font-black uppercase tracking-wider text-neutral-500">
                                Estamos preparando casos de estudio y artículos técnicos. Volvé pronto.
                            </p>
                        </div>
                    </div>
                </Transition>
            </div>
        </main>

        <PublicSiteFooter />
    </div>
</template>

<style>
.font-display { font-family: 'Space Grotesk', sans-serif; }
</style>
