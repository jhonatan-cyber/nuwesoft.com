<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { useInView } from '@/composables/useInView';
import BlurImage from '@/Components/BlurImage.vue';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/Components/ui/tooltip';
import { ArrowLeft, ArrowUpRight, Eye, Layers } from 'lucide-vue-next';

defineProps({
    project: { type: Object, required: true },
    allImages: { type: Array, default: () => [] },
});

const { t } = useI18n();
const selectedImageIndex = ref(0);
const { el: heroRef, isVisible: heroVisible } = useInView(0.05);
const categoryColors = {
    web: 'bg-brutalist-pink',
    mobile: 'bg-brutalist-blue',
    cloud: 'bg-brutalist-yellow',
    automation: 'bg-brutalist-pink',
};
</script>

<template>
<!-- ═══ Hero ═══ -->
<section ref="heroRef" class="px-6 pb-24 relative overflow-hidden">
    <!-- Decorative blobs -->
    <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
        <div class="absolute -left-16 top-32 w-72 h-72 rounded-full bg-brutalist-yellow/10 blur-3xl"></div>
        <div class="absolute -right-16 top-64 w-96 h-96 rounded-full bg-brutalist-pink/10 blur-3xl"></div>
    </div>

    <div class="max-w-[1400px] mx-auto relative z-10">
        <!-- Back link -->
        <div
            :class="['flex justify-end transition-all duration-700', heroVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0']"
        >
            <Link :href="route('portafolio')" class="inline-flex items-center gap-2 border-2 border-black px-4 py-2 text-xs font-black uppercase tracking-[0.2em] transition-all hover:bg-black hover:text-white dark:border-white dark:hover:bg-white dark:hover:text-black">
                <ArrowLeft class="w-4 h-4" />
                {{ t('portafolio.back') || 'VOLVER AL PORTAFOLIO' }}
            </Link>
        </div>

        <div class="mt-8 grid gap-12 lg:grid-cols-[1.1fr_0.9fr] lg:items-start">
            <!-- Left: Main image -->
            <div
                :class="['transition-all duration-700 delay-100', heroVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0']"
            >
                <div class="relative border-4 border-black shadow-brutalist dark:border-white dark:shadow-brutalist-white overflow-hidden">
                    <BlurImage
                        v-if="allImages.length > 0"
                        :src="allImages[selectedImageIndex].image_url"
                        :alt="project.name"
                        fullRes
                        class="aspect-[8/5] w-full bg-zinc-950"
                        img-class="h-full w-full !object-contain object-center transition-transform duration-500"
                    />
                    <div
                        v-else
                        class="w-full h-[24rem] md:h-[32rem] flex items-center justify-center bg-zinc-100 dark:bg-zinc-900"
                    >
                        <Layers class="w-20 h-20 text-black/10 dark:text-white/10" />
                    </div>

                    <!-- Image count badge -->
                    <div
                        v-if="allImages.length > 1"
                        class="absolute bottom-4 left-4 z-20 border-2 border-black bg-white px-3 py-1.5 text-[11px] font-black uppercase tracking-[0.18em] text-black shadow-[3px_3px_0_#000]"
                    >
                        <span class="flex items-center gap-1.5 whitespace-nowrap text-black">
                            <Eye class="w-3.5 h-3.5 shrink-0 text-black" />
                            {{ allImages.length }} {{ t('portafolio.images_label') }}
                        </span>
                    </div>
                </div>

                <!-- Thumbnail strip -->
                <div v-if="allImages.length > 1" class="mt-4 grid grid-cols-5 gap-3">
                    <button
                        v-for="(image, idx) in allImages"
                        :key="image.id"
                        type="button"
                        @click="selectedImageIndex = idx"
                        :aria-label="`Mostrar imagen ${idx + 1} de ${allImages.length}`"
                        :aria-pressed="idx === selectedImageIndex"
                        class="border-4 overflow-hidden transition-all duration-200 hover:-translate-y-1"
                        :class="idx === selectedImageIndex ? 'border-brutalist-pink dark:border-brutalist-yellow -translate-y-1' : 'border-black/30 dark:border-white/30 hover:border-black dark:hover:border-white'"
                    >
                        <BlurImage :src="image.image_url" :alt="project.name" :width="250" :height="180" class="h-20 w-full" />
                    </button>
                </div>
            </div>

            <!-- Right: Project info -->
            <div
                :class="['transition-all duration-700 delay-200', heroVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0']"
            >
                <!-- Category badge -->
                <div class="mb-6 flex items-center gap-4">
                    <span class="inline-flex h-4 w-4 rotate-45 border-2 border-black dark:border-white" :class="categoryColors[project.category] || 'bg-black'"></span>
                    <Badge class="border-4 border-black bg-white px-4 py-2 text-xs font-black uppercase tracking-[0.24em] shadow-brutalist dark:border-white dark:bg-zinc-900 dark:text-white">
                        {{ project.category }}
                    </Badge>
                    <span class="h-px flex-1 bg-black/20 dark:bg-white/20"></span>
                </div>

                <!-- Title -->
                <h1 class="text-[clamp(2.5rem,5vw,4.5rem)] font-display font-black uppercase italic leading-[0.9] tracking-tighter">
                    {{ project.name }}
                </h1>

                <!-- Description -->
                <p class="mt-8 text-lg font-black uppercase italic leading-relaxed text-black/80 dark:text-white/80">
                    {{ project.desc }}
                </p>

                <!-- Tech Stack -->
                <div class="mt-10">
                    <div class="mb-4 text-[11px] font-black uppercase tracking-[0.28em] opacity-50 flex items-center gap-3">
                        <span class="inline-block w-6 h-px bg-current"></span>
                        {{ t('portafolio.tech_stack_label') || 'TECH STACK' }}
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <TooltipProvider :delay-duration="0">
                            <Tooltip v-for="tech in project.technologies" :key="tech.id">
                                <TooltipTrigger as-child>
                                    <span class="border-2 border-black dark:border-white bg-transparent px-3 py-1.5 text-[10px] font-black uppercase tracking-[0.2em] text-black dark:text-white flex items-center justify-center w-16 h-11">
                                        <img v-if="tech.logo_url" :src="tech.logo_url" class="w-full h-full object-contain" />
                                        <span v-else class="text-[9px] truncate max-w-[50px]">{{ tech.name }}</span>
                                    </span>
                                </TooltipTrigger>
                                <TooltipContent side="top" :side-offset="8">
                                    {{ tech.name }}
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-10 flex flex-wrap gap-4">
                    <a v-if="project.project_url" :href="project.project_url" target="_blank">
                        <Button class="bg-black text-white font-black border-4 border-black px-8 py-4 text-base rounded-none shadow-brutalist hover:shadow-brutalist-hover hover:translate-x-[4px] hover:translate-y-[4px] transition-all dark:border-white dark:bg-white dark:text-black">
                            <span class="flex items-center gap-2">
                                {{ t('portafolio.view_project') }}
                                <ArrowUpRight class="w-5 h-5" />
                            </span>
                        </Button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
</template>
