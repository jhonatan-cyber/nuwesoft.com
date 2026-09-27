<script setup>
import { Star, Quote, Edit, Trash2, Check, Ban } from 'lucide-vue-next'

defineProps({ item: { type: Object, required: true } })
defineEmits(['approve', 'reject', 'edit', 'delete'])
</script>

<template>
  <div
    class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all"
  >
    <div class="flex items-start justify-between">
      <div class="flex-1">
        <div class="flex items-center gap-3 mb-2">
          <Quote class="w-5 h-5 text-brutalist-pink" />
          <span class="flex items-center gap-0.5">
            <Star
              v-for="i in 5"
              :key="i"
              :class="i <= item.rating ? 'text-brutalist-yellow fill-brutalist-yellow' : 'text-neutral-300'"
              class="w-4 h-4"
            />
          </span>
          <span
            v-if="item.status === 'pending'"
            class="rounded bg-status-warning/10 px-2 py-0.5 text-xs font-bold uppercase tracking-wider text-status-warning"
          >
            PENDIENTE
          </span>
          <span
            v-else-if="item.status === 'approved'"
            class="rounded bg-status-success/10 px-2 py-0.5 text-xs font-bold uppercase tracking-wider text-status-success"
          >
            APROBADO
          </span>
          <span
            v-else-if="item.status === 'rejected'"
            class="rounded bg-status-danger/10 px-2 py-0.5 text-xs font-bold uppercase tracking-wider text-status-danger"
          >
            RECHAZADO
          </span>
          <span
            v-if="!item.is_active"
            class="px-2 py-0.5 bg-neutral-100 dark:bg-neutral-800 text-neutral-400 text-xs font-bold uppercase tracking-wider rounded"
          >
            INACTIVO
          </span>
        </div>
        <p class="text-sm font-bold leading-relaxed text-neutral-700 dark:text-neutral-300 italic mb-3">
          "{{ item.content }}"
        </p>
        <div class="flex items-center gap-3 text-xs">
          <span class="font-bold uppercase text-neutral-900 dark:text-white">{{ item.client_name }}</span>
          <span
            v-if="item.client_role"
            class="text-neutral-400"
          >— {{ item.client_role }}</span>
          <span
            v-if="item.client_company"
            class="text-neutral-400"
          >{{ item.client_company }}</span>
        </div>
      </div>
      <div class="flex items-center gap-2 ml-4">
        <button
          v-if="item.status === 'pending'"
          class="rounded-xl border border-status-success/30 p-2 text-status-success transition-all hover:bg-status-success/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-status-success focus-visible:ring-offset-2"
          title="Aprobar"
          @click="$emit('approve', item.id)"
        >
          <Check class="w-4 h-4" />
        </button>
        <button
          v-if="item.status === 'pending'"
          class="rounded-xl border border-status-danger/30 p-2 text-status-danger transition-all hover:bg-status-danger/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-status-danger focus-visible:ring-offset-2"
          title="Rechazar"
          @click="$emit('reject', item.id)"
        >
          <Ban class="w-4 h-4" />
        </button>
        <button
          class="p-2 border border-neutral-200 dark:border-neutral-700 rounded-xl hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2"
          @click="$emit('edit', item)"
        >
          <Edit class="w-4 h-4" />
        </button>
        <button
          class="rounded-xl border border-status-danger/30 p-2 text-status-danger transition-all hover:bg-status-danger/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-status-danger focus-visible:ring-offset-2"
          @click="$emit('delete', item)"
        >
          <Trash2 class="w-4 h-4" />
        </button>
      </div>
    </div>
  </div>
</template>
