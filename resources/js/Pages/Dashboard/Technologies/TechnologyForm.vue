<script setup>
import { useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { 
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select';
import { Upload, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import { Switch } from '@/Components/ui/switch';

const props = defineProps({
    technology: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['close', 'submit']);
const { t } = useI18n();
const logoPreview = ref(props.technology?.logo_url || null);
const fileInput = ref(null);

const form = useForm({
    name: props.technology?.name || '',
    category: props.technology?.category || 'backend',
    is_active: props.technology ? !!props.technology.is_active : true,
    invert_dark: props.technology ? !!props.technology.invert_dark : false,
    logo: null,
    _method: props.technology ? 'PUT' : 'POST'
});

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.logo = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            logoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const removeLogo = () => {
    form.logo = null;
    logoPreview.value = null;
    if (fileInput.value) fileInput.value.value = '';
};

const submit = () => {
    const url = props.technology 
        ? route('technologies.update', props.technology.id) 
        : route('technologies.store');

    // Only send logo if it's a File object, otherwise remove it
    if (!(form.logo instanceof File)) {
        form.logo = undefined;
    }

    form.post(url, {
        onSuccess: () => {
            // Small delay so Radix Vue can finish its dialog close animation
            // and remove data-scroll-locked from <body> before Inertia navigates.
            setTimeout(() => emit('close'), 80);
        },
        forceFormData: true,
    });
};
</script>

<template>
    <form id="technology-form" @submit.prevent="submit" class="space-y-6 px-6">
        <div class="grid gap-6">
            <!-- Name -->
            <div class="space-y-2">
                <Label for="name" class="text-xs font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                    {{ t('technologies.fields.name') }}
                </Label>
                <Input 
                    id="name" 
                    v-model="form.name" 
                    :placeholder="t('technologies.placeholders.name')"
                    class="bg-white dark:bg-neutral-900 border-neutral-200 dark:border-neutral-800 rounded-xl focus:ring-black dark:focus:ring-white px-5 py-5"
                />
                <p v-if="form.errors.name" class="text-xs font-medium text-status-danger">{{ form.errors.name }}</p>
            </div>

            <!-- Category -->
            <div class="space-y-2">
                <Label class="text-xs font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                    {{ t('technologies.fields.category') }}
                </Label>
                <Select v-model="form.category">
                    <SelectTrigger class="bg-white dark:bg-neutral-900 border-neutral-200 dark:border-neutral-800 rounded-xl px-5 py-5">
                        <SelectValue :placeholder="t('technologies.placeholders.category')" />
                    </SelectTrigger>
                    <SelectContent class="rounded-xl border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
                        <SelectItem value="languages">{{ t('technologies.categories.languages') }}</SelectItem>
                        <SelectItem value="frontend">{{ t('technologies.categories.frontend') }}</SelectItem>
                        <SelectItem value="backend">{{ t('technologies.categories.backend') }}</SelectItem>
                        <SelectItem value="mobile">{{ t('technologies.categories.mobile') }}</SelectItem>
                        <SelectItem value="database">{{ t('technologies.categories.database') }}</SelectItem>
                        <SelectItem value="infrastructure">{{ t('technologies.categories.infrastructure') }}</SelectItem>
                        <SelectItem value="automation">{{ t('technologies.categories.automation') }}</SelectItem>
                        <SelectItem value="ui">{{ t('technologies.categories.ui') }}</SelectItem>
                    </SelectContent>
                </Select>
                <p v-if="form.errors.category" class="text-xs font-medium text-status-danger">{{ form.errors.category }}</p>
            </div>

            <!-- Logo Upload -->
            <div class="space-y-2">
                <Label class="text-xs font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                    {{ t('technologies.fields.logo') || 'LOGO' }}
                </Label>
                
                <div v-if="logoPreview" class="relative group w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-4">
                    <img :src="logoPreview" class="w-full h-full object-contain rounded-xl border border-neutral-200 dark:border-neutral-800 p-2 bg-white dark:bg-neutral-900 shadow-sm" />
                    <button 
                        type="button"
                        @click="removeLogo"
                        class="absolute -right-2 -top-2 rounded-full bg-status-danger p-1.5 text-white opacity-0 shadow-lg transition-opacity group-hover:opacity-100"
                    >
                        <Trash2 class="w-3 h-3 sm:w-3.5 sm:h-3.5" />
                    </button>
                </div>

                <div 
                    v-else
                    @click="$refs.fileInput.click()"
                    class="border-2 border-dashed border-neutral-200 dark:border-neutral-800 rounded-xl p-6 sm:p-8 text-center cursor-pointer hover:border-neutral-400 dark:hover:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-900 transition-all group"
                >
                    <input 
                        type="file" 
                        ref="fileInput" 
                        class="hidden" 
                        @change="handleFileChange" 
                        accept="image/*"
                    />
                    <div class="flex flex-col items-center gap-2">
                        <div class="p-3 rounded-full bg-neutral-100 dark:bg-neutral-800 group-hover:bg-neutral-200 dark:group-hover:bg-neutral-700 transition-colors">
                            <Upload class="w-5 h-5 text-neutral-400 group-hover:text-neutral-600 dark:group-hover:text-neutral-300" />
                        </div>
                        <span class="text-xs font-bold text-neutral-400 group-hover:text-neutral-600 dark:group-hover:text-neutral-300 uppercase tracking-widest">{{ t('technologies.actions.upload_logo') || 'SUBIR LOGO' }}</span>
                    </div>
                </div>
                <p v-if="form.errors.logo" class="text-xs font-medium text-status-danger">{{ form.errors.logo }}</p>
            </div>

            <!-- Invert Dark Mode -->
            <div class="flex items-center justify-between gap-4 p-3 sm:p-4 rounded-xl bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800">
                <div class="space-y-0.5 min-w-0">
                    <Label class="text-sm font-bold truncate">{{ t('technologies.fields.invert_dark') }}</Label>
                    <p class="text-xs sm:text-xs text-neutral-500 uppercase tracking-wider">{{ t('technologies.descriptions.invert_dark') }}</p>
                </div>
                <Switch v-model="form.invert_dark" class="shrink-0" />
            </div>
        </div>

    </form>
</template>
