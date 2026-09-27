<script setup>
import { TooltipRoot, useForwardPropsEmits } from 'radix-vue'

const props = defineProps({
  modelValue: { type: null },
  open: { type: null },
  defaultOpen: { type: null },
  delayDuration: { type: null },
  disableHoverableContent: { type: null },
  disableOutsidePointerEvents: { type: null },
  ignorePrevIsolation: { type: null },
})
const emits = defineEmits(['update:open'])

const forwarded = useForwardPropsEmits(props, emits)
</script>

<template>
  <TooltipRoot v-bind="forwarded">
    <slot />
  </TooltipRoot>
</template>
