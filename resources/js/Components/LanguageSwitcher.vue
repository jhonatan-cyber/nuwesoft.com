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
      <Button variant="outline" class="h-10 px-4 gap-2 rounded-none border-4 border-black dark:border-white bg-white dark:bg-black text-black dark:text-white font-black uppercase italic shadow-brutalist dark:shadow-brutalist-white hover:bg-brutalist-yellow hover:text-black transition-all">
        <Languages class="w-4 h-4" />
        {{ locale }}
        <ChevronDown class="w-3 h-3" />
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent align="end" class="w-40 mt-2 p-0 rounded-none border-4 border-black dark:border-white bg-white dark:bg-black shadow-brutalist dark:shadow-brutalist-white">
      <DropdownMenuItem @click="changeLanguage('es')" :class="[
        'cursor-pointer rounded-none px-4 py-3 text-sm font-black uppercase italic transition-colors border-b-2 border-black dark:border-white last:border-b-0',
        locale === 'es' ? 'bg-brutalist-pink text-white' : 'text-black dark:text-white hover:bg-brutalist-yellow hover:text-black focus:bg-brutalist-yellow focus:text-black'
      ]">
        Español
      </DropdownMenuItem>
      <DropdownMenuItem @click="changeLanguage('en')" :class="[
        'cursor-pointer rounded-none px-4 py-3 text-sm font-black uppercase italic transition-colors border-b-2 border-black dark:border-white last:border-b-0',
        locale === 'en' ? 'bg-brutalist-pink text-white' : 'text-black dark:text-white hover:bg-brutalist-yellow hover:text-black focus:bg-brutalist-yellow focus:text-black'
      ]">
        English
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
