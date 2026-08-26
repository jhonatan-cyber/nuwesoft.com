import { router } from '@inertiajs/vue3';
import { nextTick, ref, watch } from 'vue';
import { useRekaCleanup } from '@/composables/useRekaCleanup';

export function useProjectIndex(props) {
    const search = ref(props.filters?.search || '');
    const perPage = ref(String(props.projects.per_page || 10));
    const isCreateModalOpen = ref(false);
    const editingProject = ref(null);
    const updatingStatusId = ref(null);
    const isStatusConfirmOpen = ref(false);
    const statusTarget = ref(null);
    const isDeleteOpen = ref(false);
    const deleteTarget = ref(null);
    const isDeleting = ref(false);

    useRekaCleanup(isCreateModalOpen);
    useRekaCleanup(isStatusConfirmOpen);
    useRekaCleanup(isDeleteOpen);

    watch(perPage, (newValue) => {
        router.get(route('projects.index'), { per_page: newValue }, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    });

    watch(isCreateModalOpen, (open) => {
        if (!open && editingProject.value) {
            nextTick(() => { editingProject.value = null; });
        }
    });

    let searchTimer;
    watch(search, () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            router.get(route('projects.index'), {
                search: search.value,
                per_page: perPage.value,
            }, {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            });
        }, 400);
    });

    const handlePageChange = (page) => router.get(route('projects.index'), {
        page,
        per_page: perPage.value,
        search: search.value,
    }, { preserveState: true, preserveScroll: true });

    const openCreateModal = () => {
        editingProject.value = null;
        isCreateModalOpen.value = true;
    };

    const openEditModal = (project) => {
        editingProject.value = project;
        isCreateModalOpen.value = true;
    };

    const closeFormModal = () => {
        isCreateModalOpen.value = false;
        nextTick(() => { editingProject.value = null; });
    };

    const openStatusConfirmation = (project) => {
        statusTarget.value = project;
        isStatusConfirmOpen.value = true;
    };

    const confirmStatusChange = () => {
        if (!statusTarget.value) return;
        const project = statusTarget.value;
        updatingStatusId.value = project.id;
        router.patch(route('projects.status', project.id), { is_active: !project.is_active }, {
            preserveScroll: true,
            onFinish: () => {
                updatingStatusId.value = null;
                isStatusConfirmOpen.value = false;
                statusTarget.value = null;
            },
        });
    };

    const openDelete = (project) => {
        deleteTarget.value = project;
        isDeleteOpen.value = true;
    };

    const confirmDelete = () => {
        if (!deleteTarget.value) return;
        isDeleting.value = true;
        router.delete(route('projects.destroy', deleteTarget.value.id), {
            onFinish: () => {
                isDeleting.value = false;
                isDeleteOpen.value = false;
                deleteTarget.value = null;
            },
        });
    };

    return {
        search, perPage, isCreateModalOpen, editingProject, updatingStatusId,
        isStatusConfirmOpen, statusTarget, isDeleteOpen, deleteTarget, isDeleting,
        handlePageChange, openCreateModal, openEditModal, closeFormModal,
        openStatusConfirmation, confirmStatusChange, openDelete, confirmDelete,
    };
}
