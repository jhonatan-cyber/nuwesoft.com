<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import PublicGridBackground from '@/Components/PublicGridBackground.vue'
import PublicSiteHeader from '@/Components/PublicSiteHeader.vue'
import PublicSiteFooter from '@/Components/PublicSiteFooter.vue'
import HeroSection from '@/Components/HeroSection.vue'
import MarqueeBar from '@/Components/MarqueeBar.vue'
import ManifestoSection from '@/Components/ManifestoSection.vue'
import ServicesPreviewSection from '@/Components/ServicesPreviewSection.vue'
import CTASection from '@/Components/CTASection.vue'
import TestimonialsSection from '@/Components/TestimonialsSection.vue'
import FeatureFlag from '@/Components/FeatureFlag.vue'
import { usePageTracking } from '@/composables/usePageTracking'
import { useSkeletonLoader } from '@/composables/useSkeletonLoader'

const page = usePage()

usePageTracking()
const { skeletonReady } = useSkeletonLoader()
const siteName = computed(() => page.props.settings?.site_name || 'NUWESOFT')
const pageUrl = computed(() => window.location.href)
</script>

<template>
    <Head :title="`${siteName} | Nuwesoft`">
        <meta name="description" :content="siteName" />
        <meta property="og:title" :content="`${siteName} | Nuwesoft`" />
        <meta property="og:description" :content="siteName" />
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="pageUrl" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="`${siteName} | Nuwesoft`" />
        <meta name="twitter:description" :content="siteName" />
        <link rel="canonical" :href="pageUrl" />
    </Head>

    <div id="main-content" class="min-h-screen overflow-x-hidden bg-white font-sans text-black selection:bg-brutalist-yellow selection:text-black dark:bg-black dark:text-white">
        <PublicGridBackground />
        <PublicSiteHeader />

        <Transition name="fade" mode="out-in">
            <div v-if="!skeletonReady" key="skeleton" class="relative overflow-hidden pointer-events-none select-none">
                <!-- Hero skeleton -->
                <div class="relative pt-48 pb-24 md:pt-56 md:pb-32 px-6">
                    <div class="max-w-[1400px] mx-auto grid gap-12 lg:grid-cols-2">
                        <div class="space-y-6">
                            <div class="h-6 w-32 skeleton-bg"></div>
                            <div class="h-24 w-full skeleton-bg"></div>
                            <div class="h-24 w-4/5 skeleton-bg"></div>
                            <div class="h-4 w-3/4 skeleton-bg"></div>
                            <div class="h-4 w-2/3 skeleton-bg"></div>
                            <div class="h-14 w-48 skeleton-bg"></div>
                        </div>
                        <div class="space-y-6">
                            <div class="h-40 w-full skeleton-bg"></div>
                            <div class="grid grid-cols-3 gap-4">
                                <div v-for="i in 3" :key="i" class="h-24 skeleton-bg"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="absolute inset-0 shimmer-sweep z-10"></div>
            </div>

            <div v-else key="content">
                <HeroSection />
                <MarqueeBar />
                <ManifestoSection />
                <ServicesPreviewSection />
                <CTASection />
                <TestimonialsSection />

                <FeatureFlag flag="show_beta_banner">
                    <div class="relative z-10 mx-auto max-w-[1400px] px-6 py-8">
                        <div class="border-4 border-brutalist-pink bg-brutalist-pink/5 p-6 text-center">
                            <div class="inline-flex items-center gap-3 px-4 py-2 border-2 border-brutalist-pink bg-brutalist-pink/10 mb-3">
                                <span class="h-2 w-2 rounded-full bg-brutalist-pink animate-pulse"></span>
                                <span class="text-[10px] font-black uppercase tracking-[0.28em] text-brutalist-pink">BETA</span>
                            </div>
                            <p class="text-lg font-black uppercase italic text-black dark:text-white">
                                Estamos en beta cerrada. Desbloqueá funcionalidades experimentales activando el flag <code class="px-2 py-0.5 bg-black/10 dark:bg-white/10 font-mono">show_beta_banner</code> en PostHog.
                            </p>
                        </div>
                    </div>
                </FeatureFlag>
            </div>
        </Transition>

        <PublicSiteFooter />
    </div>
</template>
