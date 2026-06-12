<script setup>
import { useI18n } from 'vue-i18n'
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { Mail, Phone, Globe, Clock, MessageSquare } from 'lucide-vue-next'
import ContactSocial from '@/Components/ContactSocial.vue'

const { t } = useI18n()
const page = usePage()
const settings = computed(() => page.props.settings || {})

const contactDetails = computed(() => [
    { icon: Mail, bg: 'bg-brutalist-blue', label: 'contacto.info.email_label', content: settings.value.email || 'HELLO@NUWESOFT.COM', href: `mailto:${settings.value.email || 'hello@nuwesoft.com'}`, isKey: false },
    ...(settings.value.phone ? [{ icon: Phone, bg: 'bg-brutalist-yellow', label: 'contacto.info.phone_label', content: settings.value.phone, href: `tel:${settings.value.phone}`, isKey: false }] : []),
    { icon: Globe, bg: 'bg-brutalist-lime', label: 'contacto.info.location_label', content: settings.value.address || 'contacto.info.location_value', isKey: !settings.value.address },
    { icon: Clock, bg: 'bg-brutalist-purple', label: 'contacto.info.hours_label', content: 'contacto.info.hours_value', isKey: true },
])
</script>

<template>
    <div class="space-y-12">
        <!-- Info Card -->
        <div
            class="group relative border-4 border-black bg-white p-10 shadow-brutalist dark:border-white dark:bg-black dark:shadow-brutalist-white"
        >
            <!-- Floating diamond accent -->
            <div
                class="absolute -top-6 -right-6 flex h-24 w-24 -translate-y-0 rotate-12 items-center justify-center border-4 border-black bg-brutalist-pink transition-transform group-hover:rotate-0 dark:border-white"
            >
                <MessageSquare class="h-12 w-12 text-white" />
            </div>

            <h2
                class="mb-10 font-display text-4xl font-black uppercase italic underline decoration-brutalist-yellow decoration-8 underline-offset-4 text-black dark:text-white"
            >
                {{ t('contacto.info.title') }}
            </h2>

            <div class="space-y-8">
                <div
                    v-for="(item, idx) in contactDetails"
                    :key="idx"
                    class="flex items-start gap-6"
                >
                    <div
                        :class="[
                            'flex h-12 w-12 shrink-0 items-center justify-center border-4 border-black dark:border-white',
                            item.bg,
                        ]"
                    >
                        <component :is="item.icon" class="h-6 w-6 text-black" />
                    </div>
                    <div>
                        <span class="block text-sm font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ t(item.label) }}
                        </span>
                        <a
                            v-if="item.href"
                            :href="item.href"
                            class="block text-xl md:text-2xl font-black uppercase italic text-black transition-colors hover:text-brutalist-pink dark:text-white break-all"
                        >
                            {{ item.isKey ? t(item.content) : item.content }}
                        </a>
                        <span
                            v-else
                            class="block text-xl md:text-2xl font-black uppercase italic text-black dark:text-white break-words"
                        >
                            {{ item.isKey ? t(item.content) : item.content }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Social section -->
            <div class="mt-12 border-t-4 border-black pt-12 dark:border-white">
                <ContactSocial />
            </div>
        </div>

        <!-- Manifesto Quote Card -->
        <div
            class="hidden -rotate-2 border-4 border-black bg-brutalist-blue p-8 shadow-brutalist dark:border-white dark:shadow-brutalist-white lg:block"
        >
            <p class="text-3xl font-black uppercase italic leading-none text-black">
                &ldquo;{{ t('manifesto.quote') }}&rdquo;
            </p>
        </div>
    </div>
</template>
