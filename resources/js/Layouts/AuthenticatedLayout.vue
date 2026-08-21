<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import DashboardThemeSwitcher from '@/Components/DashboardThemeSwitcher.vue';
import DashboardLanguageSwitcher from '@/Components/DashboardLanguageSwitcher.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { 
    Menu, 
    X, 
    LogOut, 
    User, 
    Home, 
    LayoutDashboard, 
    Briefcase,
    Code2,
    Mail,
    ChevronRight,
    Bell,
    ChevronDown,
} from 'lucide-vue-next';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import { Button } from '@/Components/ui/button';

const { t } = useI18n();
const isSidebarOpen = ref(true);
const isMobileMenuOpen = ref(false);
const isScrolled = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const navigation = [
    { name: t('dashboard'), href: 'dashboard', icon: LayoutDashboard },
    { name: t('dashboard_panel.projects.title'), href: 'projects.index', icon: Briefcase },
    { name: t('technologies.title'), href: 'technologies.index', icon: Code2 },
    { name: 'Mensajes', href: 'messages.index', icon: Mail },
];

const secondaryNavigation = [
    { name: t('home'), href: '/', icon: Home, isExternal: true },
];

// Handle scroll for navbar effects
onMounted(() => {
    const handleScroll = () => {
        isScrolled.value = window.scrollY > 10;
    };
    window.addEventListener('scroll', handleScroll);
    onUnmounted(() => window.removeEventListener('scroll', handleScroll));
});

// Cerrar menú móvil al navegar
router.on('navigate', () => {
    isMobileMenuOpen.value = false;
});
</script>

<template>
    <div class="min-h-screen bg-[#FAFAFC] dark:bg-[#030303] text-slate-900 dark:text-slate-100 font-sans selection:bg-indigo-500 selection:text-white overflow-x-hidden transition-colors duration-300">
        <!-- Subtle Background Elements -->
        <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-500/5 blur-[120px] dark:bg-indigo-500/10 transition-opacity duration-1000"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-blue-500/5 blur-[120px] dark:bg-blue-500/10 transition-opacity duration-1000"></div>
        </div>

        <!-- Sidebar (Desktop) -->
        <aside 
            :class="[
                'fixed left-6 top-6 bottom-6 z-50 transition-all duration-300 ease-in-out hidden lg:flex flex-col border border-slate-200 dark:border-slate-800/50 bg-white/80 dark:bg-black/40 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden',
                isSidebarOpen ? 'w-64' : 'w-20'
            ]"
        >
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center px-6 border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-white/5">
                <Link href="/" class="flex items-center gap-3 overflow-hidden">
                    <div class="shrink-0 rounded-2xl border border-slate-200 bg-white px-2 py-2 shadow-lg dark:border-slate-800 dark:bg-slate-950">
                        <ApplicationLogo class="w-10" />
                    </div>
                    <div v-if="isSidebarOpen" class="transition-all duration-300">
                        <ApplicationLogo class="w-32" />
                    </div>
                </Link>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto p-4 space-y-1 custom-scrollbar">
                <div v-if="isSidebarOpen" class="px-3 py-2 text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Principal</div>
                <Link 
                    v-for="item in navigation" 
                    :key="item.name"
                    :href="route(item.href)"
                    :class="[
                        'group flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-200',
                        route().current(item.href) 
                            ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 font-medium shadow-sm' 
                            : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-100'
                    ]"
                >
                    <component :is="item.icon" :class="['w-5 h-5 shrink-0 transition-colors', route().current(item.href) ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300']" />
                    <span v-if="isSidebarOpen" class="text-sm truncate">{{ item.name }}</span>
                </Link>

                <div class="my-4 border-t border-slate-200 dark:border-slate-800/50 mx-2 opacity-50"></div>

                <div v-if="isSidebarOpen" class="px-3 py-2 text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Recursos</div>
                <Link 
                    v-for="item in secondaryNavigation" 
                    :key="item.name"
                    :href="item.href"
                    class="group flex items-center gap-3 px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-100 transition-all duration-200"
                >
                    <component :is="item.icon" class="w-5 h-5 shrink-0 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300" />
                    <span v-if="isSidebarOpen" class="text-sm truncate">{{ item.name }}</span>
                </Link>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-slate-200 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-900/10">
                <div :class="['flex items-center gap-3 transition-all duration-300', isSidebarOpen ? 'justify-between' : 'justify-center']">
                    <div v-if="isSidebarOpen" class="flex items-center gap-3 overflow-hidden">
                        <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center border border-slate-300 dark:border-slate-700 shrink-0">
                            <span class="text-[10px] font-bold text-slate-600 dark:text-slate-300">{{ $page.props.auth.user.name.charAt(0) }}</span>
                        </div>
                        <div class="flex flex-col truncate">
                            <span class="text-xs font-semibold text-slate-900 dark:text-white truncate">{{ $page.props.auth.user.name }}</span>
                            <span class="text-[10px] text-slate-500 truncate leading-none">{{ $page.props.auth.user.email }}</span>
                        </div>
                    </div>
                    <button 
                        @click="toggleSidebar"
                        class="p-2 rounded-lg text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100 transition-colors shrink-0"
                    >
                        <ChevronRight :class="['w-4 h-4 transition-transform duration-500', isSidebarOpen ? 'rotate-180' : '']" />
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div 
            :class="[
                'transition-all duration-300 min-h-screen flex flex-col px-4 md:px-6 py-6',
                isSidebarOpen ? 'lg:ml-72' : 'lg:ml-28'
            ]"
        >
            <!-- Top Navbar (Floating) -->
            <header 
                :class="[
                    'sticky top-0 z-40 w-full transition-all duration-300 rounded-3xl overflow-hidden border mb-6 shadow-sm',
                    isScrolled 
                        ? 'bg-white/70 dark:bg-black/70 backdrop-blur-md border-slate-200 dark:border-slate-800' 
                        : 'bg-white dark:bg-black/40 border-slate-100 dark:border-slate-800/30'
                ]"
            >
                <div class="h-20 px-8 flex items-center justify-between">
                    <!-- Left: Mobile Menu & Breadcrumbs -->
                    <div class="flex items-center gap-4">
                        <button 
                            @click="toggleMobileMenu" 
                            class="lg:hidden p-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:text-indigo-600 transition-all shadow-sm"
                        >
                            <Menu class="w-5 h-5" />
                        </button>
                        
                        <div class="hidden md:flex items-center gap-2 text-xs font-medium">
                            <span class="text-slate-400">ADMIN</span>
                            <ChevronRight class="w-3 h-3 text-slate-300" />
                            <span class="text-slate-900 dark:text-white uppercase tracking-wider font-bold">{{ route().current()?.split('.')[0] }}</span>
                        </div>
                    </div>

                    <!-- Right: Actions & Profile -->
                    <div class="flex items-center gap-2 md:gap-4">
                        <!-- Theme & Lang -->
                        <div class="hidden sm:flex items-center gap-2 mr-2">
                            <DashboardThemeSwitcher />
                            <DashboardLanguageSwitcher />
                        </div>

                        <div class="h-6 w-px bg-slate-200 dark:bg-slate-800/50 mx-1 hidden sm:block"></div>

                        <!-- User Profile Dropdown -->
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="ghost" class="relative h-10 w-10 md:w-auto md:px-3 md:gap-3 rounded-xl hover:bg-white dark:hover:bg-slate-900 border border-transparent hover:border-slate-200 dark:hover:border-slate-800 transition-all shadow-none">
                                    <div class="h-7 w-7 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center border border-indigo-200 dark:border-indigo-800/50">
                                        <span class="text-indigo-700 dark:text-indigo-400 text-[10px] font-bold">{{ $page.props.auth.user.name.charAt(0) }}</span>
                                    </div>
                                    <span class="hidden md:block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-tight">{{ $page.props.auth.user.name }}</span>
                                    <ChevronDown class="hidden md:block w-3 h-3 text-slate-400" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" class="w-56 mt-2 p-2 rounded-2xl border-slate-200 dark:border-slate-800 shadow-xl bg-white/90 dark:bg-black/90 backdrop-blur-xl">
                                <div class="px-2 py-2 mb-1">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Identificado como</p>
                                    <p class="text-sm font-bold truncate">{{ $page.props.auth.user.name }}</p>
                                    <p class="text-[10px] text-slate-500 truncate leading-none mt-0.5">{{ $page.props.auth.user.email }}</p>
                                </div>
                                <div class="h-px bg-slate-100 dark:bg-slate-800 my-1"></div>
                                <DropdownMenuItem @click="router.get(route('profile.edit'))" class="cursor-pointer rounded-xl focus:bg-indigo-50 dark:focus:bg-indigo-500/10 focus:text-indigo-600 dark:focus:text-indigo-400 py-2.5 transition-colors">
                                    <User class="mr-2 h-4 w-4" />
                                    <span class="font-medium">{{ t('profile') }}</span>
                                </DropdownMenuItem>
                                <div class="h-px bg-slate-100 dark:bg-slate-800 my-1"></div>
                                <DropdownMenuItem @click="router.post(route('logout'))" class="cursor-pointer rounded-xl text-rose-500 focus:bg-rose-50 dark:focus:bg-rose-500/10 focus:text-rose-600 py-2.5 transition-colors">
                                    <LogOut class="mr-2 h-4 w-4" />
                                    <span class="font-bold uppercase text-[10px] tracking-widest">{{ t('logout') }}</span>
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 relative z-10">
                <!-- Header slot -->
                <div v-if="$slots.header" class="mb-8 px-2">
                    <slot name="header" />
                </div>

                <!-- Main Slot -->
                <div class="animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <slot />
                </div>
            </main>

            <!-- Refined Footer -->
            <footer class="mt-12 py-8 px-10 rounded-3xl border border-slate-200 dark:border-slate-800/50 text-center md:text-left bg-white/30 dark:bg-black/30 backdrop-blur-sm">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">
                        &copy; 2026 NUWESOFT CORE &bull; V2.2-REF &bull; {{ t('footer.no_compromise') }}
                    </p>
                    <div class="flex items-center gap-6 opacity-30">
                        <div class="h-1 w-8 bg-slate-300 dark:bg-slate-700 rounded-full"></div>
                        <div class="h-1 w-12 bg-indigo-500 rounded-full"></div>
                        <div class="h-1 w-8 bg-slate-300 dark:bg-slate-700 rounded-full"></div>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Mobile Menu Overlay -->
        <transition 
            enter-active-class="transition duration-400 ease-out"
            enter-from-class="opacity-0 -translate-x-full"
            enter-to-class="opacity-100 translate-x-0"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="opacity-100 translate-x-0"
            leave-to-class="opacity-0 -translate-x-full"
        >
            <div v-if="isMobileMenuOpen" class="fixed inset-0 z-[60] lg:hidden">
                <!-- Backdrop -->
                <div @click="toggleMobileMenu" class="absolute inset-0 bg-slate-950/20 backdrop-blur-md"></div>
                
                <!-- Drawer -->
                <div class="absolute left-0 top-0 bottom-0 w-[85%] max-w-xs bg-white dark:bg-black border-r border-slate-200 dark:border-slate-800 p-6 flex flex-col shadow-2xl">
                    <div class="flex items-center justify-between mb-10">
                        <Link href="/" class="flex items-center gap-3">
                            <div class="rounded-2xl border border-slate-200 bg-white px-2 py-2 shadow-lg dark:border-slate-800 dark:bg-slate-950">
                                <ApplicationLogo class="w-28" />
                            </div>
                        </Link>
                        <button @click="toggleMobileMenu" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-900 text-slate-500">
                            <X class="w-6 h-6" />
                        </button>
                    </div>

                    <nav class="flex-1 space-y-2 overflow-y-auto custom-scrollbar pr-2">
                        <Link 
                            v-for="item in navigation" 
                            :key="item.name"
                            :href="route(item.href)"
                            :class="[
                                'flex items-center gap-4 p-4 rounded-2xl transition-all duration-300',
                                route().current(item.href) 
                                    ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-600/30' 
                                    : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900'
                            ]"
                        >
                            <component :is="item.icon" class="w-6 h-6" />
                            <span class="font-bold uppercase tracking-tight">{{ item.name }}</span>
                        </Link>

                        <div class="py-6 px-2"><div class="h-px bg-slate-100 dark:bg-slate-800"></div></div>

                        <Link 
                            v-for="item in secondaryNavigation" 
                            :key="item.name"
                            :href="item.href"
                            class="flex items-center gap-4 p-4 rounded-2xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 transition-all"
                        >
                            <component :is="item.icon" class="w-6 h-6" />
                            <span class="font-bold uppercase tracking-tight">{{ item.name }}</span>
                        </Link>
                    </nav>

                    <div class="mt-auto pt-6 space-y-6">
                        <div class="flex flex-col gap-4">
                            <DashboardThemeSwitcher />
                            <DashboardLanguageSwitcher />
                        </div>
                        <button 
                            @click="router.post(route('logout'))"
                            class="w-full flex items-center justify-center gap-3 p-4 rounded-2xl bg-rose-500 text-white hover:bg-rose-600 transition-all font-bold text-xs uppercase tracking-[0.2em] shadow-lg shadow-rose-500/20"
                        >
                            <LogOut class="w-5 h-5" />
                            {{ t('logout') }}
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Dynamic Status Bar (Desktop Bottom) -->
        <div class="fixed bottom-6 right-8 z-40 hidden lg:flex items-center gap-4 bg-white/80 dark:bg-black/80 backdrop-blur-md border border-slate-200 dark:border-slate-800 px-4 py-2 rounded-full shadow-sm">
            <div class="flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-[9px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Sincronizado</span>
            </div>
            <div class="h-3 w-px bg-slate-200 dark:bg-slate-800"></div>
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">v2.2-REF</span>
        </div>
    </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600;700&display=swap');

.font-display { font-family: 'Space Grotesk', sans-serif; }

.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { @apply bg-slate-200 dark:bg-slate-800 rounded-full; }

/* Smooth page transitions */
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
