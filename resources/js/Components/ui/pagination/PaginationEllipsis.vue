<script setup>
import { PaginationEllipsis } from 'radix-vue'
import { MoreHorizontal } from 'lucide-vue-next'
import { cn } from '@/utils'

const props = defineProps({
  class: { type: null, required: false },
})
</script>

<template>
  <PaginationEllipsis :class="cn('flex h-10 w-10 items-center justify-center text-slate-400', props.class)">
    <MoreHorizontal class="h-4 w-4" />
  </PaginationEllipsis>
</template>
