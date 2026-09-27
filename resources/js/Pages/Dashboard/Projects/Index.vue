<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import { 
    Plus, 
    Pencil, 
    Trash2, 
    Briefcase,
    FolderPlus,
    LayoutGrid,
    Search,
    Power
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
import ProjectAdminCard from '@/Components/ProjectAdminCard.vue';
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
            <div class="h-0.5 w-8 bg-black dark:bg-white rounded-full" />
            <p class="text-xs font-bold text-neutral-500 dark:text-neutral-300 uppercase tracking-[0.2em]">
              {{ t('dashboard_panel.projects.subtitle') }}
            </p>
          </div>
        </div>
        <Button
          class="bg-black hover:bg-neutral-800 text-white dark:bg-white dark:hover:bg-neutral-200 dark:text-black rounded-xl px-4 py-2 shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98] group"
          @click="openCreateModal"
        >
          <Plus class="w-4 h-4 mr-1.5 group-hover:rotate-90 transition-transform duration-300" />
          <span class="font-bold uppercase tracking-widest text-xs">{{ t('dashboard_panel.projects.create') }}</span>
        </Button>
      </div>
    </template>

    <div class="space-y-6">
      <!-- Filters Bar -->
      <div
        v-if="projects.total > 0"
        class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-4 rounded-3xl shadow-xl"
      >
        <div class="flex items-center gap-3 w-full sm:w-auto">
          <div class="relative flex-1 sm:flex-none">
            <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" />
            <input
              v-model="search"
              placeholder="Buscar proyectos..."
              class="w-full sm:w-56 pl-10 pr-3 py-2 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl text-sm font-bold uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white placeholder:text-neutral-400"
            >
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
              <SelectItem
                value="5"
                class="text-xs font-bold"
              >
                5
              </SelectItem>
              <SelectItem
                value="10"
                class="text-xs font-bold"
              >
                10
              </SelectItem>
              <SelectItem
                value="20"
                class="text-xs font-bold"
              >
                20
              </SelectItem>
              <SelectItem
                value="50"
                class="text-xs font-bold"
              >
                50
              </SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      <!-- Empty State -->
      <div
        v-if="projects.data.length === 0"
        class="flex flex-col items-center justify-center py-20 bg-white dark:bg-black border-2 border-dashed border-neutral-200 dark:border-neutral-800 rounded-3xl"
      >
        <div class="p-6 rounded-full bg-neutral-100 dark:bg-neutral-800 mb-6">
          <Briefcase class="w-12 h-12 text-neutral-300 dark:text-neutral-600" />
        </div>
        <h3 class="text-xl font-black uppercase tracking-tight text-neutral-400">
          {{ t('dashboard_panel.projects.empty') }}
        </h3>
      </div>

      <div
        v-else
        data-testid="projects-grid"
        class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3"
      >
        <ProjectAdminCard
          v-for="project in projects.data"
          :key="project.id"
          :project="project"
          :updating-status-id="updatingStatusId"
          @status="openStatusConfirmation"
          @edit="openEditModal"
          @delete="openDelete"
        />
      </div>

      <!-- Paginador -->
      <div
        v-if="projects.last_page > 1"
        class="flex flex-col items-center gap-3 pt-8"
      >
        <Pagination
          :total="projects.total"
          :sibling-count="1"
          :items-per-page="projects.per_page"
          :default-page="projects.current_page"
          @update:page="handlePageChange"
        >
          <PaginationList
            v-slot="{ items }"
            class="flex items-center gap-2 bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-2 rounded-2xl shadow-xl"
          >
            <PaginationFirst />
            <PaginationPrev />
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
              <FolderPlus
                v-if="!editingProject"
                class="w-6 h-6"
              />
              <Pencil
                v-else
                class="w-6 h-6"
              />
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
            :on-success="closeFormModal"
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
