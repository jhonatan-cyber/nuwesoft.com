<script setup>
import { ref, onMounted, watch } from 'vue';

const theme = ref('system');

const updateTheme = () => {
    const html = document.documentElement;
    const isDark = 
        theme.value === 'dark' || 
        (theme.value === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
    
    if (isDark) {
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
    
    // Listen for system changes if in system mode
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        if (theme.value === 'system') {
            updateTheme();
        }
    });
});

watch(theme, () => {
    updateTheme();
});

const toggleTheme = (newTheme) => {
    theme.value = newTheme;
};
</script>

<template>
    <div class="flex items-center p-1 bg-white dark:bg-black border-4 border-black dark:border-white shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_0px_rgba(255,255,255,1)]">
        <button 
            @click="toggleTheme('light')"
            :class="[
                'p-2 transition-all duration-300',
                theme === 'light' ? 'bg-brutalist-yellow text-black' : 'text-gray-500 hover:text-black dark:hover:text-white'
            ]"
            title="Modo Claro"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </button>
        
        <button 
            @click="toggleTheme('dark')"
            :class="[
                'p-2 transition-all duration-300',
                theme === 'dark' ? 'bg-brutalist-blue text-black' : 'text-gray-500 hover:text-black dark:hover:text-white'
            ]"
            title="Modo Oscuro"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
        
        <button 
            @click="toggleTheme('system')"
            :class="[
                'p-2 transition-all duration-300',
                theme === 'system' ? 'bg-brutalist-pink text-white' : 'text-gray-500 hover:text-black dark:hover:text-white'
            ]"
            title="Sistema"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </button>
    </div>
</template>
