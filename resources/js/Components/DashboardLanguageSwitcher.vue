<script setup>
import { useI18n } from 'vue-i18n';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/Components/ui/tooltip';

const { locale } = useI18n();

const toggle = () => {
  locale.value = locale.value === 'es' ? 'en' : 'es';
  localStorage.setItem('locale', locale.value);
};
</script>

<template>
  <TooltipProvider :delay-duration="0">
    <Tooltip>
      <TooltipTrigger as-child>
        <button
          @click="toggle"
          :aria-label="locale === 'es' ? 'Cambiar idioma a inglés' : 'Switch language to Spanish'"
          class="flex h-9 w-9 items-center justify-center rounded-xl border border-neutral-200 bg-white text-neutral-600 shadow-sm transition-all duration-300 hover:bg-neutral-100 hover:text-black dark:border-neutral-800 dark:bg-black dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2"
        >
          <span class="text-[11px] font-black uppercase tracking-wider">{{ locale }}</span>
        </button>
      </TooltipTrigger>
      <TooltipContent>
        <p>{{ locale === 'es' ? 'Switch to English' : 'Cambiar a Español' }}</p>
      </TooltipContent>
    </Tooltip>
  </TooltipProvider>
</template>
