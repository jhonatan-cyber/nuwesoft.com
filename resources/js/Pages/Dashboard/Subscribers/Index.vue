<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { ref, computed } from 'vue'
import { useSkeletonLoader } from '@/composables/useSkeletonLoader'
import { Users, UserCheck, UserX, Calendar, Search, Trash2, Download, X, Mail } from 'lucide-vue-next'
import ConfirmDialog from '@/Components/ConfirmDialog.vue'

const { t } = useI18n()
const { skeletonReady } = useSkeletonLoader()

const props = defineProps({
    subscribers: { type: Object, default: () => ({}) },
    stats: { type: Object, default: () => ({}) },
    currentStatus: { type: String, default: 'all' },
    currentSearch: { type: String, default: '' },
})

const searchQuery = ref(props.currentSearch)

const statusFilters = [
    { value: 'all', label: 'TODOS' },
    { value: 'active', label: 'ACTIVOS' },
    { value: 'unsubscribed', label: 'DESUSCRITOS' },
]

const filterByStatus = (status) => {
    router.get(route('subscribers.index', { status, search: searchQuery.value }), {}, { preserveState: true })
}

const search = () => {
    router.get(route('subscribers.index', { status: props.currentStatus, search: searchQuery.value }), {}, { preserveState: true })
}

const clearSearch = () => {
    searchQuery.value = ''
    router.get(route('subscribers.index', { status: props.currentStatus }), {}, { preserveState: true })
}

// Delete
const isDeleteOpen = ref(false)
const deleteTarget = ref(null)
const isDeleting = ref(false)

const openDelete = (subscriber) => {
    deleteTarget.value = subscriber
    isDeleteOpen.value = true
}

const confirmDelete = () => {
    if (!deleteTarget.value) return
    isDeleting.value = true
    router.delete(route('subscribers.destroy', deleteTarget.value.id), {
        onFinish: () => {
            isDeleting.value = false
            isDeleteOpen.value = false
            deleteTarget.value = null
        },
    })
}

// Bulk delete
const selectedIds = ref([])
const selectAll = computed({
    get: () => props.subscribers.data?.length > 0 && selectedIds.value.length === props.subscribers.data.length,
    set: (val) => {
        selectedIds.value = val ? props.subscribers.data.map(s => s.id) : []
    },
})

const toggleSelect = (id) => {
    const idx = selectedIds.value.indexOf(id)
    if (idx > -1) selectedIds.value.splice(idx, 1)
    else selectedIds.value.push(id)
}

const bulkDelete = () => {
    if (!selectedIds.value.length) return
    if (!confirm(`Eliminar ${selectedIds.value.length} suscriptores?`)) return

    router.post(route('subscribers.bulk-destroy'), { ids: selectedIds.value }, {
        onFinish: () => { selectedIds.value = [] },
    })
}

const exportCsv = () => {
    window.location.href = route('subscribers.export', { status: props.currentStatus })
}

const formatDate = (dateStr) => {
    if (!dateStr) return '—'
    return new Date(dateStr).toLocaleDateString('es-AR', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>

<template>
    <Head title="Suscriptores | Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-display font-bold tracking-tight text-neutral-900 dark:text-white uppercase italic">
                        SUSCRIPTORES
                    </h2>
                    <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-[0.2em] mt-1">
                        NEWSLETTER / {{ stats.active }} ACTIVOS
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button v-if="selectedIds.length" @click="bulkDelete"
                        class="flex items-center gap-2 bg-red-500 text-white px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-red-600 transition-all">
                        <Trash2 class="w-4 h-4" />
                        ELIMINAR ({{ selectedIds.length }})
                    </button>
                    <button @click="exportCsv"
                        class="flex items-center gap-2 border-2 border-neutral-200 dark:border-neutral-700 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-widest hover:border-black dark:hover:border-white transition-all">
                        <Download class="w-4 h-4" />
                        EXPORTAR CSV
                    </button>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-2xl p-5">
                    <Users class="w-5 h-5 text-neutral-400 mb-2" />
                    <p class="text-2xl font-black text-neutral-900 dark:text-white">{{ stats.total }}</p>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Total</p>
                </div>
                <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-2xl p-5">
                    <UserCheck class="w-5 h-5 text-green-500 mb-2" />
                    <p class="text-2xl font-black text-green-600">{{ stats.active }}</p>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Activos</p>
                </div>
                <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-2xl p-5">
                    <UserX class="w-5 h-5 text-red-400 mb-2" />
                    <p class="text-2xl font-black text-red-500">{{ stats.unsubscribed }}</p>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Desuscriptos</p>
                </div>
                <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-2xl p-5">
                    <Calendar class="w-5 h-5 text-brutalist-yellow mb-2" />
                    <p class="text-2xl font-black text-brutalist-yellow">{{ stats.this_month }}</p>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Este mes</p>
                </div>
            </div>

            <Transition name="fade" mode="out-in">
                <div v-if="!skeletonReady" key="skeleton" class="space-y-4">
                    <div v-for="i in 5" :key="'skel-'+i"
                        class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-2xl p-5 overflow-hidden relative pointer-events-none select-none">
                        <div class="absolute inset-0 shimmer-sweep z-10"></div>
                        <div class="relative z-20 flex items-center gap-4">
                            <div class="w-5 h-5 skeleton-bg rounded"></div>
                            <div class="flex-1 space-y-2">
                                <div class="h-4 w-48 skeleton-bg"></div>
                                <div class="h-3 w-32 skeleton-bg"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else key="content">
                    <!-- Filters + Search -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
                        <div class="flex items-center gap-2">
                            <button v-for="filter in statusFilters" :key="filter.value"
                                @click="filterByStatus(filter.value)"
                                :class="[
                                    'px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded-lg transition-all',
                                    currentStatus === filter.value
                                        ? 'bg-black dark:bg-white text-white dark:text-black'
                                        : 'bg-neutral-100 dark:bg-neutral-800 text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300'
                                ]">
                                {{ filter.label }}
                                <span v-if="filter.value === 'active'" class="ml-1 text-green-500">({{ stats.active }})</span>
                            </button>
                        </div>

                        <div class="flex-1"></div>

                        <!-- Search -->
                        <div class="relative">
                            <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" />
                            <input v-model="searchQuery"
                                @keyup.enter="search"
                                placeholder="Buscar por email o nombre..."
                                class="w-full sm:w-64 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl pl-10 pr-8 py-2 text-xs font-bold focus:border-black dark:focus:border-white focus:outline-none" />
                            <button v-if="searchQuery" @click="clearSearch"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-600">
                                <X class="w-4 h-4" />
                            </button>
                        </div>
                    </div>

                    <!-- List -->
                    <div v-if="subscribers.data?.length" class="space-y-2">
                        <div class="flex items-center gap-3 px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-neutral-400">
                            <input type="checkbox" v-model="selectAll"
                                class="w-4 h-4 border-2 border-neutral-300 rounded" />
                            <span class="w-10">#</span>
                            <span class="flex-1">EMAIL</span>
                            <span class="w-32 hidden md:block">NOMBRE</span>
                            <span class="w-20 hidden md:block">FUENTE</span>
                            <span class="w-24 hidden md:block">ESTADO</span>
                            <span class="w-24 hidden md:block">FECHA</span>
                            <span class="w-10"></span>
                        </div>

                        <div v-for="item in subscribers.data" :key="item.id"
                            class="flex items-center gap-3 bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-xl px-4 py-3 hover:shadow-sm transition-all">
                            <input type="checkbox" :checked="selectedIds.includes(item.id)"
                                @change="toggleSelect(item.id)"
                                class="w-4 h-4 border-2 border-neutral-300 rounded" />
                            <span class="w-10 text-[10px] font-bold text-neutral-400">{{ item.id }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-neutral-900 dark:text-white truncate">{{ item.email }}</p>
                            </div>
                            <span class="w-32 text-xs text-neutral-500 hidden md:block truncate">{{ item.name || '—' }}</span>
                            <span class="w-20 hidden md:block">
                                <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded bg-neutral-100 dark:bg-neutral-800 text-neutral-500">
                                    {{ item.source }}
                                </span>
                            </span>
                            <span class="w-24 hidden md:block">
                                <span v-if="item.status === 'active'"
                                    class="px-2 py-0.5 text-[9px] font-bold uppercase rounded bg-green-100 dark:bg-green-900/30 text-green-600">
                                    ACTIVO
                                </span>
                                <span v-else
                                    class="px-2 py-0.5 text-[9px] font-bold uppercase rounded bg-red-100 dark:bg-red-900/30 text-red-500">
                                    {{ item.status === 'unsubscribed' ? 'DESUSC.' : item.status.toUpperCase() }}
                                </span>
                            </span>
                            <span class="w-24 text-[10px] text-neutral-400 hidden md:block">{{ formatDate(item.subscribed_at || item.created_at) }}</span>
                            <button @click="openDelete(item)"
                                class="p-2 border border-neutral-200 dark:border-neutral-700 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-all text-red-500">
                                <Trash2 class="w-4 h-4" />
                            </button>
                        </div>
                    </div>

                    <div v-else class="bg-white dark:bg-black border border-dashed border-neutral-200 dark:border-neutral-800 rounded-2xl p-12 text-center">
                        <Mail class="w-12 h-12 text-neutral-300 dark:text-neutral-600 mx-auto mb-4" />
                        <p class="text-lg font-bold uppercase tracking-tight text-neutral-400">
                            {{ searchQuery ? 'NO SE ENCONTRARON RESULTADOS' : 'NO HAY SUSCRIPTORES' }}
                        </p>
                    </div>

                    <!-- Pagination -->
                    <div v-if="subscribers.last_page > 1" class="flex items-center justify-center gap-2 mt-8">
                        <template v-for="link in subscribers.links" :key="link.label">
                            <button v-if="link.url" @click="router.get(link.url, {}, { preserveState: true })"
                                :class="[
                                    'px-3 py-1.5 text-xs font-bold rounded-lg transition-all',
                                    link.active
                                        ? 'bg-black dark:bg-white text-white dark:text-black'
                                        : 'border border-neutral-200 dark:border-neutral-700 text-neutral-500 hover:border-black dark:hover:border-white'
                                ]" v-html="link.label">
                            </button>
                        </template>
                    </div>
                </div>
            </Transition>

            <ConfirmDialog
                v-model:open="isDeleteOpen"
                :description="'Eliminar a ' + (deleteTarget?.email || '') + '? Esta acción no se puede deshacer.'"
                :loading="isDeleting"
                @confirm="confirmDelete"
            />
        </div>
    </AuthenticatedLayout>
</template>

<style>
.font-display { font-family: 'Space Grotesk', sans-serif; }
</style>
