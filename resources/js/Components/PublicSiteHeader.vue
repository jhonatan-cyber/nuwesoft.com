<script setup>
import { Link } from '@inertiajs/vue3';
import { onUnmounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { Menu, X } from 'lucide-vue-next';
import ThemeSwitcher from '@/Components/ThemeSwitcher.vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Button } from '@/Components/ui/button';

const { t } = useI18n();

const navigation = [
    { href: 'servicios', key: 'services' },
    { href: 'portafolio', key: 'portfolio' },
    { href: 'contacto', key: 'contact' },
];

const isMenuOpen = ref(false);

const closeMenu = () => {
    isMenuOpen.value = false;
    document.body.style.overflow = '';
};

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
    document.body.style.overflow = isMenuOpen.value ? 'hidden' : '';
};

onUnmounted(() => {
    document.body.style.overflow = '';
});
</script>

<template>
    <nav class="fixed top-0 z-40 w-full border-b-4 border-black bg-white/95 backdrop-blur-md dark:border-white dark:bg-black/95">
        <div class="mx-auto max-w-[1400px] px-6">
            <div class="flex h-20 items-center justify-between">
                <Link href="/" class="group flex items-center space-x-3">
                    <div class="border-4 border-black bg-white px-3 pb-2 pt-4 shadow-brutalist transition-transform group-hover:-rotate-3 dark:border-white dark:bg-zinc-950 dark:shadow-brutalist-white">
                        <ApplicationLogo class="w-32 md:w-36" />
                    </div>
                </Link>

                <div class="hidden items-center space-x-8 text-black dark:text-white md:flex">
                    <Link
                        v-for="item in navigation"
                        :key="item.key"
                        :href="route(item.href)"
                        class="text-sm font-black uppercase tracking-widest decoration-brutalist-pink decoration-4 underline-offset-8 transition-all hover:underline"
                        :class="route().current(item.href) ? 'text-brutalist-pink' : ''"
                    >
                        {{ t(item.key) }}
                    </Link>

                    <ThemeSwitcher />
                    <LanguageSwitcher />

                    <Button as-child class="rounded-none border-4 border-black bg-brutalist-blue font-black text-black shadow-brutalist transition-all hover:translate-x-[4px] hover:translate-y-[4px] hover:shadow-brutalist-hover dark:border-white dark:shadow-brutalist-white">
                        <Link :href="route('login')">
                            {{ t('dashboard') }}
                        </Link>
                    </Button>
                </div>

                <div class="flex items-center space-x-4 md:hidden">
                    <ThemeSwitcher />
                    <LanguageSwitcher />
                    <button
                        type="button"
                        @click="toggleMenu"
                        class="border-4 border-black bg-brutalist-yellow p-2 text-black shadow-[4px_4px_0px_rgba(0,0,0,1)] transition-all active:translate-x-[4px] active:translate-y-[4px] active:shadow-none dark:border-white dark:bg-zinc-800 dark:text-white"
                    >
                        <Menu v-if="!isMenuOpen" class="h-8 w-8" />
                        <X v-else class="h-8 w-8" />
                    </button>
                </div>
            </div>
        </div>

        <transition name="menu-fade">
            <div v-if="isMenuOpen" class="fixed inset-0 z-50 flex flex-col border-t-8 border-black bg-brutalist-pink p-8 dark:border-white dark:bg-zinc-900 md:hidden">
                <div class="mb-16 flex items-center justify-between">
                    <Link href="/" class="group flex items-center space-x-3" @click="closeMenu">
                        <div class="border-4 border-black bg-white px-3 pb-2 pt-4 shadow-brutalist dark:border-white dark:bg-zinc-950 dark:shadow-brutalist-white">
                            <ApplicationLogo class="w-32" />
                        </div>
                    </Link>

                    <button
                        type="button"
                        @click="closeMenu"
                        class="border-4 border-black bg-white p-2 text-black shadow-[4px_4px_0px_rgba(0,0,0,1)] dark:border-white dark:bg-zinc-800 dark:text-white"
                    >
                        <X class="h-8 w-8" />
                    </button>
                </div>

                <div class="flex flex-grow flex-col space-y-8">
                    <Link
                        v-for="item in navigation"
                        :key="item.key"
                        :href="route(item.href)"
                        @click="closeMenu"
                        class="border-l-8 border-black px-4 py-2 text-5xl font-black uppercase italic tracking-tighter text-black transition-colors hover:bg-brutalist-yellow dark:border-white dark:text-white"
                        :class="route().current(item.href) ? 'bg-brutalist-yellow text-black dark:text-black' : ''"
                    >
                        {{ t(item.key) }}
                    </Link>
                    <Link
                        :href="route('login')"
                        @click="closeMenu"
                        class="border-l-8 border-black px-4 py-2 text-5xl font-black uppercase italic tracking-tighter text-black transition-colors hover:bg-brutalist-blue dark:border-white dark:text-white"
                    >
                        {{ t('dashboard') }}
                    </Link>
                </div>

                <div class="mt-auto grid grid-cols-2 gap-4 border-t-4 border-black pt-8 dark:border-white">
                    <ThemeSwitcher class="h-16 w-full border-4 border-black bg-white dark:border-white dark:bg-zinc-950" />
                    <LanguageSwitcher class="h-16 w-full border-4 border-black bg-white dark:border-white dark:bg-zinc-950" />
                </div>
            </div>
        </transition>
    </nav>
</template>

<style scoped>
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
