<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Button } from '@/Components/ui/button';
import { Checkbox } from '@/Components/ui/checkbox';
import DashboardThemeSwitcher from '@/Components/DashboardThemeSwitcher.vue';
import DashboardLanguageSwitcher from '@/Components/DashboardLanguageSwitcher.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { usePage } from '@inertiajs/vue3';
import { AlertCircle, Lock, Mail, ArrowRight, Shield, Zap, Globe, Eye, EyeOff } from 'lucide-vue-next';

const { t } = useI18n();
const page = usePage();
const logoUrl = computed(() => page.props.settings?.logo_url || null);
const showPassword = ref(false);

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

const features = computed(() => [
    { icon: Shield, text: t('auth.feature_projects') },
    { icon: Zap, text: t('auth.feature_tech') },
    { icon: Globe, text: t('auth.feature_multilang') },
]);

const gridSvg = "data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E";
</script>

<template>
    <Head :title="t('login')" />
    <div class="relative flex min-h-screen overflow-hidden bg-neutral-50 transition-colors duration-300 dark:bg-black">
        <!-- Shared Decorative blobs (span both columns) -->
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-brutalist-yellow/10 blur-[100px] dark:bg-brutalist-yellow/10"></div>
            <div class="absolute -bottom-32 -right-32 h-96 w-96 rounded-full bg-brutalist-pink/10 blur-[100px] dark:bg-brutalist-pink/10"></div>
            <div class="absolute left-1/2 top-1/2 h-64 w-64 -translate-x-1/2 -translate-y-1/2 rounded-full bg-brutalist-blue/10 blur-[100px] dark:bg-brutalist-blue/10"></div>
            <div class="absolute left-[15%] top-[20%] h-16 w-16 rotate-45 border-2 border-black/5 bg-brutalist-purple/30 dark:border-white/5"></div>
            <div class="absolute bottom-[25%] right-[20%] h-10 w-10 rotate-12 bg-brutalist-lime/20"></div>
        </div>

        <!-- Shared Grid overlay -->
        <div class="pointer-events-none absolute inset-0 opacity-[0.03]" :style="{ backgroundImage: `url(${gridSvg})` }"></div>

        <!-- Left: Branding Column -->
        <div class="relative hidden w-1/2 flex-col justify-between overflow-hidden p-12 lg:flex">
            <!-- Ghost Logo (background watermark) -->
            <div class="pointer-events-none absolute inset-0 flex items-center justify-center" aria-hidden="true">
                <img
                    v-if="logoUrl"
                    :src="logoUrl"
                    alt=""
                    class="w-[90%] max-w-3xl -translate-y-8 scale-150 select-none opacity-[0.12] blur-[3px]"
                />
            </div>

            <!-- Center: Tagline -->
            <div class="relative z-10 mx-auto max-w-md text-center">
                <div class="mb-6 inline-flex -rotate-2 items-center gap-3 rounded-full border border-black/10 bg-white/70 px-6 py-2 text-[10px] font-black uppercase tracking-[0.28em] text-brutalist-yellow shadow-sm backdrop-blur-sm dark:border-white/10 dark:bg-white/5">
                    <span class="inline-block h-2 w-2 rounded-full bg-brutalist-yellow"></span>
                    {{ t('auth.admin_version') }}
                </div>
                <h1 class="font-display text-5xl font-black uppercase italic leading-[0.85] tracking-tighter text-black dark:text-white">
                    {{ t('auth.control_title') }}<br/>
                    <span class="text-brutalist-yellow underline decoration-brutalist-pink decoration-4 underline-offset-8">{{ t('auth.nuwesoft_brand') }}</span>
                </h1>
                <p class="mt-6 text-sm font-black uppercase tracking-[0.15em] text-black/40 dark:text-white/40">
                    {{ t('auth.tagline') }}
                </p>

                <!-- Feature pills -->
                <div class="mt-10 flex flex-wrap justify-center gap-3">
                    <div
                        v-for="feature in features"
                        :key="feature.text"
                        class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white/70 px-4 py-2 text-[10px] font-black uppercase tracking-[0.2em] text-black/60 shadow-sm backdrop-blur-sm dark:border-white/10 dark:bg-white/5 dark:text-white/60"
                    >
                        <component :is="feature.icon" class="h-3.5 w-3.5 text-brutalist-pink" />
                        {{ feature.text }}
                    </div>
                </div>
            </div>

            <!-- Bottom: Footer -->
            <div class="relative z-10 flex items-center justify-between text-[10px] font-black uppercase tracking-[0.2em] text-black/30 dark:text-white/20">
                <span>{{ t('auth.copyright') }}</span>
                <span class="flex items-center gap-2">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-brutalist-yellow"></span>
                    {{ t('auth.system_online') }}
                </span>
            </div>
        </div>

        <!-- Right: Form Column -->
        <div class="relative flex w-full flex-col justify-center px-6 lg:w-1/2 lg:px-16">
            <!-- Mobile header -->
            <div class="mb-10 flex flex-col items-center gap-4 lg:hidden">
                <ApplicationLogo class="w-36" />
                <div class="flex gap-3">
                    <DashboardThemeSwitcher />
                    <DashboardLanguageSwitcher />
                </div>
            </div>

            <!-- Desktop switchers (inside form column) -->
            <div class="absolute right-6 top-6 z-50 hidden gap-3 lg:flex">
                <DashboardThemeSwitcher />
                <DashboardLanguageSwitcher />
            </div>

            <div class="mx-auto mt-8 w-full max-w-sm">
                <!-- Form Card -->
                <div class="rounded-[2.5rem] border border-black/10 bg-white/80 p-8 shadow-2xl backdrop-blur-xl dark:border-white/10 dark:bg-white/5">
                    <!-- Header -->
                    <div class="mb-8 space-y-1">
                        <h1 class="font-display text-3xl font-black uppercase italic leading-[0.9] tracking-tighter text-black dark:text-white">
                            {{ t('auth.login_title') }}
                        </h1>
                    </div>

                    <!-- Status message -->
                    <div
                        v-if="status"
                        class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-600 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400"
                    >
                        {{ status }}
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submit" class="space-y-5">
                        <!-- Email -->
                        <div class="space-y-2">
                            <Label for="email" class="ml-1 text-xs font-bold uppercase tracking-wider text-black/50 dark:text-white/50">
                                {{ t('auth.email') }}
                            </Label>
                            <div class="relative group">
                                <Mail class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-black/30 transition-colors group-focus-within:text-brutalist-yellow dark:text-white/30" />
                                <Input
                                    id="email"
                                    type="email"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    class="h-12 rounded-2xl border-black/10 bg-white/70 pl-11 text-black transition-all focus:border-brutalist-yellow focus:ring-brutalist-yellow placeholder:text-black/30 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/30"
                                    :placeholder="t('auth.email_placeholder')"
                                />
                            </div>
                            <p v-if="form.errors.email" class="ml-1 mt-1 flex items-center gap-1.5 text-xs font-medium text-rose-500 dark:text-rose-400">
                                <AlertCircle class="h-3.5 w-3.5" />
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between px-1">
                                <Label for="password" class="text-xs font-bold uppercase tracking-wider text-black/50 dark:text-white/50">
                                    {{ t('auth.password') }}
                                </Label>
                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="text-[10px] font-bold uppercase tracking-widest text-brutalist-pink transition-colors hover:text-brutalist-yellow"
                                >
                                    {{ t('auth.forgot_password') }}
                                </Link>
                            </div>
                            <div class="relative group">
                                <Lock class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-black/30 transition-colors group-focus-within:text-brutalist-yellow dark:text-white/30" />
                                <Input
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    v-model="form.password"
                                    required
                                    autocomplete="current-password"
                                    class="h-12 rounded-2xl border-black/10 bg-white/70 pl-11 pr-11 text-black transition-all focus:border-brutalist-yellow focus:ring-brutalist-yellow placeholder:text-black/30 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/30"
                                    :placeholder="t('auth.password_placeholder')"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 rounded-lg p-1 text-black/30 transition-colors hover:text-black/60 dark:text-white/30 dark:hover:text-white/60 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2"
                                >
                                    <EyeOff v-if="showPassword" class="h-4 w-4" />
                                    <Eye v-else class="h-4 w-4" />
                                </button>
                            </div>
                            <p v-if="form.errors.password" class="ml-1 mt-1 flex items-center gap-1.5 text-xs font-medium text-rose-500 dark:text-rose-400">
                                <AlertCircle class="h-3.5 w-3.5" />
                                {{ form.errors.password }}
                            </p>
                        </div>

                        <!-- Remember -->
                        <div class="flex items-center gap-3 pl-1">
                            <Checkbox
                                id="remember"
                                v-model="form.remember"
                                class="rounded-lg border-black/20 text-brutalist-pink dark:border-white/20"
                            />
                            <label
                                for="remember"
                                class="cursor-pointer text-xs font-medium leading-none text-black/60 dark:text-white/60"
                            >
                                {{ t('auth.remember_me') }}
                            </label>
                        </div>

                        <!-- Submit -->
                        <Button
                            type="submit"
                            class="group h-14 w-full rounded-2xl bg-black font-black uppercase tracking-widest text-white shadow-lg transition-all hover:bg-brutalist-yellow hover:text-black active:scale-[0.98] dark:bg-brutalist-yellow dark:text-black dark:hover:bg-white"
                            :class="{ 'cursor-not-allowed opacity-50': form.processing }"
                            :disabled="form.processing"
                        >
                            <template v-if="form.processing">
                                <span class="animate-pulse">{{ t('auth.processing') }}</span>
                            </template>
                            <template v-else>
                                <span class="flex items-center justify-center gap-3">
                                    {{ t('auth.sign_in') }}
                                    <ArrowRight class="h-5 w-5 transition-transform group-hover:translate-x-1" />
                                </span>
                            </template>
                        </Button>

                        <!-- Register link removed — admin-only access -->
                    </form>
                </div>
            </div>

            <!-- Mobile footer -->
            <div class="mt-10 text-center text-[9px] font-black uppercase tracking-[0.2em] text-black/30 dark:text-white/30 lg:hidden">
                NUWESOFT &bull; CONTROL CENTER
            </div>
        </div>
    </div>
</template>
