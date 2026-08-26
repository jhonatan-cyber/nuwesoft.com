<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import {
    Plus, Search, MoreHorizontal, Pencil, Trash2, Code2,
    CheckCircle2, XCircle, X, AlertTriangle,
} from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import {
    Dialog, DialogContent, DialogHeader, DialogTitle,
    DialogDescription, DialogTrigger, DialogFooter,
} from '@/Components/ui/dialog';
import {
    DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import { Badge } from '@/Components/ui/badge';
import {
    Select, SelectContent, SelectItem, SelectTrigger, SelectValue,
} from '@/Components/ui/select';
import {
    Pagination, PaginationEllipsis, PaginationFirst, PaginationLast,
    PaginationList, PaginationListItem, PaginationNext, PaginationPrev,
} from '@/Components/ui/pagination';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import SkeletonDashCard from '@/Components/SkeletonDashCard.vue';
import TechnologyForm from './TechnologyForm.vue';
import { useSkeletonLoader } from '@/composables/useSkeletonLoader';
import { useTechnologyIndex } from './useTechnologyIndex';

const props = defineProps({
    technologies: Object,
    filters: {
        type: Object,
        default: () => ({ search: '', sort_field: 'created_at', sort_order: 'desc' }),
    },
});

const { t } = useI18n();
const { skeletonReady } = useSkeletonLoader();
const {
    search, sortField, sortOrder, perPage, deleteTarget, isDeleteModalOpen,
    deleteForm, isCreateModalOpen, isEditModalOpen, editingTechnology,
    handlePageChange, toggleSort, sortIcon, openEditModal, closeModals,
    openDeleteConfirm, confirmDelete, getCategoryColor,
} = useTechnologyIndex(props);
</script>

<template>
    <Head :title="t('technologies.title')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-black tracking-tight text-neutral-900 dark:text-white uppercase">
                        {{ t('technologies.title') }}
                    </h2>
                    <p class="text-xs font-bold text-neutral-500 dark:text-neutral-300 uppercase tracking-[0.2em] mt-1">
                        {{ t('technologies.subtitle') }}
                    </p>
                </div>

                <Dialog v-model:open="isCreateModalOpen">
                    <DialogTrigger as-child>
                        <Button size="sm" class="bg-black hover:bg-neutral-800 text-white dark:bg-white dark:hover:bg-neutral-200 dark:text-black rounded-xl px-4 py-2 shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98] group">
                            <Plus class="w-4 h-4 mr-1.5 group-hover:rotate-90 transition-transform duration-300" />
                            <span class="font-bold uppercase tracking-widest text-xs">{{ t('technologies.actions.add') }}</span>
                        </Button>
                    </DialogTrigger>
                    <DialogContent class="sm:max-w-[500px] max-h-[85dvh] flex flex-col !rounded-[2rem] border border-neutral-200 dark:border-neutral-800 !bg-white dark:!bg-black shadow-2xl dashboard-dialog-enter">
                        <DialogHeader class="shrink-0">
                            <DialogTitle class="text-xl sm:text-2xl font-black uppercase tracking-tight">{{ t('technologies.modals.create_title') }}</DialogTitle>
                            <DialogDescription class="text-xs sm:text-xs font-bold text-neutral-400 uppercase tracking-widest">{{ t('technologies.modals.create_desc') }}</DialogDescription>
                        </DialogHeader>
                        <div class="flex-1 overflow-y-auto scrollbar-imperceptible min-h-0">
                            <TechnologyForm @close="closeModals" />
                        </div>
                        <DialogFooter class="shrink-0 flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-4 border-t border-neutral-100 dark:border-neutral-800 mt-4">
                            <Button 
                                type="button" 
                                variant="ghost" 
                                @click="closeModals"
                                class="rounded-xl font-bold uppercase text-xs tracking-wider w-full sm:w-auto"
                            >
                                {{ t('technologies.modals.cancel') }}
                            </Button>
                            <Button 
                                type="submit" 
                                form="technology-form"
                                class="bg-black hover:bg-neutral-800 text-white dark:bg-white dark:hover:bg-neutral-200 dark:text-black rounded-xl px-8 shadow-lg font-bold uppercase text-xs tracking-wider w-full sm:w-auto"
                            >
                                {{ t('technologies.modals.save') }}
                            </Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </div>
        </template>

        <div class="space-y-6">
            <Transition name="fade" mode="out-in">
                <!-- Skeleton Grid -->
                <div v-if="!skeletonReady" key="skeleton" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-8 gap-4">
                    <div v-for="i in 16" :key="'skel-' + i"
                        class="relative bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-3xl p-4 overflow-hidden pointer-events-none select-none">
                        <div class="absolute inset-0 shimmer-sweep z-10"></div>
                        <div class="flex flex-col items-center text-center gap-3">
                            <div class="w-full h-6 flex justify-end">
                                <div class="w-8 h-8 rounded-lg skeleton-bg"></div>
                            </div>
                            <div class="w-16 h-16 rounded-full skeleton-bg"></div>
                            <div class="space-y-2 w-full">
                                <div class="h-3 w-3/4 mx-auto rounded skeleton-bg"></div>
                                <div class="h-5 w-20 mx-auto rounded-full skeleton-bg"></div>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-neutral-100 dark:border-neutral-800 flex justify-center">
                            <div class="h-3 w-16 rounded skeleton-bg"></div>
                        </div>
                    </div>
                </div>

                <div v-else key="content">
                    <!-- Filters Bar -->
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4 bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-4 rounded-3xl shadow-xl">
                <!-- Search -->
                <div class="relative w-full md:flex-1 group">
                    <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400 group-focus-within:text-black dark:group-focus-within:text-white transition-colors" />
                    <Input 
                        v-model="search"
                        :placeholder="t('technologies.search_placeholder')"
                        class="pl-12 pr-10 py-6 bg-white dark:bg-neutral-900 border-neutral-200 dark:border-neutral-800 rounded-2xl focus:ring-black dark:focus:ring-white shadow-sm transition-all text-xs font-bold uppercase tracking-wider"
                    />
                    <button 
                        v-if="search" 
                        @click="search = ''"
                        class="absolute right-3 top-1/2 -translate-y-1/2 p-1 rounded-full text-neutral-400 hover:text-neutral-900 dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2"
                    >
                        <X class="w-4 h-4" />
                    </button>
                </div>

                <!-- Sort & Per Page -->
                <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end px-2">
                    <!-- Sort by -->
                    <div class="hidden sm:flex items-center gap-1 rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-1 shadow-sm">
                        <button
                            v-for="opt in [
                                { key: 'name', label: t('technologies.fields.name') },
                                { key: 'category', label: t('technologies.fields.category') },
                                { key: 'created_at', label: t('technologies.fields.date') },
                            ]"
                            :key="opt.key"
                            @click="toggleSort(opt.key)"
                            :class="['inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2',
                                    sortField === opt.key
                                        ? 'bg-neutral-100 dark:bg-neutral-800 text-black dark:text-white shadow-sm'
                                        : 'text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300'
                            ]"
                        >
                            <component :is="sortIcon(opt.key)" class="w-3 h-3" />
                            {{ opt.label }}
                        </button>
                    </div>

                    <div class="h-6 w-px bg-neutral-200 dark:bg-neutral-800 hidden md:block"></div>

                    <!-- Per page -->
                    <div class="flex items-center gap-3">
                        <p class="text-xs font-bold text-neutral-400 uppercase tracking-widest hidden sm:block">{{ t('pagination.per_page') }}</p>
                        <Select v-model="perPage">
                            <SelectTrigger class="w-24 h-10 rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 font-bold text-xs">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                                <SelectItem value="12" class="text-xs font-bold">12</SelectItem>
                                <SelectItem value="24" class="text-xs font-bold">24</SelectItem>
                                <SelectItem value="48" class="text-xs font-bold">48</SelectItem>
                                <SelectItem value="96" class="text-xs font-bold">96</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </div>
            </div>

            <!-- Grid -->
            <div v-if="technologies.data.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-8 gap-4">
                <div 
                    v-for="tech in technologies.data" 
                    :key="tech.id"
                    class="group relative bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-3xl p-4 hover:shadow-2xl transition-all duration-500 overflow-hidden"
                >
                    <!-- Background Accent -->
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-neutral-100 dark:bg-neutral-800 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700"></div>

                    <div class="relative flex flex-col items-center text-center gap-3">
                        <div class="w-full flex justify-end absolute -top-1 -right-1 z-10">
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="ghost" class="h-8 w-8 p-0 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-800 border border-transparent hover:border-neutral-200 dark:hover:border-neutral-700">
                                        <MoreHorizontal class="h-4 w-4 text-neutral-400" />
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end" class="w-48 p-2 rounded-2xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-black shadow-2xl">
                                    <DropdownMenuItem @click="openEditModal(tech)" class="rounded-xl cursor-pointer focus:bg-neutral-100 dark:focus:bg-neutral-800 focus:text-black dark:focus:text-white py-2.5">
                                        <Pencil class="mr-2 h-4 w-4" />
                                        <span class="font-bold uppercase text-xs tracking-widest">{{ t('actions.edit') }}</span>
                                    </DropdownMenuItem>
                                    <div class="h-px bg-neutral-100 dark:bg-neutral-800 my-1"></div>
                                    <DropdownMenuItem @click="openDeleteConfirm(tech)" class="cursor-pointer rounded-xl py-2.5 text-status-danger focus:bg-status-danger/10 focus:text-status-danger">
                                        <Trash2 class="mr-2 h-4 w-4" />
                                        <span class="font-bold uppercase text-xs tracking-widest">{{ t('actions.delete') }}</span>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>

                        <!-- Logo -->
                        <div class="w-full h-16 shrink-0 flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                            <img v-if="tech.logo_url" :src="tech.logo_url" :alt="tech.name" :class="['max-w-full max-h-full object-contain', tech.invert_dark ? 'dark:invert dark:brightness-0 dark:invert' : '']" />
                            <Code2 v-else class="w-8 h-8 text-neutral-300 dark:text-neutral-700" />
                        </div>

                        <div class="w-full min-w-0">
                            <h3 class="font-black text-sm tracking-tight text-neutral-900 dark:text-white uppercase truncate px-1">
                                {{ tech.name }}
                            </h3>
                            <div class="flex flex-col items-center gap-1 mt-1.5">
                                <Badge variant="outline" :class="['rounded-full px-2 py-0 text-xs font-bold uppercase tracking-widest', getCategoryColor(tech.category)]">
                                    {{ t(`technologies.categories.${tech.category}`) }}
                                </Badge>
                                <Badge v-if="!tech.is_active" variant="destructive" class="rounded-full px-2 py-0 text-xs font-bold uppercase tracking-widest">
                                    {{ t('technologies.status.inactive') }}
                                </Badge>
                            </div>
                        </div>
                    </div>

                    <!-- Compact Status -->
                    <div class="mt-3 pt-3 border-t border-neutral-100 dark:border-neutral-800 flex items-center justify-center">
                        <div class="flex items-center gap-1.5">
                            <component :is="tech.is_active ? CheckCircle2 : XCircle" :class="['w-3 h-3', tech.is_active ? 'text-neutral-900 dark:text-white' : 'text-neutral-400']" />
                            <span class="text-xs font-bold uppercase tracking-[0.1em] text-neutral-400">{{ tech.is_active ? t('technologies.status.active') : t('technologies.status.inactive') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="flex flex-col items-center justify-center py-20 bg-white dark:bg-black border-2 border-dashed border-neutral-200 dark:border-neutral-800 rounded-3xl">
                <div class="p-6 rounded-full bg-neutral-100 dark:bg-neutral-800 mb-6">
                    <Code2 class="w-12 h-12 text-neutral-300 dark:text-neutral-600" />
                </div>
                <h3 class="text-xl font-black uppercase tracking-tight text-neutral-400">{{ t('technologies.empty') }}</h3>
                <p class="text-xs font-bold text-neutral-500 uppercase tracking-widest mt-2">{{ t('technologies.empty_desc') }}</p>
            </div>

                </div>
            </Transition>

            <!-- Pagination Centered -->
            <div v-if="technologies.total > 0" class="flex flex-col items-center gap-3 pt-8">
                <Pagination
                    v-if="technologies.last_page > 1"
                    v-slot="{ page }"
                    :total="technologies.total"
                    :sibling-count="1"
                    :items-per-page="technologies.per_page"
                    :default-page="technologies.current_page"
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
                <p class="text-xs font-bold text-neutral-400 uppercase tracking-widest">
                    {{ t('pagination.showing') }} {{ technologies.from }}–{{ technologies.to }} {{ t('pagination.of') }} {{ technologies.total }}
                </p>
            </div>
        </div>

        <!-- Edit Modal -->
        <Dialog v-model:open="isEditModalOpen">
            <DialogContent class="sm:max-w-[500px] max-h-[85dvh] flex flex-col !rounded-[2rem] border border-neutral-200 dark:border-neutral-800 !bg-white dark:!bg-black shadow-2xl dashboard-dialog-enter">
                <DialogHeader class="shrink-0">
                    <DialogTitle class="text-xl sm:text-2xl font-black uppercase tracking-tight">{{ t('technologies.modals.edit_title') }}</DialogTitle>
                    <DialogDescription class="text-xs sm:text-xs font-bold text-neutral-400 uppercase tracking-widest">{{ t('technologies.modals.edit_desc') }}</DialogDescription>
                </DialogHeader>
                <div class="flex-1 overflow-y-auto scrollbar-imperceptible min-h-0">
                    <TechnologyForm v-if="editingTechnology" :technology="editingTechnology" @close="closeModals" />
                </div>
                <DialogFooter class="shrink-0 flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-4 border-t border-neutral-100 dark:border-neutral-800 mt-4">
                    <Button 
                        type="button" 
                        variant="ghost" 
                        @click="closeModals"
                        class="rounded-xl font-bold uppercase text-xs tracking-wider w-full sm:w-auto"
                    >
                        {{ t('technologies.modals.cancel') }}
                    </Button>
                    <Button 
                        type="submit" 
                        form="technology-form"
                        class="bg-black hover:bg-neutral-800 text-white dark:bg-white dark:hover:bg-neutral-200 dark:text-black rounded-xl px-8 shadow-lg font-bold uppercase text-xs tracking-wider w-full sm:w-auto"
                    >
                        {{ t('technologies.modals.save') }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Delete Confirmation -->
        <ConfirmDialog
            v-model:open="isDeleteModalOpen"
            :icon="AlertTriangle"
            :description="t('actions.confirm_delete') + ' ' + (deleteTarget?.name || '')"
            :loading="deleteForm.processing"
            @confirm="confirmDelete"
        />
    </AuthenticatedLayout>
</template>

<style>
/* Shimmer classes are global in app.css */
</style>
