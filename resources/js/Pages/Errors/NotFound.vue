<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ArrowLeft, Home } from 'lucide-vue-next'
import { Button } from '@/Components/ui/button'

const status = 404
</script>

<template>
    <Head title="404 - Página no encontrada" />

    <div class="relative min-h-screen overflow-hidden bg-white font-sans text-black dark:bg-black dark:text-white">
        <!-- Decorative blobs -->
        <div class="pointer-events-none absolute -left-32 top-1/4 h-96 w-96 rounded-full bg-brutalist-pink/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-32 bottom-1/4 h-96 w-96 rounded-full bg-brutalist-yellow/10 blur-3xl"></div>

        <div class="relative z-10 flex min-h-screen flex-col items-center justify-center px-6 text-center">
            <!-- Massive 404 -->
            <div class="relative mb-8">
                <span class="select-none text-[12rem] font-display font-black italic leading-none text-black/5 dark:text-white/5 md:text-[20rem]">404</span>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="border-8 border-black bg-brutalist-pink px-8 py-4 text-4xl font-black uppercase italic text-white shadow-brutalist dark:border-white dark:shadow-brutalist-white md:text-6xl">
                        ¡UPS!
                    </span>
                </div>
            </div>

            <p class="mb-12 max-w-xl text-xl font-black uppercase leading-tight text-black/70 dark:text-zinc-300 md:text-2xl">
                Esta página no existe o fue movida. Hasta los mejores servidores se pierden a veces.
            </p>

            <div class="flex flex-wrap justify-center gap-6">
                <Button as-child class="group h-auto rounded-none border-4 border-black bg-black px-8 py-5 text-xl font-black text-white shadow-brutalist transition-all hover:translate-x-[4px] hover:translate-y-[4px] hover:bg-brutalist-yellow hover:text-black hover:shadow-brutalist-hover dark:border-white dark:shadow-brutalist-white">
                    <Link href="/">
                        <Home class="mr-3 h-6 w-6" />
                        VOLVER AL INICIO
                    </Link>
                </Button>
                <Button as-child variant="outline" class="group h-auto rounded-none border-4 border-black px-8 py-5 text-xl font-black text-black shadow-none transition-all hover:bg-black hover:text-white dark:border-white dark:text-white dark:hover:bg-white dark:hover:text-black">
                    <Link href="javascript:history.back()">
                        <ArrowLeft class="mr-3 h-6 w-6" />
                        PÁGINA ANTERIOR
                    </Link>
                </Button>
            </div>

            <!-- Brutalist decoration -->
            <div class="mt-16 flex items-center gap-4 text-[10px] font-black uppercase tracking-[0.3em] text-zinc-400">
                <span class="inline-block h-px w-12 bg-zinc-400"></span>
                <span>ERROR 404</span>
                <span class="inline-block h-px w-12 bg-zinc-400"></span>
            </div>
        </div>
    </div>
</template>

<style>
.font-display { font-family: 'Space Grotesk', sans-serif; }
</style>
