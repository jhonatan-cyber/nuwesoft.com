<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { computed } from 'vue';
import { usePostHog } from '@/composables/usePostHog';
import { usePageTracking } from '@/composables/usePageTracking';
import { useSkeletonLoader } from '@/composables/useSkeletonLoader';

usePageTracking();
import LocalFeatureFlags from '@/Components/LocalFeatureFlags.vue';
import { 
    LayoutDashboard, 
    Rocket, 
    Shield, 
    Zap, 
    Clock, 
    Activity, 
    ArrowRight, 
    ExternalLink,
    Code,
    Cpu,
    Briefcase,
    MessageSquare,
    ChevronRight,
    Globe,
    Server,
    Database,
    Layers,
    TrendingUp,
    Users,
    MousePointerClick,
    Flag
} from 'lucide-vue-next';

const { t } = useI18n();

const { allFlags } = usePostHog();
const posthogConfigured = computed(() => !!import.meta.env.VITE_POSTHOG_KEY);
const activeFlags = computed(() => {
    const flags = allFlags();
    return Object.entries(flags).filter(([, val]) => val).map(([key]) => key);
});

const { skeletonReady } = useSkeletonLoader();

const props = defineProps({
    stats: { type: Object, default: () => ({}) },
    recent_projects: { type: Array, default: () => [] },
    latest_messages: { type: Array, default: () => [] },
})

const dynamicStats = computed(() => [
    { label: t('dashboard_panel.stats.projects'),         value: String(props.stats.active_projects     || '0'), icon: Briefcase,    color: 'text-black dark:text-white',  bg: 'bg-black/10 dark:bg-white/10' },
    { label: t('dashboard_panel.stats.uptime'),           value: '99.9%',                                        icon: Activity,     color: 'text-emerald-500',             bg: 'bg-emerald-500/10' },
    { label: t('dashboard_panel.stats.technologies'),     value: String(props.stats.active_technologies || '0'), icon: Code,         color: 'text-rose-500',                bg: 'bg-rose-500/10' },
    { label: t('dashboard_panel.stats.pending_messages'), value: String(props.stats.pending_messages    || '0'), icon: MessageSquare, color: 'text-neutral-500',            bg: 'bg-neutral-500/10' },
]);

const shortcuts = [
    { label: 'dashboard_panel.shortcuts.projects_admin', href: route('projects.index'), icon: Layers, desc: 'Gestión técnica de proyectos' },
    { label: 'dashboard_panel.shortcuts.services', href: '/servicios', icon: Cpu, desc: 'Servicios y arquitectura' },
    { label: 'dashboard_panel.shortcuts.messages', href: route('messages.index'), icon: MessageSquare, desc: 'Bandeja de mensajes de contacto' },
];
</script>

<template>
    <Head :title="t('dashboard')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="space-y-1">
                    <h2 class="text-3xl md:text-4xl font-display font-bold tracking-tight text-neutral-900 dark:text-white uppercase italic">
                        {{ t('dashboard_panel.welcome') }}
                    </h2>
                    <div class="flex items-center gap-3">
                        <div class="h-0.5 w-8 bg-black dark:bg-white rounded-full"></div>
                        <p class="text-[10px] font-bold text-neutral-500 dark:text-neutral-300 uppercase tracking-[0.2em]">
                            {{ t('dashboard_panel.subtitle') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 px-3 py-1.5 rounded-xl shadow-sm">
                        <span class="text-[10px] font-bold text-neutral-500 dark:text-neutral-400 uppercase tracking-widest flex items-center gap-2">
                            <Server class="w-3 h-3" />
                        </span>
                    </div>
                    <div class="bg-black dark:bg-white px-3 py-1.5 rounded-xl shadow-lg">
                        <span class="text-[10px] font-bold text-white dark:text-black uppercase tracking-widest animate-pulse flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-white rounded-full"></span> ONLINE
                        </span>
                    </div>
                </div>
            </div>
        </template>

        <div class="space-y-10 pb-10">
            <Transition name="fade" mode="out-in">
                <div v-if="!skeletonReady" key="skeleton" class="space-y-10">
                    <!-- Stats skeleton -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div v-for="i in 4" :key="'stat-' + i"
                            class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-6 rounded-3xl overflow-hidden relative pointer-events-none select-none">
                            <div class="absolute inset-0 shimmer-sweep z-10"></div>
                            <div class="relative z-20 space-y-4">
                                <div class="w-10 h-10 rounded-2xl skeleton-bg"></div>
                                <div class="space-y-2">
                                    <div class="h-3 w-24 rounded skeleton-bg"></div>
                                    <div class="h-8 w-16 skeleton-bg"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Analytics skeleton -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div v-for="i in 4" :key="'analytics-' + i" class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-5 rounded-2xl overflow-hidden relative pointer-events-none select-none">
                            <div class="absolute inset-0 shimmer-sweep z-10"></div>
                            <div class="relative z-20 space-y-3">
                                <div class="w-8 h-8 rounded-xl skeleton-bg"></div>
                                <div class="h-6 w-16 skeleton-bg"></div>
                                <div class="h-3 w-20 skeleton-bg"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Shortcuts + Arsenal skeleton -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2 space-y-6">
                            <div class="h-6 w-48 skeleton-bg"></div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div v-for="i in 3" :key="'shortcut-' + i" class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-6 rounded-3xl overflow-hidden relative pointer-events-none select-none">
                                    <div class="absolute inset-0 shimmer-sweep z-10"></div>
                                    <div class="relative z-20 space-y-4">
                                        <div class="w-12 h-12 rounded-2xl skeleton-bg"></div>
                                        <div class="h-5 w-3/4 skeleton-bg"></div>
                                        <div class="h-3 w-full skeleton-bg"></div>
                                        <div class="h-3 w-2/3 skeleton-bg"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div class="h-6 w-32 skeleton-bg"></div>
                            <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-8 rounded-[40px] overflow-hidden relative pointer-events-none select-none">
                                <div class="absolute inset-0 shimmer-sweep z-10"></div>
                                <div class="relative z-20 space-y-4">
                                    <div class="flex flex-wrap gap-2">
                                        <div v-for="i in 6" :key="'chip-' + i" class="h-6 w-16 rounded-lg skeleton-bg"></div>
                                    </div>
                                    <div class="h-24 w-full skeleton-bg"></div>
                                    <div class="h-12 w-full skeleton-bg"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else key="content">
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="stat in dynamicStats" :key="stat.label" 
                    class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-6 rounded-3xl shadow-sm hover:shadow-xl transition-all group overflow-hidden relative">
                    <div class="absolute top-0 right-0 p-3 opacity-[0.03] group-hover:opacity-10 transition-opacity">
                        <component :is="stat.icon" class="w-20 h-20 -mr-6 -mt-6" />
                    </div>
                    <div class="relative z-10 space-y-4">
                        <div :class="[stat.bg, 'w-10 h-10 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110 duration-500']">
                            <component :is="stat.icon" :class="['w-5 h-5', stat.color]" />
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-neutral-400 dark:text-neutral-500 uppercase tracking-widest mb-1">{{ stat.label }}</p>
                            <p class="text-3xl font-display font-bold text-neutral-900 dark:text-white tracking-tight">{{ stat.value }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PostHog Analytics Widget -->
            <div v-if="posthogConfigured" class="mb-8">
                <div class="flex items-center gap-4 mb-6">
                    <TrendingUp class="w-5 h-5 text-neutral-500" />
                    <h3 class="text-xl font-display font-bold uppercase tracking-tight text-neutral-900 dark:text-white">
                        POSTHOG ANALYTICS
                    </h3>
                    <div class="flex-1 h-px bg-neutral-100 dark:bg-neutral-800"></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-5 rounded-2xl shadow-sm">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 rounded-xl bg-brutalist-pink/10 flex items-center justify-center">
                                <Flag class="w-4 h-4 text-brutalist-pink" />
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">FEATURE FLAGS</span>
                        </div>
                        <p class="text-2xl font-display font-bold">{{ activeFlags.length }}</p>
                        <p class="text-[10px] text-neutral-400 uppercase tracking-wider mt-1">Flags activos</p>
                    </div>

                    <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-5 rounded-2xl shadow-sm">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 rounded-xl bg-brutalist-blue/10 flex items-center justify-center">
                                <Users class="w-4 h-4 text-brutalist-blue" />
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">AUTOCAPTURE</span>
                        </div>
                        <p class="text-2xl font-display font-bold">ACTIVE</p>
                        <p class="text-[10px] text-neutral-400 uppercase tracking-wider mt-1">Eventos automáticos</p>
                    </div>

                    <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-5 rounded-2xl shadow-sm">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 rounded-xl bg-brutalist-yellow/10 flex items-center justify-center">
                                <TrendingUp class="w-4 h-4 text-brutalist-yellow" />
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">PAGEVIEWS</span>
                        </div>
                        <p class="text-2xl font-display font-bold">AUTO</p>
                        <p class="text-[10px] text-neutral-400 uppercase tracking-wider mt-1">Tracking via Inertia</p>
                    </div>

                    <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-5 rounded-2xl shadow-sm">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                                <MousePointerClick class="w-4 h-4 text-emerald-500" />
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">EVENTOS KEY</span>
                        </div>
                        <p class="text-2xl font-display font-bold">5</p>
                        <p class="text-[10px] text-neutral-400 uppercase tracking-wider mt-1">Eventos trackeados</p>
                    </div>
                </div>

                <div v-if="activeFlags.length > 0" class="mt-4 p-4 bg-brutalist-yellow/5 border border-brutalist-yellow/20 rounded-2xl">
                    <div class="flex items-center gap-2 mb-2">
                        <Flag class="w-4 h-4 text-brutalist-yellow" />
                        <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-600 dark:text-neutral-400">FLAGS ACTIVOS</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span v-for="flag in activeFlags" :key="flag"
                            class="px-3 py-1 bg-brutalist-yellow/20 border border-brutalist-yellow/40 text-[10px] font-bold uppercase tracking-wider rounded-lg text-brutalist-yellow-800 dark:text-brutalist-yellow-200"
                        >
                            {{ flag }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- PostHog Not Configured -->
            <div v-else class="mb-8">
                <div class="flex items-center gap-4 mb-6">
                    <TrendingUp class="w-5 h-5 text-neutral-400" />
                    <h3 class="text-xl font-display font-bold uppercase tracking-tight text-neutral-500">
                        POSTHOG ANALYTICS
                    </h3>
                    <div class="flex-1 h-px bg-neutral-100 dark:bg-neutral-800"></div>
                </div>
                <div class="bg-neutral-50 dark:bg-neutral-900 border border-dashed border-neutral-200 dark:border-neutral-800 p-6 rounded-2xl">
                    <p class="text-xs font-bold uppercase tracking-widest text-neutral-400">
                        Configurá <code class="px-2 py-0.5 bg-neutral-200 dark:bg-neutral-700 rounded">POSTHOG_KEY</code> en tu <code class="px-2 py-0.5 bg-neutral-200 dark:bg-neutral-700 rounded">.env</code> para activar analytics.
                    </p>
                </div>
            </div>

            <!-- Local Feature Flags Panel -->
            <div class="max-w-2xl">
                <LocalFeatureFlags />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Shortcuts Section -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex items-center gap-4">
                        <h3 class="text-xl font-display font-bold uppercase tracking-tight text-neutral-900 dark:text-white">
                            {{ t('dashboard_panel.shortcuts.title') }}
                        </h3>
                        <div class="flex-1 h-px bg-neutral-100 dark:bg-neutral-800"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <Link v-for="item in shortcuts" :key="item.label" :href="item.href"
                            class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-6 rounded-3xl shadow-sm hover:border-neutral-400 dark:hover:border-neutral-600 transition-all group flex flex-col h-full overflow-hidden relative">
                            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-[0.02] transition-opacity"></div>
                            
                            <div class="w-12 h-12 rounded-2xl border border-neutral-100 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 flex items-center justify-center mb-6 group-hover:bg-black group-hover:text-white dark:group-hover:bg-white dark:group-hover:text-black transition-all duration-500">
                                <component :is="item.icon" class="w-6 h-6" />
                            </div>
                            
                            <h4 class="text-lg font-bold uppercase tracking-tight mb-2 italic">{{ t(item.label) }}</h4>
                            <p class="text-[11px] text-neutral-400 uppercase font-medium mb-8 leading-tight">{{ item.desc }}</p>
                            
                            <div class="mt-auto flex items-center justify-between border-t border-neutral-50 dark:border-neutral-800 pt-4">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 group-hover:text-black dark:group-hover:text-white transition-colors">Abrir módulo</span>
                                <ArrowRight class="w-4 h-4 transform group-hover:translate-x-1 transition-transform text-neutral-300 group-hover:text-black dark:group-hover:text-white" />
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Right Sidebar - Arsenal -->
                <div class="space-y-8">
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <h3 class="text-xl font-display font-bold uppercase tracking-tight text-neutral-900 dark:text-white">
                                {{ t('dashboard_panel.arsenal_summary') }}
                            </h3>
                            <div class="flex-1 h-px bg-neutral-100 dark:bg-neutral-800"></div>
                        </div>

                        <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-8 rounded-[40px] shadow-sm relative overflow-hidden group">
                            <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-black/5 dark:bg-white/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                            
                            <div class="flex flex-wrap gap-2 mb-8 relative z-10">
                                <span v-for="tech in ['PHP 8.3', 'Go', 'Docker', 'Inertia', 'Vue 3', 'Tailwind', 'Bun', 'Radix']" :key="tech"
                                    class="px-3 py-1 bg-neutral-50 dark:bg-neutral-800 border border-neutral-100 dark:border-neutral-700 text-[9px] font-bold uppercase tracking-widest rounded-lg text-neutral-600 dark:text-neutral-400">
                                    {{ tech }}
                                </span>
                            </div>
                            
                            <div class="flex items-start gap-4 mb-8 relative z-10">
                                <div class="w-1 h-12 bg-black dark:bg-white rounded-full"></div>
                                <p class="text-[11px] font-medium text-neutral-500 dark:text-neutral-400 leading-relaxed uppercase tracking-tight">
                                    Arquitectura optimizada bajo el núcleo de ingeniería V2.2-REF. Sincronización completa.
                                </p>
                            </div>
                            
                            <Link href="/servicios" class="group/btn flex items-center justify-between bg-black dark:bg-white text-white dark:text-black p-5 rounded-2xl font-bold uppercase tracking-[0.2em] text-[10px] hover:bg-neutral-800 dark:hover:bg-neutral-200 hover:text-white dark:hover:text-black transition-all shadow-xl relative z-10 overflow-hidden">
                                <div class="absolute inset-0 bg-white/10 translate-y-full group-hover/btn:translate-y-0 transition-transform"></div>
                                <span class="relative z-10">{{ t('dashboard_panel.tech_stack') }}</span>
                                <ExternalLink class="w-4 h-4 relative z-10 group-hover/btn:rotate-45 transition-transform" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
                </div>
            </Transition>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.font-display {
    font-family: 'Space Grotesk', system-ui, sans-serif;
}

/* Custom animations for the terminal dots */
@keyframes pulse-emerald {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 1; box-shadow: 0 0 15px rgba(16, 185, 129, 0.4); }
}
</style>
