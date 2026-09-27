<script setup>
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<template>
  <!-- Refined Footer -->
  <footer class="mt-12 py-8 px-10 rounded-3xl border border-neutral-200 dark:border-neutral-800 text-center md:text-left bg-white dark:bg-black">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
      <p class="text-[9px] font-bold text-neutral-500 dark:text-neutral-300 uppercase tracking-[0.2em]">
        &copy; 2026 NUWESOFT CORE &bull; {{ t('footer.no_compromise') }}
      </p>
      <div class="flex items-center gap-6 opacity-30">
        <div class="h-1 w-8 bg-neutral-300 dark:bg-neutral-700 rounded-full" />
        <div class="h-1 w-12 bg-black dark:bg-white rounded-full" />
        <div class="h-1 w-8 bg-neutral-300 dark:bg-neutral-700 rounded-full" />
      </div>
    </div>
  </footer>
</template>
