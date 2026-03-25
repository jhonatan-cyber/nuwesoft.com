<script setup>
import { AccordionRoot, useForwardPropsEmits } from 'radix-vue'

const props = defineProps({
  type: { type: String, required: false },
  collapsible: { type: Boolean, required: false },
  defaultValue: { type: [String, Array], required: false },
  modelValue: { type: [String, Array], required: false },
  dir: { type: String, required: false },
  orientation: { type: String, required: false },
  disabled: { type: Boolean, required: false },
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
})

const emits = defineEmits(['update:modelValue'])

const forwarded = useForwardPropsEmits(props, emits)
</script>

<template>
  <AccordionRoot v-bind="forwarded">
    <slot />
  </AccordionRoot>
</template>
