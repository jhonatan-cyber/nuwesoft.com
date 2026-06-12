<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useSkeletonLoader } from '@/composables/useSkeletonLoader';
import { useRekaCleanup } from '@/composables/useRekaCleanup';
import { useI18n } from 'vue-i18n';
import {
    Mail, MailOpen, Trash2, CheckCheck, RefreshCw,
    Search, X, ChevronRight, AlertTriangle, Inbox
} from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Download } from 'lucide-vue-next';
import {
    Dialog, DialogContent, DialogHeader,
    DialogTitle, DialogDescription, DialogFooter,
} from '@/Components/ui/dialog';
import { Input } from '@/Components/ui/input';
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

function formatDate(date) {
    return new Date(date).toLocaleDateString('es-AR', {
        day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit',
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
                    <Badge v-if="unreadCount > 0"
                        class="bg-brutalist-pink text-white border-0 text-xs font-black px-3 py-1">
                        {{ t('messages.stats.unread', { count: unreadCount }) }}
                    </Badge>
                    <Button
                        variant="outline"
                        size="sm"
                        class="rounded-xl font-bold uppercase text-[10px] tracking-widest"
                        @click="window.location.href = route('messages.export.csv', { filter: filter !== 'all' ? filter : undefined })"
                    >
                        <Download class="w-4 h-4 mr-1.5" />
                        CSV
                    </Button>
                    <Button
                        v-if="unreadCount > 0"
                        variant="outline"
                        size="sm"
                        class="rounded-xl font-bold uppercase text-[10px] tracking-widest"
                        @click="router.post(route('messages.read-all'), {}, { preserveScroll: true })"
                    >
                        <CheckCheck class="w-4 h-4 mr-1.5" />
                        {{ t('messages.actions.mark_all_read') }}
                    </Button>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Filters -->
            <div class="flex items-center gap-2 bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-2 rounded-2xl shadow-sm w-fit">
                <button v-for="opt in [
                    { key: 'all', label: t('messages.filters.all') },
                    { key: 'unread', label: t('messages.filters.unread') },
                    { key: 'read', label: t('messages.filters.read') },
                ]" :key="opt.key"
                    @click="filter = opt.key"
                    :class="['px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2',
                                    filter === opt.key
                                        ? 'bg-black dark:bg-white text-white dark:text-black shadow-sm'
                                        : 'text-neutral-400 hover:text-neutral-700 dark:hover:text-neutral-300'
                    ]"
                >
                    {{ opt.label }}
                </button>
            </div>

            <Transition name="fade" mode="out-in">
                <!-- Skeleton List -->
                <div v-if="!skeletonReady" key="skeleton" class="space-y-3">
                    <div v-for="i in 6" :key="'skel-' + i"
                        class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-2xl overflow-hidden pointer-events-none select-none relative">
                        <div class="absolute inset-0 shimmer-sweep z-10"></div>
                        <div class="flex items-center gap-4 p-5">
                            <div class="w-2 h-2 rounded-full skeleton-bg shrink-0"></div>
                            <div class="w-10 h-10 rounded-xl skeleton-bg shrink-0"></div>
                            <div class="flex-1 space-y-2">
                                <div class="flex items-center gap-3">
                                    <div class="h-4 w-32 rounded skeleton-bg"></div>
                                    <div class="h-3 w-24 rounded skeleton-bg"></div>
                                </div>
                                <div class="h-3 w-3/4 rounded skeleton-bg"></div>
                            </div>
                            <div class="h-3 w-20 rounded skeleton-bg shrink-0 hidden sm:block"></div>
                            <div class="h-8 w-8 rounded-lg skeleton-bg shrink-0"></div>
                        </div>
                    </div>
                </div>

                <!-- Messages List -->
                <div v-else key="content" class="space-y-3">
                <div v-for="msg in messages.data" :key="msg.id"
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
                        @click="toggleExpand(msg.id)"
                    >
                        <!-- Read indicator -->
                        <div :class="[
                            'w-2 h-2 rounded-full shrink-0',
                            msg.read_at ? 'bg-neutral-300 dark:bg-neutral-700' : 'bg-brutalist-pink animate-pulse'
                        ]" />

                        <!-- Icon -->
                        <div :class="[
                            'w-10 h-10 rounded-xl flex items-center justify-center shrink-0',
                            msg.read_at ? 'bg-neutral-100 dark:bg-neutral-800' : 'bg-brutalist-pink/10'
                        ]">
                            <component :is="msg.read_at ? MailOpen : Mail"
                                :class="['w-5 h-5', msg.read_at ? 'text-neutral-400' : 'text-brutalist-pink']" />
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span :class="['text-sm font-black uppercase truncate', !msg.read_at && 'text-black dark:text-white']">
                                    {{ msg.nombre }}
                                </span>
                                <span class="text-[10px] text-neutral-400 font-mono break-all">{{ msg.email }}</span>
                            </div>
                            <p class="text-[11px] text-neutral-500 truncate mt-0.5">
                                {{ msg.mensaje }}
                            </p>
                        </div>

                        <!-- Date + actions -->
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-[10px] text-neutral-400 font-mono hidden sm:block">
                                {{ formatDate(msg.created_at) }}
                            </span>
                            <Button variant="ghost" size="sm"
                                class="h-8 w-8 p-0 rounded-lg text-rose-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10"
                                @click.stop="openDelete(msg)"
                            >
                                <Trash2 class="w-4 h-4" />
                            </Button>
                            <ChevronRight :class="[
                                'w-4 h-4 text-neutral-300 transition-transform duration-200',
                                expanded === msg.id && 'rotate-90'
                            ]" />
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
                        <div v-if="expanded === msg.id"
                            class="border-t border-neutral-100 dark:border-neutral-800 px-5 pb-5 pt-4 bg-neutral-50 dark:bg-neutral-900">
                            <p class="text-sm text-neutral-700 dark:text-neutral-300 leading-relaxed whitespace-pre-wrap">
                                {{ msg.mensaje }}
                            </p>
                            <div class="mt-4 flex items-center justify-between">
                                <a :href="`mailto:${msg.email}`"
                                    class="text-[10px] font-black uppercase tracking-widest text-brutalist-pink hover:underline">
                                    {{ t('messages.actions.reply_by_email') }} →
                                </a>
                                <span class="text-[10px] text-neutral-400">
                                    {{ formatDate(msg.created_at) }}
                                </span>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>

            </Transition>

            <!-- Empty state -->
            <div v-if="skeletonReady && messages.data.length === 0"
                class="flex flex-col items-center justify-center py-24 bg-white dark:bg-black border-2 border-dashed border-neutral-200 dark:border-neutral-800 rounded-3xl">
                <div class="p-6 rounded-full bg-neutral-100 dark:bg-neutral-800 mb-6">
                    <Inbox class="w-12 h-12 text-neutral-300 dark:text-neutral-600" />
                </div>
                <h3 class="text-xl font-black uppercase tracking-tight text-neutral-400">{{ t('messages.empty.title') }}</h3>
                <p class="text-xs font-bold text-neutral-500 uppercase tracking-widest mt-2">
                    {{ filter === 'unread' ? t('messages.empty.unread') : t('messages.empty.all') }}
                </p>
            </div>

            <!-- Pagination -->
            <div v-if="skeletonReady && messages.total > 0" class="flex flex-col items-center gap-3 pt-4">
                <Pagination
                    v-if="messages.last_page > 1"
                    v-slot="{ page }"
                    :total="messages.total"
                    :sibling-count="1"
                    :items-per-page="messages.per_page"
                    :default-page="messages.current_page"
                    @update:page="handlePageChange"
                >
                    <PaginationList v-slot="{ items }" class="flex items-center gap-2 bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-2 rounded-2xl shadow-xl">
                        <PaginationFirst /><PaginationPrev />
                        <template v-for="(item, index) in items">
                            <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value" :as-child="true" />
                            <PaginationEllipsis v-else :key="item.type" :index="index" />
                        </template>
                        <PaginationNext /><PaginationLast />
                    </PaginationList>
                </Pagination>
                <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest">
                    {{ t('messages.pagination.showing', { from: messages.from, to: messages.to, total: messages.total }) }}
                </p>
            </div>
        </div>

        <!-- Delete confirmation -->
        <Dialog v-model:open="isDeleteOpen">
            <DialogContent class="sm:max-w-[400px] !rounded-[2rem] border border-neutral-200 dark:border-neutral-800 !bg-white dark:!bg-black shadow-2xl p-8 dashboard-dialog-enter">
                <DialogHeader>
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-neutral-100 dark:bg-neutral-800">
                        <AlertTriangle class="h-7 w-7 text-rose-500" />
                    </div>
                    <DialogTitle class="text-center text-xl font-black uppercase">{{ t('messages.actions.delete') }}</DialogTitle>
                    <DialogDescription class="text-center text-xs font-bold text-neutral-400 uppercase tracking-widest">
                        {{ t('messages.actions.delete_confirm', { name: deleteTarget?.nombre }) }}
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="flex flex-col-reverse sm:flex-row gap-3 sm:justify-center pt-2">
                    <Button variant="outline" @click="isDeleteOpen = false"
                        class="rounded-xl font-bold uppercase text-[10px] tracking-widest">
                        {{ t('messages.actions.cancel') }}
                    </Button>
                    <Button @click="confirmDelete" :disabled="deleteForm.processing"
                        class="rounded-xl bg-rose-500 hover:bg-rose-600 text-white font-bold uppercase text-[10px] tracking-widest shadow-lg shadow-rose-500/20">
                        <span v-if="deleteForm.processing" class="animate-pulse">{{ t('messages.actions.deleting') }}</span>
                        <span v-else>{{ t('actions.delete') }}</span>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AuthenticatedLayout>
</template>
