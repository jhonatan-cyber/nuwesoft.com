<script setup>
import { TooltipTrigger } from 'radix-vue'

const props = defineProps({
  asChild: { type: null },
  as: { type: null },
})
</script>

<template>
  <TooltipTrigger v-bind="props">
    <slot />
  </TooltipTrigger>
</template>
