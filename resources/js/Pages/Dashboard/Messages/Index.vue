<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { ref } from 'vue';
import {
    Mail,
    Trash2,
    CheckCheck,
    Eye,
    User,
    Clock,
    MessageSquare,
    ChevronRight,
} from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import Modal from '@/Components/Modal.vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/Components/ui/dialog';
import {
    Pagination,
    PaginationEllipsis,
    PaginationFirst,
    PaginationLast,
    PaginationList,
    PaginationListItem,
    PaginationNext,
    PaginationPrev,
} from '@/Components/ui/pagination';

const props = defineProps({
    messages: Object,
});

const { t } = useI18n();
const selectedMessage = ref(null);
const deleteConfirm = ref(null);

const openMessage = (message) => {
    selectedMessage.value = message;
    if (!message.is_read) {
        router.patch(route('messages.markAsRead', message.id), {}, {
            preserveState: true,
            preserveScroll: true,
        });
        message.is_read = true;
    }
};

const closeMessage = () => {
    selectedMessage.value = null;
};

const confirmDelete = (message) => {
    deleteConfirm.value = message;
};

const deleteMessage = () => {
    if (deleteConfirm.value) {
        router.delete(route('messages.destroy', deleteConfirm.value.id));
        deleteConfirm.value = null;
        if (selectedMessage.value?.id === deleteConfirm.value?.id) {
            selectedMessage.value = null;
        }
    }
};

const handlePageChange = (page) => {
    router.get(route('messages.index'), { page }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('es-CL', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head :title="'Mensajes de Contacto'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="space-y-1">
                    <h2 class="text-3xl md:text-4xl font-display font-bold tracking-tight text-slate-900 dark:text-white uppercase italic">
                        Mensajes de Contacto
                    </h2>
                    <div class="flex items-center gap-3">
                        <div class="h-0.5 w-8 bg-indigo-500 rounded-full"></div>
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">
                            Bandeja de entrada
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-4 py-2 rounded-2xl shadow-sm">
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <Mail class="w-4 h-4" /> {{ messages.total }} mensajes
                        </span>
                    </div>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Empty State -->
            <div v-if="messages.data.length === 0"
                class="bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 p-20 rounded-[40px] text-center shadow-sm">
                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <Mail class="w-10 h-10 text-slate-300 dark:text-slate-600" />
                </div>
                <p class="text-xl font-bold uppercase tracking-tight text-slate-400">
                    No hay mensajes
                </p>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mt-2">
                    Los mensajes del formulario de contacto aparecerán aquí
                </p>
            </div>

            <!-- Messages List -->
            <div v-else class="space-y-3">
                <div v-for="message in messages.data" :key="message.id"
                    @click="openMessage(message)"
                    class="group bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 p-6 rounded-3xl shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all cursor-pointer overflow-hidden relative"
                    :class="{ 'border-l-4 border-l-indigo-500': !message.is_read }">
                    
                    <div class="flex items-start gap-4">
                        <!-- Avatar -->
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 transition-all duration-300"
                            :class="message.is_read ? 'bg-slate-100 dark:bg-slate-800' : 'bg-indigo-100 dark:bg-indigo-900/30'">
                            <User class="w-6 h-6"
                                :class="message.is_read ? 'text-slate-400' : 'text-indigo-600 dark:text-indigo-400'" />
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="font-bold text-sm text-slate-900 dark:text-white truncate"
                                    :class="{ 'font-extrabold': !message.is_read }">
                                    {{ message.name }}
                                </h3>
                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase">
                                    {{ message.email }}
                                </span>
                                <div v-if="!message.is_read"
                                    class="w-2 h-2 bg-indigo-500 rounded-full shrink-0 animate-pulse"></div>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed mt-1">
                                {{ message.message }}
                            </p>
                        </div>

                        <!-- Meta -->
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest hidden sm:block">
                                {{ formatDate(message.created_at) }}
                            </span>
                            <div class="flex items-center gap-1">
                                <Button variant="ghost" size="sm" @click.stop="openMessage(message)"
                                    class="h-8 w-8 p-0 rounded-xl text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400">
                                    <Eye class="w-4 h-4" />
                                </Button>
                                <Button variant="ghost" size="sm" @click.stop="confirmDelete(message)"
                                    class="h-8 w-8 p-0 rounded-xl text-slate-400 hover:text-rose-500">
                                    <Trash2 class="w-4 h-4" />
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="messages.last_page > 1" class="flex justify-center pt-8">
                <Pagination
                    v-slot="{ page }"
                    :total="messages.total"
                    :sibling-count="1"
                    :items-per-page="messages.per_page"
                    :default-page="messages.current_page"
                    @update:page="handlePageChange"
                >
                    <PaginationList v-slot="{ items }" class="flex items-center gap-2 bg-white/50 dark:bg-slate-900/50 backdrop-blur-xl border border-slate-200 dark:border-slate-800 p-2 rounded-2xl shadow-xl">
                        <PaginationFirst />
                        <PaginationPrev />
                        <template v-for="(item, index) in items">
                            <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value" :as-child="true" />
                            <PaginationEllipsis v-else :key="item.type" :index="index" />
                        </template>
                        <PaginationNext />
                        <PaginationLast />
                    </PaginationList>
                </Pagination>
            </div>
        </div>

        <!-- Read Message Modal -->
        <Modal :show="!!selectedMessage" @close="closeMessage" max-width="2xl">
            <div v-if="selectedMessage" class="p-8 bg-white dark:bg-slate-950 rounded-[32px] border border-slate-200 dark:border-slate-800">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center">
                            <User class="w-7 h-7 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ selectedMessage.name }}</h3>
                            <a :href="`mailto:${selectedMessage.email}`"
                                class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ selectedMessage.email }}
                            </a>
                        </div>
                    </div>
                    <button @click="closeMessage"
                        class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                        ✕
                    </button>
                </div>

                <div class="flex items-center gap-2 mb-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    <Clock class="w-3 h-3" />
                    {{ formatDate(selectedMessage.created_at) }}
                </div>

                <div class="bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 rounded-2xl p-6">
                    <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">
                        {{ selectedMessage.message }}
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-slate-100 dark:border-slate-800">
                    <a :href="`mailto:${selectedMessage.email}?subject=Re: Mensaje desde NUWESOFT&body=Hola ${selectedMessage.name},%0A%0A`"
                        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl font-bold uppercase tracking-widest text-[10px] transition-all shadow-lg shadow-indigo-600/20">
                        <Mail class="w-4 h-4" /> Responder
                    </a>
                </div>
            </div>
        </Modal>

        <!-- Delete Confirmation -->
        <Modal :show="!!deleteConfirm" @close="deleteConfirm = null" max-width="md">
            <div v-if="deleteConfirm" class="p-8 bg-white dark:bg-slate-950 rounded-[32px] border border-slate-200 dark:border-slate-800 text-center">
                <div class="w-16 h-16 bg-rose-100 dark:bg-rose-900/30 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <Trash2 class="w-8 h-8 text-rose-600 dark:text-rose-400" />
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Eliminar mensaje</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-8">
                    ¿Estás seguro de eliminar el mensaje de <strong>{{ deleteConfirm.name }}</strong>? Esta acción no se puede deshacer.
                </p>
                <div class="flex items-center justify-center gap-3">
                    <Button variant="ghost" @click="deleteConfirm = null"
                        class="rounded-2xl font-bold uppercase tracking-widest text-[10px]">
                        Cancelar
                    </Button>
                    <Button @click="deleteMessage"
                        class="bg-rose-600 hover:bg-rose-700 text-white px-8 py-3 rounded-2xl font-bold uppercase tracking-widest text-[10px] shadow-lg shadow-rose-600/20 transition-all">
                        <Trash2 class="w-4 h-4 mr-2" /> Eliminar
                    </Button>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<style scoped>
.font-display { font-family: 'Space Grotesk', sans-serif; }
</style>
