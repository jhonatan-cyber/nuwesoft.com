<script setup>
import { ref, onUnmounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import ThemeSwitcher from '@/Components/ThemeSwitcher.vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import { Menu, X, LogOut, User, Home, LayoutDashboard, Settings } from 'lucide-vue-next';

const { t } = useI18n();
const showingNavigationDropdown = ref(false);

const toggleMenu = () => {
    showingNavigationDropdown.value = !showingNavigationDropdown.value;
    if (showingNavigationDropdown.value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
};

onUnmounted(() => {
    document.body.style.overflow = '';
});

router.on('start', () => {
    showingNavigationDropdown.value = false;
    document.body.style.overflow = '';
});

const navigation = [
    { name: 'Dashboard', href: 'dashboard', icon: LayoutDashboard },
    { name: 'Home', href: '/', icon: Home, isExternal: true },
];
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-black text-black dark:text-white font-sans selection:bg-brutalist-yellow selection:text-black">
        <!-- Grid Background -->
        <div class="fixed inset-0 pointer-events-none opacity-[0.03] bg-[linear-gradient(to_right,#808080_1px,transparent_1px),linear-gradient(to_bottom,#808080_1px,transparent_1px)] bg-[size:40px_40px] z-0"></div>

        <nav class="sticky top-0 w-full z-40 bg-white dark:bg-zinc-900 border-b-4 border-black dark:border-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-20 justify-between items-center">
                    <div class="flex items-center gap-8">
                        <!-- Logo -->
                        <Link href="/" class="group flex items-center space-x-3">
                            <div class="w-10 h-10 bg-brutalist-yellow border-4 border-black dark:border-white flex items-center justify-center transform group-hover:-rotate-6 transition-transform">
                                <span class="text-xl font-black text-black">N</span>
                            </div>
                            <span class="text-2xl font-display font-black tracking-tighter uppercase italic text-black dark:text-white hidden sm:block">NUWESOFT</span>
                        </Link>

                        <!-- Desktop Nav -->
                        <div class="hidden sm:flex space-x-4">
                            <Link 
                                v-for="item in navigation" 
                                :key="item.name"
                                :href="item.isExternal ? item.href : route(item.href)"
                                class="px-4 py-2 text-sm font-black uppercase tracking-widest border-2 border-transparent hover:border-black dark:hover:border-white hover:bg-brutalist-yellow hover:text-black transition-all"
                                :class="{ 'bg-black text-white dark:bg-white dark:text-black': !item.isExternal && route().current(item.href) }"
                            >
                                {{ item.name }}
                            </Link>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="hidden md:flex items-center space-x-4 mr-4 border-r-2 border-black dark:border-white pr-4">
                            <ThemeSwitcher />
                            <LanguageSwitcher />
                        </div>

                        <!-- User Menu -->
                        <div class="hidden sm:flex sm:items-center">
                            <div class="flex items-center gap-4 bg-gray-100 dark:bg-zinc-800 border-2 border-black dark:border-white px-4 py-2 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_0px_rgba(255,255,255,1)]">
                                <div class="w-8 h-8 bg-brutalist-blue border-2 border-black flex items-center justify-center">
                                    <User class="w-5 h-5 text-black" />
                                </div>
                                <span class="text-sm font-black uppercase tracking-tighter">{{ $page.props.auth.user.name }}</span>
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="ml-4 p-1 hover:bg-brutalist-pink transition-colors border-2 border-transparent hover:border-black"
                                >
                                    <LogOut class="w-5 h-5" />
                                </Link>
                            </div>
                        </div>

                        <!-- Mobile Hamburger -->
                        <div class="flex items-center sm:hidden gap-2">
                             <ThemeSwitcher />
                             <button
                                @click="toggleMenu"
                                class="p-2 border-4 border-black dark:border-white bg-brutalist-yellow text-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all"
                            >
                                <Menu v-if="!showingNavigationDropdown" class="h-6 w-6" />
                                <X v-else class="h-6 w-6" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <transition name="menu-fade">
                <div v-if="showingNavigationDropdown" class="fixed inset-0 z-50 bg-brutalist-yellow dark:bg-zinc-900 flex flex-col p-8 border-t-8 border-black dark:border-white sm:hidden">
                    <div class="flex justify-between items-center mb-12">
                        <span class="text-4xl font-display font-black uppercase italic tracking-tighter">MENÚ</span>
                        <button @click="toggleMenu" class="p-2 border-4 border-black dark:border-white bg-white dark:bg-black">
                            <X class="h-8 w-8" />
                        </button>
                    </div>

                    <div class="flex flex-col space-y-6">
                        <Link 
                            v-for="item in navigation" 
                            :key="item.name"
                            :href="item.isExternal ? item.href : route(item.href)"
                            @click="toggleMenu"
                            class="text-4xl font-display font-black uppercase tracking-tighter border-b-8 border-black pb-2 hover:bg-black hover:text-white px-2 transition-colors"
                        >
                            {{ item.name }}
                        </Link>
                        
                        <div class="pt-8 flex flex-col gap-4">
                            <div class="p-4 bg-white dark:bg-zinc-800 border-4 border-black dark:border-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                                <p class="text-xs font-mono font-black text-gray-500 uppercase mb-2">Usuario Autenticado</p>
                                <p class="text-xl font-black uppercase tracking-tighter">{{ $page.props.auth.user.name }}</p>
                                <p class="text-sm font-mono font-bold text-gray-600 truncate">{{ $page.props.auth.user.email }}</p>
                            </div>

                            <div class="flex gap-4">
                                <LanguageSwitcher />
                            </div>

                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="w-full bg-brutalist-pink text-white border-4 border-black p-4 text-2xl font-display font-black uppercase shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]"
                            >
                                Cerrar Sesión
                            </Link>
                        </div>
                    </div>
                </div>
            </transition>
        </nav>

        <!-- Page Heading -->
        <header v-if="$slots.header" class="relative z-10 bg-white dark:bg-zinc-900 border-b-4 border-black dark:border-white shadow-[0_8px_0_0_rgba(0,0,0,0.05)]">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Page Content -->
        <main class="relative z-10">
            <slot />
        </main>

        <!-- Footer simple Master -->
        <footer class="mt-auto py-12 border-t-4 border-black dark:border-white bg-white dark:bg-zinc-950">
             <div class="mx-auto max-w-7xl px-4 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="font-mono font-bold text-sm uppercase tracking-tighter">
                    © 2026 NUWESOFT // MASTER_CONTROL_PANEL_V2.0
                </p>
                <div class="flex gap-4">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-xs font-mono font-black uppercase">Sistemas Nominales</span>
                </div>
             </div>
        </footer>
    </div>
</template>

<style scoped>
.font-display {
    font-family: 'Space Grotesk', system-ui, sans-serif;
}

.menu-fade-enter-active,
.menu-fade-leave-active {
    transition: transform 0.4s cubic-bezier(0.87, 0, 0.13, 1);
}

.menu-fade-enter-from,
.menu-fade-leave-to {
    transform: translateY(-100%);
}
</style>
