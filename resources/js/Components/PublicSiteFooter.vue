<script setup>
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { Facebook, Github, Linkedin, Youtube } from 'lucide-vue-next';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const { t } = useI18n();

const navigation = [
    { href: 'servicios', key: 'services', pageKey: 'servicios' },
    { href: 'portafolio', key: 'portfolio', pageKey: 'portafolio' },
    { href: 'contacto', key: 'contact', pageKey: 'contacto' },
];

const socialLinks = [
    { name: 'Facebook', href: '#', labelKey: 'footer.facebook', tone: 'hover:bg-brutalist-blue hover:text-white' },
    { name: 'X', href: '#', labelKey: 'footer.x', tone: 'hover:bg-white hover:text-black' },
    { name: 'TikTok', href: '#', labelKey: 'footer.tiktok', tone: 'hover:bg-brutalist-pink hover:text-white' },
    { name: 'LinkedIn', href: '#', labelKey: 'footer.linkedin', tone: 'hover:bg-brutalist-blue hover:text-white' },
    { name: 'GitHub', href: '#', labelKey: 'footer.github', tone: 'hover:bg-brutalist-yellow hover:text-black' },
    { name: 'YouTube', href: '#', labelKey: 'footer.youtube', tone: 'hover:bg-brutalist-pink hover:text-white' },
];
</script>

<template>
    <footer class="border-t-8 border-brutalist-pink bg-black py-24 text-white">
        <div class="mx-auto max-w-[1400px] px-6">
            <div class="grid items-start gap-16 md:grid-cols-4">
                <div class="col-span-2">
                    <div class="mb-10 flex items-center space-x-3">
                        <div class="border-4 border-white/80 bg-transparent px-2 py-1 shadow-[6px_6px_0px_rgba(255,255,255,0.45)]">
                            <ApplicationLogo variant="light" class="w-44" />
                        </div>
                    </div>
                    <p class="mb-12 max-w-md text-2xl font-black italic uppercase leading-none">
                        {{ t('footer.tagline') }}
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a
                            v-for="social in socialLinks"
                            :key="social.name"
                            :href="social.href"
                            :aria-label="social.name"
                            :class="['group flex h-14 w-14 items-center justify-center border-4 border-white text-white transition-all duration-300 hover:-translate-y-1', social.tone]"
                        >
                            <Facebook v-if="social.name === 'Facebook'" class="h-6 w-6" />
                            <Github v-else-if="social.name === 'GitHub'" class="h-6 w-6" />
                            <Linkedin v-else-if="social.name === 'LinkedIn'" class="h-6 w-6" />
                            <Youtube v-else-if="social.name === 'YouTube'" class="h-6 w-6" />
                            <svg v-else-if="social.name === 'X'" viewBox="0 0 24 24" class="h-6 w-6 fill-current" aria-hidden="true">
                                <path d="M18.9 2H22l-6.77 7.74L23.2 22h-6.24l-4.89-7.4L5.6 22H2.5l7.24-8.27L1.8 2h6.4l4.42 6.76L18.9 2Zm-1.09 18h1.72L7.26 3.9H5.41L17.81 20Z" />
                            </svg>
                            <svg v-else viewBox="0 0 24 24" class="h-6 w-6 fill-current" aria-hidden="true">
                                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.65h-3.2v12.43a2.88 2.88 0 1 1-2-2.75V8.47a6.08 6.08 0 1 0 5.2 6v-6.3a8.03 8.03 0 0 0 4.77 1.58V6.69Z" />
                            </svg>
                        </a>
                    </div>
                    <div class="mt-6 flex flex-wrap gap-x-6 gap-y-3 text-xs font-black uppercase tracking-[0.24em] text-white/55">
                        <span v-for="social in socialLinks" :key="social.labelKey">{{ t(social.labelKey) }}</span>
                    </div>
                </div>

                <div>
                    <h5 class="mb-10 text-brutalist-yellow font-black uppercase tracking-[0.2em]">{{ t('footer.links') }}</h5>
                    <ul class="space-y-6 text-xl font-black italic uppercase">
                        <li v-for="item in navigation" :key="item.href">
                            <Link :href="route(item.href)" class="transition-colors hover:text-brutalist-pink">
                                {{ t(item.pageKey) }}
                            </Link>
                        </li>
                    </ul>
                </div>

                <div>
                    <h5 class="mb-10 text-brutalist-blue font-black uppercase tracking-[0.2em]">{{ t('footer.studio') }}</h5>
                    <p class="text-xl font-black italic uppercase leading-none">
                        {{ t('footer.remote') }} <br>
                        <span class="text-brutalist-pink">{{ t('footer.global') }}</span> <br>
                        {{ t('footer.built') }}
                    </p>
                </div>
            </div>

            <div class="mt-24 flex flex-col items-center justify-between gap-8 border-t-4 border-white/20 pt-12 md:flex-row">
                <p class="text-lg font-black uppercase">{{ t('footer.rights') }}</p>
                <div class="flex gap-12 text-sm font-black uppercase">
                    <span class="cursor-pointer hover:underline">{{ t('footer.privacy') }}</span>
                    <span class="cursor-pointer hover:underline">{{ t('footer.terms') }}</span>
                </div>
            </div>
        </div>
    </footer>
</template>
