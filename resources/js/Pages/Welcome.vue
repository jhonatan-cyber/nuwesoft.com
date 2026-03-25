<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import ThemeSwitcher from '@/Components/ThemeSwitcher.vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import n8nLogo from '@/Assets/n8n.svg';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import {
  Card,
  CardHeader,
  CardTitle,
  CardDescription,
  CardContent,
} from '@/Components/ui/card';
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/Components/ui/tooltip'
import { Rocket, Shield, Zap, Sparkles, TrendingUp, Cpu, Menu, X, ArrowRight, Code, Layers, Globe } from 'lucide-vue-next';

const navigation = [
    { name: 'Services', href: 'servicios', key: 'services' },
    { name: 'Portfolio', href: 'portafolio', key: 'portfolio' },
    { name: 'Contact', href: 'contacto', key: 'contact' },
];

const { t } = useI18n();

const isVisible = ref(false);
const isMenuOpen = ref(false);

onMounted(() => {
    isVisible.value = true;
});

onUnmounted(() => {
    document.body.style.overflow = '';
});

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
    if (isMenuOpen.value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
};
</script>

<template>
    <Head title="NUWESOFT | Digital Master Engineering" />
    
    <div class="min-h-screen bg-white dark:bg-black text-black dark:text-white font-sans selection:bg-brutalist-yellow selection:text-black overflow-x-hidden">
        <!-- Grid Background -->
        <div class="fixed inset-0 pointer-events-none opacity-[0.05] bg-[linear-gradient(to_right,#808080_1px,transparent_1px),linear-gradient(to_bottom,#808080_1px,transparent_1px)] bg-[size:40px_40px] z-0"></div>

        <!-- Navigation -->
        <nav class="fixed top-0 w-full z-40 bg-white dark:bg-black border-b-4 border-black dark:border-white">
            <div class="max-w-[1400px] mx-auto px-6">
                <div class="flex justify-between h-20 items-center">
                    <Link href="/" class="group flex items-center space-x-3">
                        <div class="w-12 h-12 bg-brutalist-yellow border-4 border-black dark:border-white flex items-center justify-center transform group-hover:-rotate-6 transition-transform">
                            <span class="text-2xl font-black text-black">N</span>
                        </div>
                        <span class="text-3xl font-display font-black tracking-tighter uppercase italic group-hover:bg-brutalist-pink group-hover:text-white px-2 transition-colors text-black dark:text-white">NUWESOFT</span>
                    </Link>
                    
                    <div class="hidden md:flex items-center space-x-8 text-black dark:text-white">
                        <Link v-for="item in navigation" :key="item.key" :href="route(item.href)"
                            class="text-sm font-black uppercase tracking-widest hover:underline decoration-brutalist-pink decoration-4 underline-offset-8 transition-all">
                            {{ t(item.key) }}
                        </Link>
                        
                        <ThemeSwitcher />
                        <LanguageSwitcher />

                        <Button as-child class="bg-brutalist-blue text-black font-black border-4 border-black dark:border-white shadow-brutalist dark:shadow-brutalist-white hover:shadow-brutalist-hover hover:translate-x-[4px] hover:translate-y-[4px] transition-all rounded-none">
                            <Link :href="route('login')">
                                {{ t('dashboard') }}
                            </Link>
                        </Button>
                    </div>

                    <div class="flex items-center space-x-4 md:hidden">
                        <ThemeSwitcher />
                        <LanguageSwitcher />
                        <button @click="toggleMenu" class="text-black dark:text-white p-2 border-4 border-black dark:border-white bg-brutalist-yellow dark:bg-zinc-800 shadow-[4px_4px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-x-[4px] active:translate-y-[4px] transition-all">
                            <Menu v-if="!isMenuOpen" class="w-8 h-8" />
                            <X v-else class="w-8 h-8" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Overlay -->
            <transition name="menu-fade">
                <div v-if="isMenuOpen" class="fixed inset-0 z-50 bg-brutalist-pink dark:bg-zinc-900 md:hidden flex flex-col p-8 border-t-8 border-black dark:border-white">
                    <div class="flex justify-between items-center mb-16">
                         <Link href="/" @click="toggleMenu" class="group flex items-center space-x-3">
                            <div class="w-12 h-12 bg-brutalist-yellow border-4 border-black dark:border-white flex items-center justify-center transform group-hover:-rotate-6 transition-transform">
                                <span class="text-2xl font-black text-black">N</span>
                            </div>
                            <span class="text-3xl font-display font-black tracking-tighter uppercase italic text-black dark:text-white">NUWESOFT</span>
                        </Link>
                        <button @click="toggleMenu" class="text-black dark:text-white p-2 border-4 border-black dark:border-white bg-white dark:bg-zinc-800 shadow-[4px_4px_0px_rgba(0,0,0,1)]">
                            <X class="w-8 h-8" />
                        </button>
                    </div>

                    <div class="flex flex-col space-y-8 flex-grow">
                        <Link v-for="item in navigation" :key="item.key" :href="route(item.href)" 
                            @click="toggleMenu"
                            class="text-5xl font-black uppercase italic tracking-tighter text-black dark:text-white hover:bg-brutalist-yellow px-4 py-2 transition-colors border-l-8 border-black dark:border-white">
                            {{ t(item.key) }}
                        </Link>
                        <Link :href="route('login')" @click="toggleMenu"
                            class="text-5xl font-black uppercase italic tracking-tighter text-black dark:text-white hover:bg-brutalist-blue px-4 py-2 transition-colors border-l-8 border-black dark:border-white">
                            {{ t('dashboard') }}
                        </Link>
                    </div>

                    <div class="mt-auto pt-8 border-t-4 border-black dark:border-white grid grid-cols-2 gap-4">
                        <ThemeSwitcher class="w-full h-16 border-4 border-black bg-white" />
                        <LanguageSwitcher class="w-full h-16 border-4 border-black bg-white" />
                    </div>
                </div>
            </transition>
        </nav>

        <!-- Hero Section -->
        <header class="relative pt-48 pb-32">
            <div class="max-w-[1400px] mx-auto px-6 relative z-10">
                <div class="grid lg:grid-cols-12 gap-16 items-start">
                    <div class="lg:col-span-8">
                        <div :class="['transition-all duration-700 transform', isVisible ? 'translate-x-0 opacity-100' : '-translate-x-12 opacity-0']">
                            <Badge class="bg-brutalist-pink text-white font-black border-2 border-black dark:border-white mb-6 uppercase tracking-[0.2em] px-4 py-1 animate-bounce">
                                {{ t('hero.badge') }}
                            </Badge>
                            
                            <h1 class="text-5xl sm:text-7xl md:text-9xl lg:text-[12rem] font-display font-black leading-[0.8] tracking-tighter uppercase italic mb-8 text-black dark:text-white relative">
                                {{ t('hero.title1') }} <br/>
                                <span class="text-brutalist-yellow drop-shadow-[4px_4px_0px_rgba(0,0,0,1)] md:drop-shadow-[8px_8px_0px_rgba(0,0,0,1)] dark:drop-shadow-[4px_4px_0px_rgba(255,255,255,1)] md:drop-shadow-[8px_8px_0px_rgba(255,255,255,1)]">{{ t('hero.title2') }}</span> <br/>
                                <span class="relative">
                                    {{ t('hero.title3') }}
                                </span>
                            </h1>

                            <p class="text-xl md:text-3xl font-black max-w-2xl mb-12 uppercase leading-tight text-black dark:text-zinc-400">
                                {{ t('hero.subtitle', { l: 'LARAVEL', v: 'VUE' }) }}
                            </p>

                            <div class="flex flex-wrap gap-6">
                                <Button as-child class="bg-black dark:bg-white text-white dark:text-black font-black border-4 border-black dark:border-white h-auto py-6 px-10 text-2xl shadow-brutalist dark:shadow-brutalist-white hover:shadow-brutalist-hover hover:translate-x-[4px] hover:translate-y-[4px] transition-all rounded-none group">
                                    <Link :href="route('contacto')">
                                        {{ t('hero.cta_start') }}
                                        <Rocket class="ml-4 w-8 h-8 group-hover:translate-x-2 group-hover:-translate-y-2 transition-transform" />
                                    </Link>
                                </Button>
                                <Button as-child variant="outline" class="bg-transparent text-black dark:text-white font-black border-4 border-black dark:border-white h-auto py-6 px-10 text-2xl hover:bg-brutalist-yellow hover:text-black transition-all rounded-none shadow-brutalist dark:shadow-brutalist-white">
                                    <Link :href="route('servicios')">
                                        {{ t('hero.cta_solutions') }}
                                    </Link>
                                </Button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="lg:col-span-4 relative hidden lg:block">
                        <div class="aspect-square bg-brutalist-blue border-8 border-black dark:border-white flex items-center justify-center relative shadow-brutalist-lg dark:shadow-brutalist-white-lg rotate-3 hover:rotate-0 transition-transform duration-500 overflow-hidden group">
                           <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                           <span class="text-[12rem] font-display font-black text-black dark:text-white leading-none select-none group-hover:scale-110 transition-transform duration-700">NW</span>
                           <!-- Floating Tech Icons -->
                           <div class="absolute top-4 left-4 w-12 h-12 bg-white border-4 border-black flex items-center justify-center -rotate-12 group-hover:rotate-0 transition-transform">
                               <Cpu class="w-6 h-6 text-black" />
                           </div>
                           <div class="absolute bottom-4 right-4 w-12 h-12 bg-brutalist-pink border-4 border-black flex items-center justify-center rotate-12 group-hover:rotate-0 transition-transform">
                               <Zap class="w-6 h-6 text-white" />
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Marquee / Stats -->
        <section class="py-8 bg-black dark:bg-white overflow-hidden border-y-4 border-black dark:border-white">
            <div class="flex whitespace-nowrap animate-[marquee_20s_linear_infinite]">
                <div v-for="n in 10" :key="n" class="flex items-center space-x-12 px-6">
                    <span class="text-2xl font-black text-white dark:text-black uppercase italic">{{ t('marquee.clean_code') }}</span>
                    <span class="text-3xl text-brutalist-yellow">★</span>
                    <span class="text-2xl font-black text-white dark:text-black uppercase italic">{{ t('marquee.scalable') }}</span>
                    <span class="text-3xl text-brutalist-pink">★</span>
                    <span class="text-2xl font-black text-white dark:text-black uppercase italic">{{ t('marquee.support') }}</span>
                    <span class="text-3xl text-brutalist-blue">★</span>
                </div>
            </div>
        </section>

        <!-- Manifesto Section -->
        <section class="py-20 md:py-32 relative overflow-hidden bg-white dark:bg-black">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-64 md:w-96 h-64 md:h-96 bg-brutalist-yellow opacity-10 rounded-full -translate-y-1/2 translate-x-1/2 blur-2xl md:blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-64 md:w-96 h-64 md:h-96 bg-brutalist-pink opacity-10 rounded-full translate-y-1/2 -translate-x-1/2 blur-2xl md:blur-3xl"></div>

            <div class="max-w-[1400px] mx-auto px-6 relative z-10">
                <div class="flex flex-col lg:flex-row items-start justify-between mb-16 md:mb-24 gap-12">
                    <div class="max-w-4xl">
                        <Badge class="bg-black dark:bg-white text-white dark:text-black font-black border-2 border-black dark:border-white mb-6 uppercase tracking-[0.3em] px-4 py-1">{{ t('manifesto.badge') }}</Badge>
                        <h2 class="text-5xl sm:text-7xl md:text-9xl font-display font-black uppercase italic leading-[0.8] tracking-tighter mb-8 text-black dark:text-white">
                            {{ t('manifesto.title1') }} <br/>
                            <span class="text-brutalist-blue stroke-text">{{ t('manifesto.title2') }}</span> <br/>
                            <span>{{ t('manifesto.title3') }}</span>
                        </h2>
                    </div>
                    <div class="lg:max-w-md w-full">
                        <div class="bg-brutalist-yellow border-4 md:border-8 border-black dark:border-white p-6 md:p-10 shadow-brutalist dark:shadow-brutalist-white -rotate-2 md:-rotate-3 hover:rotate-0 transition-transform duration-500">
                            <p class="font-black text-xl md:text-3xl italic uppercase leading-tight md:leading-none text-black">
                                {{ t('manifesto.quote') }}
                            </p>
                            <div class="mt-6 md:mt-8 flex justify-end">
                                <Sparkles class="w-10 h-10 md:w-12 md:h-12 text-black animate-pulse" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-0 border-4 border-black dark:border-white divide-y-4 md:divide-y-0 md:divide-x-4 divide-black dark:divide-white shadow-brutalist-lg dark:shadow-brutalist-white-lg bg-white dark:bg-zinc-900">
                    <div v-for="(point, key) in [
                        { key: 'p1', icon: Zap, color: 'text-brutalist-yellow' },
                        { key: 'p2', icon: Shield, color: 'text-brutalist-pink' },
                        { key: 'p3', icon: TrendingUp, color: 'text-brutalist-blue' }
                    ]" :key="key" class="p-8 md:p-12 group hover:bg-black dark:hover:bg-white transition-colors duration-300">
                        <component :is="point.icon" :class="['w-12 h-12 md:w-16 md:h-16 mb-6 md:mb-8 group-hover:scale-110 group-hover:rotate-12 transition-transform', point.color]" />
                        <h4 class="text-2xl md:text-3xl font-black uppercase mb-4 md:mb-6 text-black dark:text-white group-hover:text-white dark:group-hover:text-black leading-none">
                            {{ t(`manifesto.points.${point.key}.name`) }}
                        </h4>
                        <p class="font-bold text-lg md:text-xl uppercase tracking-tight text-black dark:text-zinc-400 group-hover:text-white dark:group-hover:text-black transition-colors">
                            {{ t(`manifesto.points.${point.key}.desc`) }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Dynamic Trust Section (Logos Marquee) -->
        <section class="py-12 md:py-20 bg-brutalist-pink border-y-8 border-black">
            <div class="max-w-[1400px] mx-auto px-6 mb-8 md:mb-12 flex justify-between items-end">
                <h3 class="text-2xl md:text-4xl font-black uppercase italic text-white leading-none">{{ t('trust.title') }}</h3>
                <div class="hidden md:block w-48 h-1 bg-white"></div>
            </div>
            <div class="overflow-hidden flex space-x-12">
                <div class="flex animate-marquee space-x-12 whitespace-nowrap py-4">
                    <div v-for="n in 10" :key="n" class="flex items-center space-x-12">
                        <span class="text-4xl md:text-6xl font-display font-black text-black opacity-20 uppercase leading-none">ENGINEERING</span>
                        <div class="w-4 h-4 bg-white rotate-45"></div>
                        <span class="text-4xl md:text-6xl font-display font-black text-white uppercase italic leading-none">INNOVATION</span>
                        <div class="w-3 h-3 md:w-4 md:h-4 bg-black rotate-45"></div>
                        <span class="text-4xl md:text-6xl font-display font-black text-black opacity-20 uppercase underline decoration-white leading-none">MASTER</span>
                        <div class="w-4 h-4 bg-white rotate-45"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Preview -->
        <section class="py-32 bg-brutalist-blue border-y-8 border-black">
            <div class="max-w-[1400px] mx-auto px-6">
                <div class="text-center mb-24">
                    <h2 class="text-7xl md:text-9xl font-display font-black uppercase italic tracking-tighter leading-[0.8] mb-8">
                        {{ t('services_preview.title1') }} <br/> <span class="text-white">{{ t('services_preview.title2') }}</span>
                    </h2>
                </div>

                <div class="grid md:grid-cols-3 gap-8 md:gap-12">
                    <div v-for="(service, i) in [
                        { title: t('services_preview.card1.title'), desc: t('services_preview.card1.desc'), color: 'bg-white dark:bg-zinc-900', icon: Code, accent: 'bg-brutalist-blue' },
                        { title: t('services_preview.card2.title'), desc: t('services_preview.card2.desc'), color: 'bg-brutalist-yellow', icon: Layers, accent: 'bg-brutalist-pink' },
                        { title: t('services_preview.card3.title'), desc: t('services_preview.card3.desc'), color: 'bg-brutalist-pink', icon: Globe, accent: 'bg-white' }
                    ]" :key="i" 
                    class="group relative"
                    :class="['transition-all duration-500 delay-[' + (i * 100) + 'ms]', isVisible ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0']">
                        <div :class="['absolute inset-0 border-4 border-black dark:border-white translate-x-2 translate-y-2 group-hover:translate-x-4 group-hover:translate-y-4 transition-transform duration-300', service.accent]"></div>
                        <div :class="['relative border-4 border-black dark:border-white p-10 h-full transition-transform duration-300 group-hover:-translate-x-1 group-hover:-translate-y-1', service.color]">
                            <div class="w-16 h-16 border-4 border-black dark:border-white bg-black dark:bg-white text-white dark:text-black flex items-center justify-center mb-8 group-hover:scale-110 group-hover:rotate-6 transition-transform">
                                <component :is="service.icon" class="w-8 h-8" />
                            </div>
                            <h3 class="text-3xl font-black uppercase mb-4 leading-none text-black dark:text-white">{{ service.title }}</h3>
                            <p class="font-bold text-lg mb-8 uppercase tracking-tight opacity-80 text-black dark:text-white">{{ service.desc }}</p>
                            <Link :href="route('servicios')" class="flex items-center space-x-2 font-black text-lg uppercase tracking-widest hover:text-brutalist-pink transition-colors text-black dark:text-white group/btn">
                                <span>{{ t('services_preview.details') }}</span>
                                <ArrowRight class="w-6 h-6 group-hover/btn:translate-x-2 transition-transform" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 md:py-48 px-6">
            <div class="max-w-5xl mx-auto bg-black border-4 md:border-8 border-brutalist-yellow p-8 md:p-16 text-center shadow-brutalist rotate-1">
                <h2 class="text-4xl sm:text-5xl md:text-8xl font-display font-black text-white uppercase italic leading-[0.8] mb-12">
                    {{ t('cta.title1') }} <br/> <span class="text-brutalist-yellow">{{ t('cta.title2') }}</span>
                </h2>
                <div class="flex flex-col sm:flex-row justify-center gap-8">
                    <Button as-child class="bg-brutalist-pink text-white font-black border-4 border-black h-auto py-6 md:py-8 px-8 md:px-12 text-2xl md:text-3xl shadow-brutalist hover:shadow-brutalist-hover hover:translate-x-[4px] hover:translate-y-[4px] transition-all rounded-none">
                        <Link :href="route('contacto')">
                            {{ t('cta.button') }}
                        </Link>
                    </Button>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-black text-white border-t-8 border-brutalist-pink py-24">
            <div class="max-w-[1400px] mx-auto px-6">
                <div class="grid md:grid-cols-4 gap-16 items-start">
                    <div class="col-span-2">
                        <div class="flex items-center space-x-3 mb-10">
                            <div class="w-12 h-12 bg-brutalist-yellow border-4 border-white flex items-center justify-center -rotate-6">
                                <span class="text-2xl font-black text-black">N</span>
                            </div>
                            <span class="text-4xl font-display font-black italic">NUWESOFT</span>
                        </div>
                        <p class="text-2xl font-black italic max-w-md leading-none uppercase mb-12">
                            {{ t('footer.tagline') }}
                        </p>
                        <div class="flex gap-6">
                            <div v-for="i in 3" :key="i" class="w-14 h-14 border-4 border-white flex items-center justify-center hover:bg-brutalist-blue hover:text-black transition-colors cursor-pointer">
                                <div class="w-6 h-6 border-4 border-current"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="text-brutalist-yellow font-black uppercase tracking-[0.2em] mb-10">{{ t('footer.links') }}</h5>
                        <ul class="space-y-6 text-xl font-black italic uppercase">
                            <li v-for="item in navigation" :key="item.name">
                                <Link :href="route(item.href)" class="hover:text-brutalist-pink transition-colors">{{ t(`${item.href}`) }}</Link>
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h5 class="text-brutalist-blue font-black uppercase tracking-[0.2em] mb-10">{{ t('footer.studio') }}</h5>
                        <p class="text-xl font-black italic uppercase leading-none">
                            {{ t('footer.remote') }} <br/>
                            <span class="text-brutalist-pink">{{ t('footer.global') }}</span> <br/>
                            {{ t('footer.built') }}
                        </p>
                    </div>
                </div>
                
                <div class="mt-24 pt-12 border-t-4 border-white/20 flex flex-col md:flex-row justify-between items-center gap-8">
                    <p class="text-lg font-black uppercase">{{ t('footer.rights') }}</p>
                    <div class="flex gap-12 font-black uppercase text-sm">
                        <span class="hover:underline cursor-pointer">{{ t('footer.privacy') }}</span>
                        <span class="hover:underline cursor-pointer">{{ t('footer.terms') }}</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
@keyframes marquee {
    from { transform: translateX(0); }
    to { transform: translateX(-50%); }
}

.animate-marquee {
    animation: marquee 20s linear infinite;
}

.menu-fade-enter-active,
.menu-fade-leave-active {
    transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

.menu-fade-enter-from,
.menu-fade-leave-to {
    opacity: 0;
    transform: translateY(-100%);
}

.stroke-text {
    -webkit-text-stroke: 2px currentColor;
    color: transparent;
}

.font-display {
    font-family: 'Space Grotesk', sans-serif;
}

body {
    @apply bg-white dark:bg-black transition-colors duration-500;
}

::selection {
    @apply bg-brutalist-yellow text-black;
}

::-webkit-scrollbar {
    width: 12px;
}

::-webkit-scrollbar-track {
    @apply bg-black;
}

::-webkit-scrollbar-thumb {
    @apply bg-brutalist-pink border-4 border-black;
}

::-webkit-scrollbar-thumb:hover {
    @apply bg-brutalist-yellow;
}
</style>
