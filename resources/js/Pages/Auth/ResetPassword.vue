<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { AlertCircle } from 'lucide-vue-next';

const props = defineProps({
    email: { type: String, required: true },
    token: { type: String, required: true },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => form.post(route('password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
});
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div class="mb-6">
            <h1 class="text-2xl font-black uppercase italic tracking-tight">Reset Password</h1>
            <p class="mt-2 text-xs font-bold text-neutral-500 uppercase tracking-widest">
                Choose a new secure password for your account.
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div class="space-y-2">
                <Label for="email" class="text-xs font-bold uppercase tracking-wider text-neutral-500">Email</Label>
                <Input id="email" type="email" v-model="form.email" required autofocus autocomplete="username"
                    class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900" />
                <p v-if="form.errors.email" class="flex items-center gap-1.5 text-xs font-medium text-rose-500">
                    <AlertCircle class="h-3.5 w-3.5" /> {{ form.errors.email }}
                </p>
            </div>

            <div class="space-y-2">
                <Label for="password" class="text-xs font-bold uppercase tracking-wider text-neutral-500">New Password</Label>
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

            <div class="flex justify-end">
                <Button type="submit" :disabled="form.processing"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                    class="rounded-xl bg-black hover:bg-neutral-800 dark:bg-white dark:hover:bg-neutral-200 text-white dark:text-black font-black uppercase tracking-widest text-xs px-6">
                    {{ form.processing ? 'Resetting...' : 'Reset Password' }}
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
