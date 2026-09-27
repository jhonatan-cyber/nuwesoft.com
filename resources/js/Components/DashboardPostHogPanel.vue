<script setup>
import { computed } from 'vue';
import { TrendingUp, Users, MousePointerClick, Flag } from 'lucide-vue-next';
import { usePostHog } from '@/composables/usePostHog';

const { allFlags } = usePostHog();
const posthogConfigured = computed(() => !!import.meta.env.VITE_POSTHOG_KEY);
const activeFlags = computed(() => {
    const flags = allFlags();
    return Object.entries(flags).filter(([, val]) => val).map(([key]) => key);
});
</script>

<template>
  <!-- PostHog Analytics Widget -->
  <div
    v-if="posthogConfigured"
    class="mb-8"
  >
    <div class="flex items-center gap-4 mb-6">
      <TrendingUp class="w-5 h-5 text-neutral-500" />
      <h3 class="text-xl font-display font-bold uppercase tracking-tight text-neutral-900 dark:text-white">
        POSTHOG ANALYTICS
      </h3>
      <div class="flex-1 h-px bg-neutral-100 dark:bg-neutral-800" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-5 rounded-2xl shadow-sm">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-8 h-8 rounded-xl bg-brutalist-pink/10 flex items-center justify-center">
            <Flag class="w-4 h-4 text-brutalist-pink" />
          </div>
          <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">FEATURE FLAGS</span>
        </div>
        <p class="text-2xl font-display font-bold">
          {{ activeFlags.length }}
        </p>
        <p class="text-[10px] text-neutral-400 uppercase tracking-wider mt-1">
          Flags activos
        </p>
      </div>

      <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-5 rounded-2xl shadow-sm">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-8 h-8 rounded-xl bg-brutalist-blue/10 flex items-center justify-center">
            <Users class="w-4 h-4 text-brutalist-blue" />
          </div>
          <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">AUTOCAPTURE</span>
        </div>
        <p class="text-2xl font-display font-bold">
          ACTIVE
        </p>
        <p class="text-[10px] text-neutral-400 uppercase tracking-wider mt-1">
          Eventos automáticos
        </p>
      </div>

      <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-5 rounded-2xl shadow-sm">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-8 h-8 rounded-xl bg-brutalist-yellow/10 flex items-center justify-center">
            <TrendingUp class="w-4 h-4 text-brutalist-yellow" />
          </div>
          <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">PAGEVIEWS</span>
        </div>
        <p class="text-2xl font-display font-bold">
          AUTO
        </p>
        <p class="text-[10px] text-neutral-400 uppercase tracking-wider mt-1">
          Tracking via Inertia
        </p>
      </div>

      <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-5 rounded-2xl shadow-sm">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-8 h-8 rounded-xl bg-emerald-500/10 flex items-center justify-center">
            <MousePointerClick class="w-4 h-4 text-emerald-500" />
          </div>
          <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">EVENTOS KEY</span>
        </div>
        <p class="text-2xl font-display font-bold">
          5
        </p>
        <p class="text-[10px] text-neutral-400 uppercase tracking-wider mt-1">
          Eventos trackeados
        </p>
      </div>
    </div>

    <div
      v-if="activeFlags.length > 0"
      class="mt-4 p-4 bg-brutalist-yellow/5 border border-brutalist-yellow/20 rounded-2xl"
    >
      <div class="flex items-center gap-2 mb-2">
        <Flag class="w-4 h-4 text-brutalist-yellow" />
        <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-600 dark:text-neutral-400">FLAGS ACTIVOS</span>
      </div>
      <div class="flex flex-wrap gap-2">
        <span
          v-for="flag in activeFlags"
          :key="flag"
          class="px-3 py-1 bg-brutalist-yellow/20 border border-brutalist-yellow/40 text-[10px] font-bold uppercase tracking-wider rounded-lg text-brutalist-yellow-800 dark:text-brutalist-yellow-200"
        >
          {{ flag }}
        </span>
      </div>
    </div>
  </div>

  <!-- PostHog Not Configured -->
  <div
    v-else
    class="mb-8"
  >
    <div class="flex items-center gap-4 mb-6">
      <TrendingUp class="w-5 h-5 text-neutral-400" />
      <h3 class="text-xl font-display font-bold uppercase tracking-tight text-neutral-500">
        POSTHOG ANALYTICS
      </h3>
      <div class="flex-1 h-px bg-neutral-100 dark:bg-neutral-800" />
    </div>
    <div class="bg-neutral-50 dark:bg-neutral-900 border border-dashed border-neutral-200 dark:border-neutral-800 p-6 rounded-2xl">
      <p class="text-xs font-bold uppercase tracking-widest text-neutral-400">
        Configurá <code class="px-2 py-0.5 bg-neutral-200 dark:bg-neutral-700 rounded">POSTHOG_KEY</code> en tu <code class="px-2 py-0.5 bg-neutral-200 dark:bg-neutral-700 rounded">.env</code> para activar analytics.
      </p>
    </div>
  </div>
</template>
