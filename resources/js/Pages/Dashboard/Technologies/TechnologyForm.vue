<script setup>
import { useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Button } from '@/Components/ui/button';
import { Switch } from '@/Components/ui/switch';
import { 
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select';
import { Loader2, Upload, Trash2, X } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps({
    technology: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['close']);
const { t } = useI18n();
const logoPreview = ref(props.technology?.logo_url || null);
const fileInput = ref(null);

const form = useForm({
    name: props.technology?.name || '',
    category: props.technology?.category || 'backend',
    is_active: props.technology ? !!props.technology.is_active : true,
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

    form.post(url, {
        onSuccess: () => emit('close'),
    });
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div class="grid gap-6">
            <!-- Name -->
            <div class="space-y-2">
                <Label for="name" class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    {{ t('technologies.fields.name') }}
                </Label>
                <Input 
                    id="name" 
                    v-model="form.name" 
                    :placeholder="t('technologies.placeholders.name')"
                    class="bg-white/50 dark:bg-slate-900/50 border-slate-200 dark:border-slate-800 rounded-xl focus:ring-indigo-500"
                />
                <p v-if="form.errors.name" class="text-xs text-rose-500 font-medium">{{ form.errors.name }}</p>
            </div>

            <!-- Category -->
            <div class="space-y-2">
                <Label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    {{ t('technologies.fields.category') }}
                </Label>
                <Select v-model="form.category">
                    <SelectTrigger class="bg-white/50 dark:bg-slate-900/50 border-slate-200 dark:border-slate-800 rounded-xl">
                        <SelectValue :placeholder="t('technologies.placeholders.category')" />
                    </SelectTrigger>
                    <SelectContent class="rounded-xl border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-slate-950/95 backdrop-blur-xl">
                        <SelectItem value="languages">Languages</SelectItem>
                        <SelectItem value="frameworks">Frameworks</SelectItem>
                        <SelectItem value="libraries">Libraries</SelectItem>
                        <SelectItem value="database">Database</SelectItem>
                        <SelectItem value="cloud">Cloud / DevOps</SelectItem>
                        <SelectItem value="mobile">Mobile</SelectItem>
                        <SelectItem value="ai">Artificial Intelligence</SelectItem>
                        <SelectItem value="tools">Tools</SelectItem>
                    </SelectContent>
                </Select>
                <p v-if="form.errors.category" class="text-xs text-rose-500 font-medium">{{ form.errors.category }}</p>
            </div>

            <!-- Logo Upload -->
            <div class="space-y-2">
                <Label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    {{ t('technologies.fields.logo') || 'LOGO' }}
                </Label>
                
                <div v-if="logoPreview" class="relative group w-24 h-24 mx-auto mb-4">
                    <img :src="logoPreview" class="w-full h-full object-contain rounded-2xl border border-slate-200 dark:border-slate-800 p-2 bg-white dark:bg-slate-900 shadow-sm" />
                    <button 
                        type="button"
                        @click="removeLogo"
                        class="absolute -top-2 -right-2 p-1.5 bg-rose-500 text-white rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity"
                    >
                        <Trash2 class="w-3.5 h-3.5" />
                    </button>
                </div>

                <div 
                    v-else
                    @click="$refs.fileInput.click()"
                    class="border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl p-8 text-center cursor-pointer hover:border-indigo-500/50 hover:bg-indigo-50/30 dark:hover:bg-indigo-500/5 transition-all group"
                >
                    <input 
                        type="file" 
                        ref="fileInput" 
                        class="hidden" 
                        @change="handleFileChange" 
                        accept="image/*"
                    />
                    <div class="flex flex-col items-center gap-2">
                        <div class="p-3 rounded-full bg-slate-100 dark:bg-slate-800 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/30 transition-colors">
                            <Upload class="w-5 h-5 text-slate-400 group-hover:text-indigo-500" />
                        </div>
                        <span class="text-xs font-bold text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300 uppercase tracking-widest">{{ t('technologies.actions.upload_logo') || 'SUBIR LOGO' }}</span>
                    </div>
                </div>
                <p v-if="form.errors.logo" class="text-xs text-rose-500 font-medium">{{ form.errors.logo }}</p>
            </div>

            <!-- Status -->
            <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/30 border border-slate-200 dark:border-slate-800">
                <div class="space-y-0.5">
                    <Label class="text-sm font-bold">{{ t('technologies.fields.is_active') }}</Label>
                    <p class="text-[10px] text-slate-500 uppercase tracking-wider">{{ t('technologies.descriptions.is_active') }}</p>
                </div>
                <Switch :checked="form.is_active" @update:checked="val => form.is_active = val" />
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
            <Button 
                type="button" 
                variant="ghost" 
                @click="emit('close')"
                class="rounded-xl font-bold uppercase text-[10px] tracking-widest"
            >
                {{ t('technologies.modals.cancel') }}
            </Button>
            <Button 
                type="submit" 
                :disabled="form.processing"
                class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl px-8 shadow-lg shadow-indigo-600/20 font-bold uppercase text-[10px] tracking-[0.2em]"
            >
                <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                {{ t('technologies.modals.save') }}
            </Button>
        </div>
    </form>
</template>
