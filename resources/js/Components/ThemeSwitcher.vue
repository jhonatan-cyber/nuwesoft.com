<script setup>
import { ref, onMounted, watch } from 'vue';
import { Sun, Moon, Monitor } from 'lucide-vue-next';

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
    <div class="flex items-center p-1 bg-white dark:bg-black border-4 border-black dark:border-white shadow-brutalist dark:shadow-brutalist-white">
        <button 
            @click="toggleTheme('light')"
            :class="[
                'p-1.5 transition-all duration-200 flex items-center justify-center border-2',
                theme === 'light' 
                    ? 'bg-brutalist-yellow text-black border-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]' 
                    : 'text-black dark:text-white border-transparent hover:bg-gray-100 dark:hover:bg-zinc-800'
            ]"
            title="Modo Claro"
        >
            <Sun class="w-4 h-4" />
        </button>
        
        <button 
            @click="toggleTheme('dark')"
            :class="[
                'p-1.5 transition-all duration-200 flex items-center justify-center border-2',
                theme === 'dark' 
                    ? 'bg-brutalist-yellow text-black border-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]' 
                    : 'text-black dark:text-white border-transparent hover:bg-gray-100 dark:hover:bg-zinc-800'
            ]"
            title="Modo Oscuro"
        >
            <Moon class="w-4 h-4" />
        </button>
        
        <button 
            @click="toggleTheme('system')"
            :class="[
                'p-1.5 transition-all duration-200 flex items-center justify-center border-2',
                theme === 'system' 
                    ? 'bg-brutalist-yellow text-black border-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]' 
                    : 'text-black dark:text-white border-transparent hover:bg-gray-100 dark:hover:bg-zinc-800'
            ]"
            title="Sistema"
        >
            <Monitor class="w-4 h-4" />
        </button>
    </div>
</template>
