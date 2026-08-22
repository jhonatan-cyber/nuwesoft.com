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
const logoUrl = computed(() => page.props.settings?.logo_url || '/images/nuwesoft-logo.png');
</script>

<template>
    <div class="relative flex items-center justify-center overflow-hidden py-1">
        <img
            :src="logoUrl"
            alt="NUWESOFT"
            class="h-10 w-auto object-contain"
        />
    </div>
</template>
