<script setup>
defineProps({
    variant: {
        type: String,
        default: 'auto',
    },
});

import nuwesoftLogo from '@/Assets/nuwesoft-logo-transparent.png';
import nuwesoftLogoDark from '@/Assets/nuwesoft-logo-dark.png';
</script>

<template>
    <div class="relative flex items-center justify-center overflow-visible py-1">
        <img
            :src="nuwesoftLogo"
            alt="NUWESOFT"
            class="h-auto w-auto max-w-full object-contain"
            :class="variant === 'auto' ? 'block dark:hidden' : variant === 'light' ? 'block' : 'hidden'"
        />
        <img
            :src="nuwesoftLogoDark"
            alt="NUWESOFT"
            class="h-auto w-auto max-w-full object-contain"
            :class="variant === 'auto' ? 'hidden dark:block' : variant === 'dark' ? 'block' : 'hidden'"
        />
    </div>
</template>
