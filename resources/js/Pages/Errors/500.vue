<script setup>
import { Link } from '@inertiajs/vue3';
import { Home, AlertTriangle } from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import PublicSiteHeader from '@/Components/PublicSiteHeader.vue';
import PublicSiteFooter from '@/Components/PublicSiteFooter.vue';
</script>

<template>
    <div class="min-h-screen bg-white dark:bg-black text-black dark:text-white font-sans">
        <PublicSiteHeader />

        <main class="flex items-center justify-center min-h-screen px-6 pt-20">
            <div class="max-w-4xl mx-auto text-center">
                <div class="mb-8">
                    <span class="text-[20rem] font-display font-black leading-none text-rose-500/20 select-none">
                        500
                    </span>
                </div>

                <div class="relative -mt-32 space-y-8">
                    <div class="inline-flex items-center gap-3 border-4 border-black bg-rose-600 px-4 py-2 font-black uppercase tracking-[0.2em] text-white shadow-brutalist dark:border-white">
                        <AlertTriangle class="w-5 h-5" />
                        ERROR DEL SERVIDOR
                    </div>

                    <h1 class="text-5xl md:text-7xl font-display font-black uppercase italic leading-[0.85]">
                        ALGO SE<br />
                        <span class="bg-brutalist-yellow px-3 text-black">ROMPIÓ</span><br />
                        EN SERIO
                    </h1>

                    <p class="max-w-2xl mx-auto text-lg font-black uppercase leading-relaxed text-black/60 dark:text-white/60">
                        Nuestro equipo técnico ya está investigando. Vuelve a intentar en unos minutos.
                    </p>

                    <div class="flex flex-wrap justify-center gap-6 pt-8">
                        <Button as-child class="rounded-none border-4 border-black bg-black px-8 py-5 text-xl font-black text-white shadow-brutalist transition-all hover:translate-x-[4px] hover:translate-y-[4px] hover:shadow-brutalist-hover dark:border-white dark:bg-brutalist-yellow dark:text-black">
                            <Link href="/">
                                <Home class="mr-3 h-6 w-6" />
                                INICIO
                            </Link>
                        </Button>
                    </div>
                </div>
            </div>
        </main>

        <PublicSiteFooter />
    </div>
</template>

<style>
.font-display { font-family: 'Space Grotesk', sans-serif; }
</style>
