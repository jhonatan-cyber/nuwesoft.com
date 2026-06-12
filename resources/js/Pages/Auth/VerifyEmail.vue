<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Button } from '@/Components/ui/button';

const props = defineProps({ status: { type: String } });

const form = useForm({});
const submit = () => form.post(route('verification.send'));
const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="mb-6">
            <h1 class="text-2xl font-black uppercase italic tracking-tight">Verify Email</h1>
            <p class="mt-2 text-xs font-bold text-neutral-500 uppercase tracking-widest">
                Check your inbox and click the verification link we sent you.
            </p>
        </div>

        <div v-if="verificationLinkSent" class="mb-4 text-xs font-bold text-emerald-600 uppercase tracking-widest">
            A new verification link has been sent to your email.
        </div>

        <form @submit.prevent="submit">
            <div class="flex items-center justify-between gap-4">
                <Button type="submit" :disabled="form.processing"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                    class="rounded-xl bg-black hover:bg-neutral-800 dark:bg-white dark:hover:bg-neutral-200 text-white dark:text-black font-black uppercase tracking-widest text-xs px-6">
                    {{ form.processing ? 'Sending...' : 'Resend Verification Email' }}
                </Button>

                <Link :href="route('logout')" method="post" as="button"
                    class="text-xs font-black uppercase tracking-widest text-neutral-400 hover:text-black dark:hover:text-white transition-colors">
                    Log Out
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
