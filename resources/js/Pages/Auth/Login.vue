<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Button } from '@/Components/ui/button';
import { Checkbox } from '@/Components/ui/checkbox';
import { AlertCircle, Lock, Mail } from 'lucide-vue-next';

const { t } = useI18n();

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
        <Head :title="t('login')" />

        <div class="mb-8 space-y-1 text-center">
            <h1 class="text-3xl font-display font-bold tracking-tight text-slate-900 dark:text-white">{{ t('auth.login_title') }}</h1>
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ t('auth.login_subtitle') }}</p>
        </div>

        <div v-if="status" class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-sm font-medium">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-2">
                <Label for="email" class="text-xs font-bold uppercase tracking-wider text-slate-500 ml-1">{{ t('auth.email') }}</Label>
                <div class="relative group">
                    <Mail class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
                    <Input
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                        class="pl-11 h-12 bg-white/50 dark:bg-slate-900/50 border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                        :placeholder="t('auth.email_placeholder')"
                    />
                </div>
                <p v-if="form.errors.email" class="text-xs font-medium text-rose-500 flex items-center gap-1.5 mt-1 ml-1">
                    <AlertCircle class="w-3.5 h-3.5" />
                    {{ form.errors.email }}
                </p>
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between px-1">
                    <Label for="password" class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ t('auth.password') }}</Label>
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-[10px] font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors"
                    >
                        {{ t('auth.forgot_password') }}
                    </Link>
                </div>
                <div class="relative group">
                    <Lock class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
                    <Input
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        class="pl-11 h-12 bg-white/50 dark:bg-slate-900/50 border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                        :placeholder="t('auth.password_placeholder')"
                    />
                </div>
                <p v-if="form.errors.password" class="text-xs font-medium text-rose-500 flex items-center gap-1.5 mt-1 ml-1">
                    <AlertCircle class="w-3.5 h-3.5" />
                    {{ form.errors.password }}
                </p>
            </div>

            <div class="flex items-center space-x-2 ml-1">
                <Checkbox id="remember" :checked="form.remember" @update:checked="(val) => form.remember = val" class="rounded-lg border-slate-300 dark:border-slate-700 text-indigo-600" />
                <label
                    for="remember"
                    class="text-xs font-medium text-slate-600 dark:text-slate-400 leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 cursor-pointer"
                >
                    {{ t('auth.remember_me') }}
                </label>
            </div>

            <Button
                type="submit"
                class="w-full h-12 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold uppercase tracking-widest text-xs shadow-lg shadow-indigo-600/20 transition-all active:scale-[0.98]"
                :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                :disabled="form.processing"
            >
                <template v-if="form.processing">
                    <span class="animate-pulse">{{ t('auth.processing') }}</span>
                </template>
                <template v-else>
                    {{ t('auth.sign_in') }}
                </template>
            </Button>
        </form>
    </GuestLayout>
</template>
