<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="border-4 border-black dark:border-white bg-white dark:bg-black p-4 font-mono font-bold uppercase tracking-tight shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_0px_rgba(255,255,255,1)] focus:ring-0 focus:border-brutalist-pink focus:translate-x-[2px] focus:translate-y-[2px] focus:shadow-none transition-all outline-none"
        v-model="model"
        ref="input"
    />
</template>
