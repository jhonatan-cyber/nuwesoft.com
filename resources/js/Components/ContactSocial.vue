<script setup>
import { useI18n } from 'vue-i18n'
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { Twitter, Linkedin, Github } from 'lucide-vue-next'

const { t } = useI18n()
const page = usePage()
const settings = computed(() => page.props.settings || {})

const socialLinks = computed(() => [
    { name: 'Twitter', icon: Twitter, href: settings.value.social_twitter, color: 'hover:bg-brutalist-blue' },
    { name: 'LinkedIn', icon: Linkedin, href: settings.value.social_linkedin, color: 'hover:bg-brutalist-pink' },
    { name: 'GitHub', icon: Github, href: settings.value.social_github, color: 'hover:bg-brutalist-yellow hover:text-black' },
].filter(s => s.href));
</script>

<template>
    <div>
        <span class="mb-6 block text-sm font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">
            {{ t('contacto.info.social_label') }}
        </span>
        <div class="flex gap-4">
            <a
                v-for="social in socialLinks"
                :key="social.name"
                :href="social.href"
                target="_blank"
                rel="noopener noreferrer"
                :class="[
                    'flex h-16 w-16 items-center justify-center border-4 border-black shadow-brutalist transition-all hover:translate-x-[4px] hover:translate-y-[4px] hover:shadow-none dark:border-white dark:shadow-brutalist-white',
                    social.color,
                ]"
            >
                <component :is="social.icon" class="h-8 w-8 text-black dark:text-white" />
            </a>
        </div>
    </div>
</template>
