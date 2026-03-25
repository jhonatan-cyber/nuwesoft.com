<script setup>
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { Save, X, Upload, XCircle, Image as ImageIcon } from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Textarea } from '@/Components/ui/textarea';
import { Label } from '@/Components/ui/label';
import { Switch } from '@/Components/ui/switch';
import { capitalizeWords, capitalizeFirstLetter } from '@/utils/text';

const props = defineProps({
    project: Object,
    technologies: Array,
    onSuccess: Function
});

const { t } = useI18n();

const existingImages = ref(props.project?.images || []);
const newImages = ref([]);
const removeImageIds = ref([]);
const selectedTechnologies = ref(props.project?.technologies?.map(t => t.id) || []);

const form = useForm({
    name: props.project?.name ?? '',
    category: props.project?.category ?? 'web',
    stack: props.project?.stack ? (Array.isArray(props.project.stack) ? props.project.stack.join(', ') : props.project.stack) : '',
    desc: props.project?.desc ?? '',
    icon: props.project?.icon ?? 'Briefcase',
    project_url: props.project?.project_url ?? '',
    is_active: props.project?.is_active ?? true,
});

const handleImagesChange = (event) => {
    const files = Array.from(event.target.files);
    if (files.length) {
        newImages.value = [...newImages.value, ...files];
    }
};

const removeExistingImage = (imageId) => {
    removeImageIds.value.push(imageId);
    existingImages.value = existingImages.value.filter(img => img.id !== imageId);
};

const removeNewImage = (index) => {
    newImages.value.splice(index, 1);
};

const getPreviewUrl = (file) => {
    return URL.createObjectURL(file);
};

const submit = () => {
    const formattedStack = typeof form.stack === 'string' 
        ? form.stack.split(',').map(s => s.trim()).filter(s => s !== '')
        : form.stack;

    const data = new FormData();
    data.append('name', form.name);
    data.append('category', form.category);
    data.append('stack', JSON.stringify(formattedStack));
    data.append('desc', form.desc);
    data.append('icon', form.icon);
    data.append('project_url', form.project_url);
    data.append('is_active', form.is_active ? '1' : '0');

    if (selectedTechnologies.value.length > 0) {
        selectedTechnologies.value.forEach(id => {
            data.append('technologies[]', id);
        });
    }

    if (removeImageIds.value.length > 0) {
        removeImageIds.value.forEach(id => {
            data.append('remove_images[]', id);
        });
    }

    if (newImages.value.length > 0) {
        newImages.value.forEach((file) => {
            data.append('images[]', file);
        });
    }

    // Para update via POST con _method=PUT (Laravel standard for FormData updates)
    if (props.project) {
        data.append('_method', 'PUT');
        form.transform(() => data).post(route('projects.update', props.project.id), {
            onSuccess: () => props.onSuccess?.(),
            preserveScroll: true
        });
    } else {
        form.transform(() => data).post(route('projects.store'), {
            onSuccess: () => props.onSuccess?.(),
            preserveScroll: true
        });
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6 py-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="space-y-2">
                    <Label for="name" class="dark:text-slate-200">{{ t('dashboard_panel.projects.fields.name') }}</Label>
                    <Input id="name" v-model="form.name" @blur="form.name = capitalizeWords(form.name)" required class="bg-white/50 dark:bg-slate-900/50 border-gray-200 dark:border-slate-800 focus:border-blue-500 rounded-xl transition-colors" />
                    <div v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</div>
                </div>

                <div class="space-y-2">
                    <Label for="category" class="dark:text-slate-200">{{ t('dashboard_panel.projects.fields.category') }}</Label>
                    <select id="category" v-model="form.category" class="w-full flex h-10 rounded-xl border border-gray-200 dark:border-slate-800 bg-white/50 dark:bg-slate-900/50 px-3 py-2 text-sm ring-offset-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:ring-offset-slate-950 dark:focus-visible:ring-blue-600 transition-colors">
                        <option value="web">Web</option>
                        <option value="mobile">Mobile</option>
                        <option value="design">Design</option>
                        <option value="other">Other</option>
                    </select>
                    <div v-if="form.errors.category" class="text-sm text-red-500">{{ form.errors.category }}</div>
                </div>

                <div class="space-y-2">
                    <Label for="stack" class="dark:text-slate-200">{{ t('dashboard_panel.projects.fields.stack') }}</Label>
                    <Input id="stack" v-model="form.stack" placeholder="Vue, Laravel, Tailwind" class="bg-white/50 dark:bg-slate-900/50 border-gray-200 dark:border-slate-800 focus:border-blue-500 rounded-xl transition-colors" />
                    <div v-if="form.errors.stack" class="text-sm text-red-500">{{ form.errors.stack }}</div>
                </div>

                <div class="space-y-2">
                    <Label for="project_url" class="dark:text-slate-200">{{ t('dashboard_panel.projects.fields.url') }}</Label>
                    <Input id="project_url" v-model="form.project_url" type="url" class="bg-white/50 dark:bg-slate-900/50 border-gray-200 dark:border-slate-800 focus:border-blue-500 rounded-xl transition-colors" />
                    <div v-if="form.errors.project_url" class="text-sm text-red-500">{{ form.errors.project_url }}</div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="space-y-2">
                    <Label class="dark:text-slate-200 uppercase text-[10px] font-bold tracking-widest text-slate-400">Stack Tecnológico (Selección)</Label>
                    <div class="grid grid-cols-2 gap-2 max-h-[180px] overflow-y-auto p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 custom-scrollbar">
                        <label v-for="tech in technologies" :key="tech.id" class="flex items-center gap-2 p-2 rounded-xl hover:bg-white dark:hover:bg-slate-800 cursor-pointer transition-colors group border border-transparent hover:border-slate-100 dark:hover:border-slate-700">
                            <input 
                                type="checkbox" 
                                :value="tech.id" 
                                v-model="selectedTechnologies"
                                class="rounded border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500 bg-white dark:bg-slate-950"
                            />
                            <div class="flex items-center gap-2 overflow-hidden">
                                <img v-if="tech.logo_url" :src="tech.logo_url" class="w-4 h-4 object-contain opacity-70 group-hover:opacity-100" />
                                <span class="text-[10px] font-bold uppercase tracking-tight text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white truncate">{{ tech.name }}</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="desc" class="dark:text-slate-200">{{ t('dashboard_panel.projects.fields.description') }}</Label>
                    <Textarea id="desc" v-model="form.desc" @blur="form.desc = capitalizeFirstLetter(form.desc)" rows="4" class="bg-white/50 dark:bg-slate-900/50 border-gray-200 dark:border-slate-800 focus:border-blue-500 rounded-xl transition-colors" />
                    <div v-if="form.errors.desc" class="text-sm text-red-500">{{ form.errors.desc }}</div>
                </div>

                <div class="flex items-center space-x-2 pt-4">
                    <Switch id="is_active" :checked="form.is_active" @update:checked="form.is_active = $event" />
                    <Label for="is_active" class="dark:text-slate-200">{{ t('dashboard_panel.projects.fields.active') }}</Label>
                </div>
            </div>
        </div>

        <div class="space-y-4 pt-4 border-t border-gray-100 dark:border-slate-800">
            <Label class="text-lg font-semibold flex items-center gap-2 dark:text-slate-100">
                <ImageIcon class="w-5 h-5 text-blue-500" />
                {{ t('dashboard_panel.projects.fields.images') }}
            </Label>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <!-- Existing Images -->
                <div v-for="image in existingImages" :key="image.id" class="relative group aspect-video rounded-xl overflow-hidden border border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-900">
                    <img :src="image.url" class="w-full h-full object-cover" />
                    <button type="button" @click="removeExistingImage(image.id)" class="absolute top-1 right-1 p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                        <XCircle class="w-4 h-4" />
                    </button>
                </div>

                <!-- New Images Previews -->
                <div v-for="(file, index) in newImages" :key="index" class="relative group aspect-video rounded-xl overflow-hidden border border-blue-200 dark:border-blue-900/30 bg-blue-50 dark:bg-blue-900/20">
                    <img :src="getPreviewUrl(file)" class="w-full h-full object-cover" />
                    <button type="button" @click="removeNewImage(index)" class="absolute top-1 right-1 p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                        <XCircle class="w-4 h-4" />
                    </button>
                </div>

                <!-- Upload Button -->
                <label class="flex flex-col items-center justify-center aspect-video rounded-xl border-2 border-dashed border-gray-300 dark:border-slate-700 hover:border-blue-500 dark:hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all cursor-pointer group">
                    <Upload class="w-6 h-6 text-gray-400 group-hover:text-blue-500 dark:group-hover:text-blue-400" />
                    <span class="text-xs text-gray-500 dark:text-slate-400 mt-2 group-hover:text-blue-600 dark:group-hover:text-blue-300">{{ t('dashboard_panel.projects.actions.upload') }}</span>
                    <input type="file" multiple accept="image/*" class="hidden" @change="handleImagesChange" />
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-slate-800">
            <Button type="submit" :disabled="form.processing" class="bg-blue-600 hover:bg-blue-700 text-white px-8 rounded-xl shadow-lg shadow-blue-200 dark:shadow-blue-900/20 transition-all flex items-center gap-2">
                <Save class="w-4 h-4" />
                {{ form.processing ? t('dashboard_panel.projects.actions.saving') : t('dashboard_panel.projects.actions.save') }}
            </Button>
        </div>
    </form>
</template>
