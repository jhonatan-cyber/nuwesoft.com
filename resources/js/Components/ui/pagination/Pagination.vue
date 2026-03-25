<script setup>
import { PaginationRoot } from 'radix-vue'

const props = defineProps({
  total: { type: Number, required: true },
  itemsPerPage: { type: Number, default: 10 },
  siblingCount: { type: Number, default: 1 },
  showEdges: { type: Boolean, default: true },
  defaultPage: { type: Number, default: 1 },
})
</script>

<template>
  <PaginationRoot v-bind="props">
    <slot />
  </PaginationRoot>
</template>
