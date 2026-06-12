<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';
import PublicGridBackground from '@/Components/PublicGridBackground.vue';
import PublicSiteHeader from '@/Components/PublicSiteHeader.vue';
import PublicSiteFooter from '@/Components/PublicSiteFooter.vue';
import { Construction, RefreshCw } from 'lucide-vue-next';

const page = usePage();
const settings = computed(() => page.props.settings || {});
const siteName = computed(() => settings.value.site_name || 'NUWESOFT');

const countdown = ref(30);
const isCounting = ref(false);

const startCountdown = () => {
    isCounting.value = true;
    countdown.value = 30;
    const interval = setInterval(() => {
        countdown.value--;
        if (countdown.value <= 0) {
            clearInterval(interval);
            isCounting.value = false;
            window.location.reload();
        }
    }, 1000);
};
</script>

<template>
    <Head :title="`Mantenimiento · ${siteName}`">
        <meta name="robots" content="noindex" />
    </Head>

    <div class="min-h-screen overflow-x-hidden bg-white font-sans text-black selection:bg-brutalist-yellow selection:text-black dark:bg-black dark:text-white">
        <PublicGridBackground />
        <PublicSiteHeader />

        <main class="relative z-10 flex min-h-[calc(100vh-12rem)] items-center justify-center px-6 py-32">
            <!-- Decorative elements -->
            <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
                <div class="absolute right-[10%] top-20 h-64 w-64 rounded-full bg-brutalist-blue/10 blur-3xl"></div>
                <div class="absolute left-[15%] bottom-32 h-80 w-80 rounded-full bg-brutalist-yellow/10 blur-3xl"></div>
                <div class="absolute left-1/3 top-1/2 h-24 w-24 rotate-45 border-4 border-black/10 dark:border-white/10"></div>
            </div>

            <div class="relative z-10 mx-auto max-w-2xl text-center">
                <!-- Icon -->
                <div class="mb-8 flex justify-center">
                    <div class="inline-flex h-24 w-24 items-center justify-center border-4 border-black bg-brutalist-yellow shadow-brutalist dark:border-white dark:shadow-brutalist-white">
                        <Construction class="h-12 w-12 text-black" />
                    </div>
                </div>

                <!-- Badge -->
                <div class="mb-8 flex items-center justify-center gap-4">
                    <span class="inline-flex h-4 w-4 rotate-45 border-2 border-black bg-brutalist-blue dark:border-white"></span>
                    <span class="inline-block border-4 border-black bg-black px-5 py-2 text-sm font-black uppercase tracking-[0.28em] text-white shadow-brutalist dark:border-white dark:bg-white dark:text-black">
                        MANTENIMIENTO PROGRAMADO
                    </span>
                    <span class="inline-flex h-4 w-4 rotate-45 border-2 border-black bg-brutalist-blue dark:border-white"></span>
                </div>

                <!-- Message -->
                <h1 class="mb-6 text-6xl font-display font-black uppercase italic leading-[0.85] tracking-tighter md:text-8xl">
                    VOLVEMOS PRONTO
                </h1>
                <p class="mx-auto mb-10 max-w-lg text-xl font-black uppercase italic leading-tight text-black/60 dark:text-white/60">
                    ESTAMOS REALIZANDO MEJORAS EN NUESTRA INFRAESTRUCTURA. EL SITIO ESTARÁ DISPONIBLE EN BREVE.
                </p>

                <!-- Refresh button -->
                <button
                    @click="startCountdown"
                    :disabled="isCounting"
                    class="group inline-flex items-center gap-3 border-4 border-black bg-black px-8 py-4 text-sm font-black uppercase tracking-[0.2em] text-white shadow-brutalist transition-all hover:-translate-y-1 hover:shadow-brutalist-hover disabled:cursor-not-allowed disabled:opacity-50 dark:border-white dark:bg-white dark:text-black dark:shadow-brutalist-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2"
                >
                    <RefreshCw class="h-5 w-5 transition-transform group-hover:rotate-180" :class="{ 'animate-spin': isCounting }" />
                    <span v-if="isCounting">REINTENTANDO EN {{ countdown }}s</span>
                    <span v-else>REINTENTAR</span>
                </button>

                <!-- Decorative line -->
                <div class="mx-auto mt-16 flex items-center justify-center gap-4">
                    <span class="inline-block h-px w-12 bg-black/20 dark:bg-white/20"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-black/20 dark:text-white/20">503</span>
                    <span class="inline-block h-px w-12 bg-black/20 dark:bg-white/20"></span>
                </div>
            </div>
        </main>

        <PublicSiteFooter />
    </div>
</template>

<style>
.font-display { font-family: 'Space Grotesk', sans-serif; }
body {
    @apply bg-white dark:bg-black transition-colors duration-500;
}
</style>
