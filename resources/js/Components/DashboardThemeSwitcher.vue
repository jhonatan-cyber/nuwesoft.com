<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import { Sun, Moon } from 'lucide-vue-next';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/Components/ui/tooltip';

const theme = ref('system');

const isDark = computed(() => {
    return theme.value === 'dark' || 
        (theme.value === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
});

const updateTheme = () => {
    const html = document.documentElement;
    if (isDark.value) {
        html.classList.add('dark');
    } else {
        html.classList.remove('dark');
    }
    localStorage.setItem('theme', theme.value);
};

onMounted(() => {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        theme.value = savedTheme;
    }
    updateTheme();

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        if (theme.value === 'system') {
            updateTheme();
        }
    });
});

watch(theme, () => {
    updateTheme();
});

const toggle = () => {
    theme.value = isDark.value ? 'light' : 'dark';
};
</script>

<template>
    <TooltipProvider :delay-duration="0">
        <Tooltip>
            <TooltipTrigger as-child>
                <button
                    @click="toggle"
                    :aria-label="isDark ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'"
                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-neutral-200 bg-white text-neutral-600 shadow-sm transition-all duration-300 hover:bg-neutral-100 hover:text-black dark:border-neutral-800 dark:bg-black dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2"
                >
                    <Sun v-if="!isDark" class="h-4 w-4" />
                    <Moon v-else class="h-4 w-4" />
                </button>
            </TooltipTrigger>
            <TooltipContent>
                <p>{{ isDark ? 'Modo Claro' : 'Modo Oscuro' }}</p>
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>
