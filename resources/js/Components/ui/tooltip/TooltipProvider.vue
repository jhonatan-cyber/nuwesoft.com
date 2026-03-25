<script setup>
import { TooltipProvider } from 'radix-vue'

const props = defineProps(['delayDuration', 'skipDelayDuration', 'disableHoverableContent'])
</script>

<template>
  <TooltipProvider v-bind="props">
    <slot />
  </TooltipProvider>
</template>
