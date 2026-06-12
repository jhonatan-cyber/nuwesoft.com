<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { ref, computed, h } from 'vue';
import { useSkeletonLoader } from '@/composables/useSkeletonLoader';
import {
    Save,
    Globe,
    Mail,
    Phone,
    MapPin,
    Image,
    Facebook,
    Twitter,
    Linkedin,
    Github,
    Youtube,
    Link as LinkIcon,
    X,
} from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/Components/ui/card';


const props = defineProps({
    settings: Object,
});

const { t } = useI18n();

const { skeletonReady } = useSkeletonLoader();

const logoPreview = ref(props.settings.logo_url || null);
const logoFile = ref(null);

// Build form from settings
const form = useForm({
    site_name: props.settings.site_name || 'NUWESOFT',
    tagline: props.settings.tagline || '',
    email: props.settings.email || '',
    phone: props.settings.phone || '',
    address: props.settings.address || '',
    social_facebook: props.settings.social_facebook || '',
    social_twitter: props.settings.social_twitter || '',
    social_linkedin: props.settings.social_linkedin || '',
    social_github: props.settings.social_github || '',
    social_youtube: props.settings.social_youtube || '',
    social_tiktok: props.settings.social_tiktok || '',
});

const handleLogoChange = (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    logoFile.value = file;
    const reader = new FileReader();
    reader.onload = (ev) => {
        logoPreview.value = ev.target?.result;
    };
    reader.readAsDataURL(file);
};

const removeLogo = () => {
    logoPreview.value = null;
    logoFile.value = null;
};

const processing = ref(false);

const submit = () => {
    const fd = new FormData();

    // Text fields
    Object.entries(form.data()).forEach(([key, val]) => {
        fd.append(key, val ?? '');
    });

    // Logo file if selected
    if (logoFile.value) {
        fd.append('logo', logoFile.value);
    }

    processing.value = true;

    router.post(route('dashboard.settings.update'), fd, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            logoFile.value = null;
            processing.value = false;
        },
        onError: (errors) => {
            processing.value = false;
            Object.entries(errors).forEach(([key, msg]) => {
                form.setError(key, msg);
            });
        },
        onFinish: () => {
            processing.value = false;
        },
    });
};

const socialFields = [
    { key: 'social_facebook', icon: Facebook, label: 'Facebook', color: 'text-blue-600', placeholder: 'https://facebook.com/...' },
    { key: 'social_twitter', icon: Twitter, label: 'X (Twitter)', color: 'text-neutral-900 dark:text-white', placeholder: 'https://x.com/...' },
    { key: 'social_linkedin', icon: Linkedin, label: 'LinkedIn', color: 'text-blue-700', placeholder: 'https://linkedin.com/company/...' },
    { key: 'social_github', icon: Github, label: 'GitHub', color: 'text-neutral-900 dark:text-white', placeholder: 'https://github.com/...' },
    { key: 'social_youtube', icon: Youtube, label: 'YouTube', color: 'text-red-600', placeholder: 'https://youtube.com/@...' },
    { key: 'social_tiktok', icon: { render() { return h('svg', { viewBox: '0 0 24 24', fill: 'currentColor' }, [h('path', { d: 'M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z' })]) } }, label: 'TikTok', color: 'text-neutral-900 dark:text-white', placeholder: 'https://tiktok.com/@...' },
];
</script>

<template>
    <Head :title="t('settings.title')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="space-y-1">
                    <h2 class="text-3xl md:text-4xl font-display font-bold tracking-tight text-neutral-900 dark:text-white uppercase italic">
                        {{ t('settings.title') }}
                    </h2>
                    <div class="flex items-center gap-3">
                        <div class="h-0.5 w-8 bg-black dark:bg-white rounded-full"></div>
                        <p class="text-[10px] font-bold text-neutral-500 dark:text-neutral-300 uppercase tracking-[0.2em]">
                            {{ t('settings.subtitle') }}
                        </p>
                    </div>
                </div>
            </div>
        </template>

        <div class="max-w-4xl mx-auto space-y-8 pb-12">
            <Transition name="fade" mode="out-in">
                <!-- Skeleton -->
                <div v-if="!skeletonReady" key="skeleton" class="space-y-8">
                    <div v-for="i in 3" :key="'card-' + i"
                        class="rounded-3xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-black overflow-hidden pointer-events-none select-none relative">
                        <div class="absolute inset-0 shimmer-sweep z-10"></div>
                        <div class="p-6 border-b border-neutral-100 dark:border-neutral-800">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl skeleton-bg shrink-0"></div>
                                <div class="space-y-2 flex-1">
                                    <div class="h-5 w-48 rounded skeleton-bg"></div>
                                    <div class="h-3 w-32 rounded skeleton-bg"></div>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-5">
                            <div v-for="j in 3" :key="'field-' + j" class="space-y-2">
                                <div class="h-3 w-24 rounded skeleton-bg"></div>
                                <div class="h-12 rounded-xl skeleton-bg"></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <div class="h-14 w-36 rounded-xl skeleton-bg"></div>
                    </div>
                </div>

                <div v-else key="content">
                    <!-- Success Alert -->
                    <div v-if="$page.props.flash?.success"
                class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl px-6 py-4 flex items-center gap-3"
            >
                <span class="text-emerald-600 dark:text-emerald-400 text-sm font-bold">{{ $page.props.flash.success }}</span>
            </div>

            <!-- Site Identity -->
            <Card class="border-neutral-200 dark:border-neutral-800 rounded-3xl bg-white dark:bg-black shadow-sm overflow-hidden">
                <CardHeader class="border-b border-neutral-100 dark:border-neutral-800 pb-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-neutral-100 dark:bg-neutral-800 rounded-2xl">
                            <Globe class="w-6 h-6 text-neutral-900 dark:text-white" />
                        </div>
                        <div>
                            <CardTitle class="text-xl font-display font-bold uppercase italic tracking-tight text-neutral-900 dark:text-white">
                                {{ t('settings.sections.identity') }}
                            </CardTitle>
                            <CardDescription class="text-[10px] font-bold text-neutral-500 dark:text-neutral-300 uppercase tracking-[0.2em]">
                                {{ t('settings.sections.identity_desc') }}
                            </CardDescription>
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="p-6 space-y-6">
                    <!-- Logo -->
                    <div class="space-y-3">
                        <Label class="text-xs font-bold uppercase tracking-wider text-neutral-500">{{ t('settings.fields.logo') }}</Label>
                        <div class="flex items-center gap-6">
                            <div class="w-32 h-16 rounded-xl border-2 border-dashed border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 flex items-center justify-center overflow-hidden relative group">
                                <img v-if="logoPreview" :src="logoPreview" class="max-w-full max-h-full object-contain p-2" />
                                <Image v-else class="w-8 h-8 text-neutral-300 dark:text-neutral-700" />
                                <button
                                    v-if="logoPreview"
                                    @click="removeLogo"
                                    class="absolute top-1 right-1 p-1 rounded-full bg-black/60 text-white opacity-0 group-hover:opacity-100 transition-opacity focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-black focus-visible:opacity-100"
                                >
                                    <X class="w-3 h-3" />
                                </button>
                            </div>
                            <div class="flex-1">
                                <Input
                                    type="file"
                                    accept="image/*"
                                    @change="handleLogoChange"
                                    class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 text-xs"
                                />
                                <p class="text-[9px] font-medium text-neutral-400 mt-1">{{ t('settings.hints.logo') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Site Name -->
                    <div class="space-y-2">
                        <Label for="site_name" class="text-xs font-bold uppercase tracking-wider text-neutral-500">{{ t('settings.fields.site_name') }}</Label>
                        <Input
                            id="site_name"
                            v-model="form.site_name"
                            class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 h-12"
                        />
                        <p v-if="form.errors.site_name" class="text-xs text-rose-500">{{ form.errors.site_name }}</p>
                    </div>

                    <!-- Tagline -->
                    <div class="space-y-2">
                        <Label for="tagline" class="text-xs font-bold uppercase tracking-wider text-neutral-500">{{ t('settings.fields.tagline') }}</Label>
                        <Input
                            id="tagline"
                            v-model="form.tagline"
                            class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 h-12"
                        />
                        <p v-if="form.errors.tagline" class="text-xs text-rose-500">{{ form.errors.tagline }}</p>
                    </div>
                </CardContent>
            </Card>

            <!-- Contact Info -->
            <Card class="border-neutral-200 dark:border-neutral-800 rounded-3xl bg-white dark:bg-black shadow-sm overflow-hidden">
                <CardHeader class="border-b border-neutral-100 dark:border-neutral-800 pb-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-neutral-100 dark:bg-neutral-800 rounded-2xl">
                            <Mail class="w-6 h-6 text-neutral-900 dark:text-white" />
                        </div>
                        <div>
                            <CardTitle class="text-xl font-display font-bold uppercase italic tracking-tight text-neutral-900 dark:text-white">
                                {{ t('settings.sections.contact') }}
                            </CardTitle>
                            <CardDescription class="text-[10px] font-bold text-neutral-500 dark:text-neutral-300 uppercase tracking-[0.2em]">
                                {{ t('settings.sections.contact_desc') }}
                            </CardDescription>
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="p-6 space-y-6">
                    <!-- Email -->
                    <div class="space-y-2">
                        <Label for="email" class="text-xs font-bold uppercase tracking-wider text-neutral-500 flex items-center gap-2">
                            <Mail class="w-3.5 h-3.5" /> {{ t('settings.fields.email') }}
                        </Label>
                        <Input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 h-12"
                        />
                        <p v-if="form.errors.email" class="text-xs text-rose-500">{{ form.errors.email }}</p>
                    </div>

                    <!-- Phone -->
                    <div class="space-y-2">
                        <Label for="phone" class="text-xs font-bold uppercase tracking-wider text-neutral-500 flex items-center gap-2">
                            <Phone class="w-3.5 h-3.5" /> {{ t('settings.fields.phone') }}
                        </Label>
                        <Input
                            id="phone"
                            v-model="form.phone"
                            type="tel"
                            class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 h-12"
                        />
                        <p v-if="form.errors.phone" class="text-xs text-rose-500">{{ form.errors.phone }}</p>
                    </div>

                    <!-- Address -->
                    <div class="space-y-2">
                        <Label for="address" class="text-xs font-bold uppercase tracking-wider text-neutral-500 flex items-center gap-2">
                            <MapPin class="w-3.5 h-3.5" /> {{ t('settings.fields.address') }}
                        </Label>
                        <Input
                            id="address"
                            v-model="form.address"
                            class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 h-12"
                        />
                        <p v-if="form.errors.address" class="text-xs text-rose-500">{{ form.errors.address }}</p>
                    </div>
                </CardContent>
            </Card>

            <!-- Social Links -->
            <Card class="border-neutral-200 dark:border-neutral-800 rounded-3xl bg-white dark:bg-black shadow-sm overflow-hidden">
                <CardHeader class="border-b border-neutral-100 dark:border-neutral-800 pb-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-neutral-100 dark:bg-neutral-800 rounded-2xl">
                            <LinkIcon class="w-6 h-6 text-neutral-900 dark:text-white" />
                        </div>
                        <div>
                            <CardTitle class="text-xl font-display font-bold uppercase italic tracking-tight text-neutral-900 dark:text-white">
                                {{ t('settings.sections.social') }}
                            </CardTitle>
                            <CardDescription class="text-[10px] font-bold text-neutral-500 dark:text-neutral-300 uppercase tracking-[0.2em]">
                                {{ t('settings.sections.social_desc') }}
                            </CardDescription>
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="p-6 space-y-5">
                    <div v-for="field in socialFields" :key="field.key" class="space-y-2">
                        <Label :for="field.key" class="text-xs font-bold uppercase tracking-wider text-neutral-500 flex items-center gap-2">
                            <component :is="field.icon" :class="['w-3.5 h-3.5', field.color]" />
                            {{ field.label }}
                        </Label>
                        <div class="relative">
                            <LinkIcon class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" />
                            <Input
                                :id="field.key"
                                v-model="form[field.key]"
                                :placeholder="field.placeholder"
                                class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 h-12 pl-11"
                            />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4">
                <Button
                    @click="submit"
                    :disabled="processing"
                    class="bg-black hover:bg-neutral-800 text-white dark:bg-white dark:hover:bg-neutral-200 dark:text-black rounded-xl px-10 py-6 shadow-lg font-bold uppercase text-xs tracking-wider transition-all hover:scale-[1.02] active:scale-[0.98]"
                >
                    <Save class="w-4 h-4 mr-2" />
                    <template v-if="processing">
                        <span class="animate-pulse">{{ t('settings.saving') }}</span>
                    </template>
                    <template v-else>
                        {{ t('settings.save') }}
                    </template>
                </Button>
            </div>
                </div>
            </Transition>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.font-display { font-family: 'Space Grotesk', sans-serif; }

</style>
