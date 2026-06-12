<script setup>
import { Link } from '@inertiajs/vue3';
import DashboardThemeSwitcher from '@/Components/DashboardThemeSwitcher.vue';
import DashboardLanguageSwitcher from '@/Components/DashboardLanguageSwitcher.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:bg-black focus:text-white focus:px-6 focus:py-3 focus:text-sm focus:font-black focus:uppercase focus:tracking-widest focus:border-4 focus:border-white focus:outline-none">
        Saltar al contenido
    </a>
    <div id="main-content" class="min-h-screen bg-neutral-50 dark:bg-black text-neutral-900 dark:text-white font-sans selection:bg-black selection:text-white dark:selection:bg-white dark:selection:text-black flex flex-col items-center justify-center p-6 relative transition-colors duration-300">
        <!-- Background blobs -->
        <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-black/5 blur-[120px] dark:bg-white/5"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-black/5 blur-[120px] dark:bg-white/5"></div>
        </div>

        <!-- Theme & Lang switchers -->
        <div class="fixed top-6 right-6 z-50 flex gap-3">
            <DashboardThemeSwitcher />
            <DashboardLanguageSwitcher />
        </div>

        <div class="relative z-10 w-full max-w-md animate-in fade-in zoom-in-95 duration-500">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <Link href="/" class="group transition-transform hover:scale-105 active:scale-95">
                    <div class="rounded-3xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-6 py-5 shadow-xl">
                        <ApplicationLogo class="w-48 sm:w-56" />
                    </div>
                </Link>
            </div>

            <!-- Card with Page Transition -->
            <Transition name="page" mode="out-in">
                <div :key="$page.url" class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 p-8 shadow-2xl rounded-[2.5rem] overflow-hidden">
                    <slot />
                </div>
            </Transition>

            <!-- Footer decorativo -->
            <div class="mt-8 flex flex-col items-center gap-3 opacity-30">
                <div class="flex items-center gap-4">
                    <div class="h-px w-12 bg-neutral-300 dark:bg-neutral-700"></div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-neutral-500">
                        NUWESOFT &bull; AUTH GATEWAY
                    </p>
                    <div class="h-px w-12 bg-neutral-300 dark:bg-neutral-700"></div>
                </div>
            </div>
        </div>
    </div>
</template>
