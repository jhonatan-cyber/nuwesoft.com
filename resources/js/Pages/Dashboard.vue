<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
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
    Mail
} from 'lucide-vue-next';

const { t } = useI18n();

const props = defineProps({
    stats: Object,
    recent_projects: Array,
});

const dynamicStats = [
    { label: 'Proyectos Activos', value: props.stats.active_projects, icon: Briefcase, color: 'text-indigo-500', bg: 'bg-indigo-500/10' },
    { label: 'Tecnologías', value: props.stats.active_technologies, icon: Code, color: 'text-emerald-500', bg: 'bg-emerald-500/10' },
    { label: 'Mensajes Sin Leer', value: props.stats.unread_messages, icon: Mail, color: 'text-rose-500', bg: 'bg-rose-500/10' },
    { label: 'Total Proyectos', value: props.stats.total_projects, icon: Layers, color: 'text-blue-500', bg: 'bg-blue-500/10' },
];

const shortcuts = [
    { label: 'dashboard_panel.shortcuts.projects_admin', href: route('projects.index'), icon: Layers, desc: 'Gestión técnica de proyectos' },
    { label: 'Mensajes de Contacto', href: route('messages.index'), icon: Mail, desc: 'Bandeja de mensajes' },
    { label: 'dashboard_panel.shortcuts.services', href: '/servicios', icon: Cpu, desc: 'Servicios y arquitectura' },
];
</script>

<template>
    <Head :title="t('dashboard')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="space-y-1">
                    <h2 class="text-3xl md:text-4xl font-display font-bold tracking-tight text-slate-900 dark:text-white uppercase italic">
                        {{ t('dashboard_panel.welcome') }}
                    </h2>
                    <div class="flex items-center gap-3">
                        <div class="h-0.5 w-8 bg-indigo-500 rounded-full"></div>
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">
                            {{ t('dashboard_panel.subtitle') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-3 py-1.5 rounded-xl shadow-sm">
                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <Server class="w-3 h-3" /> v2.2-REF
                        </span>
                    </div>
                    <div class="bg-indigo-600 px-3 py-1.5 rounded-xl shadow-lg shadow-indigo-600/20">
                        <span class="text-[10px] font-bold text-white uppercase tracking-widest animate-pulse flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-white rounded-full"></span> ONLINE
                        </span>
                    </div>
                </div>
            </div>
        </template>

        <div class="space-y-10 pb-10">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="stat in dynamicStats" :key="stat.label" 
                    class="bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 p-6 rounded-3xl shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all group overflow-hidden relative">
                    <div class="absolute top-0 right-0 p-3 opacity-[0.03] group-hover:opacity-10 transition-opacity">
                        <component :is="stat.icon" class="w-20 h-20 -mr-6 -mt-6" />
                    </div>
                    <div class="relative z-10 space-y-4">
                        <div :class="[stat.bg, 'w-10 h-10 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110 duration-500']">
                            <component :is="stat.icon" :class="['w-5 h-5', stat.color]" />
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">{{ t(stat.label) }}</p>
                            <p class="text-3xl font-display font-bold text-slate-900 dark:text-white tracking-tight">{{ stat.value }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Shortcuts Section -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex items-center gap-4">
                        <h3 class="text-xl font-display font-bold uppercase tracking-tight text-slate-900 dark:text-white">
                            {{ t('dashboard_panel.shortcuts.title') }}
                        </h3>
                        <div class="flex-1 h-px bg-slate-100 dark:bg-slate-800"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <Link v-for="item in shortcuts" :key="item.label" :href="item.href"
                            class="bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 p-6 rounded-3xl shadow-sm hover:border-indigo-500/50 transition-all group flex flex-col h-full overflow-hidden relative">
                            <div class="absolute inset-0 bg-indigo-600 opacity-0 group-hover:opacity-[0.02] transition-opacity"></div>
                            
                            <div class="w-12 h-12 rounded-2xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                                <component :is="item.icon" class="w-6 h-6" />
                            </div>
                            
                            <h4 class="text-lg font-bold uppercase tracking-tight mb-2 italic">{{ t(item.label) }}</h4>
                            <p class="text-[11px] text-slate-400 uppercase font-medium mb-8 leading-tight">{{ item.desc }}</p>
                            
                            <div class="mt-auto flex items-center justify-between border-t border-slate-50 dark:border-slate-800/50 pt-4">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 group-hover:text-indigo-600 transition-colors">Abrir módulo</span>
                                <ArrowRight class="w-4 h-4 transform group-hover:translate-x-1 transition-transform text-slate-300 group-hover:text-indigo-500" />
                            </div>
                        </Link>
                    </div>

                    <!-- System Status Terminal - Refined -->
                    <div class="bg-slate-950 p-8 rounded-[40px] border border-slate-800/50 shadow-2xl relative overflow-hidden font-mono group transition-all duration-500 hover:border-emerald-500/30">
                        <!-- Glass reflections -->
                        <div class="absolute -top-1/2 -left-1/2 w-full h-full bg-emerald-500/5 blur-[100px] pointer-events-none"></div>
                        
                        <div class="flex items-center justify-between mb-8 relative z-10">
                            <div class="flex items-center gap-4">
                                <div class="relative flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-20"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                </div>
                                <h3 class="text-sm font-bold uppercase tracking-[0.3em] text-emerald-500/80">CORE_SYNC_MONITOR</h3>
                            </div>
                            <span class="text-[10px] text-slate-600 font-bold tracking-widest">ENCRYPTION: AES-256-GCM</span>
                        </div>

                        <div class="space-y-5 text-xs relative z-10">
                            <div class="flex items-center justify-between p-3 rounded-2xl bg-white/5 border border-white/5 group/row hover:border-emerald-500/20 transition-all">
                                <span class="text-slate-500 font-bold tracking-tighter uppercase">> CPU_LOAD_OPTIMIZED</span>
                                <div class="flex items-center gap-3">
                                    <div class="w-32 h-1.5 bg-slate-900 rounded-full overflow-hidden flex">
                                        <div class="h-full bg-emerald-500 w-[24%] shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                                    </div>
                                    <span class="text-emerald-400 font-bold">24%</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 rounded-2xl bg-white/5 border border-white/5 group/row hover:border-indigo-500/20 transition-all">
                                <span class="text-slate-500 font-bold tracking-tighter uppercase">> MEMORY_STACK_ALLOC</span>
                                <div class="flex items-center gap-3">
                                    <div class="w-32 h-1.5 bg-slate-900 rounded-full overflow-hidden flex">
                                        <div class="h-full bg-indigo-500 w-[62%] shadow-[0_0_8px_rgba(99,102,241,0.5)]"></div>
                                    </div>
                                    <span class="text-indigo-400 font-bold">62%</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 rounded-2xl bg-emerald-500/5 border border-emerald-500/10">
                                <div class="flex items-center gap-3">
                                    <Activity class="w-4 h-4 text-emerald-500" />
                                    <span class="text-slate-400 uppercase font-bold text-[10px] tracking-widest">Sincronización Global</span>
                                </div>
                                <span class="text-emerald-400 font-bold">ACTIVO</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar - Arsenal -->
                <div class="space-y-8">
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <h3 class="text-xl font-display font-bold uppercase tracking-tight text-slate-900 dark:text-white">
                                {{ t('dashboard_panel.arsenal_summary') }}
                            </h3>
                            <div class="flex-1 h-px bg-slate-100 dark:bg-slate-800"></div>
                        </div>

                        <div class="bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 p-8 rounded-[40px] shadow-sm relative overflow-hidden group">
                            <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-indigo-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                            
                            <div class="flex flex-wrap gap-2 mb-8 relative z-10">
                                <span v-for="tech in ['PHP 8.3', 'Go', 'Docker', 'Inertia', 'Vue 3', 'Tailwind', 'Bun', 'Radix']" :key="tech"
                                    class="px-3 py-1 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 text-[9px] font-bold uppercase tracking-widest rounded-lg text-slate-600 dark:text-slate-400">
                                    {{ tech }}
                                </span>
                            </div>
                            
                            <div class="flex items-start gap-4 mb-8 relative z-10">
                                <div class="w-1 h-12 bg-indigo-500 rounded-full"></div>
                                <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 leading-relaxed uppercase tracking-tight">
                                    Arquitectura optimizada bajo el núcleo de ingeniería V2.2-REF. Sincronización completa.
                                </p>
                            </div>
                            
                            <Link href="/servicios" class="group/btn flex items-center justify-between bg-slate-900 dark:bg-white text-white dark:text-black p-5 rounded-2xl font-bold uppercase tracking-[0.2em] text-[10px] hover:bg-indigo-600 dark:hover:bg-indigo-500 hover:text-white transition-all shadow-xl shadow-slate-900/10 dark:shadow-none relative z-10 overflow-hidden">
                                <div class="absolute inset-0 bg-white/10 translate-y-full group-hover/btn:translate-y-0 transition-transform"></div>
                                <span class="relative z-10">{{ t('dashboard_panel.tech_stack') }}</span>
                                <ExternalLink class="w-4 h-4 relative z-10 group-hover/btn:rotate-45 transition-transform" />
                            </Link>
                        </div>
                    </div>

                    <!-- Master Alert -->
                    <div class="bg-indigo-600 p-8 rounded-[40px] shadow-2xl shadow-indigo-600/30 relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                        
                        <div class="flex items-center gap-3 mb-4 relative z-10">
                            <Zap class="w-6 h-6 text-white fill-white animate-pulse" />
                            <h4 class="text-lg font-bold uppercase tracking-tight text-white italic">NOTIFICACIÓN MAESTRA</h4>
                        </div>
                        <p class="text-[11px] font-bold text-indigo-100 uppercase leading-normal tracking-wide relative z-10">
                            SISTEMAS OPERANDO EN ESTADO ÓPTIMO. INTERFAZ REFINADA ACTIVADA CON SUCESO.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.font-display { font-family: 'Space Grotesk', system-ui, sans-serif; }
</style>
