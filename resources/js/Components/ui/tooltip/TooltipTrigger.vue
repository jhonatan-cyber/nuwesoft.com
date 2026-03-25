<script setup>
import { TooltipTrigger } from 'radix-vue'

const props = defineProps(['asChild', 'as'])
</script>

<template>
  <TooltipTrigger v-bind="props">
    <slot />
  </TooltipTrigger>
</template>
