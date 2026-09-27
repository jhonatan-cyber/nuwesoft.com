<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { Save, ScanSearch, Loader2, LockKeyhole } from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Textarea } from '@/Components/ui/textarea';
import { Label } from '@/Components/ui/label';
import { Switch } from '@/Components/ui/switch';
import { capitalizeWords, capitalizeFirstLetter } from '@/utils/text';
import { useProjectAnalyzer } from '@/composables/useProjectAnalyzer';
import ProjectImagesField from '@/Components/ProjectImagesField.vue';

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
const automaticCaptureNames = ref(new Set());
const gallerySection = ref(null);

const form = useForm({
    name: props.project?.name ?? '',
    category: props.project?.category ?? 'web',
    desc: props.project?.desc ?? '',
    icon: props.project?.icon ?? 'Briefcase',
    project_url: props.project?.project_url ?? '',
    is_active: props.project?.is_active ?? true,
});

const {
    isAnalyzing,
    needsCredentials,
    analyzerMessage,
    analyzerError,
    analyzerUsername,
    analyzerPassword,
    authenticationFields,
    authenticationValues,
    analyzeTechnologies,
} = useProjectAnalyzer({ form, selectedTechnologies, newImages, automaticCaptureNames, gallerySection });

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

const submit = () => {
    const data = new FormData();
    data.append('name', form.name);
    data.append('category', form.category);
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
  <form
    class="space-y-6 py-4"
    @submit.prevent="submit"
  >
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="space-y-4">
        <div class="space-y-2">
          <Label
            for="name"
            class="dark:text-slate-200"
          >{{ t('dashboard_panel.projects.fields.name') }}</Label>
          <Input
            id="name"
            v-model="form.name"
            required
            class="bg-white/50 dark:bg-slate-900/50 border-gray-200 dark:border-slate-800 focus:border-blue-500 rounded-xl transition-colors"
            @blur="form.name = capitalizeWords(form.name)"
          />
          <div
            v-if="form.errors.name"
            class="text-sm text-status-danger"
          >
            {{ form.errors.name }}
          </div>
        </div>

        <div class="space-y-2">
          <Label
            for="category"
            class="dark:text-slate-200"
          >{{ t('dashboard_panel.projects.fields.category') }}</Label>
          <select
            id="category"
            v-model="form.category"
            class="w-full flex h-10 rounded-xl border border-gray-200 dark:border-slate-800 bg-white/50 dark:bg-slate-900/50 px-3 py-2 text-sm ring-offset-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:ring-offset-slate-950 dark:focus-visible:ring-blue-600 transition-colors"
          >
            <option value="web">
              Web
            </option>
            <option value="mobile">
              Mobile
            </option>
            <option value="cloud">
              Cloud / DevOps
            </option>
            <option value="automation">
              Automatización
            </option>
          </select>
          <div
            v-if="form.errors.category"
            class="text-sm text-status-danger"
          >
            {{ form.errors.category }}
          </div>
        </div>

        <div class="space-y-2">
          <Label
            for="project_url"
            class="dark:text-slate-200"
          >{{ t('dashboard_panel.projects.fields.url') }}</Label>
          <div class="flex gap-2">
            <Input
              id="project_url"
              v-model="form.project_url"
              type="url"
              placeholder="https://ejemplo.com"
              class="bg-white/50 dark:bg-slate-900/50 border-gray-200 dark:border-slate-800 focus:border-blue-500 rounded-xl transition-colors"
            />
            <Button
              type="button"
              variant="outline"
              :disabled="isAnalyzing || !form.project_url"
              class="shrink-0 rounded-xl"
              @click="analyzeTechnologies"
            >
              <Loader2
                v-if="isAnalyzing"
                class="mr-2 h-4 w-4 animate-spin"
              />
              <ScanSearch
                v-else
                class="mr-2 h-4 w-4"
              />
              Analizar
            </Button>
          </div>
          <div
            v-if="form.errors.project_url"
            class="text-sm text-status-danger"
          >
            {{ form.errors.project_url }}
          </div>
          <div
            v-if="needsCredentials"
            class="space-y-3 rounded-xl border border-status-warning/30 bg-status-warning/10 p-3"
          >
            <p class="flex items-center gap-2 text-xs font-semibold text-status-warning">
              <LockKeyhole class="h-4 w-4" /> Acceso temporal (las credenciales no se guardan)
            </p>
            <div
              v-if="authenticationFields.length"
              class="grid grid-cols-1 gap-2 sm:grid-cols-2"
            >
              <div
                v-for="field in authenticationFields"
                :key="field.name"
                class="space-y-1"
              >
                <Label
                  :for="`auth-${field.name}`"
                  class="text-xs text-status-warning"
                >{{ field.label }}</Label>
                <Input
                  :id="`auth-${field.name}`"
                  v-model="authenticationValues[field.name]"
                  :type="field.type"
                  :autocomplete="field.autocomplete"
                  :required="field.required"
                  :placeholder="field.label"
                  class="rounded-xl border-gray-200 bg-white/50 transition-colors focus:border-blue-500 dark:border-slate-800 dark:bg-slate-900/50"
                />
              </div>
            </div>
            <div
              v-else
              class="grid grid-cols-1 gap-2 sm:grid-cols-2"
            >
              <Input
                v-model="analyzerUsername"
                autocomplete="off"
                placeholder="Usuario"
                class="rounded-xl border-gray-200 bg-white/50 transition-colors focus:border-blue-500 dark:border-slate-800 dark:bg-slate-900/50"
              />
              <Input
                v-model="analyzerPassword"
                type="password"
                autocomplete="new-password"
                placeholder="Contraseña"
                class="rounded-xl border-gray-200 bg-white/50 transition-colors focus:border-blue-500 dark:border-slate-800 dark:bg-slate-900/50"
              />
            </div>
            <Button
              type="button"
              :disabled="isAnalyzing"
              class="rounded-xl bg-blue-600 px-5 text-white shadow-md shadow-blue-200 transition-all hover:bg-blue-700 dark:shadow-blue-900/20"
              @click="analyzeTechnologies"
            >
              <Loader2
                v-if="isAnalyzing"
                class="mr-2 h-4 w-4 animate-spin"
              />
              <ScanSearch
                v-else
                class="mr-2 h-4 w-4"
              />
              Continuar análisis
            </Button>
          </div>
          <p
            v-if="analyzerMessage"
            class="text-xs font-medium text-status-success"
          >
            {{ analyzerMessage }}
          </p>
          <div
            v-if="analyzerError"
            role="alert"
            class="rounded-xl border border-status-danger/30 bg-status-danger/10 px-3 py-2 text-xs font-semibold text-status-danger"
          >
            {{ analyzerError }}
          </div>
        </div>
      </div>

      <div class="space-y-4">
        <div class="space-y-2">
          <Label class="dark:text-slate-200 uppercase text-xs font-bold tracking-widest text-slate-400">Stack Tecnológico (Selección)</Label>
          <div class="grid grid-cols-2 gap-2 max-h-[180px] overflow-y-auto p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 custom-scrollbar">
            <label
              v-for="tech in technologies"
              :key="tech.id"
              class="flex items-center gap-2 p-2 rounded-xl hover:bg-white dark:hover:bg-slate-800 cursor-pointer transition-colors group border border-transparent hover:border-slate-100 dark:hover:border-slate-700"
            >
              <input 
                v-model="selectedTechnologies" 
                type="checkbox" 
                :value="tech.id"
                class="rounded border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500 bg-white dark:bg-slate-950"
              >
              <div class="flex items-center gap-2 overflow-hidden">
                <img
                  v-if="tech.logo_url"
                  :src="tech.logo_url"
                  :class="['w-4 h-4 object-contain opacity-70 group-hover:opacity-100', tech.invert_dark ? 'dark:invert dark:brightness-0 dark:invert' : '']"
                >
                <span class="text-xs font-bold uppercase tracking-tight text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white truncate">{{ tech.name }}</span>
              </div>
            </label>
          </div>
        </div>

        <div class="space-y-2">
          <Label
            for="desc"
            class="dark:text-slate-200"
          >{{ t('dashboard_panel.projects.fields.description') }}</Label>
          <Textarea
            id="desc"
            v-model="form.desc"
            rows="4"
            class="bg-white/50 dark:bg-slate-900/50 border-gray-200 dark:border-slate-800 focus:border-blue-500 rounded-xl transition-colors"
            @blur="form.desc = capitalizeFirstLetter(form.desc)"
          />
          <div
            v-if="form.errors.desc"
            class="text-sm text-status-danger"
          >
            {{ form.errors.desc }}
          </div>
        </div>

        <div class="flex items-center space-x-2 pt-4">
          <Switch
            id="is_active"
            v-model="form.is_active"
          />
          <Label
            for="is_active"
            class="dark:text-slate-200"
          >{{ t('dashboard_panel.projects.fields.active') }}</Label>
        </div>
      </div>
    </div>

    <ProjectImagesField
      ref="gallerySection"
      :existing-images="existingImages"
      :new-images="newImages"
      :automatic-capture-names="automaticCaptureNames"
      @remove-existing="removeExistingImage"
      @remove-new="removeNewImage"
      @files-change="handleImagesChange"
    />
    <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-slate-800">
      <Button
        type="submit"
        :disabled="form.processing"
        class="bg-blue-600 hover:bg-blue-700 text-white px-8 rounded-xl shadow-lg shadow-blue-200 dark:shadow-blue-900/20 transition-all flex items-center gap-2"
      >
        <Save class="w-4 h-4" />
        {{ form.processing ? t('dashboard_panel.projects.actions.saving') : t('dashboard_panel.projects.actions.save') }}
      </Button>
    </div>
  </form>
</template>
