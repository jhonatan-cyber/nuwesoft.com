import { router, useForm } from '@inertiajs/vue3';
import { ArrowDown, ArrowUp, ArrowUpDown } from 'lucide-vue-next';
import { nextTick, ref, watch } from 'vue';
import { useRekaCleanup } from '@/composables/useRekaCleanup';

const categoryColors = {
    languages: 'bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 border-neutral-200 dark:border-neutral-700',
    frontend: 'bg-neutral-900 dark:bg-white text-white dark:text-black border-neutral-900 dark:border-white',
    backend: 'bg-neutral-300 dark:bg-neutral-600 text-neutral-900 dark:text-white border-neutral-400 dark:border-neutral-500',
    mobile: 'bg-neutral-200 dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200 border-neutral-300 dark:border-neutral-600',
    database: 'bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 border-neutral-200 dark:border-neutral-700',
    infrastructure: 'bg-neutral-300 dark:bg-neutral-600 text-neutral-900 dark:text-white border-neutral-400 dark:border-neutral-500',
    automation: 'bg-neutral-900 dark:bg-white text-white dark:text-black border-neutral-900 dark:border-white',
    ui: 'bg-neutral-200 dark:bg-neutral-700 text-neutral-700 dark:text-neutral-300 border-neutral-300 dark:border-neutral-600',
};

export function useTechnologyIndex(props) {
    const search = ref(props.filters?.search || '');
    const sortField = ref(props.filters?.sort_field || 'created_at');
    const sortOrder = ref(props.filters?.sort_order || 'desc');
    const perPage = ref(String(props.technologies.per_page || 24));
    const deleteTarget = ref(null);
    const isDeleteModalOpen = ref(false);
    const deleteForm = useForm({});
    const isCreateModalOpen = ref(false);
    const isEditModalOpen = ref(false);
    const editingTechnology = ref(null);

    useRekaCleanup(isCreateModalOpen, isEditModalOpen, isDeleteModalOpen);

    const applyFilters = (extra = {}) => router.get(route('technologies.index'), {
        search: search.value || '', sort_field: sortField.value,
        sort_order: sortOrder.value, per_page: perPage.value, ...extra,
    }, {
        preserveState: true, preserveScroll: true, replace: true,
        only: ['technologies', 'filters'],
    });

    let debounceTimer;
    watch(search, () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => applyFilters(), 400);
    });
    watch(perPage, () => applyFilters());
    watch([sortField, sortOrder], () => applyFilters());
    watch(isDeleteModalOpen, (open) => { if (!open) deleteTarget.value = null; });

    const handlePageChange = (page) => applyFilters({ page });
    const toggleSort = (field) => {
        if (sortField.value === field) sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
        else {
            sortField.value = field;
            sortOrder.value = 'asc';
        }
    };
    const sortIcon = (field) => sortField.value !== field
        ? ArrowUpDown
        : (sortOrder.value === 'asc' ? ArrowUp : ArrowDown);
    const openEditModal = (technology) => {
        editingTechnology.value = technology;
        nextTick(() => { isEditModalOpen.value = true; });
    };
    const closeModals = () => {
        isCreateModalOpen.value = false;
        isEditModalOpen.value = false;
        nextTick(() => { editingTechnology.value = null; });
    };
    const openDeleteConfirm = (technology) => {
        deleteTarget.value = technology;
        nextTick(() => { isDeleteModalOpen.value = true; });
    };
    const confirmDelete = () => {
        if (!deleteTarget.value) return;
        deleteForm.delete(route('technologies.destroy', deleteTarget.value.id), {
            onSuccess: () => {
                isDeleteModalOpen.value = false;
                deleteTarget.value = null;
            },
        });
    };
    const getCategoryColor = (category) => categoryColors[category]
        || 'bg-neutral-100 dark:bg-neutral-800 text-neutral-500 dark:text-neutral-400 border-neutral-200 dark:border-neutral-700';

    return {
        search, sortField, sortOrder, perPage, deleteTarget, isDeleteModalOpen,
        deleteForm, isCreateModalOpen, isEditModalOpen, editingTechnology,
        handlePageChange, toggleSort, sortIcon, openEditModal, closeModals,
        openDeleteConfirm, confirmDelete, getCategoryColor,
    };
}
