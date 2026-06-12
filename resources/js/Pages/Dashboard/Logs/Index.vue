<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useSkeletonLoader } from '@/composables/useSkeletonLoader';
import { useI18n } from 'vue-i18n';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { AlertTriangle, RefreshCw, Search, Globe, Monitor, Clock, Link } from 'lucide-vue-next';
import { Badge } from '@/Components/ui/badge';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/Components/ui/card';

const { t } = useI18n();
const page = usePage();
const settings = computed(() => page.props.settings || {});
const siteName = computed(() => settings.value.site_name || 'NUWESOFT');

const props = defineProps({
    logs: { type: Array, default: () => [] },
    stats: { type: Object, default: () => ({ total: 0, today: 0, unique_urls: 0 }) },
});

const { skeletonReady } = useSkeletonLoader();

const search = ref('');
const loading = ref(false);

const filteredLogs = computed(() => {
    if (!search.value) return props.logs;
    const q = search.value.toLowerCase();
    return props.logs.filter(log =>
        log.url?.toLowerCase().includes(q) ||
        log.referer?.toLowerCase().includes(q) ||
        log.ip?.includes(q)
    );
});

const refresh = () => {
    loading.value = true;
    window.location.reload();
};
</script>

<template>
    <Head title="404 Logs" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-display font-black uppercase italic tracking-tight">404 LOGS</h2>
                    <p class="text-xs font-bold uppercase tracking-widest text-neutral-500 mt-1">MONITOREO DE BROKEN LINKS</p>
                </div>
                <Button @click="refresh" variant="outline" class="gap-2 rounded-xl border-neutral-200 dark:border-neutral-800">
                    <RefreshCw class="w-4 h-4" :class="{ 'animate-spin': loading }" />
                    ACTUALIZAR
                </Button>
            </div>
        </template>

        <Transition name="fade" mode="out-in">
            <div v-if="!skeletonReady" key="skeleton" class="space-y-6">
                <!-- Stats skeleton -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div v-for="i in 3" :key="i"
                        class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-black p-6 relative overflow-hidden pointer-events-none select-none">
                        <div class="absolute inset-0 shimmer-sweep z-10"></div>
                        <div class="space-y-3">
                            <div class="h-3 w-24 rounded skeleton-bg"></div>
                            <div class="h-10 w-16 rounded skeleton-bg"></div>
                        </div>
                    </div>
                </div>
                <!-- Search skeleton -->
                <div class="h-12 rounded-xl skeleton-bg mb-6"></div>
                <!-- Table skeleton -->
                <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-black overflow-hidden">
                    <div class="p-6 space-y-4">
                        <div v-for="i in 5" :key="'row-' + i" class="flex items-center gap-4">
                            <div class="flex-1 space-y-2">
                                <div class="h-3 w-3/4 rounded skeleton-bg"></div>
                                <div class="h-2 w-1/2 rounded skeleton-bg"></div>
                            </div>
                            <div class="h-3 w-24 rounded skeleton-bg hidden md:block"></div>
                            <div class="h-3 w-16 rounded skeleton-bg hidden lg:block"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else key="content">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <Card class="rounded-2xl border-neutral-200 dark:border-neutral-800 shadow-sm">
                        <CardHeader class="pb-2">
                            <CardTitle class="text-[10px] font-bold uppercase tracking-widest text-neutral-500 flex items-center gap-2">
                                <AlertTriangle class="w-3.5 h-3.5 text-rose-500" />
                                TOTAL 404S
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p class="text-4xl font-display font-black">{{ stats.total }}</p>
                        </CardContent>
                    </Card>
                    <Card class="rounded-2xl border-neutral-200 dark:border-neutral-800 shadow-sm">
                        <CardHeader class="pb-2">
                            <CardTitle class="text-[10px] font-bold uppercase tracking-widest text-neutral-500 flex items-center gap-2">
                                <Clock class="w-3.5 h-3.5 text-brutalist-blue" />
                                HOY
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p class="text-4xl font-display font-black">{{ stats.today }}</p>
                        </CardContent>
                    </Card>
                    <Card class="rounded-2xl border-neutral-200 dark:border-neutral-800 shadow-sm">
                        <CardHeader class="pb-2">
                            <CardTitle class="text-[10px] font-bold uppercase tracking-widest text-neutral-500 flex items-center gap-2">
                                <Link class="w-3.5 h-3.5 text-brutalist-yellow" />
                                URLS UNICAS
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p class="text-4xl font-display font-black">{{ stats.unique_urls }}</p>
                        </CardContent>
                    </Card>
                </div>

                <!-- Search -->
                <div class="relative mb-6">
                    <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" />
                    <Input
                        v-model="search"
                        placeholder="Buscar por URL, referer o IP..."
                        class="pl-12 rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-black"
                    />
                </div>

                <!-- Logs Table -->
                <Card class="rounded-2xl border-neutral-200 dark:border-neutral-800 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900">
                                    <th class="text-left px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-neutral-500">URL</th>
                                    <th class="text-left px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-neutral-500 hidden md:table-cell">REFERER</th>
                                    <th class="text-left px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-neutral-500 hidden lg:table-cell">IP</th>
                                    <th class="text-left px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-neutral-500 hidden lg:table-cell">USER AGENT</th>
                                    <th class="text-left px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-neutral-500">FECHA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="filteredLogs.length === 0">
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-3 text-neutral-400">
                                            <Globe class="w-8 h-8" />
                                            <p class="text-xs font-bold uppercase tracking-widest">No hay logs de 404</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr
                                    v-for="(log, idx) in filteredLogs"
                                    :key="idx"
                                    class="border-b border-neutral-100 dark:border-neutral-800 hover:bg-neutral-50 dark:hover:bg-neutral-900 transition-colors"
                                >
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <Globe class="w-3.5 h-3.5 shrink-0 text-rose-400" />
                                            <span class="font-mono text-xs text-rose-600 dark:text-rose-400 break-all max-w-[250px] inline-block">{{ log.url }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 hidden md:table-cell">
                                        <span class="text-xs text-neutral-500 break-all max-w-[200px] inline-block">{{ log.referer || '—' }}</span>
                                    </td>
                                    <td class="px-6 py-4 hidden lg:table-cell">
                                        <div class="flex items-center gap-1.5">
                                            <Monitor class="w-3 h-3 text-neutral-400" />
                                            <span class="text-xs text-neutral-600 dark:text-neutral-400">{{ log.ip || '—' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 hidden lg:table-cell">
                                        <span class="text-[10px] text-neutral-400 max-w-[150px] inline-block truncate" :title="log.user_agent">{{ log.user_agent || '—' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-xs text-neutral-500">{{ log.created_at }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </Card>
            </div>
        </Transition>

        <!-- Info -->
        <div class="mt-8 rounded-2xl border border-brutalist-yellow/30 bg-brutalist-yellow/5 p-6">
            <div class="flex items-start gap-3">
                <AlertTriangle class="w-5 h-5 text-brutalist-yellow shrink-0 mt-0.5" />
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-neutral-700 dark:text-neutral-300 mb-1">MONITOREO DE BROKEN LINKS</p>
                    <p class="text-xs text-neutral-500 leading-relaxed">
                        Los errores 404 se registran automáticamente cuando un visitante intenta acceder a una ruta inexistente.
                        Revisá esta lista periódicamente para identificar y corregir broken links que afecten la experiencia de usuario y el SEO.
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.font-display { font-family: 'Space Grotesk', sans-serif; }
/* Shimmer classes are global in app.css */
</style>
