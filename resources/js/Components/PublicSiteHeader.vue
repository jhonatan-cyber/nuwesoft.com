<script setup>
import { Link } from '@inertiajs/vue3';
import { computed, onUnmounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { Menu, X } from 'lucide-vue-next';
import ThemeSwitcher from '@/Components/ThemeSwitcher.vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const { t, locale } = useI18n();

const navigation = computed(() => {
    locale.value;

    return [
    { href: 'servicios', name: t('services') },
    { href: 'portafolio', name: t('portfolio') },
    { href: 'blog.index', name: 'BLOG' },
    { href: 'contacto', name: t('contact') },
    ];
});

const isMenuOpen = ref(false);

let scrollPosition = 0;

function lockBodyScroll() {
    scrollPosition = window.scrollY;
    document.body.style.overflow = 'hidden';
    document.body.style.position = 'fixed';
    document.body.style.top = `-${scrollPosition}px`;
    document.body.style.width = '100%';
}

function unlockBodyScroll() {
    document.body.style.overflow = '';
    document.body.style.position = '';
    document.body.style.top = '';
    document.body.style.width = '';
    window.scrollTo(0, scrollPosition);
}

const closeMenu = () => {
    isMenuOpen.value = false;
    unlockBodyScroll();
};

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
    if (isMenuOpen.value) {
        lockBodyScroll();
    } else {
        unlockBodyScroll();
    }
};

onUnmounted(() => {
    if (isMenuOpen.value) {
        unlockBodyScroll();
    }
});
</script>

<template>
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:bg-black focus:text-white focus:px-6 focus:py-3 focus:text-sm focus:font-black focus:uppercase focus:tracking-widest focus:border-4 focus:border-white focus:outline-none">
        Saltar al contenido principal
    </a>
    <nav class="fixed top-0 z-40 w-full border-b-4 border-black bg-white/95 backdrop-blur-md dark:border-white dark:bg-black/95 animate-in fade-in slide-in-from-top-3 duration-500">
        <div class="mx-auto max-w-[1400px] px-6">
            <div class="flex h-20 items-center justify-between">
                <Link href="/" class="group flex items-center space-x-3">
                    <div class="border-4 border-black bg-white px-2 pb-1 pt-2 shadow-brutalist transition-transform group-hover:-rotate-3 dark:border-white dark:bg-zinc-950 dark:shadow-brutalist-white">
                        <ApplicationLogo class="w-20 md:w-24" />
                    </div>
                </Link>

                <div class="hidden items-center space-x-8 text-black dark:text-white md:flex">
                    <Link
                        v-for="item in navigation"
                        :key="item.href"
                        :href="route(item.href)"
                        class="text-sm font-black uppercase tracking-widest decoration-brutalist-pink decoration-4 underline-offset-8 transition-all hover:underline"
                        :class="route().current(item.href) ? 'text-brutalist-pink' : ''"
                    >
                        {{ item.name }}
                    </Link>

                    <ThemeSwitcher />
                    <LanguageSwitcher />
                </div>

                <div class="flex items-center md:hidden">
                    <button
                        type="button"
                        @click="toggleMenu"
                        class="border-4 border-black bg-brutalist-yellow p-2 text-black shadow-[4px_4px_0px_rgba(0,0,0,1)] transition-all active:translate-x-[4px] active:translate-y-[4px] active:shadow-none dark:border-white dark:bg-zinc-800 dark:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2"
                    >
                        <Menu v-if="!isMenuOpen" class="h-8 w-8" />
                        <X v-else class="h-8 w-8" />
                    </button>
                </div>
            </div>
        </div>

        <teleport to="body">
            <transition name="menu-fade">
                <div v-if="isMenuOpen" class="mobile-menu-container fixed inset-0 z-50 flex flex-col border-t-8 border-black p-8 dark:border-white md:hidden">
                    <div class="mb-16 flex items-center justify-between">
                        <Link href="/" class="group flex items-center space-x-3" @click="closeMenu">
                            <div class="border-4 border-black bg-white px-2 pb-1 pt-2 shadow-brutalist dark:border-white dark:bg-zinc-950 dark:shadow-brutalist-white">
                                <ApplicationLogo class="w-20" />
                            </div>
                        </Link>

                        <button
                            type="button"
                            @click="closeMenu"
                            class="border-4 border-black bg-white p-2 text-black shadow-[4px_4px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-zinc-800 dark:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2"
                        >
                            <X class="h-8 w-8" />
                        </button>
                    </div>

                    <div class="flex flex-grow flex-col space-y-8">
                        <Link
                            v-for="item in navigation"
                            :key="item.href"
                            :href="route(item.href)"
                            @click="closeMenu"
                            class="border-l-8 border-black px-4 py-2 text-5xl font-black uppercase italic tracking-tighter text-black transition-colors hover:bg-brutalist-yellow dark:border-white dark:text-white"
                            :class="route().current(item.href) ? 'bg-brutalist-yellow text-black dark:text-black' : ''"
                        >
                            {{ item.name }}
                        </Link>

                    </div>

                    <div class="mt-auto grid grid-cols-2 gap-4 border-t-4 border-black pt-8 dark:border-white">
                        <ThemeSwitcher class="h-16 w-full border-4 border-black bg-white dark:border-white dark:bg-zinc-950" />
                        <LanguageSwitcher class="h-16 w-full border-4 border-black bg-white dark:border-white dark:bg-zinc-950" />
                    </div>
                </div>
            </transition>
        </teleport>
    </nav>
</template>

<style>
.mobile-menu-container {
    background-color: #FF2E63 !important;
}

.dark .mobile-menu-container {
    background-color: #121214 !important;
}

.menu-fade-enter-active,
.menu-fade-leave-active {
    transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

.menu-fade-enter-from,
.menu-fade-leave-to {
    opacity: 0;
    transform: translateY(-1rem);
}
</style>
