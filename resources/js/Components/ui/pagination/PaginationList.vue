<script setup>
import { PaginationList } from 'radix-vue'
import { cn } from '@/lib/utils'

const props = defineProps({
  class: { type: null, required: false },
})
</script>

<template>
  <PaginationList :class="cn('flex items-center gap-1', props.class)">
    <slot />
  </PaginationList>
</template>
