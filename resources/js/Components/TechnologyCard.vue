<script setup>
import { useI18n } from 'vue-i18n';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import {
    DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import {
    MoreHorizontal, Pencil, Trash2, Code2, CheckCircle2, XCircle,
} from 'lucide-vue-next';

defineProps({
    tech: { type: Object, required: true },
    categoryColor: { type: Function, default: () => '' },
});
defineEmits(['edit', 'delete']);

const { t } = useI18n();
</script>

<template>
  <div 
    class="group relative bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-3xl p-4 hover:shadow-2xl transition-all duration-500 overflow-hidden"
  >
    <!-- Background Accent -->
    <div class="absolute -right-4 -top-4 w-16 h-16 bg-neutral-100 dark:bg-neutral-800 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700" />

    <div class="relative flex flex-col items-center text-center gap-3">
      <div class="w-full flex justify-end absolute -top-1 -right-1 z-10">
        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <Button
              variant="ghost"
              class="h-8 w-8 p-0 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-800 border border-transparent hover:border-neutral-200 dark:hover:border-neutral-700"
            >
              <MoreHorizontal class="h-4 w-4 text-neutral-400" />
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent
            align="end"
            class="w-48 p-2 rounded-2xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-black shadow-2xl"
          >
            <DropdownMenuItem
              class="rounded-xl cursor-pointer focus:bg-neutral-100 dark:focus:bg-neutral-800 focus:text-black dark:focus:text-white py-2.5"
              @click="$emit('edit', tech)"
            >
              <Pencil class="mr-2 h-4 w-4" />
              <span class="font-bold uppercase text-xs tracking-widest">{{ t('actions.edit') }}</span>
            </DropdownMenuItem>
            <div class="h-px bg-neutral-100 dark:bg-neutral-800 my-1" />
            <DropdownMenuItem
              class="cursor-pointer rounded-xl py-2.5 text-status-danger focus:bg-status-danger/10 focus:text-status-danger"
              @click="$emit('delete', tech)"
            >
              <Trash2 class="mr-2 h-4 w-4" />
              <span class="font-bold uppercase text-xs tracking-widest">{{ t('actions.delete') }}</span>
            </DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </div>

      <!-- Logo -->
      <div class="w-full h-16 shrink-0 flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
        <img
          v-if="tech.logo_url"
          :src="tech.logo_url"
          :alt="tech.name"
          :class="['max-w-full max-h-full object-contain', tech.invert_dark ? 'dark:invert dark:brightness-0 dark:invert' : '']"
        >
        <Code2
          v-else
          class="w-8 h-8 text-neutral-300 dark:text-neutral-700"
        />
      </div>

      <div class="w-full min-w-0">
        <h3 class="font-black text-sm tracking-tight text-neutral-900 dark:text-white uppercase truncate px-1">
          {{ tech.name }}
        </h3>
        <div class="flex flex-col items-center gap-1 mt-1.5">
          <Badge
            variant="outline"
            :class="['rounded-full px-2 py-0 text-xs font-bold uppercase tracking-widest', categoryColor(tech.category)]"
          >
            {{ t(`technologies.categories.${tech.category}`) }}
          </Badge>
          <Badge
            v-if="!tech.is_active"
            variant="destructive"
            class="rounded-full px-2 py-0 text-xs font-bold uppercase tracking-widest"
          >
            {{ t('technologies.status.inactive') }}
          </Badge>
        </div>
      </div>
    </div>

    <!-- Compact Status -->
    <div class="mt-3 pt-3 border-t border-neutral-100 dark:border-neutral-800 flex items-center justify-center">
      <div class="flex items-center gap-1.5">
        <component
          :is="tech.is_active ? CheckCircle2 : XCircle"
          :class="['w-3 h-3', tech.is_active ? 'text-neutral-900 dark:text-white' : 'text-neutral-400']"
        />
        <span class="text-xs font-bold uppercase tracking-[0.1em] text-neutral-400">{{ tech.is_active ? t('technologies.status.active') : t('technologies.status.inactive') }}</span>
      </div>
    </div>
  </div>
</template>
