<script setup>
import { TooltipProvider } from 'radix-vue'

const props = defineProps({
  delayDuration: { type: null },
  skipDelayDuration: { type: null },
  disableHoverableContent: { type: null },
})
</script>

<template>
  <TooltipProvider v-bind="props">
    <slot />
  </TooltipProvider>
</template>
