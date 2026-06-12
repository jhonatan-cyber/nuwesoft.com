<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { AlertCircle } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
});
</script>

<template>
    <GuestLayout>
        <Head title="Registro" />

        <div class="mb-6">
            <h1 class="text-2xl font-black uppercase italic tracking-tight">Nuevo Operativo</h1>
            <p class="mt-2 text-xs font-bold text-neutral-500 uppercase tracking-widest">REGISTRO DE CREDENCIALES ALPHA</p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div class="space-y-2">
                <Label for="name" class="text-xs font-bold uppercase tracking-wider text-neutral-500">Nombre Completo</Label>
                <Input id="name" type="text" v-model="form.name" required autofocus autocomplete="name"
                    class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900" />
                <p v-if="form.errors.name" class="flex items-center gap-1.5 text-xs font-medium text-rose-500">
                    <AlertCircle class="h-3.5 w-3.5" /> {{ form.errors.name }}
                </p>
            </div>

            <div class="space-y-2">
                <Label for="email" class="text-xs font-bold uppercase tracking-wider text-neutral-500">Email</Label>
                <Input id="email" type="email" v-model="form.email" required autocomplete="username"
                    class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900" />
                <p v-if="form.errors.email" class="flex items-center gap-1.5 text-xs font-medium text-rose-500">
                    <AlertCircle class="h-3.5 w-3.5" /> {{ form.errors.email }}
                </p>
            </div>

            <div class="space-y-2">
                <Label for="password" class="text-xs font-bold uppercase tracking-wider text-neutral-500">Password</Label>
                <Input id="password" type="password" v-model="form.password" required autocomplete="new-password"
                    class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900" />
                <p v-if="form.errors.password" class="flex items-center gap-1.5 text-xs font-medium text-rose-500">
                    <AlertCircle class="h-3.5 w-3.5" /> {{ form.errors.password }}
                </p>
            </div>

            <div class="space-y-2">
                <Label for="password_confirmation" class="text-xs font-bold uppercase tracking-wider text-neutral-500">Confirm Password</Label>
                <Input id="password_confirmation" type="password" v-model="form.password_confirmation" required autocomplete="new-password"
                    class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900" />
                <p v-if="form.errors.password_confirmation" class="flex items-center gap-1.5 text-xs font-medium text-rose-500">
                    <AlertCircle class="h-3.5 w-3.5" /> {{ form.errors.password_confirmation }}
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                <Link :href="route('login')"
                    class="text-xs font-black uppercase tracking-widest text-neutral-400 hover:text-black dark:hover:text-white transition-colors">
                    ¿Ya tienes cuenta?
                </Link>
                <Button type="submit" :disabled="form.processing"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                    class="w-full sm:w-auto rounded-xl bg-black hover:bg-neutral-800 dark:bg-white dark:hover:bg-neutral-200 text-white dark:text-black font-black uppercase tracking-widest text-xs px-8 py-5">
                    {{ form.processing ? 'Registrando...' : 'REGISTRAR_SISTEMA' }}
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
