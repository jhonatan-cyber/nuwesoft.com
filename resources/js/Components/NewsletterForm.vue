<script setup>
import { ref } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { Mail, CheckCircle, Loader2 } from 'lucide-vue-next'

const { t } = useI18n()
const { props } = usePage()

const form = useForm({
    email: '',
    name: '',
    form_token: '', // honeypot
    source: 'blog',
})

const submitted = ref(false)
const flashSuccess = computed(() => props.flash?.newsletter_success)

const submit = () => {
    form.post(route('newsletter.subscribe'), {
        preserveScroll: true,
        onSuccess: () => {
            if (!form.errors.email) {
                submitted.value = true
                form.reset()
            }
        },
    })
}
</script>

<template>
    <!-- Success flash from server -->
    <div v-if="flashSuccess" class="bg-green-500/10 border border-green-500/30 rounded-xl p-4 flex items-center gap-3 mb-6">
        <CheckCircle class="w-5 h-5 text-green-500 flex-shrink-0" />
        <p class="text-sm font-bold text-green-600">{{ flashSuccess }}</p>
    </div>

    <!-- Subscription form -->
    <div v-if="!submitted" class="bg-white/5 border border-white/10 rounded-2xl p-6 md:p-8">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-brutalist-yellow/20 flex items-center justify-center">
                <Mail class="w-5 h-5 text-brutalist-yellow" />
            </div>
            <div>
                <h3 class="text-sm font-black uppercase tracking-wider text-white">
                    {{ t('newsletter.title', 'Suscribite al newsletter') }}
                </h3>
                <p class="text-[10px] text-white/40 uppercase tracking-widest">
                    {{ t('newsletter.subtitle', 'Novedades, artículos y tips directo a tu email') }}
                </p>
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-3">
            <!-- Honeypot -->
            <input v-model="form.form_token" type="text" name="form_token" class="hidden" tabindex="-1" autocomplete="off" />

            <input v-model="form.name" type="text" maxlength="255"
                :placeholder="t('newsletter.name_placeholder', 'Tu nombre (opcional)')"
                class="w-full bg-white/5 border border-white/20 rounded-lg px-4 py-2.5 text-sm text-white placeholder:text-white/30 focus:border-brutalist-yellow focus:ring-1 focus:ring-brutalist-yellow outline-none transition-colors" />

            <div class="flex gap-2">
                <input v-model="form.email" type="email" required maxlength="255"
                    :placeholder="t('newsletter.email_placeholder', 'tu@email.com')"
                    class="flex-1 bg-white/5 border border-white/20 rounded-lg px-4 py-2.5 text-sm text-white placeholder:text-white/30 focus:border-brutalist-yellow focus:ring-1 focus:ring-brutalist-yellow outline-none transition-colors" />
                <button type="submit" :disabled="form.processing"
                    class="bg-brutalist-yellow text-black px-5 py-2.5 rounded-lg font-black text-xs uppercase tracking-wider hover:bg-brutalist-yellow/90 transition-colors disabled:opacity-50 flex items-center gap-2">
                    <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                    <span>{{ t('newsletter.submit', 'Suscribir') }}</span>
                </button>
            </div>

            <p v-if="form.errors.email" class="text-red-400 text-xs mt-1">{{ form.errors.email }}</p>
            <p class="text-[9px] text-white/20">{{ t('newsletter.privacy', 'No spam. Podés desuscribirte en cualquier momento.') }}</p>
        </form>
    </div>

    <!-- Already subscribed -->
    <div v-else class="bg-green-500/10 border border-green-500/30 rounded-2xl p-6 md:p-8 text-center">
        <CheckCircle class="w-10 h-10 text-green-500 mx-auto mb-3" />
        <p class="text-sm font-black uppercase text-white">{{ t('newsletter.success', '¡Te suscribiste!') }}</p>
        <p class="text-xs text-white/40 mt-1">{{ t('newsletter.success_msg', 'Recibirás nuestras novedades pronto.') }}</p>
    </div>
</template>
