<script setup>
import { useI18n } from 'vue-i18n';
import { Button } from '@/Components/ui/button';
import { Save } from 'lucide-vue-next';

defineProps({ processing: { type: Boolean, default: false } });
defineEmits(['submit']);

const { t } = useI18n();
</script>

<template>
  <!-- Submit Button -->
  <div class="flex justify-end pt-4">
    <Button
      :disabled="processing"
      class="bg-black hover:bg-neutral-800 text-white dark:bg-white dark:hover:bg-neutral-200 dark:text-black rounded-xl px-10 py-6 shadow-lg font-bold uppercase text-xs tracking-wider transition-all hover:scale-[1.02] active:scale-[0.98]"
      @click="$emit('submit')"
    >
      <Save class="w-4 h-4 mr-2" />
      <template v-if="processing">
        <span class="animate-pulse">{{ t('settings.saving') }}</span>
      </template>
      <template v-else>
        {{ t('settings.save') }}
      </template>
    </Button>
  </div>
</template>
