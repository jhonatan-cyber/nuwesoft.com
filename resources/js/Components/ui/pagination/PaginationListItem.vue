<script setup>
import { PaginationListItem } from 'radix-vue'
import { Button } from '@/Components/ui/button'
import { cn } from '@/lib/utils'

const props = defineProps({
  value: { type: Number, required: true },
  asChild: { type: Boolean, default: true },
  class: { type: null, required: false },
})
</script>

<template>
  <PaginationListItem v-bind="props">
    <Button
      :variant="value === $parent.page ? 'default' : 'outline'"
      size="icon"
      :class="cn('w-10 h-10 rounded-xl font-bold transition-all active:scale-95', 
                 value === $parent.page 
                 ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 border-transparent' 
                 : 'border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-indigo-600',
                 props.class)"
    >
      {{ value }}
    </Button>
  </PaginationListItem>
</template>
