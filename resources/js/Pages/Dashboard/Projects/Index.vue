<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import { 
    Plus, 
    Pencil, 
    Trash2, 
    ExternalLink,
    ChevronRight,
    Briefcase,
    Images,
    FolderPlus,
    LayoutGrid,
    Search,
    Power
} from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/Components/ui/tooltip';
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
import { useProjectIndex } from './useProjectIndex';

const props = defineProps({
    projects: Object,
    technologies: Array,
    filters: { type: Object, default: () => ({ search: '' }) }
});

const { t } = useI18n();

const {
    search, perPage, isCreateModalOpen, editingProject, updatingStatusId,
    isStatusConfirmOpen, statusTarget, isDeleteOpen, deleteTarget, isDeleting,
    handlePageChange, openCreateModal, openEditModal, closeFormModal,
    openStatusConfirmation, confirmStatusChange, openDelete, confirmDelete,
} = useProjectIndex(props);
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
                        <p class="text-xs font-bold text-neutral-500 dark:text-neutral-300 uppercase tracking-[0.2em]">
                            {{ t('dashboard_panel.projects.subtitle') }}
                        </p>
                    </div>
                </div>
                <Button @click="openCreateModal" class="bg-black hover:bg-neutral-800 text-white dark:bg-white dark:hover:bg-neutral-200 dark:text-black rounded-xl px-4 py-2 shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98] group">
                    <Plus class="w-4 h-4 mr-1.5 group-hover:rotate-90 transition-transform duration-300" />
                    <span class="font-bold uppercase tracking-widest text-xs">{{ t('dashboard_panel.projects.create') }}</span>
                </Button>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Filters Bar -->
            <div v-if="projects.total > 0" class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-4 rounded-3xl shadow-xl">
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <div class="relative flex-1 sm:flex-none">
                        <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" />
                        <input
                            v-model="search"
                            placeholder="Buscar proyectos..."
                            class="w-full sm:w-56 pl-10 pr-3 py-2 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl text-sm font-bold uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white placeholder:text-neutral-400"
                        />
                    </div>
                    <div class="w-10 h-10 bg-neutral-100 dark:bg-neutral-800 rounded-xl flex items-center justify-center shrink-0">
                        <LayoutGrid class="w-5 h-5 text-neutral-500" />
                    </div>
                    <p class="text-sm font-bold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider whitespace-nowrap">
                        {{ t('pagination.showing') }} {{ projects.from }}-{{ projects.to }} {{ t('pagination.of') }} {{ projects.total }}
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-xs font-bold text-neutral-400 uppercase tracking-widest">{{ t('pagination.per_page') }}</span>
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

            <div v-else data-testid="projects-grid" class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                <div v-for="project in projects.data" :key="project.id"
                    data-testid="project-card"
                    class="group relative bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-3xl overflow-hidden hover:shadow-2xl transition-all duration-500 flex flex-col">

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
                            <span class="text-xs font-black text-neutral-900 dark:text-white">{{ project.images.length }}</span>
                        </div>

                        <!-- Overlay galería -->
                        <Link :href="route('projects.show', project.slug)"
                            class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all flex items-center justify-center cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-inset">
                            <div class="bg-white text-black font-black uppercase tracking-widest text-xs px-5 py-2.5 rounded-2xl shadow-xl flex items-center gap-2 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                                <ChevronRight class="w-4 h-4" /> {{ t('dashboard_panel.projects.gallery.view') }}
                            </div>
                        </Link>
                    </div>

                    <!-- Card Body -->
                    <div class="flex-1 flex flex-col p-4 gap-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-2 py-0.5 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 text-xs font-bold rounded-lg uppercase tracking-widest border border-neutral-200 dark:border-neutral-700">
                                {{ project.category }}
                            </span>
                            <span v-if="!project.is_active"
                                class="rounded-lg border border-status-danger/30 bg-status-danger/10 px-2 py-0.5 text-xs font-bold uppercase tracking-widest text-status-danger">
                                {{ t('dashboard_panel.projects.status.inactive') }}
                            </span>
                            <span
                                v-if="project.media_status && project.media_status !== 'completed'"
                                :title="project.media_error || undefined"
                                class="inline-flex items-center gap-1.5 rounded-lg border px-2 py-1 text-xs font-bold uppercase tracking-wide"
                                :class="project.media_status === 'failed'
                                    ? 'border-status-danger/30 bg-status-danger/10 text-status-danger'
                                    : 'border-status-warning/30 bg-status-warning/10 text-status-warning'"
                            >
                                <span
                                    class="size-2 rounded-full"
                                    :class="project.media_status === 'failed' ? 'bg-status-danger' : 'animate-pulse bg-status-warning'"
                                />
                                {{ {
                                    pending: 'Subida pendiente',
                                    processing: 'Procesando imágenes',
                                    failed: 'Falló la subida',
                                }[project.media_status] || project.media_status }}
                            </span>
                            <span class="text-xs font-bold text-neutral-300 dark:text-neutral-700 tracking-[0.2em] ml-auto">
                                #{{ String(project.id).padStart(3, '0') }}
                            </span>
                        </div>

                        <h3 class="text-sm font-black text-neutral-900 dark:text-white tracking-tight uppercase line-clamp-1">
                            {{ project.name }}
                        </h3>

                        <p class="text-sm text-neutral-500 dark:text-neutral-400 line-clamp-2 leading-relaxed flex-1">
                            {{ project.desc }}
                        </p>

                        <!-- Tech badges -->
                        <div v-if="project.technologies && project.technologies.length > 0" class="flex flex-wrap gap-1.5">
                            <span v-for="tech in project.technologies" :key="tech.id"
                                class="text-xs font-bold bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 px-2 py-0.5 border border-neutral-200 dark:border-neutral-700 rounded-lg uppercase tracking-widest flex items-center gap-1">
                                <img v-if="tech.logo_url" :src="tech.logo_url" class="w-2.5 h-2.5 object-contain" loading="lazy" />
                                {{ tech.name }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-neutral-100 dark:border-neutral-800 mt-auto">
                            <TooltipProvider :delay-duration="150">
                                <Tooltip>
                                    <TooltipTrigger as-child>
                                        <Button
                                            size="icon"
                                            :disabled="updatingStatusId === project.id"
                                            :aria-label="project.is_active ? 'Desactivar proyecto' : 'Activar proyecto'"
                                            variant="ghost"
                                            :class="[
                                                'size-11 rounded-xl border transition-all [&>svg]:size-3.5',
                                                project.is_active
                                                    ? 'border-status-success/30 text-status-success hover:bg-status-success/10'
                                                    : 'border-neutral-200 text-neutral-400 hover:bg-neutral-100 dark:border-neutral-700 dark:hover:bg-neutral-800',
                                            ]"
                                            @click="openStatusConfirmation(project)"
                                        >
                                            <Power :class="updatingStatusId === project.id && 'animate-pulse'" />
                                        </Button>
                                    </TooltipTrigger>
                                    <TooltipContent side="top" :side-offset="8">
                                        {{ project.is_active ? 'Desactivar proyecto' : 'Activar proyecto' }}
                                    </TooltipContent>
                                </Tooltip>

                                <Tooltip>
                                    <TooltipTrigger as-child>
                                        <Button
                                            size="icon"
                                            variant="outline"
                                            :aria-label="`Editar ${project.name}`"
                                            class="size-11 rounded-xl border-neutral-200 bg-white transition-all hover:bg-neutral-100 dark:border-neutral-800 dark:bg-neutral-900 dark:hover:bg-neutral-800 [&>svg]:size-3.5"
                                            @click="openEditModal(project)"
                                        >
                                            <Pencil />
                                        </Button>
                                    </TooltipTrigger>
                                    <TooltipContent side="top" :side-offset="8">Editar proyecto</TooltipContent>
                                </Tooltip>

                                <Tooltip>
                                    <TooltipTrigger as-child>
                                        <Button
                                            size="icon"
                                            variant="ghost"
                                            :aria-label="`Eliminar ${project.name}`"
                                            class="size-11 rounded-xl border border-status-danger/30 text-status-danger transition-all hover:bg-status-danger/10 [&>svg]:size-3.5"
                                            @click="openDelete(project)"
                                        >
                                            <Trash2 />
                                        </Button>
                                    </TooltipTrigger>
                                    <TooltipContent side="top" :side-offset="8">Eliminar proyecto</TooltipContent>
                                </Tooltip>

                                <Tooltip v-if="project.project_url">
                                    <TooltipTrigger as-child>
                                        <a
                                            :href="project.project_url"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            :aria-label="`Abrir ${project.name} en una pestaña nueva`"
                                            class="flex size-11 items-center justify-center rounded-xl bg-black text-white transition-transform hover:scale-[1.02] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black focus-visible:ring-offset-2 dark:bg-white dark:text-black dark:focus-visible:ring-white [&>svg]:size-3.5"
                                        >
                                            <ExternalLink />
                                        </a>
                                    </TooltipTrigger>
                                    <TooltipContent side="top" :side-offset="8">Abrir proyecto</TooltipContent>
                                </Tooltip>
                            </TooltipProvider>
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
                            <DialogDescription class="text-xs font-bold text-neutral-400 uppercase tracking-[0.2em]">
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
        </Dialog>

        <!-- Status Confirmation -->
        <ConfirmDialog
            v-model:open="isStatusConfirmOpen"
            :title="statusTarget?.is_active ? 'Desactivar proyecto' : 'Activar proyecto'"
            :description="statusTarget?.is_active
                ? `¿Confirmas que deseas desactivar ${statusTarget?.name || 'este proyecto'}? Dejará de mostrarse en el portafolio.`
                : `¿Confirmas que deseas activar ${statusTarget?.name || 'este proyecto'}? Se mostrará en el portafolio.`"
            :confirm-label="statusTarget?.is_active ? 'Sí, desactivar' : 'Sí, activar'"
            loading-label="Actualizando..."
            :icon="Power"
            variant="warning"
            :loading="updatingStatusId !== null"
            @confirm="confirmStatusChange"
        />

        <!-- Delete Confirmation -->
        <ConfirmDialog
            v-model:open="isDeleteOpen"
            title="Eliminar proyecto"
            :description="`¿Confirmas que deseas eliminar ${deleteTarget?.name || 'este proyecto'}? También se eliminarán definitivamente sus imágenes de Cloudinary.`"
            confirm-label="Sí, eliminar"
            loading-label="Eliminando..."
            :icon="Trash2"
            variant="danger"
            :loading="isDeleting"
            @confirm="confirmDelete"
        />
    </AuthenticatedLayout>
</template>

<style scoped>
.font-display { font-family: 'Space Grotesk', system-ui, sans-serif; }

.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.dark .custom-scrollbar::-webkit-scrollbar-track { background: #18181b; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #000; border: 2px solid transparent; border-radius: 9999px; }
.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #fff; border: 2px solid #18181b; }
</style>
