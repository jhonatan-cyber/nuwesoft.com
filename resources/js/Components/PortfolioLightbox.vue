<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { Button } from '@/Components/ui/button'
import { Dialog, DialogContent } from '@/Components/ui/dialog'
import { useRekaCleanup } from '@/composables/useRekaCleanup'
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/Components/ui/tooltip'
import { X, ChevronLeft, ChevronRight, ExternalLink } from 'lucide-vue-next'
import BlurImage from '@/Components/BlurImage.vue'

const { t } = useI18n()

const props = defineProps({
    project: { type: Object, default: null },
    currentIndex: { type: Number, default: 0 },
    getTechLogo: { type: Function, default: () => null },
})

const emit = defineEmits(['close', 'prev', 'next', 'update:currentIndex'])

const isOpen = computed({
    get: () => !!props.project,
    set: (val) => { if (!val) emit('close') },
})

useRekaCleanup(isOpen)

const showPrev = () => emit('prev')
const showNext = () => emit('next')
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogContent
            class="!max-w-6xl !w-[calc(100%-2rem)] !rounded-none !border-4 border-white !bg-black !text-white !p-0 shadow-[10px_10px_0px_rgba(255,255,255,0.3)] lightbox-dialog-enter"
        >
            <button
                type="button"
                class="absolute right-2 top-2 z-20 border-2 border-white bg-black p-3 text-white transition-all hover:bg-white hover:text-black hover:scale-110 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-black md:-right-4 md:-top-4 md:p-2.5"
                @click="emit('close')"
            >
                <X class="h-5 w-5" />
            </button>

            <div v-if="project" class="grid lg:grid-cols-[1.2fr_0.8fr]">
                <!-- Image Panel -->
                <div class="relative min-h-[22rem] bg-zinc-950 overflow-hidden">
                    <transition name="lightbox-image" mode="out-in">
                        <BlurImage
                            :key="currentIndex"
                            :src="project.images[currentIndex].image_url"
                            :alt="project.name"
                            fullRes
                            class="h-full w-full"
                            img-class="h-full w-full object-contain"
                        />
                    </transition>

                    <!-- Navigation Arrows (larger touch targets on mobile) -->
                    <button
                        v-if="project.images.length > 1"
                        type="button"
                        class="absolute left-1 top-1/2 -translate-y-1/2 border-2 border-white bg-black/80 p-4 text-white transition-all hover:bg-brutalist-yellow hover:text-black hover:scale-110 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brutalist-yellow focus-visible:ring-offset-2 focus-visible:ring-offset-black md:left-4 md:p-3"
                        @click.stop="showPrev"
                    >
                        <ChevronLeft class="h-6 w-6" />
                    </button>
                    <button
                        v-if="project.images.length > 1"
                        type="button"
                        class="absolute right-1 top-1/2 -translate-y-1/2 border-2 border-white bg-black/80 p-4 text-white transition-all hover:bg-brutalist-yellow hover:text-black hover:scale-110 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brutalist-yellow focus-visible:ring-offset-2 focus-visible:ring-offset-black md:right-4 md:p-3"
                        @click.stop="showNext"
                    >
                        <ChevronRight class="h-6 w-6" />
                    </button>

                    <!-- Image Counter -->
                    <div class="absolute bottom-4 left-4 border-2 border-white/30 bg-black/70 px-3 py-1.5 text-xs font-black uppercase tracking-wider">
                        {{ currentIndex + 1 }} / {{ project.images.length }}
                    </div>
                </div>

                <!-- Info Panel -->
                <div class="border-t-4 border-white p-6 lg:border-l-4 lg:border-t-0 flex flex-col">
                    <div class="mb-3 text-[11px] font-black uppercase tracking-[0.28em] text-brutalist-yellow flex items-center gap-2">
                        <span class="inline-block w-6 h-px bg-brutalist-yellow"></span>
                        {{ t('portafolio.project_label') }}
                    </div>

                    <h3 class="text-3xl font-display font-black uppercase italic leading-none">
                        {{ project.name }}
                    </h3>

                    <p class="mt-6 text-sm font-black uppercase leading-relaxed text-white/75">
                        {{ project.desc }}
                    </p>

                    <!-- Tech in lightbox -->
                    <div class="mt-6 flex flex-wrap gap-2">
                        <TooltipProvider :delay-duration="0">
                            <Tooltip
                                v-for="tech in project.technologies"
                                :key="tech.id"
                            >
                                <TooltipTrigger as-child>
                                    <span
                                        class="border-2 border-white/50 bg-transparent px-3 py-1.5 text-[11px] font-black uppercase tracking-[0.2em] text-white flex items-center justify-center w-14 h-10"
                                    >
                                        <img v-if="tech.logo_url" :src="tech.logo_url" class="w-full h-full object-contain" />
                                        <span v-else>{{ tech.name }}</span>
                                    </span>
                                </TooltipTrigger>
                                <TooltipContent side="top" :side-offset="8">
                                    {{ tech.name }}
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                    </div>

                    <!-- Thumbnail Navigation -->
                    <div v-if="project.images.length > 1" class="mt-auto pt-6">
                        <div class="mb-3 text-[10px] font-black uppercase tracking-[0.24em] text-white/50">
                            {{ t('portafolio.gallery_label') }}
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            <button
                                v-for="(image, imageIndex) in project.images"
                                :key="image.id"
                                type="button"
                                class="overflow-hidden border-2 transition-all duration-200 hover:scale-105"
                                :class="[imageIndex === currentIndex ? 'border-brutalist-yellow' : 'border-white/20 hover:border-white/50', 'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brutalist-yellow focus-visible:ring-inset']"
                                @click="emit('update:currentIndex', imageIndex)"
                            >
                                <BlurImage :src="image.image_url" :alt="project.name" :width="150" :height="100" class="h-16 w-full" />
                            </button>
                        </div>
                    </div>

                    <!-- Project URL -->
                    <a v-if="project.project_url" :href="project.project_url" target="_blank" class="mt-6 block">
                        <Button class="h-auto w-full rounded-none border-4 border-white bg-white py-4 font-black uppercase italic text-black transition-all hover:bg-brutalist-pink hover:text-white group/btn">
                            <span class="flex items-center justify-center gap-2">
                                {{ t('portafolio.view_project') }}
                                <ExternalLink class="h-4 w-4 group-hover/btn:translate-x-1 group-hover/btn:-translate-y-1 transition-transform" />
                            </span>
                        </Button>
                    </a>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>

<style scoped>
/* Hide the built-in DialogClose button — we use a custom positioned one */
:deep([data-reka-dialog-close]) {
    display: none;
}
</style>
