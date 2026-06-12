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
                 ? 'bg-black text-white dark:bg-white dark:text-black shadow-lg border-transparent' 
                 : 'border-neutral-200 dark:border-neutral-800 text-neutral-600 dark:text-neutral-400 hover:bg-neutral-50 dark:hover:bg-neutral-800 hover:text-black dark:hover:text-white',
                 props.class)"
    >
      {{ value }}
    </Button>
  </PaginationListItem>
</template>
