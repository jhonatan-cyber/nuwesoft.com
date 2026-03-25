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
    MessageSquare
} from 'lucide-vue-next';

const { t } = useI18n();

const stats = [
    { label: 'dashboard_panel.stats.projects', value: '12', icon: Briefcase, color: 'bg-blue-500' },
    { label: 'dashboard_panel.stats.uptime', value: '99.9%', icon: Activity, color: 'bg-green-500' },
    { label: 'dashboard_panel.stats.deploy', value: '2h ago', icon: Clock, color: 'bg-purple-500' },
    { label: 'dashboard_panel.stats.commits', value: '142', icon: Code, color: 'bg-orange-500' },
];

const shortcuts = [
    { label: 'dashboard_panel.shortcuts.services', href: '/servicios', icon: Cpu, desc: 'Gestión técnica' },
    { label: 'dashboard_panel.shortcuts.portfolio', href: '/portafolio', icon: LayoutDashboard, desc: 'Vitrina de proyectos' },
    { label: 'dashboard_panel.shortcuts.contact', href: '/contacto', icon: MessageSquare, desc: 'Canales directos' },
];
</script>

<template>
    <Head :title="t('dashboard')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-4xl font-display font-black uppercase italic leading-tight text-black">
                        {{ t('dashboard_panel.welcome') }}
                    </h2>
                    <p class="text-sm font-mono font-bold text-gray-500 uppercase tracking-tighter">
                        {{ t('dashboard_panel.subtitle') }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-black text-white text-xs font-mono font-bold border-2 border-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] uppercase">
                        V 2.0.4-MASTER
                    </span>
                    <span class="px-3 py-1 bg-green-400 text-black text-xs font-mono font-bold border-2 border-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] uppercase">
                        ONLINE
                    </span>
                </div>
            </div>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
                
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="stat in stats" :key="stat.label" 
                        class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all group">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-mono font-black text-gray-500 uppercase mb-1">{{ t(stat.label) }}</p>
                                <p class="text-4xl font-display font-black text-black">{{ stat.value }}</p>
                            </div>
                            <div :class="[stat.color, 'p-3 border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] group-hover:shadow-none transition-all']">
                                <component :is="stat.icon" class="w-6 h-6 text-white" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Shortcuts Section -->
                    <div class="lg:col-span-2 space-y-6">
                        <h3 class="text-2xl font-display font-black uppercase italic border-b-4 border-black inline-block mb-2">
                            {{ t('dashboard_panel.shortcuts.title') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <Link v-for="item in shortcuts" :key="item.label" :href="item.href"
                                class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:bg-yellow-400 transition-colors group">
                                <component :is="item.icon" class="w-10 h-10 mb-4 text-black" />
                                <h4 class="text-xl font-display font-black uppercase mb-1 leading-tight">{{ t(item.label) }}</h4>
                                <p class="text-xs font-mono font-bold text-gray-600 uppercase">{{ item.desc }}</p>
                                <ArrowRight class="mt-4 w-6 h-6 transform group-hover:translate-x-2 transition-transform" />
                            </Link>
                        </div>

                        <!-- System Status Mock -->
                        <div class="bg-black text-white p-8 border-4 border-black shadow-[12px_12px_0px_0px_rgba(59,130,246,0.5)]">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                                <h3 class="text-2xl font-display font-black uppercase tracking-tighter">NÚCLEO MAESTRO OPERATIVO</h3>
                            </div>
                            <div class="space-y-4 font-mono text-sm">
                                <div class="flex justify-between border-b border-gray-800 pb-2">
                                    <span class="text-gray-500">> CPU_LOAD</span>
                                    <span class="text-green-400">[ |||||||||| 24% ]</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-800 pb-2">
                                    <span class="text-gray-500">> MEMORY_SYNC</span>
                                    <span class="text-blue-400">[ |||||||||||||| 62% ]</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-800 pb-2">
                                    <span class="text-gray-500">> LATENCY_STABLE</span>
                                    <span class="text-yellow-400">14ms</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">> ENCRYPTION</span>
                                    <span class="text-white">AES-256-GCM ACTIVE</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tech Arsenal Summary -->
                    <div class="space-y-6">
                        <h3 class="text-2xl font-display font-black uppercase italic border-b-4 border-black inline-block mb-2">
                            {{ t('dashboard_panel.arsenal_summary') }}
                        </h3>
                        <div class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                            <div class="flex flex-wrap gap-2 mb-6">
                                <span v-for="tech in ['PHP', 'Go', 'Docker', 'React', 'Node', 'AWS', 'Vue', 'Python']" :key="tech"
                                    class="px-2 py-1 bg-gray-100 border-2 border-black text-[10px] font-mono font-black uppercase">
                                    {{ tech }}
                                </span>
                            </div>
                            <p class="text-xs font-mono font-bold text-gray-600 mb-6 uppercase leading-relaxed">
                                El arsenal completo está sincronizado y listo para despliegue masivo en cualquier infraestructura global.
                            </p>
                            <Link href="/servicios" class="flex items-center justify-between bg-black text-white p-4 font-display font-black uppercase italic hover:bg-gray-800 transition-colors">
                                {{ t('dashboard_panel.tech_stack') }}
                                <ExternalLink class="w-5 h-5" />
                            </Link>
                        </div>

                        <!-- Info Card -->
                        <div class="bg-yellow-400 border-4 border-black p-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                            <h4 class="text-xl font-display font-black uppercase mb-2 italic">NOTIFICACIÓN MAESTRA</h4>
                            <p class="text-sm font-mono font-bold text-black uppercase">
                                Todos los sistemas están operando bajo parámetros óptimos. Próxima ventana de mantenimiento programada: T+48h.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.font-display {
    font-family: 'Space Grotesk', system-ui, sans-serif;
}
</style>
