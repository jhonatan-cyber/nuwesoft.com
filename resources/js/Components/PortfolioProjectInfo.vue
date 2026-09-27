<script setup>
import { useI18n } from 'vue-i18n';
import { Badge } from '@/Components/ui/badge';
import { useInView } from '@/composables/useInView';

defineProps({
    project: { type: Object, required: true },
    images: { type: Array, default: () => [] },
});

const { t } = useI18n();
const { el: contentRef, isVisible: contentVisible } = useInView(0.05);
</script>

<template>
  <!-- ═══ More Info ═══ -->
  <section
    ref="contentRef"
    class="px-6 py-24"
  >
    <div class="max-w-[1400px] mx-auto">
      <div
        :class="['grid gap-16 lg:grid-cols-2 transition-all duration-700', contentVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0']"
      >
        <div>
          <div class="mb-6 flex items-center gap-4">
            <span class="inline-flex h-4 w-4 rotate-45 border-2 border-black bg-brutalist-blue" />
            <span class="text-[11px] font-black uppercase tracking-[0.28em] opacity-50">{{ t('portafolio.project_overview') || 'DESCRIPCION' }}</span>
            <span class="h-px flex-1 bg-black/20 dark:bg-white/20" />
          </div>
          <p class="text-xl font-black uppercase italic leading-relaxed">
            {{ project.desc }}
          </p>
        </div>
        <div>
          <div class="mb-6 flex items-center gap-4">
            <span class="inline-flex h-4 w-4 rotate-45 border-2 border-black bg-brutalist-yellow" />
            <span class="text-[11px] font-black uppercase tracking-[0.28em] opacity-50">{{ t('portafolio.project_details') || 'DETALLES' }}</span>
            <span class="h-px flex-1 bg-black/20 dark:bg-white/20" />
          </div>
          <div class="space-y-4">
            <div class="flex items-center gap-4 border-2 border-black p-4">
              <span class="text-[10px] font-black uppercase tracking-[0.24em] opacity-50 w-24">{{ t('portafolio.category_label') || 'CATEGORIA' }}</span>
              <Badge class="border-2 border-black bg-white px-3 py-1 text-[10px] font-black uppercase dark:border-white dark:bg-zinc-900 dark:text-white">
                {{ project.category }}
              </Badge>
            </div>
            <div class="flex items-center gap-4 border-2 border-black p-4">
              <span class="text-[10px] font-black uppercase tracking-[0.24em] opacity-50 w-24">{{ t('portafolio.tech_count') || 'TECNOLOGIAS' }}</span>                                            <span class="text-sm font-black uppercase italic">{{ project.technologies?.length || 0 }}</span>
            </div>
            <div class="flex items-center gap-4 border-2 border-black p-4">
              <span class="text-[10px] font-black uppercase tracking-[0.24em] opacity-50 w-24">{{ t('portafolio.images_count') || 'IMAGENES' }}</span>
              <span class="text-sm font-black uppercase italic">{{ images.length }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
