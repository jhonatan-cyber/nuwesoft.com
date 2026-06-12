<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { AlertCircle } from 'lucide-vue-next';

defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-black uppercase tracking-tight text-neutral-900 dark:text-white">
                Profile Information
            </h2>
            <p class="mt-1 text-xs font-bold text-neutral-500 uppercase tracking-widest">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="space-y-5">
            <div class="space-y-2">
                <Label for="name" class="text-xs font-bold uppercase tracking-wider text-neutral-500">Name</Label>
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

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="text-xs font-bold text-neutral-500">
                Your email address is unverified.
                <Link :href="route('verification.send')" method="post" as="button"
                    class="ml-1 font-black text-black dark:text-white underline hover:no-underline">
                    Resend verification email.
                </Link>
                <div v-show="status === 'verification-link-sent'" class="mt-2 text-emerald-600 font-bold">
                    A new verification link has been sent.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <Button type="submit" :disabled="form.processing"
                    class="rounded-xl bg-black hover:bg-neutral-800 dark:bg-white dark:hover:bg-neutral-200 text-white dark:text-black font-black uppercase tracking-widest text-xs px-6">
                    {{ form.processing ? 'Saving...' : 'Save' }}
                </Button>
                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-xs font-bold text-emerald-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
