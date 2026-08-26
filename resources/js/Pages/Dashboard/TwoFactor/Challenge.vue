<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { ref } from 'vue';
import { Shield, AlertTriangle } from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';

const { t } = useI18n();

const form = useForm({ code: '' });
const error = ref('');

function submit() {
    error.value = '';
    if (form.code.length < 6) {
        error.value = 'Ingresá el código de 6 dígitos';
        return;
    }
    form.post(route('2fa.verify'), {
        onError: (errors) => {
            error.value = errors.code || 'Código inválido';
        },
    });
}
</script>

<template>
    <Head title="Verificar identidad" />

    <div class="min-h-screen flex items-center justify-center bg-neutral-50 dark:bg-black px-4">
        <div class="w-full max-w-md space-y-8">
            <!-- Logo + Title -->
            <div class="text-center space-y-4">
                <div class="w-16 h-16 rounded-3xl bg-brutalist-pink/10 flex items-center justify-center mx-auto">
                    <Shield class="w-8 h-8 text-brutalist-pink" />
                </div>
                <div>
                    <h1 class="text-2xl font-black uppercase tracking-tight text-neutral-900 dark:text-white">
                        VERIFICÁ TU IDENTIDAD
                    </h1>
                    <p class="text-xs font-bold text-neutral-500 uppercase tracking-[0.2em] mt-2">
                        Ingresá el código de tu app de authenticación
                    </p>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-3xl p-8 shadow-xl">
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <Input
                            v-model="form.code"
                            type="text"
                            inputmode="numeric"
                            maxlength="6"
                            placeholder="000000"
                            class="text-center text-3xl font-mono tracking-[0.5em] w-full py-6"
                            autofocus
                        />
                        <p v-if="error" class="mt-3 flex items-center justify-center gap-1 text-center text-xs text-status-danger">
                            <AlertTriangle class="w-3 h-3" />
                            {{ error }}
                        </p>
                    </div>

                    <Button
                        type="submit"
                        :disabled="form.processing || form.code.length < 6"
                        class="w-full bg-black dark:bg-white text-white dark:text-black font-black uppercase tracking-widest text-xs py-6 rounded-xl"
                    >
                        <span v-if="form.processing">Verificando...</span>
                        <span v-else>CONTINUAR</span>
                    </Button>
                </form>

                <div class="mt-6 pt-6 border-t border-neutral-100 dark:border-neutral-800 text-center">
                    <p class="text-xs text-neutral-400 uppercase tracking-widest">
                        ¿No tenés acceso a tu app? Usá un código de recuperación.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
