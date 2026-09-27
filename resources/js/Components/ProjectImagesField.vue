<script setup>
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { Label } from '@/Components/ui/label';
import { Upload, XCircle, Image as ImageIcon } from 'lucide-vue-next';

defineProps({
    existingImages: { type: Array, default: () => [] },
    newImages: { type: Array, default: () => [] },
    automaticCaptureNames: { type: Set, default: () => new Set() },
});
defineEmits(['remove-existing', 'remove-new', 'files-change']);

const { t } = useI18n();
const root = ref(null);

const getPreviewUrl = (file) => URL.createObjectURL(file);

defineExpose({ scrollIntoView: (options) => root.value?.scrollIntoView(options) });
</script>

<template>
  <div
    ref="root"
    class="space-y-4 pt-4 border-t border-gray-100 dark:border-slate-800"
  >
    <Label class="text-lg font-semibold flex items-center gap-2 dark:text-slate-100">
      <ImageIcon class="w-5 h-5 text-blue-500" />
      {{ t('dashboard_panel.projects.fields.images') }}
      <span
        v-if="newImages.length"
        class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-bold text-blue-700 dark:bg-blue-950 dark:text-blue-300"
      >{{ newImages.length }} nuevas</span>
    </Label>
          
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
      <!-- Existing Images -->
      <div
        v-for="image in existingImages"
        :key="image.id"
        class="relative group aspect-video rounded-xl overflow-hidden border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-900"
      >
        <img
          :src="image.url"
          class="w-full h-full object-cover"
        >
        <button
          type="button"
          class="absolute right-1 top-1 rounded-full bg-status-danger p-1 text-white opacity-0 transition-opacity group-hover:opacity-100 focus-visible:opacity-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-status-danger focus-visible:ring-offset-2"
          @click="$emit('remove-existing', image.id)"
        >
          <XCircle class="w-4 h-4" />
        </button>
      </div>

      <!-- New Images Previews -->
      <div
        v-for="(file, index) in newImages"
        :key="index"
        class="relative group aspect-video rounded-xl overflow-hidden border border-blue-200 dark:border-blue-900/30 bg-blue-50 dark:bg-blue-900/20"
      >
        <img
          :src="getPreviewUrl(file)"
          class="w-full h-full object-cover"
        >
        <span
          v-if="automaticCaptureNames.has(file.name)"
          class="absolute bottom-1.5 left-1.5 rounded-md bg-slate-950/80 px-2 py-1 text-xs font-bold uppercase tracking-wide text-white backdrop-blur"
        >Captura automática</span>
        <button
          type="button"
          class="absolute right-1 top-1 rounded-full bg-status-danger p-1 text-white opacity-0 transition-opacity group-hover:opacity-100 focus-visible:opacity-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-status-danger focus-visible:ring-offset-2"
          @click="$emit('remove-new', index)"
        >
          <XCircle class="w-4 h-4" />
        </button>
      </div>

      <!-- Upload Button -->
      <label class="flex flex-col items-center justify-center aspect-video rounded-xl border-2 border-dashed border-gray-300 dark:border-slate-700 hover:border-blue-500 dark:hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all cursor-pointer group">
        <Upload class="w-6 h-6 text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400" />
        <span class="text-xs text-gray-500 dark:text-slate-400 mt-2 group-hover:text-blue-600 dark:group-hover:text-blue-300">{{ t('dashboard_panel.projects.actions.upload') }}</span>
        <input
          type="file"
          multiple
          accept="image/*"
          class="hidden"
          @change="$emit('files-change', $event)"
        >
      </label>
    </div>
  </div>
</template>
