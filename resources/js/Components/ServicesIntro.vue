<script setup>
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<template>
  <!-- Intro: Mapa de Capacidades -->
  <div class="relative mb-16 overflow-hidden border-4 border-black bg-black p-8 text-white shadow-brutalist dark:border-white dark:shadow-brutalist-white md:p-12">
    <div class="pointer-events-none absolute -right-4 -top-4 select-none text-[8rem] font-display font-black italic leading-none text-white/5 md:text-[12rem]">
      ///
    </div>
    <div class="relative z-10 max-w-3xl">
      <p class="mb-2 text-sm font-black uppercase tracking-[0.28em] text-brutalist-yellow">
        {{ t('servicios.map_label') }}
      </p>
      <h2 class="text-4xl font-display font-black uppercase italic leading-none md:text-5xl">
        {{ t('servicios.map_title') }}
      </h2>
      <p class="mt-6 text-base font-black uppercase leading-relaxed text-white/65 md:text-lg">
        {{ t('servicios.map_text') }}
      </p>
    </div>
    <div class="relative z-10 mt-8 flex items-center gap-4 border-t-4 border-white/15 pt-6">
      <span class="flex h-2 w-2 animate-pulse rounded-full bg-brutalist-yellow" />
      <span class="text-[10px] font-black uppercase tracking-[0.28em] text-white/40">4 SERVICIOS · 1 EJECUCION</span>
    </div>
  </div>
</template>
