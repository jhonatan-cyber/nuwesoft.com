<script setup>
import { Clock, Pencil, Trash2, LogIn, Plus } from 'lucide-vue-next';

defineProps({ activityLog: { type: Array, default: () => [] } });
</script>

<template>
  <!-- Activity Log -->
  <div v-if="activityLog.length > 0">
    <div class="flex items-center gap-4 mb-6">
      <Clock class="w-5 h-5 text-neutral-500" />
      <h3 class="text-xl font-display font-bold uppercase tracking-tight text-neutral-900 dark:text-white">
        ACTIVITY LOG
      </h3>
      <div class="flex-1 h-px bg-neutral-100 dark:bg-neutral-800" />
    </div>
    <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-2xl overflow-hidden">
      <div
        v-for="log in activityLog"
        :key="log.id"
        class="flex items-start gap-4 px-5 py-4 border-b border-neutral-50 dark:border-neutral-800 last:border-0 hover:bg-neutral-50 dark:hover:bg-neutral-900 transition-colors"
      >
        <div
          :class="[
            'w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mt-0.5',
            log.type === 'created' ? 'bg-emerald-500/10' :
            log.type === 'updated' ? 'bg-blue-500/10' :
            log.type === 'deleted' ? 'bg-red-500/10' : 'bg-neutral-100 dark:bg-neutral-800'
          ]"
        >
          <component
            :is="
              log.type === 'created' ? Plus :
              log.type === 'updated' ? Pencil :
              log.type === 'deleted' ? Trash2 : LogIn
            "
            :class="[
              'w-4 h-4',
              log.type === 'created' ? 'text-emerald-500' :
              log.type === 'updated' ? 'text-blue-500' :
              log.type === 'deleted' ? 'text-red-500' : 'text-neutral-400'
            ]"
          />
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-xs font-medium text-neutral-700 dark:text-neutral-300 leading-relaxed">
            {{ log.description }}
          </p>
          <p class="text-[10px] text-neutral-400 mt-1 font-mono">
            {{ new Date(log.created_at).toLocaleString('es-AR') }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
