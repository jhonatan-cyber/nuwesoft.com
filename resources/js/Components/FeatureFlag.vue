<script setup>
import { computed } from 'vue';
import { usePostHog } from '@/composables/usePostHog';

const props = defineProps({
    flag: { type: String, required: true },
    fallback: { type: [Boolean, String], default: false },
});

const { isFeatureEnabled } = usePostHog();
const show = computed(() => isFeatureEnabled(props.flag) ?? props.fallback);
</script>

<template>
    <slot v-if="show" />
</template>
