<script setup>
import { useI18n } from 'vue-i18n';
import { Button } from '@/Components/ui/button';
import { ChevronRight, Download, FileText, Trash2 } from 'lucide-vue-next';

defineProps({
    msg: { type: Object, required: true },
    expanded: { type: Boolean, default: false },
    isSelected: { type: Boolean, default: false },
});
defineEmits(['expand', 'select', 'delete']);

const { t } = useI18n();

function formatDate(date) {
    return new Date(date).toLocaleDateString('es-AR', {
        day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit',
    });
}
</script>

<template>
  <div
    :class="[
      'bg-white dark:bg-black border rounded-2xl overflow-hidden transition-all duration-200',
      msg.read_at
        ? 'border-neutral-200 dark:border-neutral-800'
        : 'border-brutalist-pink/40 dark:border-brutalist-pink/30 shadow-sm'
    ]"
  >
    <!-- Row header -->
    <div
      class="flex items-center gap-4 p-5 cursor-pointer hover:bg-neutral-50 dark:hover:bg-neutral-900 transition-colors"
      @click="$emit('expand', msg.id)"
    >
      <!-- Checkbox -->
      <label
        class="shrink-0 flex items-center justify-center"
        @click.stop
      >
        <input
          type="checkbox"
          :checked="isSelected"
          class="h-4 w-4 rounded border-neutral-300 dark:border-neutral-600 text-brutalist-pink focus:ring-brutalist-pink cursor-pointer"
          @change="$emit('select', msg.id)"
        >
      </label>

      <!-- Read indicator -->
      <div
        :class="[
          'w-2 h-2 rounded-full shrink-0',
          msg.read_at ? 'bg-neutral-300 dark:bg-neutral-700' : 'bg-brutalist-pink animate-pulse'
        ]"
      />

      <!-- Icon -->
      <div
        :class="[
          'w-10 h-10 rounded-xl flex items-center justify-center shrink-0',
          msg.read_at ? 'bg-neutral-100 dark:bg-neutral-800' : 'bg-brutalist-pink/10'
        ]"
      >
        <component
          :is="msg.read_at ? MailOpen : Mail"
          :class="['w-5 h-5', msg.read_at ? 'text-neutral-400' : 'text-brutalist-pink']"
        />
      </div>

      <!-- Info -->
      <div class="flex-1 min-w-0">
        <div class="flex items-center gap-3 flex-wrap">
          <span :class="['text-sm font-black uppercase truncate', !msg.read_at && 'text-black dark:text-white']">
            {{ msg.nombre }}
          </span>
          <span class="text-xs text-neutral-400 font-mono break-all">{{ msg.email }}</span>
        </div>
        <p class="text-sm text-neutral-500 truncate mt-0.5">
          {{ msg.mensaje || t('messages.no_message') }}
          <span
            v-if="msg.attachment_name"
            class="inline-flex items-center gap-1 ml-1 text-brutalist-pink"
          >
            <FileText class="w-3 h-3" />
            {{ msg.attachment_name }}
          </span>
        </p>
      </div>

      <!-- Date + actions -->
      <div class="flex items-center gap-3 shrink-0">
        <span class="text-xs text-neutral-400 font-mono hidden sm:block">
          {{ formatDate(msg.created_at) }}
        </span>
        <Button
          variant="ghost"
          size="sm"
          class="h-8 w-8 rounded-lg p-0 text-status-danger/70 hover:bg-status-danger/10 hover:text-status-danger"
          @click.stop="$emit('delete', msg)"
        >
          <Trash2 class="w-4 h-4" />
        </Button>
        <ChevronRight
          :class="[
            'w-4 h-4 text-neutral-300 transition-transform duration-200',
            expanded && 'rotate-90'
          ]"
        />
      </div>
    </div>

    <!-- Expanded body -->
    <transition
      enter-active-class="transition-all duration-200 ease-out"
      enter-from-class="opacity-0 max-h-0"
      enter-to-class="opacity-100 max-h-96"
      leave-active-class="transition-all duration-150 ease-in"
      leave-from-class="opacity-100 max-h-96"
      leave-to-class="opacity-0 max-h-0"
    >
      <div
        v-if="expanded"
        class="border-t border-neutral-100 dark:border-neutral-800 px-5 pb-5 pt-4 bg-neutral-50 dark:bg-neutral-900"
      >
        <p class="text-sm text-neutral-700 dark:text-neutral-300 leading-relaxed whitespace-pre-wrap">
          {{ msg.mensaje }}
        </p>
        <div
          v-if="msg.attachment_name && msg.attachment_url"
          class="mt-3 flex items-center gap-2 p-3 bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700"
        >
          <FileText class="w-5 h-5 text-brutalist-pink shrink-0" />
          <div class="flex-1 min-w-0">
            <p class="text-xs font-bold text-neutral-700 dark:text-neutral-300 truncate">
              {{ msg.attachment_name }}
            </p>
          </div>
          <a
            :href="msg.attachment_url"
            target="_blank"
            rel="noopener"
            class="shrink-0 px-3 py-1.5 text-xs font-black uppercase tracking-widest rounded-lg bg-brutalist-pink text-white hover:bg-brutalist-pink/90 transition-colors"
          >
            <Download class="w-3.5 h-3.5 inline-block mr-1" />
            {{ t('messages.actions.download') }}
          </a>
        </div>
        <div class="mt-4 flex items-center justify-between">
          <a
            :href="`mailto:${msg.email}`"
            class="text-xs font-black uppercase tracking-widest text-brutalist-pink hover:underline"
          >
            {{ t('messages.actions.reply_by_email') }} →
          </a>
          <span class="text-xs text-neutral-400">
            {{ formatDate(msg.created_at) }}
          </span>
        </div>
      </div>
    </transition>
  </div>
</template>
