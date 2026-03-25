<script setup>
import { useI18n } from 'vue-i18n';
import { Button } from '@/Components/ui/button';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';

const { locale } = useI18n();

const changeLanguage = (lang) => {
  locale.value = lang;
  localStorage.setItem('locale', lang);
};
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button variant="outline" class="bg-white dark:bg-black border-4 border-black dark:border-white font-black uppercase text-xs h-10 px-4 rounded-none shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_0px_rgba(255,255,255,1)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all">
        {{ locale.toUpperCase() }}
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent align="end" class="bg-white dark:bg-black border-4 border-black dark:border-white rounded-none p-0">
      <DropdownMenuItem @click="changeLanguage('es')" class="font-black uppercase text-xs p-3 focus:bg-brutalist-yellow focus:text-black cursor-pointer rounded-none border-b-2 border-black dark:border-white last:border-0">
        Español
      </DropdownMenuItem>
      <DropdownMenuItem @click="changeLanguage('en')" class="font-black uppercase text-xs p-3 focus:bg-brutalist-pink focus:text-white cursor-pointer rounded-none">
        English
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
