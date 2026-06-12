<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ArrowLeft, Home, ShieldOff } from 'lucide-vue-next'
import { Button } from '@/Components/ui/button'
</script>

<template>
    <Head title="403 - Acceso denegado" />

    <div class="relative min-h-screen overflow-hidden bg-white font-sans text-black dark:bg-black dark:text-white">
        <div class="pointer-events-none absolute -left-32 top-1/4 h-96 w-96 rounded-full bg-brutalist-blue/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-32 bottom-1/4 h-96 w-96 rounded-full bg-brutalist-pink/10 blur-3xl"></div>

        <div class="relative z-10 flex min-h-screen flex-col items-center justify-center px-6 text-center">
            <div class="mb-8 flex items-center gap-6">
                <ShieldOff class="h-20 w-20 text-brutalist-pink" />
                <span class="select-none text-[10rem] font-display font-black italic leading-none text-black dark:text-white md:text-[16rem]">403</span>
            </div>

            <div class="mb-8 inline-block border-4 border-brutalist-pink bg-brutalist-pink/10 px-6 py-3">
                <span class="text-sm font-black uppercase tracking-[0.28em] text-brutalist-pink">ACCESO RESTRINGIDO</span>
            </div>

            <p class="mb-12 max-w-xl text-xl font-black uppercase leading-tight text-black/70 dark:text-zinc-300 md:text-2xl">
                No tenés permisos para acceder a esta sección. Si creés que es un error, contactanos.
            </p>

            <div class="flex flex-wrap justify-center gap-6">
                <Button as-child class="group h-auto rounded-none border-4 border-black bg-black px-8 py-5 text-xl font-black text-white shadow-brutalist transition-all hover:translate-x-[4px] hover:translate-y-[4px] hover:bg-brutalist-yellow hover:text-black hover:shadow-brutalist-hover dark:border-white dark:shadow-brutalist-white">
                    <Link href="/">
                        <Home class="mr-3 h-6 w-6" />
                        VOLVER AL INICIO
                    </Link>
                </Button>
                <Button as-child variant="outline" class="group h-auto rounded-none border-4 border-black px-8 py-5 text-xl font-black text-black shadow-none transition-all hover:bg-black hover:text-white dark:border-white dark:text-white dark:hover:bg-white dark:hover:text-black">
                    <Link :href="route('contacto')">
                        CONTACTAR
                        <ArrowLeft class="ml-3 h-6 w-6 rotate-180" />
                    </Link>
                </Button>
            </div>

            <div class="mt-16 flex items-center gap-4 text-[10px] font-black uppercase tracking-[0.3em] text-zinc-400">
                <span class="inline-block h-px w-12 bg-zinc-400"></span>
                <span>ERROR 403</span>
                <span class="inline-block h-px w-12 bg-zinc-400"></span>
            </div>
        </div>
    </div>
</template>

<style>
.font-display { font-family: 'Space Grotesk', sans-serif; }
</style>
