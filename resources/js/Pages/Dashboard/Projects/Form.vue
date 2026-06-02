<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import ProjectForm from './ProjectForm.vue';

const props = defineProps({
    project: Object,
    technologies: Array,
});

const { t } = useI18n();
</script>

<template>
    <Head :title="project ? t('dashboard_panel.projects.edit') : t('dashboard_panel.projects.create')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl md:text-4xl font-display font-bold tracking-tight text-slate-900 dark:text-white uppercase italic">
                {{ project ? t('dashboard_panel.projects.edit') : t('dashboard_panel.projects.create') }}
            </h2>
        </template>

        <div class="bg-white/50 dark:bg-slate-900/50 backdrop-blur-xl border border-slate-200 dark:border-slate-800 p-6 rounded-[28px]">
            <ProjectForm :project="project" :technologies="technologies" />
        </div>
    </AuthenticatedLayout>
</template>

