<script setup>
import { Link } from '@inertiajs/vue3';
import DashboardThemeSwitcher from '@/Components/DashboardThemeSwitcher.vue';
import DashboardLanguageSwitcher from '@/Components/DashboardLanguageSwitcher.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <div class="min-h-screen bg-[#FAFAFC] dark:bg-[#030303] text-slate-900 dark:text-slate-100 font-sans selection:bg-indigo-500 selection:text-white flex flex-col items-center justify-center p-6 relative transition-colors duration-300">
        <!-- Subtle Background Elements (Dashboard Style) -->
        <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-500/5 blur-[120px] dark:bg-indigo-500/10 transition-opacity duration-1000"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-blue-500/5 blur-[120px] dark:bg-blue-500/10 transition-opacity duration-1000"></div>
        </div>

        <div class="fixed top-6 right-6 z-50 flex gap-4">
            <DashboardThemeSwitcher />
            <DashboardLanguageSwitcher />
        </div>

        <div class="relative z-10 w-full max-w-md animate-in fade-in zoom-in-95 duration-500">
            <!-- Logo Master -->
            <div class="flex justify-center mb-8">
                <Link href="/" class="group transition-transform hover:scale-105 active:scale-95">
                    <div class="rounded-3xl border border-slate-200 bg-white/50 px-6 py-5 shadow-xl backdrop-blur-md dark:border-slate-800 dark:bg-black/40">
                        <ApplicationLogo class="w-48 sm:w-56" />
                    </div>
                </Link>
            </div>

            <!-- Dashboard Style Card -->
            <div class="bg-white/70 dark:bg-black/40 backdrop-blur-xl border border-slate-200 dark:border-slate-800 p-8 shadow-2xl rounded-[2.5rem] overflow-hidden">
                <slot />
            </div>

            <!-- Footer decorativo -->
            <div class="mt-10 flex flex-col items-center gap-4 opacity-40">
                <div class="flex items-center gap-6">
                    <div class="h-px w-12 bg-slate-300 dark:bg-slate-700"></div>
                    <p class="font-display text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">
                        NUWESOFT &bull; AUTH GATEWAY
                    </p>
                    <div class="h-px w-12 bg-slate-300 dark:bg-slate-700"></div>
                </div>
                <div class="flex gap-2">
                    <div class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></div>
                    <div class="w-1.5 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600;700&display=swap');
.font-display { font-family: 'Space Grotesk', sans-serif; }
</style>
