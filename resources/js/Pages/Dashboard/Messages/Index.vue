<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useSkeletonLoader } from '@/composables/useSkeletonLoader';
import { useRekaCleanup } from '@/composables/useRekaCleanup';

import { useI18n } from 'vue-i18n';
import {
    Trash2,
    CheckCheck,
    AlertTriangle,
    Inbox
} from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Download } from 'lucide-vue-next';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import MessageRow from '@/Components/MessageRow.vue';
import {
    Pagination, PaginationList, PaginationListItem,
    PaginationEllipsis, PaginationFirst, PaginationLast,
    PaginationNext, PaginationPrev,
} from '@/Components/ui/pagination';

const props = defineProps({
    messages:    { type: Object, required: true },
    unreadCount: { type: Number, default: 0 },
    filters:     { type: Object, default: () => ({}) },
});

const { t } = useI18n();

const { skeletonReady } = useSkeletonLoader();

const filter = ref(props.filters?.filter || 'all');

const deleteTarget = ref(null);
const isDeleteOpen  = ref(false);
const deleteForm    = useForm({});
const expanded      = ref(null);

// ── Bulk delete ──
const selected     = ref([]);
const isBulkDeleteOpen = ref(false);
const bulkDeleteForm   = useForm({ ids: [] });

const allSelected = ref(false);

const toggleSelect = (id) => {
    const idx = selected.value.indexOf(id);
    if (idx === -1) {
        selected.value.push(id);
    } else {
        selected.value.splice(idx, 1);
    }
    allSelected.value = selected.value.length === props.messages.data.length;
};

const confirmBulkDelete = () => {
    bulkDeleteForm.ids = [...selected.value];
    bulkDeleteForm.delete(route('messages.bulk-destroy'), {
        onFinish: () => {
            selected.value = [];
            allSelected.value = false;
            isBulkDeleteOpen.value = false;
        },
    });
};

useRekaCleanup(isDeleteOpen);

watch(filter, (val) => {
    router.get(route('messages.index'), { filter: val === 'all' ? undefined : val }, {
        preserveState: true, preserveScroll: true, replace: true,
    });
});

function openDelete(msg) {
    deleteTarget.value = msg;
    isDeleteOpen.value = true;
}

function confirmDelete() {
    deleteForm.delete(route('messages.destroy', deleteTarget.value.id), {
        onSuccess: () => { isDeleteOpen.value = false; deleteTarget.value = null; },
    });
}

function toggleExpand(id) {
    expanded.value = expanded.value === id ? null : id;
    if (expanded.value === id) {
        const msg = props.messages.data.find(m => m.id === id);
        if (msg && !msg.read_at) {
            router.post(route('messages.read', id), {}, { preserveScroll: true });
        }
    }
}

function handlePageChange(page) {
    router.get(route('messages.index'), { ...props.filters, page }, {
        preserveState: true, preserveScroll: true,
    });
}

</script>

<template>
  <Head :title="t('messages.title')" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h2 class="text-3xl font-black tracking-tight text-neutral-900 dark:text-white uppercase">
            {{ t('messages.title') }}
          </h2>
          <p class="text-xs font-bold text-neutral-500 dark:text-neutral-300 uppercase tracking-[0.2em] mt-1">
            {{ t('messages.subtitle') }}
          </p>
        </div>

        <div class="flex items-center gap-3">
          <Badge
            v-if="unreadCount > 0"
            class="bg-brutalist-pink text-white border-0 text-xs font-black px-3 py-1"
          >
            {{ t('messages.stats.unread', { count: unreadCount }) }}
          </Badge>
          <Button
            variant="outline"
            size="sm"
            class="rounded-xl font-bold uppercase text-xs tracking-widest"
            @click="window.location.href = route('messages.export.csv', { filter: filter !== 'all' ? filter : undefined })"
          >
            <Download class="w-4 h-4 mr-1.5" />
            CSV
          </Button>
          <Button
            v-if="unreadCount > 0"
            variant="outline"
            size="sm"
            class="rounded-xl font-bold uppercase text-xs tracking-widest"
            @click="router.post(route('messages.read-all'), {}, { preserveScroll: true })"
          >
            <CheckCheck class="w-4 h-4 mr-1.5" />
            {{ t('messages.actions.mark_all_read') }}
          </Button>
          <Button
            v-if="selected.length > 0"
            variant="outline"
            size="sm"
            class="rounded-xl border-status-danger/30 text-xs font-bold uppercase tracking-widest text-status-danger hover:bg-status-danger/10"
            @click="isBulkDeleteOpen = true"
          >
            <Trash2 class="w-4 h-4 mr-1.5" />
            {{ t('messages.actions.delete') }} ({{ selected.length }})
          </Button>
        </div>
      </div>
    </template>

    <div class="space-y-6">
      <!-- Filters -->
      <div class="flex items-center gap-2 bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-2 rounded-2xl shadow-sm w-fit">
        <button
          v-for="opt in [
            { key: 'all', label: t('messages.filters.all') },
            { key: 'unread', label: t('messages.filters.unread') },
            { key: 'read', label: t('messages.filters.read') },
          ]"
          :key="opt.key"
          :class="['px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2',
                   filter === opt.key
                     ? 'bg-black dark:bg-white text-white dark:text-black shadow-sm'
                     : 'text-neutral-400 hover:text-neutral-700 dark:hover:text-neutral-300'
          ]"
          @click="filter = opt.key"
        >
          {{ opt.label }}
        </button>
      </div>

      <Transition
        name="fade"
        mode="out-in"
      >
        <!-- Skeleton List -->
        <div
          v-if="!skeletonReady"
          key="skeleton"
          class="space-y-3"
        >
          <div
            v-for="i in 6"
            :key="'skel-' + i"
            class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-2xl overflow-hidden pointer-events-none select-none relative"
          >
            <div class="absolute inset-0 shimmer-sweep z-10" />
            <div class="flex items-center gap-4 p-5">
              <div class="w-2 h-2 rounded-full skeleton-bg shrink-0" />
              <div class="w-10 h-10 rounded-xl skeleton-bg shrink-0" />
              <div class="flex-1 space-y-2">
                <div class="flex items-center gap-3">
                  <div class="h-4 w-32 rounded skeleton-bg" />
                  <div class="h-3 w-24 rounded skeleton-bg" />
                </div>
                <div class="h-3 w-3/4 rounded skeleton-bg" />
              </div>
              <div class="h-3 w-20 rounded skeleton-bg shrink-0 hidden sm:block" />
              <div class="h-8 w-8 rounded-lg skeleton-bg shrink-0" />
            </div>
          </div>
        </div>

        <!-- Messages List -->
        <div
          v-else
          key="content"
          class="space-y-3"
        >
          <MessageRow
            v-for="msg in messages.data"
            :key="msg.id"
            :msg="msg"
            :expanded="expanded === msg.id"
            :is-selected="selected.includes(msg.id)"
            @expand="toggleExpand"
            @select="toggleSelect"
            @delete="openDelete"
          />
        </div>
      </Transition>

      <!-- Empty state -->
      <div
        v-if="skeletonReady && messages.data.length === 0"
        class="flex flex-col items-center justify-center py-24 bg-white dark:bg-black border-2 border-dashed border-neutral-200 dark:border-neutral-800 rounded-3xl"
      >
        <div class="p-6 rounded-full bg-neutral-100 dark:bg-neutral-800 mb-6">
          <Inbox class="w-12 h-12 text-neutral-300 dark:text-neutral-600" />
        </div>
        <h3 class="text-xl font-black uppercase tracking-tight text-neutral-400">
          {{ t('messages.empty.title') }}
        </h3>
        <p class="text-xs font-bold text-neutral-500 uppercase tracking-widest mt-2">
          {{ filter === 'unread' ? t('messages.empty.unread') : t('messages.empty.all') }}
        </p>
      </div>

      <!-- Pagination -->
      <div
        v-if="skeletonReady && messages.total > 0"
        class="flex flex-col items-center gap-3 pt-4"
      >
        <Pagination
          v-if="messages.last_page > 1"
          :total="messages.total"
          :sibling-count="1"
          :items-per-page="messages.per_page"
          :default-page="messages.current_page"
          @update:page="handlePageChange"
        >
          <PaginationList
            v-slot="{ items }"
            class="flex items-center gap-2 bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-2 rounded-2xl shadow-xl"
          >
            <PaginationFirst /><PaginationPrev />
            <template v-for="(item, index) in items">
              <PaginationListItem
                v-if="item.type === 'page'"
                :key="index"
                :value="item.value"
                :as-child="true"
              />
              <PaginationEllipsis
                v-else
                :key="item.type"
                :index="index"
              />
            </template>
            <PaginationNext /><PaginationLast />
          </PaginationList>
        </Pagination>
        <p class="text-xs font-bold text-neutral-400 uppercase tracking-widest">
          {{ t('messages.pagination.showing', { from: messages.from, to: messages.to, total: messages.total }) }}
        </p>
      </div>
    </div>

    <!-- Single delete -->
    <ConfirmDialog
      v-model:open="isDeleteOpen"
      :icon="AlertTriangle"
      variant="danger"
      :description="t('messages.actions.delete_confirm', { name: deleteTarget?.nombre })"
      :loading="deleteForm.processing"
      @confirm="confirmDelete"
    />

    <!-- Bulk delete -->
    <ConfirmDialog
      v-model:open="isBulkDeleteOpen"
      :description="t('messages.actions.delete_confirm_bulk', { count: selected.length })"
      :loading="bulkDeleteForm.processing"
      :confirm-label="t('actions.delete') + ' (' + selected.length + ')'"
      @confirm="confirmBulkDelete"
    />
  </AuthenticatedLayout>
</template>
