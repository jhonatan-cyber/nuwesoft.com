<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    variant: {
        type: String,
        default: 'auto',
    },
});

const page = usePage();
const settingsLogo = computed(() => page.props.settings?.logo_url || null);
</script>

<template>
    <div class="relative flex items-center justify-center overflow-visible py-1">
        <img
            v-if="settingsLogo"
            :src="settingsLogo"
            alt="NUWESOFT"
            class="h-auto w-auto max-w-full object-contain"
        />
    </div>
</template>
