<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { usePageTracking } from '@/composables/usePageTracking'
import { useSkeletonLoader } from '@/composables/useSkeletonLoader'
import PublicGridBackground from '@/Components/PublicGridBackground.vue'
import PublicSiteHeader from '@/Components/PublicSiteHeader.vue'
import PublicSiteFooter from '@/Components/PublicSiteFooter.vue'
import { Button } from '@/Components/ui/button'
import { Badge } from '@/Components/ui/badge'
import MarkdownPreview from '@/Components/MarkdownPreview.vue'
import { safeJsonLd } from '@/utils/safeJsonLd'
import { ArrowLeft, ArrowRight, Calendar, User, Clock } from 'lucide-vue-next'

const { t } = useI18n()
const page = usePage()
const settings = computed(() => page.props.settings || {})
const siteName = computed(() => settings.value.site_name || 'NUWESOFT')
const pageUrl = computed(() => window.location.href)

const { skeletonReady } = useSkeletonLoader()

const props = defineProps({
    post: { type: Object, required: true },
    related: { type: Array, default: () => [] },
})

const articleJsonLd = computed(() => safeJsonLd({
    '@context': 'https://schema.org',
    '@type': 'Article',
    headline: props.post.title,
    description: props.post.excerpt || props.post.title,
    author: {
        '@type': 'Person',
        name: props.post.author_name || siteName.value,
    },
    datePublished: props.post.published_at,
    publisher: {
        '@type': 'Organization',
        name: siteName.value,
    },
    mainEntityOfPage: {
        '@type': 'WebPage',
        '@id': pageUrl.value,
    },
    wordCount: props.post.content ? props.post.content.split(/\s+/).length : 0,
}))

const readingTime = computed(() => {
    if (!props.post.content) return null
    const words = props.post.content.split(/\s+/).length
    const minutes = Math.max(1, Math.ceil(words / 230))
    return minutes
})
</script>

<template>
    <Head :title="`${post.title} | ${siteName}`">
        <meta name="description" :content="post.excerpt || post.title" />
        <meta property="og:title" :content="`${post.title} | ${siteName}`" />
        <meta property="og:description" :content="post.excerpt || post.title" />
        <meta property="og:type" content="article" />
        <meta property="og:url" :content="pageUrl" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="`${post.title} | ${siteName}`" />
        <meta name="twitter:description" :content="post.excerpt || post.title" />
        <link rel="canonical" :href="pageUrl" />
    </Head>

    <Teleport to="head">
        <component :is="'script'" type="application/ld+json" v-html="articleJsonLd" />
    </Teleport>

    <div class="min-h-screen overflow-x-hidden bg-white font-sans text-black selection:bg-brutalist-yellow selection:text-black dark:bg-black dark:text-white">
        <PublicGridBackground />
        <PublicSiteHeader />

        <main id="main-content" class="relative pt-40 pb-24">
            <div class="pointer-events-none absolute -left-32 top-1/4 h-72 w-72 rounded-full bg-brutalist-pink/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -right-32 bottom-1/4 h-80 w-80 rounded-full bg-brutalist-yellow/10 blur-3xl"></div>

            <div class="relative z-10 mx-auto max-w-4xl px-6">
                <!-- Back -->
                <Link :href="route('blog.index')"
                    class="group mb-12 inline-flex items-center gap-3 text-sm font-black uppercase tracking-widest text-zinc-500 transition-colors hover:text-black dark:hover:text-white">
                    <ArrowLeft class="h-5 w-5 transition-transform group-hover:-translate-x-1" />
                    VOLVER AL BLOG
                </Link>

                <!-- Skeleton / Content Transition -->
                <Transition name="fade" mode="out-in">
                    <!-- Skeleton -->
                    <div v-if="!skeletonReady" key="skeleton" class="relative overflow-hidden pointer-events-none select-none">
                        <!-- Badge skeleton -->
                        <div class="mb-6 w-28 h-7 skeleton-bg border-2 border-black dark:border-white"></div>

                        <!-- Title skeleton -->
                        <div class="space-y-3">
                            <div class="h-12 w-full skeleton-bg"></div>
                            <div class="h-12 w-3/4 skeleton-bg"></div>
                            <div class="h-12 w-5/6 skeleton-bg"></div>
                        </div>

                        <!-- Meta skeleton -->
                        <div class="flex flex-wrap items-center gap-6 mt-8">
                            <div class="h-4 w-36 skeleton-bg"></div>
                            <div class="h-4 w-28 skeleton-bg"></div>
                            <div class="flex gap-2">
                                <div class="h-4 w-14 skeleton-bg border-2 border-black/20 dark:border-white/20"></div>
                                <div class="h-4 w-18 skeleton-bg border-2 border-black/20 dark:border-white/20"></div>
                            </div>
                        </div>

                        <!-- Excerpt skeleton -->
                        <div class="mt-12 border-l-8 border-black/10 dark:border-white/10 bg-black/5 dark:bg-white/5 p-6 md:p-8 space-y-3">
                            <div class="h-5 w-full skeleton-bg"></div>
                            <div class="h-5 w-5/6 skeleton-bg"></div>
                            <div class="h-5 w-4/6 skeleton-bg"></div>
                        </div>

                        <!-- Content skeleton -->
                        <div class="mt-12 space-y-3">
                            <div v-for="j in 8" :key="'line-' + j" class="h-4 skeleton-bg" :style="{ width: (70 + Math.random() * 30) + '%' }"></div>
                        </div>

                        <!-- Related posts skeleton -->
                        <div class="mt-24 pt-16 border-t-4 border-black dark:border-white">
                            <div class="mb-10 h-8 w-96 skeleton-bg"></div>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <div v-for="j in 3" :key="'related-' + j" class="border-4 border-black dark:border-white p-6 space-y-3">
                                    <div class="h-3 w-20 skeleton-bg"></div>
                                    <div class="h-4 w-full skeleton-bg"></div>
                                    <div class="h-4 w-4/6 skeleton-bg"></div>
                                    <div class="h-3 w-16 skeleton-bg mt-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute inset-0 shimmer-sweep z-10"></div>
                    </div>

                    <!-- Real Content -->
                    <div v-else key="content">
                        <!-- Post Header -->
                        <div class="mb-16">
                            <Badge class="mb-6 inline-block border-2 border-black dark:border-white bg-transparent px-3 py-1 text-[10px] font-black uppercase tracking-widest">
                                {{ post.category }}
                            </Badge>

                            <h1 class="text-4xl font-display font-black uppercase italic leading-[0.9] tracking-tighter md:text-5xl xl:text-6xl">
                                {{ post.title }}
                            </h1>

                            <div class="flex flex-wrap items-center gap-6 mt-6 text-[11px] font-black uppercase tracking-widest text-neutral-500">
                                <span class="flex items-center gap-2">
                                    <Calendar class="w-4 h-4" />
                                    {{ post.published_at }}
                                </span>
                                <span class="flex items-center gap-2">
                                    <User class="w-4 h-4" />
                                    {{ post.author_name }}
                                </span>
                                <span v-if="readingTime" class="flex items-center gap-2">
                                    <Clock class="w-4 h-4" />
                                    {{ readingTime }} MIN READ
                                </span>
                                <span v-if="post.tags?.length" class="flex items-center gap-2">
                                    <span v-for="tag in post.tags" :key="tag"
                                        class="px-2 py-0.5 border-2 border-black/20 dark:border-white/20 text-[8px] font-black">
                                        {{ tag }}
                                    </span>
                                </span>
                            </div>
                        </div>

                        <!-- Excerpt -->
                        <div v-if="post.excerpt" class="mb-12 border-l-8 border-brutalist-yellow bg-black/5 dark:bg-white/5 p-6 md:p-8">
                            <p class="text-xl font-black uppercase leading-relaxed text-black/80 dark:text-zinc-300">
                                {{ post.excerpt }}
                            </p>
                        </div>

                        <!-- Content (Markdown rendered as HTML) -->
                        <MarkdownPreview :content="post.content" class="mt-12" />

                        <!-- Related Posts -->
                        <div v-if="related.length" class="mt-24 pt-16 border-t-4 border-black dark:border-white">
                            <h2 class="mb-10 text-3xl font-display font-black uppercase italic">
                                ARTÍCULOS <span class="text-brutalist-pink">RELACIONADOS</span>
                            </h2>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <Link v-for="item in related" :key="item.id"
                                    :href="route('blog.show', item.slug)"
                                    class="group border-4 border-black dark:border-white p-6 shadow-brutalist dark:shadow-brutalist-white transition-all hover:-translate-x-1 hover:-translate-y-1 hover:shadow-brutalist-hover dark:hover:shadow-brutalist-white-lg">
                                    <span class="text-[9px] font-black uppercase tracking-widest text-brutalist-pink">{{ item.category }}</span>
                                    <h3 class="mt-2 text-lg font-display font-black uppercase italic leading-tight group-hover:text-brutalist-pink transition-colors">
                                        {{ item.title }}
                                    </h3>
                                    <div class="flex items-center gap-2 mt-4 text-[10px] font-black uppercase tracking-widest text-neutral-500">
                                        LEER
                                        <ArrowRight class="w-3 h-3 transition-transform group-hover:translate-x-1" />
                                    </div>
                                </Link>
                            </div>
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
