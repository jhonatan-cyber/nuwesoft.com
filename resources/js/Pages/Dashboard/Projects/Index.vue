<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { ref, watch } from 'vue';
import { 
    Plus, 
    Pencil, 
    Trash2, 
    ExternalLink,
    ChevronRight,
    Briefcase,
    Images,
    X,
    Eye,
    FolderPlus,
    LayoutGrid
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
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select';
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
import ProjectForm from './ProjectForm.vue';

const props = defineProps({
    projects: Object,
    technologies: Array
});

const { t } = useI18n();

const selectedProjectForGallery = ref(null);
const isCreateModalOpen = ref(false);
const editingProject = ref(null);

const perPage = ref(String(props.projects.per_page || 10));

watch(perPage, (newValue) => {
    router.get(route('projects.index'), { per_page: newValue }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
});

const handlePageChange = (page) => {
    router.get(route('projects.index'), { 
        page: page,
        per_page: perPage.value 
    }, {
        preserveState: true,
        preserveScroll: true
    });
};

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
    editingProject.value = null;
};

const openGallery = (project) => {
    selectedProjectForGallery.value = project;
};

const closeGallery = () => {
    selectedProjectForGallery.value = null;
};

const deleteProject = (id) => {
    if (confirm(t('actions.confirm_delete'))) {
        router.delete(route('projects.destroy', id));
    }
};
</script>

<template>
    <Head :title="t('dashboard_panel.projects.title')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="space-y-1">
                    <h2 class="text-3xl md:text-4xl font-display font-bold tracking-tight text-slate-900 dark:text-white uppercase italic">
                        {{ t('dashboard_panel.projects.title') }}
                    </h2>
                    <div class="flex items-center gap-3">
                        <div class="h-0.5 w-8 bg-indigo-500 rounded-full"></div>
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">
                            {{ t('dashboard_panel.projects.subtitle') }}
                        </p>
                    </div>
                </div>
                <Button @click="openCreateModal" class="bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-600/20 px-8 py-6 rounded-2xl font-bold uppercase tracking-widest text-xs transition-all hover:scale-105 active:scale-95">
                    <Plus class="w-4 h-4 mr-2" />
                    {{ t('dashboard_panel.projects.create') }}
                </Button>
            </div>
        </template>

        <div class="space-y-8">
            <!-- Paginación Superior y Selector -->
            <div v-if="projects.total > 0" class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white/50 dark:bg-slate-900/50 backdrop-blur-xl border border-slate-200 dark:border-slate-800 p-4 rounded-[28px]">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                        <LayoutGrid class="w-5 h-5 text-indigo-600" />
                    </div>
                    <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        {{ t('pagination.showing') }} {{ projects.from }}-{{ projects.to }} {{ t('pagination.of') }} {{ projects.total }} {{ t('pagination.results') }}
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ t('pagination.per_page') }}</span>
                    <Select v-model="perPage">
                        <SelectTrigger class="w-24 h-10 rounded-xl border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 font-bold text-xs">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent class="rounded-xl border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                            <SelectItem value="5" class="text-xs font-bold">5</SelectItem>
                            <SelectItem value="10" class="text-xs font-bold">10</SelectItem>
                            <SelectItem value="20" class="text-xs font-bold">20</SelectItem>
                            <SelectItem value="50" class="text-xs font-bold">50</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
            </div>

            <div v-if="projects.data.length === 0" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-20 rounded-[40px] text-center shadow-sm">
                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <Briefcase class="w-10 h-10 text-slate-300 dark:text-slate-600" />
                </div>
                <p class="text-xl font-bold uppercase tracking-tight text-slate-400">
                    {{ t('dashboard_panel.projects.empty') }}
                </p>
            </div>

            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                <div v-for="project in projects.data" :key="project.id" 
                    class="bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 p-4 rounded-[32px] shadow-sm hover:shadow-2xl hover:shadow-indigo-500/5 transition-all group overflow-hidden flex flex-col gap-4">
                    
                    <!-- Imagen Principal / Thumbnail -->
                    <div class="w-full h-40 rounded-[24px] bg-slate-100 dark:bg-slate-800 overflow-hidden relative shrink-0 border border-slate-200 dark:border-slate-800 shadow-inner">
                        <img v-if="project.images && project.images.length > 0" :src="project.images[0].image_url" :alt="project.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                        <img v-else-if="project.image_url" :src="project.image_url" :alt="project.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                        <div v-else class="w-full h-full flex items-center justify-center bg-slate-50 dark:bg-slate-900">
                            <div class="relative w-full h-full overflow-hidden opacity-20">
                                <svg viewBox="0 0 400 300" class="w-full h-full">
                                    <defs>
                                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="1"/>
                                        </pattern>
                                    </defs>
                                    <rect width="100%" height="100%" fill="url(#grid)" />
                                    <circle cx="200" cy="150" r="80" fill="none" stroke="currentColor" stroke-width="2" stroke-dasharray="8 8" />
                                    <rect x="120" y="100" width="160" height="100" rx="8" fill="none" stroke="currentColor" stroke-width="2" />
                                </svg>
                                <Briefcase class="absolute inset-0 m-auto w-12 h-12 text-slate-400 dark:text-slate-600" />
                            </div>
                        </div>
                        
                        <!-- Badge Cantidad de Imágenes -->
                        <div v-if="project.images && project.images.length > 0" class="absolute top-4 left-4 bg-white/90 dark:bg-black/90 backdrop-blur-md border border-slate-200 dark:border-slate-800 px-3 py-1.5 rounded-xl flex items-center gap-2 shadow-sm">
                            <Images class="w-3.5 h-3.5 text-indigo-500" />
                            <span class="text-[10px] font-bold text-slate-900 dark:text-white tracking-tighter">{{ project.images.length }}</span>
                        </div>

                        <!-- Overlay Hover para abrir galería -->
                        <div @click="openGallery(project)" class="absolute inset-0 bg-indigo-600/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer backdrop-blur-[2px]">
                            <div class="bg-white text-slate-900 font-bold uppercase tracking-widest text-[10px] px-6 py-3 rounded-2xl shadow-xl flex items-center gap-2 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                <Eye class="w-4 h-4" /> {{ t('dashboard_panel.projects.gallery.view') }}
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col justify-between w-full space-y-4">
                        <div class="space-y-3 text-left">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[9px] font-bold rounded-lg uppercase tracking-widest border border-indigo-100 dark:border-indigo-500/20">
                                    {{ project.category }}
                                </span>
                                <span v-if="!project.is_active" class="px-2 py-0.5 bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 text-[9px] font-bold rounded-lg uppercase tracking-widest border border-rose-100 dark:border-rose-500/20">
                                    {{ t('dashboard_panel.projects.status.inactive') }}
                                </span>
                                <span class="text-[9px] font-bold text-slate-300 dark:text-slate-700 tracking-[0.2em] ml-auto">REF_{{ String(project.id).padStart(3, '0') }}</span>
                            </div>

                            <h3 class="text-xl font-display font-bold text-slate-900 dark:text-white tracking-tight italic uppercase group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-1">{{ project.name }}</h3>
                            
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                {{ project.desc }}
                            </p>

                            <div v-if="project.stack || (project.technologies && project.technologies.length > 0)" class="flex flex-wrap gap-1.5 mt-2">
                                <span v-for="tech in project.stack" :key="tech" class="text-[8px] font-bold bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 px-2 py-0.5 border border-slate-100 dark:border-slate-800 rounded-lg uppercase tracking-widest">
                                    {{ tech }}
                                </span>
                                <span v-for="tech in project.technologies" :key="tech.id" class="text-[8px] font-bold bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 px-2 py-0.5 border border-indigo-100 dark:border-indigo-500/20 rounded-lg uppercase tracking-widest flex items-center gap-1">
                                    <img v-if="tech.logo_url" :src="tech.logo_url" class="w-2.5 h-2.5 object-contain opacity-70" />
                                    {{ tech.name }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-4 border-t border-slate-100 dark:border-slate-800/50">
                            <Button @click="openEditModal(project)" variant="outline" class="flex-1 border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 rounded-lg font-bold uppercase tracking-widest text-[9px] h-10 hover:bg-slate-50 dark:hover:bg-slate-900 transition-all shadow-sm">
                                <Pencil class="w-3 h-3 mr-1.5 text-indigo-500" />
                                {{ t('actions.edit') }}
                            </Button>
                            
                            <Button @click="deleteProject(project.id)" variant="ghost" class="h-10 w-10 rounded-lg text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 border border-transparent hover:border-rose-100 dark:hover:border-rose-500/20 transition-all">
                                <Trash2 class="w-3.5 h-3.5" />
                            </Button>
                            
                            <a v-if="project.project_url" :href="project.project_url" target="_blank" class="h-10 w-10 flex items-center justify-center rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:scale-105 transition-transform">
                                <ExternalLink class="w-3.5 h-3.5" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Paginador Centrado -->
            <div v-if="projects.last_page > 1" class="flex justify-center pt-8">
                <Pagination
                    v-slot="{ page }"
                    :total="projects.total"
                    :sibling-count="1"
                    :items-per-page="projects.per_page"
                    :default-page="projects.current_page"
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

        <!-- Galería de Imágenes Modal - Refined -->
        <Modal :show="!!selectedProjectForGallery" @close="closeGallery" max-width="5xl">
            <div v-if="selectedProjectForGallery" class="p-4 md:p-8 bg-white dark:bg-slate-950 rounded-[40px] border border-slate-200 dark:border-slate-800 overflow-hidden relative">
                <div class="absolute top-0 right-0 p-10 opacity-[0.02] pointer-events-none">
                    <Images class="w-64 h-64" />
                </div>

                <div class="flex justify-between items-center mb-8 border-b border-slate-100 dark:border-slate-800 pb-6 relative z-10">
                    <div class="space-y-1">
                        <h4 class="text-2xl font-display font-bold text-slate-900 dark:text-white uppercase italic tracking-tight">{{ selectedProjectForGallery.name }}</h4>
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">{{ t('dashboard_panel.projects.gallery.subtitle') }}</p>
                    </div>
                    <button @click="closeGallery" class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-900 text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all border border-slate-100 dark:border-slate-800">
                        <X class="w-6 h-6" />
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-h-[60vh] overflow-y-auto custom-scrollbar p-1 relative z-10">
                    <div v-for="(image, index) in selectedProjectForGallery.images" :key="image.id" class="relative group rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 shadow-sm">
                        <div class="aspect-video overflow-hidden">
                            <img :src="image.image_url" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" />
                        </div>
                        <div class="absolute top-4 left-4 bg-white/80 dark:bg-black/80 backdrop-blur-md border border-slate-200 dark:border-slate-800 px-3 py-1 font-bold text-[9px] uppercase tracking-widest rounded-lg dark:text-slate-200">
                            {{ t('dashboard_panel.projects.fields.images') }} {{ String(index + 1).padStart(2, '0') }}
                        </div>
                        <a :href="image.image_url" target="_blank" class="absolute bottom-4 right-4 bg-indigo-600 text-white p-3 rounded-xl opacity-0 group-hover:opacity-100 transition-all shadow-xl shadow-indigo-600/30 transform translate-y-2 group-hover:translate-y-0">
                            <ExternalLink class="w-4 h-4" />
                        </a>
                    </div>

                    <div v-if="!selectedProjectForGallery.images || selectedProjectForGallery.images.length === 0" class="col-span-full py-20 text-center border-2 border-dashed border-slate-100 dark:border-slate-800 rounded-[32px]">
                        <Images class="w-12 h-12 mx-auto mb-4 text-slate-200 dark:text-slate-800" />
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ t('dashboard_panel.projects.gallery.empty') }}</p>
                    </div>
                </div>

                <div class="mt-10 flex justify-center relative z-10">
                    <Button @click="closeGallery" class="bg-slate-900 dark:bg-white text-white dark:text-black rounded-2xl font-bold uppercase tracking-widest text-[10px] px-10 py-6 transition-all shadow-xl shadow-slate-950/20">
                        {{ t('dashboard_panel.projects.gallery.close') }}
                    </Button>
                </div>
            </div>
        </Modal>

        <!-- Create/Edit Project Modal -->
        <Dialog :open="isCreateModalOpen" @update:open="isCreateModalOpen = $event">
            <DialogContent class="max-w-4xl max-h-[90vh] overflow-y-auto rounded-[40px] border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-slate-950/95 backdrop-blur-xl custom-scrollbar p-8">
                <DialogHeader class="mb-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-indigo-50 dark:bg-indigo-500/10 rounded-2xl text-indigo-600">
                            <FolderPlus v-if="!editingProject" class="w-6 h-6" />
                            <Pencil v-else class="w-6 h-6" />
                        </div>
                        <div class="text-left">
                            <DialogTitle class="text-2xl font-display font-bold uppercase italic tracking-tight text-slate-900 dark:text-white">
                                {{ editingProject ? t('dashboard_panel.projects.edit') : t('dashboard_panel.projects.create') }}
                            </DialogTitle>
                            <DialogDescription class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">
                                {{ editingProject ? t('dashboard_panel.projects.gallery.subtitle') : t('dashboard_panel.projects.subtitle') }}
                            </DialogDescription>
                        </div>
                    </div>
                </DialogHeader>

                <ProjectForm 
                    :key="editingProject ? editingProject.id : 'new'"
                    :project="editingProject" 
                    :technologies="technologies"
                    :onSuccess="closeFormModal" 
                />
            </DialogContent>
        </Dialog>
    </AuthenticatedLayout>
</template>

<style scoped>
.font-display { font-family: 'Space Grotesk', sans-serif; }

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    @apply bg-slate-200 dark:bg-slate-800 rounded-full;
}
</style>

<style scoped>
.font-display {
    font-family: 'Space Grotesk', system-ui, sans-serif;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
}
.dark .custom-scrollbar::-webkit-scrollbar-track {
    background: #18181b;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #000;
    border: 2px solid #f1f1f1;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #fff;
    border: 2px solid #18181b;
}
</style>
