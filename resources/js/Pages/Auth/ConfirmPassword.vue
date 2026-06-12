<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { AlertCircle } from 'lucide-vue-next';

const form = useForm({ password: '' });
const submit = () => form.post(route('password.confirm'), { onFinish: () => form.reset() });
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <div class="mb-6">
            <h1 class="text-2xl font-black uppercase italic tracking-tight">Confirm Password</h1>
            <p class="mt-2 text-xs font-bold text-neutral-500 uppercase tracking-widest">
                This is a secure area. Please confirm your password before continuing.
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div class="space-y-2">
                <Label for="password" class="text-xs font-bold uppercase tracking-wider text-neutral-500">Password</Label>
                <Input id="password" type="password" v-model="form.password" required autofocus autocomplete="current-password"
                    class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900" />
                <p v-if="form.errors.password" class="flex items-center gap-1.5 text-xs font-medium text-rose-500">
                    <AlertCircle class="h-3.5 w-3.5" /> {{ form.errors.password }}
                </p>
            </div>

            <div class="flex justify-end">
                <Button type="submit" :disabled="form.processing"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                    class="rounded-xl bg-black hover:bg-neutral-800 dark:bg-white dark:hover:bg-neutral-200 text-white dark:text-black font-black uppercase tracking-widest text-xs px-6">
                    {{ form.processing ? 'Confirming...' : 'Confirm' }}
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
