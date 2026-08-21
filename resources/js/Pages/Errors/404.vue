<script setup>
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { Home, ArrowLeft, Search } from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import PublicSiteHeader from '@/Components/PublicSiteHeader.vue';
import PublicSiteFooter from '@/Components/PublicSiteFooter.vue';

const { t } = useI18n();
</script>

<template>
    <div class="min-h-screen bg-white dark:bg-black text-black dark:text-white font-sans">
        <PublicSiteHeader />

        <main class="flex items-center justify-center min-h-screen px-6 pt-20">
            <div class="max-w-4xl mx-auto text-center">
                <div class="mb-8">
                    <span class="text-[20rem] font-display font-black leading-none text-brutalist-yellow/30 dark:text-brutalist-pink/20 select-none">
                        404
                    </span>
                </div>

                <div class="relative -mt-32 space-y-8">
                    <div class="inline-flex items-center gap-3 border-4 border-black bg-brutalist-pink px-4 py-2 font-black uppercase tracking-[0.2em] text-white shadow-brutalist dark:border-white">
                        <Search class="w-5 h-5" />
                        PÁGINA NO ENCONTRADA
                    </div>

                    <h1 class="text-5xl md:text-7xl font-display font-black uppercase italic leading-[0.85]">
                        SE NOS<br />
                        <span class="bg-brutalist-yellow px-3 text-black">FILTRÓ</span><br />
                        ESTA RUTA
                    </h1>

                    <p class="max-w-2xl mx-auto text-lg font-black uppercase leading-relaxed text-black/60 dark:text-white/60">
                        La página que buscas no existe, fue movida o nunca estuvo aquí. Pero tenemos mucho más que mostrarte.
                    </p>

                    <div class="flex flex-wrap justify-center gap-6 pt-8">
                        <Button as-child class="rounded-none border-4 border-black bg-black px-8 py-5 text-xl font-black text-white shadow-brutalist transition-all hover:translate-x-[4px] hover:translate-y-[4px] hover:shadow-brutalist-hover dark:border-white dark:bg-brutalist-yellow dark:text-black">
                            <Link href="/">
                                <Home class="mr-3 h-6 w-6" />
                                INICIO
                            </Link>
                        </Button>
                        <Button as-child variant="outline" class="rounded-none border-4 border-black bg-transparent px-8 py-5 text-xl font-black text-black shadow-brutalist transition-all hover:bg-brutalist-yellow hover:text-black dark:border-white dark:text-white">
                            <Link href="/portafolio">
                                PORTAFOLIO
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
