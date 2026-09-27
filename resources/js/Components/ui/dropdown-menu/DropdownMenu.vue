<script setup>
import {
  DropdownMenuRoot,
  useForwardPropsEmits,
} from 'radix-vue'

// NOTE: no defineEmits here. An empty `defineEmits({})` compiles to an
// `emits: {}` *object* in the component options, and radix-vue's
// `useEmitAsProps` calls `.forEach` on it, throwing
// `TypeError: e?.forEach is not a function` during setup — which made every
// DropdownMenu render as an empty comment node (technology card actions,
// language switcher and the layout menu were all missing). Passing only the
// props skips that path entirely; listeners still reach DropdownMenuRoot
// through the default attribute fall-through.
const props = defineProps({})
const forwarded = useForwardPropsEmits(props)
</script>

<template>
  <DropdownMenuRoot v-bind="forwarded">
    <slot />
  </DropdownMenuRoot>
</template>
