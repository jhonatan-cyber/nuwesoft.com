<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
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
    Settings,
    ChevronRight,
    AlertTriangle,
    Bell,
    ChevronDown,
    MessageSquare,
} from 'lucide-vue-next';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import { Button } from '@/Components/ui/button';

const { t } = useI18n();
const page = usePage();
const unreadMessages = computed(() => page.props.unread_messages || 0);
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
    { name: 'MENSAJES', href: 'messages.index', icon: MessageSquare, badge: unreadMessages },
    { name: t('settings.title'), href: 'dashboard.settings.index', icon: Settings },
    { name: '404 LOGS', href: 'logs.index', icon: AlertTriangle },
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
    <div class="min-h-screen bg-white dark:bg-black text-neutral-900 dark:text-white font-sans selection:bg-black selection:text-white dark:selection:bg-white dark:selection:text-black overflow-x-hidden transition-colors duration-300">
        <!-- Subtle Background Elements -->
        <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-black/5 blur-[120px] dark:bg-white/5 transition-opacity duration-1000"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-black/5 blur-[120px] dark:bg-white/5 transition-opacity duration-1000"></div>
        </div>

        <!-- Sidebar (Desktop) -->
        <aside 
            :class="[
                'fixed left-6 top-6 bottom-6 z-50 transition-all duration-300 ease-in-out hidden lg:flex flex-col border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-black backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden',
                isSidebarOpen ? 'w-64' : 'w-20'
            ]"
        >
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center px-6 border-b border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900">
                <Link href="/" class="flex items-center gap-3 overflow-hidden">
                    <div v-if="isSidebarOpen" class="transition-all duration-300">
                        <ApplicationLogo class="w-32" />
                    </div>
                </Link>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto p-4 space-y-1 custom-scrollbar">
                <Link 
                    v-for="item in navigation" 
                    :key="item.name"
                    :href="route(item.href)"
                    :class="[
                        'group flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-200',
                        route().current(item.href) 
                            ? 'bg-black dark:bg-white text-white dark:text-black font-medium shadow-sm' 
                            : 'text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800 hover:text-neutral-900 dark:hover:text-white'
                    ]"
                >
                    <component :is="item.icon" :class="['w-5 h-5 shrink-0 transition-colors', route().current(item.href) ? 'text-white dark:text-black' : 'text-neutral-400 group-hover:text-neutral-600 dark:group-hover:text-neutral-300']" />
                    <span v-if="isSidebarOpen" class="flex-1 text-sm truncate">{{ item.name }}</span>
                    <span v-if="isSidebarOpen && item.badge?.value > 0"
                        class="ml-auto text-[9px] font-black bg-brutalist-pink text-white rounded-full px-1.5 py-0.5 leading-none">
                        {{ item.badge.value }}
                    </span>
                </Link>

                <div class="my-4 border-t border-neutral-200 dark:border-neutral-800 mx-2 opacity-50"></div>

                <div v-if="isSidebarOpen" class="px-3 py-2 text-[10px] font-semibold text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Recursos</div>
                <Link 
                    v-for="item in secondaryNavigation" 
                    :key="item.name"
                    :href="item.href"
                    class="group flex items-center gap-3 px-3 py-2 rounded-xl text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800 hover:text-neutral-900 dark:hover:text-white transition-all duration-200"
                >
                    <component :is="item.icon" class="w-5 h-5 shrink-0 text-neutral-400 group-hover:text-neutral-600 dark:group-hover:text-neutral-300" />
                    <span v-if="isSidebarOpen" class="text-sm truncate">{{ item.name }}</span>
                </Link>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div 
            :class="[
                'transition-all duration-300 h-screen overflow-y-auto flex flex-col px-4 md:px-6 py-6',
                isSidebarOpen ? 'lg:ml-72' : 'lg:ml-28'
            ]"
        >
            <!-- Top Navbar (Floating) -->
            <header 
                :class="[
                    'sticky top-0 z-40 w-full transition-all duration-300 rounded-3xl border mb-6 shadow-sm',
                    isScrolled 
                        ? 'bg-white dark:bg-black border-neutral-200 dark:border-neutral-800 shadow-md' 
                        : 'bg-white dark:bg-black border-neutral-100 dark:border-neutral-800'
                ]"
            >
                <div class="h-20 px-8 flex items-center justify-between">
                    <!-- Left: Mobile Menu & Breadcrumbs -->
                    <div class="flex items-center gap-4">
                        <button 
                            @click="toggleMobileMenu" 
                            class="lg:hidden p-2 rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-black text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white transition-all shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2"
                        >
                            <Menu class="w-5 h-5" />
                        </button>
                        
                        <div class="hidden md:flex items-center gap-2 text-xs font-medium">
                            <span class="text-neutral-400">ADMIN</span>
                            <ChevronRight class="w-3 h-3 text-neutral-300" />
                            <span class="text-neutral-900 dark:text-white uppercase tracking-wider font-bold">{{ route().current()?.split('.')[0] }}</span>
                        </div>
                    </div>

                    <!-- Right: Actions & Profile -->
                    <div class="flex items-center gap-2 md:gap-4">
                        <!-- Theme & Lang -->
                        <div class="hidden sm:flex items-center gap-2 mr-2">
                            <DashboardThemeSwitcher />
                            <DashboardLanguageSwitcher />
                        </div>

                        <div class="h-6 w-px bg-neutral-200 dark:bg-neutral-800 mx-1 hidden sm:block"></div>

                        <!-- User Profile Dropdown -->
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="ghost" class="relative h-10 w-10 md:w-auto md:px-3 md:gap-3 rounded-xl hover:bg-neutral-50 dark:hover:bg-neutral-900 border border-transparent hover:border-neutral-200 dark:hover:border-neutral-800 transition-all shadow-none">
                                    <div class="h-7 w-7 rounded-full bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center border border-neutral-200 dark:border-neutral-700">
                                        <span class="text-neutral-900 dark:text-white text-[10px] font-bold">{{ $page.props.auth.user.name.charAt(0) }}</span>
                                    </div>
                                    <span class="hidden md:block text-xs font-bold text-neutral-900 dark:text-white uppercase tracking-tight">{{ $page.props.auth.user.name }}</span>
                                    <ChevronDown class="hidden md:block w-3 h-3 text-neutral-400" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" class="w-56 mt-2 p-2 rounded-2xl border-neutral-200 dark:border-neutral-800 shadow-xl bg-white dark:bg-black">
                                <div class="px-2 py-2 mb-1">
                                    <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider">Identificado como</p>
                                    <p class="text-sm font-bold truncate">{{ $page.props.auth.user.name }}</p>
                                    <p class="text-[10px] text-neutral-500 truncate leading-none mt-0.5">{{ $page.props.auth.user.email }}</p>
                                </div>
                                <div class="h-px bg-neutral-100 dark:bg-neutral-800 my-1"></div>
                                <DropdownMenuItem @click="router.get(route('profile.edit'))" class="cursor-pointer rounded-xl focus:bg-neutral-100 dark:focus:bg-neutral-800 focus:text-black dark:focus:text-white py-2.5 transition-colors">
                                    <User class="mr-2 h-4 w-4" />
                                    <span class="font-medium">{{ t('profile') }}</span>
                                </DropdownMenuItem>
                                <div class="h-px bg-neutral-100 dark:bg-neutral-800 my-1"></div>
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
            <main id="main-content" class="flex-1 relative z-10">
                <!-- Header slot -->
                <div v-if="$slots.header" class="mb-8 px-2">
                    <slot name="header" />
                </div>

                <!-- Main Slot with Page Transition -->
                <Transition name="page" mode="out-in">
                    <div :key="$page.url">
                        <slot />
                    </div>
                </Transition>
            </main>

            <!-- Refined Footer -->
            <footer class="mt-12 py-8 px-10 rounded-3xl border border-neutral-200 dark:border-neutral-800 text-center md:text-left bg-white dark:bg-black">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-[9px] font-bold text-neutral-500 dark:text-neutral-300 uppercase tracking-[0.2em]">
                        &copy; 2026 NUWESOFT CORE &bull; {{ t('footer.no_compromise') }}
                    </p>
                    <div class="flex items-center gap-6 opacity-30">
                        <div class="h-1 w-8 bg-neutral-300 dark:bg-neutral-700 rounded-full"></div>
                        <div class="h-1 w-12 bg-black dark:bg-white rounded-full"></div>
                        <div class="h-1 w-8 bg-neutral-300 dark:bg-neutral-700 rounded-full"></div>
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
                <div @click="toggleMobileMenu" class="absolute inset-0 bg-black/20 backdrop-blur-md"></div>
                
                <!-- Drawer -->
                <div class="absolute left-0 top-0 bottom-0 w-[85%] max-w-xs bg-white dark:bg-black border-r border-neutral-200 dark:border-neutral-800 p-6 flex flex-col shadow-2xl">
                    <div class="flex items-center justify-between mb-10">
                        <Link href="/" class="flex items-center gap-3">
                            <div class="rounded-2xl border border-neutral-200 bg-white px-2 py-2 shadow-lg dark:border-neutral-800 dark:bg-black">
                                <ApplicationLogo class="w-28" />
                            </div>
                        </Link>
                        <button @click="toggleMobileMenu" class="p-2 rounded-xl bg-neutral-100 dark:bg-neutral-800 text-neutral-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2">
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
                                    ? 'bg-black text-white dark:bg-white dark:text-black shadow-xl' 
                                    : 'text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-900'
                            ]"
                        >
                            <component :is="item.icon" class="w-6 h-6 shrink-0" />
                            <span class="font-bold uppercase tracking-tight truncate">{{ item.name }}</span>
                        </Link>

                        <div class="py-6 px-2"><div class="h-px bg-neutral-100 dark:bg-neutral-800"></div></div>

                        <Link 
                            v-for="item in secondaryNavigation" 
                            :key="item.name"
                            :href="item.href"
                            class="flex items-center gap-4 p-4 rounded-2xl text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-900 transition-all"
                        >
                            <component :is="item.icon" class="w-6 h-6 shrink-0" />
                            <span class="font-bold uppercase tracking-tight truncate">{{ item.name }}</span>
                        </Link>
                    </nav>

                    <div class="mt-auto pt-6 space-y-6">
                        <div class="flex flex-col gap-4">
                            <DashboardThemeSwitcher />
                            <DashboardLanguageSwitcher />
                        </div>
                        <button 
                            @click="router.post(route('logout'))"
                            class="w-full flex items-center justify-center gap-3 p-4 rounded-2xl bg-rose-500 text-white hover:bg-rose-600 transition-all font-bold text-xs uppercase tracking-[0.2em] shadow-lg shadow-rose-500/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500 focus-visible:ring-offset-2"
                        >
                            <LogOut class="w-5 h-5" />
                            {{ t('logout') }}
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<style>
.font-display { font-family: 'Space Grotesk', sans-serif; }

.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { @apply bg-neutral-200 dark:bg-neutral-800 rounded-full; }


</style>
