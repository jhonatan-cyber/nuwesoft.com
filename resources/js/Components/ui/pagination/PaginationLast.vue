<script setup>
import { PaginationLast } from 'radix-vue'
import { ChevronsRight } from 'lucide-vue-next'
import { Button } from '@/Components/ui/button'
import { cn } from '@/lib/utils'

const props = defineProps({
  asChild: { type: Boolean, default: true },
  class: { type: null, required: false },
})
</script>

<template>
  <PaginationLast v-bind="props">
    <Button
      variant="outline"
      size="icon"
      :class="cn('w-10 h-10 rounded-xl border-neutral-200 dark:border-neutral-800 text-neutral-600 dark:text-neutral-400 hover:bg-neutral-50 dark:hover:bg-neutral-800 hover:text-black dark:hover:text-white transition-all active:scale-95', props.class)"
    >
      <ChevronsRight class="h-4 w-4" />
    </Button>
  </PaginationLast>
</template>
