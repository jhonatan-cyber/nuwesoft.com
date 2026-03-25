<script setup>
import { PaginationPrev } from 'radix-vue'
import { ChevronLeft } from 'lucide-vue-next'
import { Button } from '@/Components/ui/button'
import { cn } from '@/utils'

const props = defineProps({
  asChild: { type: Boolean, default: true },
  class: { type: null, required: false },
})
</script>

<template>
  <PaginationPrev v-bind="props">
    <Button
      variant="outline"
      size="icon"
      :class="cn('w-10 h-10 rounded-xl border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-indigo-600 transition-all active:scale-95', props.class)"
    >
      <ChevronLeft class="h-4 w-4" />
    </Button>
  </PaginationPrev>
</template>
