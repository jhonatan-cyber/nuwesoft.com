<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { 
    Plus, 
    Search, 
    MoreHorizontal, 
    Pencil, 
    Trash2, 
    Code2,
    CheckCircle2,
    XCircle
} from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { 
    Dialog, 
    DialogContent, 
    DialogHeader, 
    DialogTitle, 
    DialogDescription,
    DialogTrigger 
} from '@/Components/ui/dialog';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import { Badge } from '@/Components/ui/badge';
import TechnologyForm from './TechnologyForm.vue';
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
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

const props = defineProps({
    technologies: Object
});

const { t } = useI18n();
const search = ref('');
const isCreateModalOpen = ref(false);
const editingTechnology = ref(null);

const perPage = ref(String(props.technologies.per_page || 24));

watch(perPage, (newValue) => {
    router.get(route('technologies.index'), { per_page: newValue }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
});

const handlePageChange = (page) => {
    router.get(route('technologies.index'), { 
        page: page,
        per_page: perPage.value 
    }, {
        preserveState: true,
        preserveScroll: true
    });
};

const filteredTechnologies = computed(() => {
    // Note: With server-side pagination, "filteredTechnologies" only filters the current page.
    // In a real app with many items, search should probably be server-side too.
    return props.technologies.data.filter(tech => 
        tech.name.toLowerCase().includes(search.value.toLowerCase()) ||
        tech.category.toLowerCase().includes(search.value.toLowerCase())
    );
});

const openEditModal = (tech) => {
    editingTechnology.value = tech;
};

const closeModals = () => {
    isCreateModalOpen.value = false;
    editingTechnology.value = null;
};

const deleteForm = useForm({});
const deleteTechnology = (id) => {
    if (confirm(t('actions.confirm_delete'))) {
        deleteForm.delete(route('technologies.destroy', id));
    }
};

const getCategoryColor = (category) => {
    const colors = {
        languages: 'bg-amber-500/10 text-amber-500 border-amber-500/20',
        frameworks: 'bg-indigo-500/10 text-indigo-500 border-indigo-500/20',
        libraries: 'bg-blue-500/10 text-blue-500 border-blue-500/20',
        database: 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
        cloud: 'bg-sky-500/10 text-sky-500 border-sky-500/20',
        tools: 'bg-slate-500/10 text-slate-500 border-slate-500/20',
        ai: 'bg-purple-500/10 text-purple-500 border-purple-500/20',
        mobile: 'bg-rose-500/10 text-rose-500 border-rose-500/20',
    };
    return colors[category] || 'bg-slate-500/10 text-slate-500 border-slate-500/20';
};
</script>

<template>
    <Head :title="t('technologies.title')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white uppercase">
                        {{ t('technologies.title') }}
                    </h2>
                    <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-1">
                        {{ t('technologies.subtitle') }}
                    </p>
                </div>

                <Dialog v-model:open="isCreateModalOpen">
                    <DialogTrigger as-child>
                        <Button class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl px-6 py-6 shadow-xl shadow-indigo-600/20 transition-all hover:scale-[1.02] active:scale-[0.98] group">
                            <Plus class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" />
                            <span class="font-bold uppercase tracking-widest text-xs">{{ t('technologies.actions.add') }}</span>
                        </Button>
                    </DialogTrigger>
                    <DialogContent class="sm:max-w-[500px] rounded-[2rem] border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-slate-950/95 backdrop-blur-2xl shadow-2xl">
                        <DialogHeader>
                            <DialogTitle class="text-2xl font-black uppercase tracking-tight">{{ t('technologies.modals.create_title') }}</DialogTitle>
                            <DialogDescription class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ t('technologies.modals.create_desc') }}</DialogDescription>
                        </DialogHeader>
                        <TechnologyForm @close="closeModals" />
                    </DialogContent>
                </Dialog>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Filters & Per Page -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 bg-white/50 dark:bg-slate-900/50 backdrop-blur-xl border border-slate-200 dark:border-slate-800 p-4 rounded-[28px]">
                <div class="relative w-full md:flex-1 group">
                    <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
                    <Input 
                        v-model="search"
                        :placeholder="t('technologies.search_placeholder')"
                        class="pl-12 py-6 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-indigo-500 shadow-sm transition-all text-xs font-bold uppercase tracking-wider"
                    />
                </div>

                <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end px-2">
                    <div class="flex items-center gap-3">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ t('pagination.per_page') }}</p>
                        <Select v-model="perPage">
                            <SelectTrigger class="w-24 h-10 rounded-xl border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 font-bold text-xs">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent class="rounded-xl border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                                <SelectItem value="12" class="text-xs font-bold">12</SelectItem>
                                <SelectItem value="24" class="text-xs font-bold">24</SelectItem>
                                <SelectItem value="48" class="text-xs font-bold">48</SelectItem>
                                <SelectItem value="96" class="text-xs font-bold">96</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <div class="h-6 w-px bg-slate-200 dark:bg-slate-800 hidden md:block mx-2"></div>

                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest hidden sm:block">
                        {{ t('pagination.showing') }} {{ technologies.from }}-{{ technologies.to }} {{ t('pagination.of') }} {{ technologies.total }}
                    </div>
                </div>
            </div>

            <!-- Grid -->
            <div v-if="filteredTechnologies.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-8 gap-4">
                <div 
                    v-for="tech in filteredTechnologies" 
                    :key="tech.id"
                    class="group relative bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 rounded-3xl p-4 hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-500 overflow-hidden"
                >
                    <!-- Background Accent -->
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-indigo-500/5 dark:bg-indigo-500/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700"></div>

                    <div class="relative flex flex-col items-center text-center gap-3">
                        <div class="w-full flex justify-end absolute -top-1 -right-1 z-10">
                            <!-- Actions -->
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="ghost" class="h-8 w-8 p-0 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
                                        <MoreHorizontal class="h-4 w-4 text-slate-400" />
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end" class="w-48 p-2 rounded-2xl border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-black/90 backdrop-blur-xl shadow-2xl">
                                    <DropdownMenuItem @click="openEditModal(tech)" class="rounded-xl cursor-pointer focus:bg-indigo-50 dark:focus:bg-indigo-500/10 focus:text-indigo-600 dark:focus:text-indigo-400 py-2.5">
                                        <Pencil class="mr-2 h-4 w-4" />
                                        <span class="font-bold uppercase text-[10px] tracking-widest">{{ t('actions.edit') }}</span>
                                    </DropdownMenuItem>
                                    <div class="h-px bg-slate-100 dark:bg-slate-800 my-1"></div>
                                    <DropdownMenuItem @click="deleteTechnology(tech.id)" class="rounded-xl cursor-pointer text-rose-500 focus:bg-rose-50 dark:focus:bg-rose-500/10 focus:text-rose-600 py-2.5">
                                        <Trash2 class="mr-2 h-4 w-4" />
                                        <span class="font-bold uppercase text-[10px] tracking-widest">{{ t('actions.delete') }}</span>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>

                        <!-- Logo -->
                        <div class="w-12 h-12 shrink-0 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 flex items-center justify-center p-2 shadow-sm group-hover:scale-110 transition-transform duration-500">
                            <img v-if="tech.logo_url" :src="tech.logo_url" :alt="tech.name" class="w-full h-full object-contain" />
                            <Code2 v-else class="w-6 h-6 text-slate-300 dark:text-slate-700" />
                        </div>

                        <div class="w-full min-w-0">
                            <h3 class="font-black text-[11px] tracking-tight text-slate-900 dark:text-white uppercase truncate px-1">
                                {{ tech.name }}
                            </h3>
                            <div class="flex flex-col items-center gap-1 mt-1.5">
                                <Badge variant="outline" :class="['rounded-full px-2 py-0 text-[8px] font-bold uppercase tracking-widest', getCategoryColor(tech.category)]">
                                    {{ tech.category }}
                                </Badge>
                                <Badge v-if="!tech.is_active" variant="destructive" class="rounded-full px-2 py-0 text-[8px] font-bold uppercase tracking-widest">
                                    {{ t('status.inactive') }}
                                </Badge>
                            </div>
                        </div>
                    </div>

                    <!-- Compact Status -->
                    <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800/50 flex items-center justify-center">
                        <div class="flex items-center gap-1.5">
                            <component :is="tech.is_active ? CheckCircle2 : XCircle" :class="['w-3 h-3', tech.is_active ? 'text-emerald-500' : 'text-slate-400']" />
                            <span class="text-[8px] font-bold uppercase tracking-[0.1em] text-slate-400">{{ tech.is_active ? 'Active' : 'Off' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="flex flex-col items-center justify-center py-20 bg-white/30 dark:bg-slate-900/20 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-[3rem]">
                <div class="p-6 rounded-full bg-slate-100 dark:bg-slate-800 mb-6">
                    <Code2 class="w-12 h-12 text-slate-300 dark:text-slate-600" />
                </div>
                <h3 class="text-xl font-black uppercase tracking-tight text-slate-400">{{ t('technologies.empty') }}</h3>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-2">{{ t('technologies.empty_desc') }}</p>
            </div>

            <!-- Pagination Centered -->
            <div v-if="technologies.last_page > 1" class="flex justify-center pt-8">
                <Pagination
                    v-slot="{ page }"
                    :total="technologies.total"
                    :sibling-count="1"
                    :items-per-page="technologies.per_page"
                    :default-page="technologies.current_page"
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

        <!-- Edit Modal -->
        <Dialog :open="!!editingTechnology" @update:open="val => !val && closeModals()">
            <DialogContent class="sm:max-w-[500px] rounded-[2rem] border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-slate-950/95 backdrop-blur-2xl shadow-2xl">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-black uppercase tracking-tight">{{ t('technologies.modals.edit_title') }}</DialogTitle>
                    <DialogDescription class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ t('technologies.modals.edit_desc') }}</DialogDescription>
                </DialogHeader>
                <TechnologyForm v-if="editingTechnology" :technology="editingTechnology" @close="closeModals" />
            </DialogContent>
        </Dialog>
    </AuthenticatedLayout>
</template>
