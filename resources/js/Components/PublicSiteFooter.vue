<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { computed, h } from 'vue';
import { Facebook, Github, Linkedin, Youtube, Globe, ExternalLink } from 'lucide-vue-next';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const { t } = useI18n();
const page = usePage();
const settings = computed(() => page.props.settings || {});

const navigation = [
    { href: 'servicios', key: 'services', pageKey: 'services' },
    { href: 'portafolio', key: 'portfolio', pageKey: 'portfolio' },
    { href: 'blog.index', key: 'Blog', pageKey: 'blog' },
    { href: 'contacto', key: 'contact', pageKey: 'contact' },
];

const legalLinks = [
    { href: 'privacidad', label: 'footer.privacy' },
    { href: 'terminos', label: 'footer.terms' },
];

const XIcon = {
    render() {
        return h('svg', { viewBox: '0 0 24 24', class: 'h-6 w-6 fill-current', 'aria-hidden': 'true' }, [
            h('path', { d: 'M18.9 2H22l-6.77 7.74L23.2 22h-6.24l-4.89-7.4L5.6 22H2.5l7.24-8.27L1.8 2h6.4l4.42 6.76L18.9 2Zm-1.09 18h1.72L7.26 3.9H5.41L17.81 20Z' })
        ]);
    }
};

const TikTokIcon = {
    render() {
        return h('svg', { viewBox: '0 0 24 24', class: 'h-6 w-6 fill-current', 'aria-hidden': 'true' }, [
            h('path', { d: 'M19.59 6.69a4.83 4.83 0 0 1-3.77-4.65h-3.2v12.43a2.88 2.88 0 1 1-2-2.75V8.47a6.08 6.08 0 1 0 5.2 6v-6.3a8.03 8.03 0 0 0 4.77 1.58V6.69Z' })
        ]);
    }
};

const socialLinks = computed(() => [
    { name: 'Facebook', href: settings.value.social_facebook, icon: Facebook, tone: 'hover:bg-brutalist-blue hover:text-white hover:border-brutalist-blue' },
    { name: 'X', href: settings.value.social_twitter, icon: XIcon, tone: 'hover:bg-white hover:text-black hover:border-white' },
    { name: 'TikTok', href: settings.value.social_tiktok, icon: TikTokIcon, tone: 'hover:bg-brutalist-pink hover:text-white hover:border-brutalist-pink' },
    { name: 'LinkedIn', href: settings.value.social_linkedin, icon: Linkedin, tone: 'hover:bg-brutalist-blue hover:text-white hover:border-brutalist-blue' },
    { name: 'GitHub', href: settings.value.social_github, icon: Github, tone: 'hover:bg-brutalist-yellow hover:text-black hover:border-brutalist-yellow' },
    { name: 'YouTube', href: settings.value.social_youtube, icon: Youtube, tone: 'hover:bg-brutalist-pink hover:text-white hover:border-brutalist-pink' },
].filter(s => s.href));
</script>

<template>
    <footer class="border-t-8 border-brutalist-pink bg-black py-24 text-white">
        <div class="mx-auto max-w-[1400px] px-6">
            <div class="grid grid-cols-1 items-start gap-16 md:grid-cols-4">
                <!-- Brand + Social -->
                <div class="col-span-1 md:col-span-2">
                    <div class="mb-10 inline-flex items-center space-x-3">
                        <div class="border-4 border-white/80 bg-transparent px-2 py-1 shadow-brutalist-white transition-transform hover:-rotate-1">
                            <ApplicationLogo variant="light" class="w-44" />
                        </div>
                    </div>

                    <p class="mb-12 max-w-md text-2xl font-black italic uppercase leading-[0.9]">
                        {{ t('footer.tagline') }}
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <a
                            v-for="social in socialLinks"
                            :key="social.name"
                            :href="social.href"
                            :aria-label="social.name"
                            :class="['flex h-14 w-14 items-center justify-center border-4 border-white/70 text-white/70 transition-all duration-300 hover:-translate-y-1 hover:shadow-brutalist-white', social.tone]"
                        >
                            <component :is="social.icon" class="h-6 w-6" />
                        </a>
                    </div>
                </div>

                <!-- Navigation -->
                <div>
                    <h5 class="relative mb-10 inline-block font-black uppercase tracking-[0.2em] text-brutalist-yellow">
                        <span class="relative z-10">{{ t('footer.links') }}</span>
                        <span class="absolute -bottom-1 left-0 h-1 w-full bg-brutalist-yellow/30"></span>
                    </h5>
                    <ul class="space-y-5">
                        <li v-for="item in navigation" :key="item.href">
                            <Link
                                :href="route(item.href)"
                                class="group relative inline-flex items-center gap-3 text-xl font-black italic uppercase transition-all"
                            >
                                <span class="relative z-10 transition-colors group-hover:text-brutalist-pink">{{ t(item.pageKey) }}</span>
                                <ExternalLink class="h-4 w-4 -translate-x-2 opacity-0 text-brutalist-pink transition-all group-hover:translate-x-0 group-hover:opacity-100" />
                                <span class="absolute -bottom-1 left-0 h-0.5 w-0 bg-brutalist-pink transition-all group-hover:w-full"></span>
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Studio -->
                <div>
                    <h5 class="relative mb-10 inline-block font-black uppercase tracking-[0.2em] text-brutalist-blue">
                        <span class="relative z-10">{{ t('footer.studio') }}</span>
                        <span class="absolute -bottom-1 left-0 h-1 w-full bg-brutalist-blue/30"></span>
                    </h5>
                    <div class="space-y-4 border-l-4 border-brutalist-blue/40 pl-6">
                        <p class="text-xl font-black italic uppercase leading-tight text-white/90">
                            {{ t('footer.remote') }}
                        </p>
                        <p class="text-xl font-black italic uppercase leading-tight text-brutalist-pink">
                            {{ t('footer.global') }}
                        </p>
                        <div class="pt-4 border-t-4 border-white/10">
                            <p class="flex items-start gap-3 text-sm font-black uppercase leading-relaxed text-white/50">
                                <Globe class="mt-0.5 h-4 w-4 shrink-0 text-brutalist-blue" />
                                {{ t('footer.built') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom bar -->
            <div class="mt-24 flex flex-col items-center justify-between gap-8 border-t-4 border-brutalist-pink/40 pt-10 md:flex-row">
                <p class="flex items-center gap-3 text-lg font-black uppercase tracking-wider text-white/70">
                    <span class="h-3 w-3 bg-brutalist-pink rotate-45"></span>
                    {{ t('footer.rights') }}
                </p>
                <div class="flex gap-8">
                    <Link v-for="item in legalLinks" :key="item.href" :href="route(item.href)" class="group relative text-sm font-black uppercase tracking-widest text-white/50 transition-colors hover:text-white">
                        {{ t(item.label) }}
                        <span class="absolute -bottom-1 left-0 h-0.5 w-0 bg-brutalist-yellow transition-all group-hover:w-full"></span>
                    </Link>
                </div>
            </div>
        </div>
    </footer>
</template>
