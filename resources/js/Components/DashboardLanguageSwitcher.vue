<script setup>
import { useI18n } from 'vue-i18n';
import { Button } from '@/Components/ui/button';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import { Languages, ChevronDown } from 'lucide-vue-next';

const { locale } = useI18n();

const changeLanguage = (lang) => {
  locale.value = lang;
  localStorage.setItem('locale', lang);
};
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button variant="ghost" class="h-9 px-3 gap-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 transition-all text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 shadow-sm">
        <Languages class="w-4 h-4 text-slate-400" />
        {{ locale }}
        <ChevronDown class="w-3 h-3 text-slate-400" />
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent align="end" class="w-32 mt-2 p-1.5 rounded-2xl border-slate-200 dark:border-slate-800 shadow-xl bg-white/90 dark:bg-black/90 backdrop-blur-xl">
      <DropdownMenuItem @click="changeLanguage('es')" :class="[
        'cursor-pointer rounded-xl px-3 py-2 text-xs font-bold transition-colors',
        locale === 'es' ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400 focus:bg-slate-100 dark:focus:bg-slate-800/50'
      ]">
        Español
      </DropdownMenuItem>
      <DropdownMenuItem @click="changeLanguage('en')" :class="[
        'cursor-pointer rounded-xl px-3 py-2 text-xs font-bold transition-colors',
        locale === 'en' ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400 focus:bg-slate-100 dark:focus:bg-slate-800/50'
      ]">
        English
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
