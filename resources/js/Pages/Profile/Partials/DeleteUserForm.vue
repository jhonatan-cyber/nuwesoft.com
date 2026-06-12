<script setup>
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import { useRekaCleanup } from '@/composables/useRekaCleanup';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import {
    Dialog, DialogContent, DialogHeader,
    DialogTitle, DialogDescription, DialogFooter,
} from '@/Components/ui/dialog';
import { AlertCircle, AlertTriangle } from 'lucide-vue-next';

const confirmingUserDeletion = ref(false);

useRekaCleanup(confirmingUserDeletion);

const passwordInput = ref(null);

const form = useForm({ password: '' });

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-black uppercase tracking-tight text-neutral-900 dark:text-white">
                Delete Account
            </h2>
            <p class="mt-1 text-xs font-bold text-neutral-500 uppercase tracking-widest">
                Once deleted, all resources and data will be permanently removed.
            </p>
        </header>

        <Button @click="confirmUserDeletion"
            class="rounded-xl bg-rose-500 hover:bg-rose-600 text-white font-black uppercase tracking-widest text-xs px-6 shadow-lg shadow-rose-500/20">
            Delete Account
        </Button>

        <Dialog v-model:open="confirmingUserDeletion">
            <DialogContent class="sm:max-w-[420px] !rounded-[2rem] border border-neutral-200 dark:border-neutral-800 !bg-white dark:!bg-black shadow-2xl p-8 dashboard-dialog-enter">
                <DialogHeader>
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-neutral-100 dark:bg-neutral-800">
                        <AlertTriangle class="h-7 w-7 text-rose-500" />
                    </div>
                    <DialogTitle class="text-center text-xl font-black uppercase">Delete Account</DialogTitle>
                    <DialogDescription class="text-center text-xs font-bold text-neutral-400 uppercase tracking-widest">
                        This action cannot be undone. Enter your password to confirm.
                    </DialogDescription>
                </DialogHeader>

                <div class="space-y-2 py-4">
                    <Label for="delete_password" class="text-xs font-bold uppercase tracking-wider text-neutral-500">Password</Label>
                    <Input id="delete_password" ref="passwordInput" v-model="form.password"
                        type="password" placeholder="••••••••" @keyup.enter="deleteUser"
                        class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900" />
                    <p v-if="form.errors.password" class="flex items-center gap-1.5 text-xs font-medium text-rose-500">
                        <AlertCircle class="h-3.5 w-3.5" /> {{ form.errors.password }}
                    </p>
                </div>

                <DialogFooter class="flex flex-col-reverse sm:flex-row gap-3 sm:justify-center">
                    <Button variant="outline" @click="closeModal"
                        class="rounded-xl font-bold uppercase text-xs tracking-widest">
                        Cancel
                    </Button>
                    <Button @click="deleteUser" :disabled="form.processing"
                        class="rounded-xl bg-rose-500 hover:bg-rose-600 text-white font-bold uppercase text-xs tracking-widest shadow-lg shadow-rose-500/20">
                        <span v-if="form.processing" class="animate-pulse">Deleting...</span>
                        <span v-else>Delete Account</span>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </section>
</template>
