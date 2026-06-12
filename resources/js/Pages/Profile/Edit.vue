<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import { usePageTracking } from '@/composables/usePageTracking';
import { useSkeletonLoader } from '@/composables/useSkeletonLoader';

usePageTracking();

defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
});

const { skeletonReady } = useSkeletonLoader();
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="text-3xl font-black tracking-tight text-neutral-900 dark:text-white uppercase">
                    PROFILE
                </h2>
                <p class="text-xs font-bold text-neutral-500 dark:text-neutral-300 uppercase tracking-[0.2em] mt-1">
                    MANAGE YOUR ACCOUNT
                </p>
            </div>
        </template>

        <div class="space-y-6 max-w-2xl">
            <Transition name="fade" mode="out-in">
                <div v-if="!skeletonReady" key="skeleton" class="space-y-6">
                    <div v-for="i in 3" :key="'card-' + i"
                        class="rounded-3xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-black overflow-hidden pointer-events-none select-none relative p-6">
                        <div class="absolute inset-0 shimmer-sweep z-10"></div>
                        <div class="space-y-4">
                            <div class="h-5 w-36 rounded skeleton-bg"></div>
                            <div class="h-3 w-64 rounded skeleton-bg"></div>
                            <div class="h-10 rounded-xl skeleton-bg"></div>
                            <div class="h-10 rounded-xl skeleton-bg"></div>
                        </div>
                    </div>
                </div>

                <div v-else key="content" class="space-y-6 max-w-2xl">
                    <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-6 rounded-3xl shadow-sm">
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                        />
                    </div>

                    <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-6 rounded-3xl shadow-sm">
                        <UpdatePasswordForm />
                    </div>

                    <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 p-6 rounded-3xl shadow-sm">
                        <DeleteUserForm />
                    </div>
                </div>
            </Transition>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
</style>
