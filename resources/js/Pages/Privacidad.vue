<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { usePageTracking } from '@/composables/usePageTracking'
import { useSkeletonLoader } from '@/composables/useSkeletonLoader'
import PublicGridBackground from '@/Components/PublicGridBackground.vue'
import PublicSiteHeader from '@/Components/PublicSiteHeader.vue'
import PublicSiteFooter from '@/Components/PublicSiteFooter.vue'
import { ArrowLeft } from 'lucide-vue-next'

usePageTracking()
const { t } = useI18n()
const { skeletonReady } = useSkeletonLoader()

const page = usePage()
const settings = computed(() => page.props.settings || {})
const siteName = computed(() => settings.value.site_name || 'NUWESOFT')
const pageTitle = computed(() => `${siteName.value} | ${t('privacy_page.title')}`)
const pageUrl = computed(() => window.location.href)
const pageDesc = computed(() => t('privacy_page.intro'))

const sections = [
    { title: 'privacy_page.s1_title', body: 'privacy_page.s1_body' },
    { title: 'privacy_page.s2_title', body: 'privacy_page.s2_body' },
    { title: 'privacy_page.s3_title', body: 'privacy_page.s3_body' },
    { title: 'privacy_page.s4_title', body: 'privacy_page.s4_body' },
    { title: 'privacy_page.s5_title', body: 'privacy_page.s5_body' },
    { title: 'privacy_page.s6_title', body: 'privacy_page.s6_body' },
    { title: 'privacy_page.s7_title', body: 'privacy_page.s7_body' },
]
</script>

<template>
    <Head :title="pageTitle">
        <meta name="description" :content="pageDesc" />
        <meta property="og:title" :content="pageTitle" />
        <meta property="og:description" :content="pageDesc" />
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="pageUrl" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="pageTitle" />
        <meta name="twitter:description" :content="pageDesc" />
        <link rel="canonical" :href="pageUrl" />
    </Head>

    <div class="min-h-screen overflow-x-hidden bg-white font-sans text-black selection:bg-brutalist-yellow selection:text-black dark:bg-black dark:text-white">
        <PublicGridBackground />
        <PublicSiteHeader />

        <!-- Page Content -->
        <main id="main-content" class="relative py-40 md:py-48">
            <!-- Decorative blobs -->
            <div class="pointer-events-none absolute left-0 top-1/4 h-72 w-72 rounded-full bg-brutalist-blue/10 blur-3xl"></div>
            <div class="pointer-events-none absolute bottom-1/4 right-0 h-96 w-96 rounded-full bg-brutalist-yellow/10 blur-3xl"></div>

            <div class="relative z-10 mx-auto max-w-4xl px-6">
                <!-- Back link (always visible for navigation context) -->
                <Link :href="route('home')" class="group mb-12 inline-flex items-center gap-3 text-sm font-black uppercase tracking-widest text-zinc-500 transition-colors hover:text-black dark:text-zinc-500 dark:hover:text-white">
                    <ArrowLeft class="h-5 w-5 transition-transform group-hover:-translate-x-1" />
                    VOLVER
                </Link>

                <Transition name="fade" mode="out-in">
                    <!-- ═══ SKELETON ═══ -->
                    <div v-if="!skeletonReady" key="skeleton" class="relative overflow-hidden pointer-events-none select-none">
                        <!-- Title skeleton -->
                        <div class="mb-16 space-y-6">
                            <div class="h-20 w-full skeleton-bg md:h-28"></div>
                            <div class="h-20 w-3/4 skeleton-bg md:h-28"></div>
                            <div class="h-6 max-w-md skeleton-bg"></div>
                            <div class="h-8 w-48 skeleton-bg border-4 border-black dark:border-white"></div>
                        </div>

                        <!-- Intro skeleton -->
                        <div class="mb-16 border-l-8 border-brutalist-yellow bg-black/5 p-6 dark:bg-white/5 md:p-10">
                            <div class="h-6 w-full skeleton-bg"></div>
                            <div class="mt-3 h-6 w-5/6 skeleton-bg"></div>
                            <div class="mt-3 h-6 w-4/6 skeleton-bg"></div>
                        </div>

                        <!-- Sections skeleton (3 blocks) -->
                        <div class="space-y-16">
                            <div v-for="i in 3" :key="'sec-' + i">
                                <div class="mb-6 h-10 w-72 skeleton-bg"></div>
                                <div class="h-5 w-full skeleton-bg"></div>
                                <div class="mt-2 h-5 w-5/6 skeleton-bg"></div>
                                <div class="mt-2 h-5 w-4/6 skeleton-bg"></div>
                                <div class="mt-2 h-5 w-3/6 skeleton-bg"></div>
                            </div>
                        </div>

                        <div class="absolute inset-0 shimmer-sweep z-10"></div>
                    </div>

                    <!-- ═══ REAL CONTENT ═══ -->
                    <div v-else key="content">
                        <!-- Title block -->
                        <div class="mb-16">
                            <h1 class="mb-6 text-5xl font-display font-black uppercase italic leading-[0.85] tracking-tighter text-black dark:text-white md:text-7xl xl:text-8xl">
                                {{ t('privacy_page.title') }}
                            </h1>
                            <p class="mb-6 max-w-3xl text-xl font-black uppercase leading-tight text-black/70 dark:text-zinc-300 md:text-2xl">
                                {{ t('privacy_page.subtitle') }}
                            </p>
                            <span class="inline-block border-4 border-black bg-black px-4 py-2 text-xs font-black uppercase tracking-[0.28em] text-white shadow-brutalist dark:border-white dark:bg-white dark:text-black">
                                {{ t('privacy_page.effective_date') }}
                            </span>
                        </div>

                        <!-- Intro -->
                        <div class="mb-16 border-l-8 border-brutalist-yellow bg-black/5 p-6 dark:bg-white/5 md:p-10">
                            <p class="text-lg font-black uppercase leading-relaxed text-black/80 dark:text-zinc-300 md:text-xl">
                                {{ t('privacy_page.intro') }}
                            </p>
                        </div>

                        <!-- Sections -->
                        <div class="space-y-16">
                            <article
                                v-for="section in sections"
                                :key="section.title"
                                class="group"
                            >
                                <h2 class="mb-6 inline-block text-2xl font-display font-black uppercase italic leading-tight text-black dark:text-white md:text-3xl">
                                    {{ t(section.title) }}
                                    <span class="block h-1.5 w-0 bg-brutalist-yellow transition-all duration-500 group-hover:w-full"></span>
                                </h2>
                                <p class="text-base font-black uppercase leading-relaxed text-black/70 dark:text-zinc-400 md:text-lg">
                                    {{ t(section.body) }}
                                </p>
                            </article>
                        </div>
                    </div>
                </Transition>
            </div>
        </main>

        <PublicSiteFooter />
    </div>
</template>
