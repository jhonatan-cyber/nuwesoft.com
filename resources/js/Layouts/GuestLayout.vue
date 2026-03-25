<script setup>
import { Link } from '@inertiajs/vue3';
import ThemeSwitcher from '@/Components/ThemeSwitcher.vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
</script>

<template>
    <div class="min-h-screen bg-white dark:bg-black text-black dark:text-white font-sans selection:bg-brutalist-yellow selection:text-black flex flex-col items-center justify-center p-6 relative">
        <!-- Grid Background -->
        <div class="fixed inset-0 pointer-events-none opacity-[0.05] bg-[linear-gradient(to_right,#808080_1px,transparent_1px),linear-gradient(to_bottom,#808080_1px,transparent_1px)] bg-[size:40px_40px] z-0"></div>

        <div class="fixed top-6 right-6 z-50 flex gap-4">
            <ThemeSwitcher />
            <LanguageSwitcher />
        </div>

        <div class="relative z-10 w-full max-w-lg">
            <!-- Logo Master -->
            <div class="flex justify-center mb-12">
                <Link href="/" class="group flex items-center space-x-3">
                    <div class="w-16 h-16 bg-brutalist-yellow border-8 border-black dark:border-white flex items-center justify-center transform group-hover:-rotate-12 transition-transform shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] dark:shadow-[8px_8px_0px_0px_rgba(255,255,255,1)]">
                        <span class="text-4xl font-black text-black tracking-tighter italic">N</span>
                    </div>
                </Link>
            </div>

            <!-- Card Brutalista -->
            <div class="bg-white dark:bg-zinc-900 border-8 border-black dark:border-white p-8 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] dark:shadow-[12px_12px_0px_0px_rgba(255,255,255,1)]">
                <slot />
            </div>

            <!-- Footer decorativo -->
            <div class="mt-12 flex justify-between items-center opacity-50">
                <p class="font-mono text-xs font-black uppercase tracking-tighter">
                    © 2026 NUWESOFT // AUTH_GATEWAY
                </p>
                <div class="flex gap-2 text-black dark:text-white">
                    <div class="w-2 h-2 bg-current rounded-full"></div>
                    <div class="w-2 h-2 bg-brutalist-pink rounded-full"></div>
                </div>
            </div>
        </div>
    </div>
</template>
