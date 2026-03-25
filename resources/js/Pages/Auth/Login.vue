<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="mb-12 border-b-8 border-black dark:border-white pb-6">
            <h1 class="text-5xl font-black uppercase italic tracking-tighter">Acceso Maestro</h1>
            <p class="text-sm font-mono font-bold mt-2 opacity-60">INGRESE SUS CREDENCIALES DE SISTEMA</p>
        </div>

        <form @submit.prevent="submit" class="space-y-8">
            <div>
                <InputLabel for="email" value="Protocolo de Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600"
                        >Remember me</span
                    >
                </label>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-6">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-xs font-black uppercase tracking-widest text-black dark:text-white hover:underline decoration-brutalist-pink decoration-4"
                >
                    ¿Recuperar Acceso?
                </Link>

                <PrimaryButton
                    class="w-full sm:w-auto"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    INICIAR_SESION
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
