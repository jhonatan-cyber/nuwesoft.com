<script setup>
import { useI18n } from 'vue-i18n';
import { useInView } from '@/composables/useInView';
import BlurImage from '@/Components/BlurImage.vue';

defineProps({
    project: { type: Object, required: true },
    allImages: { type: Array, default: () => [] },
});
defineEmits(['open']);

const { t } = useI18n();
const { el: galleryRef, isVisible: galleryVisible } = useInView(0.05);
</script>

<template>
<!-- ═══ Full Gallery ═══ -->
<section
    v-if="allImages.length > 1"
    ref="galleryRef"
    class="border-y-8 border-black bg-zinc-50 px-6 py-24 dark:border-white dark:bg-zinc-950"
>
    <div class="max-w-[1400px] mx-auto">
        <div
            :class="['mb-12 flex items-center gap-4 transition-all duration-700', galleryVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0']"
        >
            <span class="inline-flex h-4 w-4 rotate-45 border-2 border-black bg-brutalist-pink"></span>
            <span class="text-[11px] font-black uppercase tracking-[0.28em] opacity-50">{{ t('portafolio.gallery_label') }}</span>
            <span class="h-px flex-1 bg-black/20 dark:bg-white/20"></span>
            <span class="whitespace-nowrap border-2 border-black bg-black px-3 py-1.5 text-[10px] font-black uppercase tracking-[0.18em] text-white dark:border-white dark:bg-white dark:text-black">{{ allImages.length }} {{ t('portafolio.images_label') }}</span>
        </div>

        <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
            <div
                v-for="(image, idx) in allImages"
                :key="image.id"
                :style="{ transitionDelay: `${idx * 80}ms` }"
                :class="[
                    'transition-all duration-700',
                    galleryVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'
                ]"
            >
                <button type="button" class="group relative block w-full overflow-hidden border-4 border-black text-left shadow-brutalist transition-all duration-300 hover:-translate-y-1 hover:shadow-brutalist-hover focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-brutalist-pink" :aria-label="`Ver imagen ${idx + 1} de ${allImages.length} en pantalla completa`" @click="$emit('open', idx)">
                    <BlurImage :src="image.image_url" :alt="project.name" :width="600" :height="400" class="w-full h-48 md:h-64" img-class="transition-all duration-500 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-black/0 transition-all duration-300 group-hover:bg-black/10"></div>
                    <div class="absolute bottom-2 right-2 border-2 border-black bg-white px-2 py-1 text-[10px] font-black uppercase text-black shadow-[2px_2px_0_#000]">
                        #{{ (idx + 1).toString().padStart(2, '0') }}
                    </div>
                </button>
            </div>
        </div>
    </div>
</section>
</template>
