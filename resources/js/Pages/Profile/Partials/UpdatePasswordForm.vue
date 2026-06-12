<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { AlertCircle } from 'lucide-vue-next';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value?.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value?.focus();
            }
        },
    });
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-black uppercase tracking-tight text-neutral-900 dark:text-white">
                Update Password
            </h2>
            <p class="mt-1 text-xs font-bold text-neutral-500 uppercase tracking-widest">
                Ensure your account is using a long, random password.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="space-y-5">
            <div class="space-y-2">
                <Label for="current_password" class="text-xs font-bold uppercase tracking-wider text-neutral-500">Current Password</Label>
                <Input id="current_password" ref="currentPasswordInput" v-model="form.current_password"
                    type="password" autocomplete="current-password"
                    class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900" />
                <p v-if="form.errors.current_password" class="flex items-center gap-1.5 text-xs font-medium text-rose-500">
                    <AlertCircle class="h-3.5 w-3.5" /> {{ form.errors.current_password }}
                </p>
            </div>

            <div class="space-y-2">
                <Label for="password" class="text-xs font-bold uppercase tracking-wider text-neutral-500">New Password</Label>
                <Input id="password" ref="passwordInput" v-model="form.password"
                    type="password" autocomplete="new-password"
                    class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900" />
                <p v-if="form.errors.password" class="flex items-center gap-1.5 text-xs font-medium text-rose-500">
                    <AlertCircle class="h-3.5 w-3.5" /> {{ form.errors.password }}
                </p>
            </div>

            <div class="space-y-2">
                <Label for="password_confirmation" class="text-xs font-bold uppercase tracking-wider text-neutral-500">Confirm Password</Label>
                <Input id="password_confirmation" v-model="form.password_confirmation"
                    type="password" autocomplete="new-password"
                    class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900" />
                <p v-if="form.errors.password_confirmation" class="flex items-center gap-1.5 text-xs font-medium text-rose-500">
                    <AlertCircle class="h-3.5 w-3.5" /> {{ form.errors.password_confirmation }}
                </p>
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
