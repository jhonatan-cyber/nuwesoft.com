<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { usePostHog } from '@/composables/usePostHog';
import { usePageTracking } from '@/composables/usePageTracking';
import { useSkeletonLoader } from '@/composables/useSkeletonLoader';
import PublicGridBackground from '@/Components/PublicGridBackground.vue';
import PublicSiteHeader from '@/Components/PublicSiteHeader.vue';
import PublicSiteFooter from '@/Components/PublicSiteFooter.vue';
import ContactHero from '@/Components/ContactHero.vue';
import ContactInfo from '@/Components/ContactInfo.vue';
import ContactForm from '@/Components/ContactForm.vue';

const { t } = useI18n();
const { skeletonReady } = useSkeletonLoader();

const page = usePage();
const { capture } = usePostHog();
const settings = computed(() => page.props.settings || {});
const siteName = computed(() => settings.value.site_name || 'NUWESOFT');
const pageTitle = computed(() => t('contacto.head_title').replace('NUWESOFT', siteName.value));
const pageUrl = computed(() => window.location.href);
const pageDesc = computed(() => t('contacto.subtitle'));
const antiSpamToken = computed(() => page.props.anti_spam_token || '');

onMounted(() => {
    // Track contact page visit as conversion step in A/B funnel
    capture('contacto_page_view', {
        referrer: document.referrer || 'direct',
        source: window.location.href.includes('ref=') ? 'ad' : 'organic',
    });
});
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
    <div
        class="min-h-screen overflow-x-hidden bg-white font-sans text-black selection:bg-brutalist-yellow selection:text-black dark:bg-black dark:text-white"
    >
        <PublicGridBackground />
        <PublicSiteHeader />

        <main id="main-content" class="relative z-10">
            <Transition name="fade" mode="out-in">
                <!-- ═══ SKELETON ═══ -->
                <div v-if="!skeletonReady" key="skeleton" class="relative overflow-hidden pointer-events-none select-none">
                    <!-- Hero skeleton -->
                    <section class="relative overflow-hidden border-b-8 border-black bg-brutalist-yellow px-6 pt-20 pb-10 text-black dark:border-white dark:bg-brutalist-blue dark:text-white">
                        <div class="mx-auto max-w-[1400px]">
                            <div class="mb-4 mt-4 md:mt-6">
                                <div class="h-8 w-40 skeleton-bg border-4 border-black dark:border-white"></div>
                            </div>
                            <div class="space-y-3">
                                <div class="h-24 w-full skeleton-bg md:h-32"></div>
                                <div class="h-24 w-5/6 skeleton-bg md:h-32"></div>
                                <div class="h-24 w-4/6 skeleton-bg md:h-32"></div>
                            </div>
                            <div class="mt-4 h-8 w-3/4 skeleton-bg md:h-10"></div>
                        </div>
                    </section>

                    <!-- Grid skeleton -->
                    <section class="mx-auto grid max-w-[1400px] grid-cols-1 gap-12 px-6 py-24 lg:grid-cols-12">
                        <!-- Left: Info blocks skeleton (4 items) -->
                        <div class="lg:col-span-5 space-y-8">
                            <div class="border-4 border-black bg-white p-10 shadow-brutalist dark:border-white dark:bg-black dark:shadow-brutalist-white">
                                <div class="mb-10 h-10 w-48 skeleton-bg"></div>
                                <div v-for="j in 4" :key="'info-' + j" class="mb-8 flex items-start gap-6">
                                    <div class="h-12 w-12 shrink-0 skeleton-bg border-4 border-black dark:border-white"></div>
                                    <div class="flex-1 space-y-2">
                                        <div class="h-3 w-24 skeleton-bg"></div>
                                        <div class="h-6 w-44 skeleton-bg"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Form skeleton -->
                        <div class="lg:col-span-7">
                            <div class="border-4 border-black shadow-brutalist dark:border-white dark:shadow-brutalist-white">
                                <div class="border-b-4 border-black bg-black p-10 text-white dark:border-white dark:bg-white">
                                    <div class="h-12 w-3/4 skeleton-bg bg-white/20 dark:bg-black/20"></div>
                                    <div class="mt-4 h-6 w-1/2 skeleton-bg bg-white/20 dark:bg-black/20"></div>
                                </div>
                                <div class="space-y-8 p-10">
                                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                                        <div class="space-y-4">
                                            <div class="h-5 w-32 skeleton-bg"></div>
                                            <div class="h-16 w-full skeleton-bg border-4 border-black dark:border-white"></div>
                                        </div>
                                        <div class="space-y-4">
                                            <div class="h-5 w-32 skeleton-bg"></div>
                                            <div class="h-16 w-full skeleton-bg border-4 border-black dark:border-white"></div>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="h-5 w-32 skeleton-bg"></div>
                                        <div class="h-40 w-full skeleton-bg border-4 border-black dark:border-white"></div>
                                    </div>
                                    <div class="h-20 w-full skeleton-bg border-4 border-black dark:border-white"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="absolute inset-0 shimmer-sweep z-10"></div>
                </div>

                <!-- ═══ REAL CONTENT ═══ -->
                <div v-else key="content">
                    <ContactHero />

                    <!-- Contact Grid -->
                    <section
                        class="mx-auto grid max-w-[1400px] grid-cols-1 gap-12 px-6 py-24 lg:grid-cols-12"
                    >
                        <div class="lg:col-span-5">
                            <ContactInfo />
                        </div>
                        <div class="lg:col-span-7">
                            <ContactForm :anti-spam-token="antiSpamToken" />
                        </div>
                    </section>
                </div>
            </Transition>
        </main>

        <PublicSiteFooter />
    </div>
</template>

<style>
.font-display { font-family: 'Space Grotesk', sans-serif; }
body {
    @apply bg-white dark:bg-black transition-colors duration-500;
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}
</style>
