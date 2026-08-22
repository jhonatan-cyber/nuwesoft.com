<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { ref } from 'vue';
import { Shield, Copy, Check, AlertTriangle } from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';

const { t } = useI18n();

const props = defineProps({
    qr_code_url: { type: String, required: true },
    secret: { type: String, required: true },
});

const form = useForm({ code: '' });
const copied = ref(false);
const error = ref('');

function copySecret() {
    navigator.clipboard.writeText(props.secret);
    copied.value = true;
    setTimeout(() => copied.value = false, 2000);
}

function submit() {
    error.value = '';
    if (form.code.length !== 6) {
        error.value = 'El código debe tener 6 dígitos';
        return;
    }
    form.post(route('2fa.confirm'), {
        onError: (errors) => {
            error.value = errors.code || 'Código inválido';
        },
    });
}
</script>

<template>
    <Head title="Configurar 2FA" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-2xl bg-brutalist-pink/10 flex items-center justify-center">
                    <Shield class="w-5 h-5 text-brutalist-pink" />
                </div>
                <div>
                    <h2 class="text-3xl font-black tracking-tight text-neutral-900 dark:text-white uppercase">
                        CONFIGURAR 2FA
                    </h2>
                    <p class="text-xs font-bold text-neutral-500 dark:text-neutral-300 uppercase tracking-[0.2em] mt-1">
                        Two-Factor Authentication
                    </p>
                </div>
            </div>
        </template>

        <div class="max-w-2xl space-y-8">
            <!-- Steps -->
            <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-3xl p-8">
                <h3 class="text-lg font-black uppercase tracking-tight mb-6">PASO 1: Escaneá el QR</h3>
                <p class="text-sm text-neutral-500 mb-6">
                    Escaneá este código con Google Authenticator, Authy o tu app de authenticación preferida.
                </p>

                <div class="flex justify-center mb-6">
                    <div class="bg-white p-4 rounded-2xl border border-neutral-200">
                        <img
                            :src="`https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(qr_code_url)}`"
                            alt="QR Code for 2FA"
                            class="w-48 h-48"
                        />
                    </div>
                </div>

                <!-- Manual entry -->
                <div class="bg-neutral-50 dark:bg-neutral-900 rounded-xl p-4">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-2">
                        O ingresá el código manualmente:
                    </p>
                    <div class="flex items-center gap-3">
                        <code class="flex-1 text-sm font-mono text-neutral-700 dark:text-neutral-300 bg-white dark:bg-black px-3 py-2 rounded-lg border border-neutral-200 dark:border-neutral-700 break-all">
                            {{ secret }}
                        </code>
                        <Button variant="outline" size="sm" @click="copySecret" class="shrink-0">
                            <Check v-if="copied" class="w-4 h-4 text-emerald-500" />
                            <Copy v-else class="w-4 h-4" />
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Step 2: Verify -->
            <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-3xl p-8">
                <h3 class="text-lg font-black uppercase tracking-tight mb-6">PASO 2: Verificá el código</h3>
                <p class="text-sm text-neutral-500 mb-6">
                    Ingresá el código de 6 dígitos que muestra tu app de authenticación.
                </p>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <Input
                            v-model="form.code"
                            type="text"
                            inputmode="numeric"
                            maxlength="6"
                            placeholder="000000"
                            class="text-center text-2xl font-mono tracking-[0.5em] w-48 mx-auto"
                            autofocus
                        />
                        <p v-if="error" class="text-xs text-red-500 mt-2 text-center flex items-center justify-center gap-1">
                            <AlertTriangle class="w-3 h-3" />
                            {{ error }}
                        </p>
                    </div>

                    <Button
                        type="submit"
                        :disabled="form.processing || form.code.length !== 6"
                        class="w-full bg-black dark:bg-white text-white dark:text-black font-black uppercase tracking-widest text-xs py-6 rounded-xl"
                    >
                        <span v-if="form.processing">Verificando...</span>
                        <span v-else>HABILITAR 2FA</span>
                    </Button>
                </form>
            </div>

            <!-- Info -->
            <div class="bg-brutalist-yellow/5 border border-brutalist-yellow/20 rounded-2xl p-5">
                <div class="flex items-start gap-3">
                    <AlertTriangle class="w-5 h-5 text-brutalist-yellow shrink-0 mt-0.5" />
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-neutral-600 dark:text-neutral-400 mb-1">
                            IMPORTANTE
                        </p>
                        <p class="text-xs text-neutral-500 leading-relaxed">
                            Después de habilitar 2FA, vas a necesitar el código de authenticación cada vez que inicies sesión.
                            Guardá los codes de recuperación en un lugar seguro.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
