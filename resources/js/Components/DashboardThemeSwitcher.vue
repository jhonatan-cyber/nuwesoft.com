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
    <div class="flex items-center p-1 bg-slate-100/50 dark:bg-slate-800/50 backdrop-blur-md border border-slate-200 dark:border-slate-700/50 rounded-xl shadow-sm">
        <button 
            @click="toggleTheme('light')"
            :class="[
                'p-1.5 rounded-lg transition-all duration-300 flex items-center justify-center',
                theme === 'light' 
                    ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' 
                    : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-100'
            ]"
            title="Modo Claro"
        >
            <Sun class="w-4 h-4" />
        </button>
        
        <button 
            @click="toggleTheme('dark')"
            :class="[
                'p-1.5 rounded-lg transition-all duration-300 flex items-center justify-center',
                theme === 'dark' 
                    ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' 
                    : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-100'
            ]"
            title="Modo Oscuro"
        >
            <Moon class="w-4 h-4" />
        </button>
        
        <button 
            @click="toggleTheme('system')"
            :class="[
                'p-1.5 rounded-lg transition-all duration-300 flex items-center justify-center',
                theme === 'system' 
                    ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' 
                    : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-100'
            ]"
            title="Sistema"
        >
            <Monitor class="w-4 h-4" />
        </button>
    </div>
</template>
