<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { ref, computed, watch, nextTick } from 'vue';
import { useSkeletonLoader } from '@/composables/useSkeletonLoader';
import { useRekaCleanup } from '@/composables/useRekaCleanup';
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
    LayoutGrid,
    Search
} from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
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
import { cloudinaryThumb } from '@/lib/cloudinary'
import SkeletonDashCard from '@/Components/SkeletonDashCard.vue';

const props = defineProps({
    projects: Object,
    technologies: Array,
    filters: { type: Object, default: () => ({ search: '' }) }
});

const { t } = useI18n();

const { skeletonReady } = useSkeletonLoader();

const search = ref(props.filters?.search || '');

const selectedProjectForGallery = ref(null);
const isCreateModalOpen = ref(false);
const editingProject = ref(null);

useRekaCleanup(isCreateModalOpen);

const isGalleryOpen = computed({
    get: () => !!selectedProjectForGallery.value,
    set: (val) => { if (!val) closeGallery(); },
});

const perPage = ref(String(props.projects.per_page || 10));

watch(perPage, (newValue) => {
    router.get(route('projects.index'), { per_page: newValue }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
});

// Clean up editingProject when dialog closes via Escape/click-outside
watch(isCreateModalOpen, (val) => {
    if (!val && editingProject.value) {
        nextTick(() => {
            editingProject.value = null;
        });
    }
});

let searchTimer;
watch(search, () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        router.get(route('projects.index'), { 
            search: search.value,
            per_page: perPage.value 
        }, {
            preserveState: true,
            preserveScroll: true,
            replace: true
        });
    }, 400);
});

const handlePageChange = (page) => {
    router.get(route('projects.index'), { 
        page: page,
        per_page: perPage.value,
        search: search.value
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
    nextTick(() => {
        editingProject.value = null;
    });
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
                    <h2 class="text-3xl font-black tracking-tight text-neutral-900 dark:text-white uppercase">
                        {{ t('dashboard_panel.projects.title') }}
                    </h2>
                    <div class="flex items-center gap-3">
                        <div class="h-0.5 w-8 bg-black dark:bg-white rounded-full"></div>
                        <p class="text-[10px] font-bold text-neutral-500 dark:text-neutral-300 uppercase tracking-[0.2em]">
                            {{ t('dashboard_panel.projects.subtitle') }}
                        </p>
                    </div>
                </div>
                <Button @click="openCreateModal" class="bg-black hover:bg-neutral-800 text-white dark:bg-white dark:hover:bg-neutral-200 dark:text-black rounded-xl px-4 py-2 shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98] group">
                    <Plus class="w-4 h-4 mr-1.5 group-hover:rotate-90 transition-transform duration-300" />
                    <span class="font-bold uppercase tracking-widest text-[10px]">{{ t('dashboard_panel.projects.create') }}</span>
                </Button>
            </div>
        </template>

        <div class="space-y-6">
            <Transition name="fade" mode="out-in">
                <!-- Skeleton Grid -->
                <div v-if="!skeletonReady" key="skeleton" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
                    <SkeletonDashCard v-for="i in 10" :key="'skel-' + i" />
                </div>

                <div v-else key="content">
                    <!-- Filters Bar -->
                    <div v-if="projects.total > 0" class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-4 rounded-3xl shadow-xl">
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <div class="relative flex-1 sm:flex-none">
                        <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" />
                        <input
                            v-model="search"
                            placeholder="Buscar proyectos..."
                            class="w-full sm:w-56 pl-10 pr-3 py-2 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl text-[11px] font-bold uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white placeholder:text-neutral-400"
                        />
                    </div>
                    <div class="w-10 h-10 bg-neutral-100 dark:bg-neutral-800 rounded-xl flex items-center justify-center shrink-0">
                        <LayoutGrid class="w-5 h-5 text-neutral-500" />
                    </div>
                    <p class="text-[11px] font-bold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                        {{ t('pagination.showing') }} {{ projects.from }}-{{ projects.to }} {{ t('pagination.of') }} {{ projects.total }}
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest">{{ t('pagination.per_page') }}</span>
                    <Select v-model="perPage">
                        <SelectTrigger class="w-24 h-10 rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 font-bold text-xs">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                            <SelectItem value="5" class="text-xs font-bold">5</SelectItem>
                            <SelectItem value="10" class="text-xs font-bold">10</SelectItem>
                            <SelectItem value="20" class="text-xs font-bold">20</SelectItem>
                            <SelectItem value="50" class="text-xs font-bold">50</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="projects.data.length === 0" class="flex flex-col items-center justify-center py-20 bg-white dark:bg-black border-2 border-dashed border-neutral-200 dark:border-neutral-800 rounded-3xl">
                <div class="p-6 rounded-full bg-neutral-100 dark:bg-neutral-800 mb-6">
                    <Briefcase class="w-12 h-12 text-neutral-300 dark:text-neutral-600" />
                </div>
                <h3 class="text-xl font-black uppercase tracking-tight text-neutral-400">{{ t('dashboard_panel.projects.empty') }}</h3>
            </div>

            <!-- Grid -->
            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
                <div v-for="project in projects.data" :key="project.id"
                    class="group relative bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-3xl overflow-hidden hover:shadow-2xl transition-all duration-500 flex flex-col">

                    <!-- Imagen Principal -->
                    <div class="w-full h-40 bg-neutral-100 dark:bg-neutral-800 overflow-hidden relative shrink-0">
                        <img v-if="project.images && project.images.length > 0"
                            :src="cloudinaryThumb(project.images[0].image_url, 400, 300)"
                            :alt="project.name"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            loading="lazy" />
                        <div v-else class="w-full h-full flex items-center justify-center">
                            <Briefcase class="w-12 h-12 text-neutral-300 dark:text-neutral-700" />
                        </div>

                        <!-- Badge Imágenes -->
                        <div v-if="project.images && project.images.length > 0"
                            class="absolute top-3 left-3 bg-white/90 dark:bg-black/90 border border-neutral-200 dark:border-neutral-700 px-2.5 py-1 rounded-xl flex items-center gap-1.5 shadow-sm">
                            <Images class="w-3.5 h-3.5 text-neutral-500" />
                            <span class="text-[10px] font-black text-neutral-900 dark:text-white">{{ project.images.length }}</span>
                        </div>

                        <!-- Overlay galería -->
                        <div @click="openGallery(project)"
                            class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all flex items-center justify-center cursor-pointer">
                            <div class="bg-white text-black font-black uppercase tracking-widest text-[10px] px-5 py-2.5 rounded-2xl shadow-xl flex items-center gap-2 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                                <Eye class="w-4 h-4" /> {{ t('dashboard_panel.projects.gallery.view') }}
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="flex-1 flex flex-col p-4 gap-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-2 py-0.5 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 text-[9px] font-bold rounded-lg uppercase tracking-widest border border-neutral-200 dark:border-neutral-700">
                                {{ project.category }}
                            </span>
                            <span v-if="!project.is_active"
                                class="px-2 py-0.5 bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 text-[9px] font-bold rounded-lg uppercase tracking-widest border border-rose-200 dark:border-rose-500/20">
                                {{ t('dashboard_panel.projects.status.inactive') }}
                            </span>
                            <span class="text-[9px] font-bold text-neutral-300 dark:text-neutral-700 tracking-[0.2em] ml-auto">
                                #{{ String(project.id).padStart(3, '0') }}
                            </span>
                        </div>

                        <h3 class="text-sm font-black text-neutral-900 dark:text-white tracking-tight uppercase line-clamp-1">
                            {{ project.name }}
                        </h3>

                        <p class="text-[11px] text-neutral-500 dark:text-neutral-400 line-clamp-2 leading-relaxed flex-1">
                            {{ project.desc }}
                        </p>

                        <!-- Tech badges -->
                        <div v-if="project.technologies && project.technologies.length > 0" class="flex flex-wrap gap-1.5">
                            <span v-for="tech in project.technologies" :key="tech.id"
                                class="text-[8px] font-bold bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 px-2 py-0.5 border border-neutral-200 dark:border-neutral-700 rounded-lg uppercase tracking-widest flex items-center gap-1">
                                <img v-if="tech.logo_url" :src="tech.logo_url" class="w-2.5 h-2.5 object-contain" loading="lazy" />
                                {{ tech.name }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-2 pt-3 border-t border-neutral-100 dark:border-neutral-800 mt-auto">
                            <Button @click="openEditModal(project)" variant="outline"
                                class="flex-1 border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 rounded-xl font-bold uppercase tracking-widest text-[9px] h-9 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-all">
                                <Pencil class="w-3 h-3 mr-1.5" />
                                {{ t('actions.edit') }}
                            </Button>

                            <Button @click="deleteProject(project.id)" variant="ghost"
                                class="h-9 w-9 rounded-xl text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 border border-transparent hover:border-rose-200 dark:hover:border-rose-500/20 transition-all">
                                <Trash2 class="w-3.5 h-3.5" />
                            </Button>

                            <a v-if="project.project_url" :href="project.project_url" target="_blank"
                                class="h-9 w-9 flex items-center justify-center rounded-xl bg-black dark:bg-white text-white dark:text-black hover:scale-105 transition-transform">
                                <ExternalLink class="w-3.5 h-3.5" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paginador -->
            <div v-if="projects.last_page > 1" class="flex flex-col items-center gap-3 pt-8">
                <Pagination
                    v-slot="{ page }"
                    :total="projects.total"
                    :sibling-count="1"
                    :items-per-page="projects.per_page"
                    :default-page="projects.current_page"
                    @update:page="handlePageChange"
                >
                    <PaginationList v-slot="{ items }" class="flex items-center gap-2 bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-2 rounded-2xl shadow-xl">
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
    </Transition>
</div>

        <!-- Galería Modal -->
        <Dialog v-model:open="isGalleryOpen">
            <DialogContent class="max-w-5xl !rounded-[2rem] border border-neutral-200 dark:border-neutral-800 !bg-white dark:!bg-black p-0 shadow-2xl dashboard-dialog-enter">
                <div v-if="selectedProjectForGallery" class="p-6 md:p-8">
                    <div class="flex justify-between items-center mb-8 border-b border-neutral-100 dark:border-neutral-800 pb-6">
                        <div class="space-y-1">
                            <h4 class="text-2xl font-black text-neutral-900 dark:text-white uppercase italic tracking-tight">{{ selectedProjectForGallery.name }}</h4>
                            <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-[0.2em]">{{ t('dashboard_panel.projects.gallery.subtitle') }}</p>
                        </div>
                        <button @click="closeGallery" class="p-3 rounded-2xl bg-neutral-100 dark:bg-neutral-800 text-neutral-500 hover:text-neutral-900 dark:hover:text-white transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2">
                            <X class="w-5 h-5" />
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-h-[60vh] overflow-y-auto custom-scrollbar p-1">
                        <div v-for="(image, index) in selectedProjectForGallery.images" :key="image.id"
                            class="relative group rounded-2xl overflow-hidden border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900">
                            <div class="aspect-video overflow-hidden">
                                <img :src="cloudinaryThumb(image.image_url, 800, 600)" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy" />
                            </div>
                            <div class="absolute top-3 left-3 bg-white/90 dark:bg-black/90 border border-neutral-200 dark:border-neutral-700 px-2.5 py-1 font-black text-[9px] uppercase tracking-widest rounded-xl dark:text-white">
                                {{ String(index + 1).padStart(2, '0') }}
                            </div>
                            <a :href="image.image_url" target="_blank"
                                class="absolute bottom-3 right-3 bg-black dark:bg-white text-white dark:text-black p-2.5 rounded-xl opacity-0 group-hover:opacity-100 transition-all shadow-xl translate-y-1 group-hover:translate-y-0">
                                <ExternalLink class="w-4 h-4" />
                            </a>
                        </div>

                        <div v-if="!selectedProjectForGallery.images || selectedProjectForGallery.images.length === 0"
                            class="col-span-full py-16 text-center border-2 border-dashed border-neutral-200 dark:border-neutral-800 rounded-2xl">
                            <Images class="w-10 h-10 mx-auto mb-4 text-neutral-300 dark:text-neutral-700" />
                            <p class="text-xs font-bold text-neutral-400 uppercase tracking-widest">{{ t('dashboard_panel.projects.gallery.empty') }}</p>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-center">
                        <Button @click="closeGallery" class="bg-black hover:bg-neutral-800 dark:bg-white dark:hover:bg-neutral-200 text-white dark:text-black rounded-xl font-bold uppercase tracking-widest text-[10px] px-8 py-5 transition-all shadow-lg">
                            {{ t('dashboard_panel.projects.gallery.close') }}
                        </Button>
                    </div>
                </div>
            </DialogContent>
        </Dialog>

        <!-- Create/Edit Project Modal -->
        <Dialog v-model:open="isCreateModalOpen">
            <DialogContent class="max-w-4xl max-h-[85dvh] flex flex-col !rounded-[2rem] border border-neutral-200 dark:border-neutral-800 !bg-white dark:!bg-black shadow-2xl p-8 dashboard-dialog-enter">
                <DialogHeader class="shrink-0 mb-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-neutral-100 dark:bg-neutral-800 rounded-2xl text-neutral-900 dark:text-white">
                            <FolderPlus v-if="!editingProject" class="w-6 h-6" />
                            <Pencil v-else class="w-6 h-6" />
                        </div>
                        <div class="text-left">
                            <DialogTitle class="text-2xl font-black uppercase italic tracking-tight text-neutral-900 dark:text-white">
                                {{ editingProject ? t('dashboard_panel.projects.edit') : t('dashboard_panel.projects.create') }}
                            </DialogTitle>
                            <DialogDescription class="text-[10px] font-bold text-neutral-400 uppercase tracking-[0.2em]">
                                {{ editingProject ? t('dashboard_panel.projects.gallery.subtitle') : t('dashboard_panel.projects.subtitle') }}
                            </DialogDescription>
                        </div>
                    </div>
                </DialogHeader>

                <div class="flex-1 overflow-y-auto scrollbar-imperceptible min-h-0">
                    <ProjectForm
                        :key="editingProject ? editingProject.id : 'new'"
                        :project="editingProject"
                        :technologies="technologies"
                        :onSuccess="closeFormModal"
                    />
                </div>
            </DialogContent>
        </Dialog>    </AuthenticatedLayout>
</template>

<style scoped>
.font-display { font-family: 'Space Grotesk', system-ui, sans-serif; }

.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.dark .custom-scrollbar::-webkit-scrollbar-track { background: #18181b; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #000; border: 2px solid transparent; border-radius: 9999px; }
.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #fff; border: 2px solid #18181b; }
</style>
