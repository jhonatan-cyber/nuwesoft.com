<script setup>
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { Button } from '@/Components/ui/button';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/Components/ui/tooltip';
import { ChevronRight, Pencil, Trash2, Power, ExternalLink, Images, Briefcase } from 'lucide-vue-next';
import { cloudinaryThumb } from '@/lib/cloudinary';

defineProps({
    project: { type: Object, required: true },
    updatingStatusId: { type: Number, default: null },
});
defineEmits(['status', 'edit', 'delete']);

const { t } = useI18n();
</script>

<template>
  <div
    data-testid="project-card"
    class="group relative bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-3xl overflow-hidden hover:shadow-2xl transition-all duration-500 flex flex-col"
  >
    <div class="w-full h-40 bg-neutral-100 dark:bg-neutral-800 overflow-hidden relative shrink-0">
      <img
        v-if="project.images && project.images.length > 0"
        :src="cloudinaryThumb(project.images[0].image_url, 400, 300)"
        :alt="project.name"
        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
        loading="lazy"
      >
      <div
        v-else
        class="w-full h-full flex items-center justify-center"
      >
        <Briefcase class="w-12 h-12 text-neutral-300 dark:text-neutral-700" />
      </div>

      <!-- Badge Imágenes -->
      <div
        v-if="project.images && project.images.length > 0"
        class="absolute top-3 left-3 bg-white/90 dark:bg-black/90 border border-neutral-200 dark:border-neutral-700 px-2.5 py-1 rounded-xl flex items-center gap-1.5 shadow-sm"
      >
        <Images class="w-3.5 h-3.5 text-neutral-500" />
        <span class="text-xs font-black text-neutral-900 dark:text-white">{{ project.images.length }}</span>
      </div>

      <!-- Overlay galería -->
      <Link
        :href="route('projects.show', project.slug)"
        class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all flex items-center justify-center cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-inset"
      >
        <div class="bg-white text-black font-black uppercase tracking-widest text-xs px-5 py-2.5 rounded-2xl shadow-xl flex items-center gap-2 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300">
          <ChevronRight class="w-4 h-4" /> {{ t('dashboard_panel.projects.gallery.view') }}
        </div>
      </Link>
    </div>

    <!-- Card Body -->
    <div class="flex-1 flex flex-col p-4 gap-3">
      <div class="flex flex-wrap items-center gap-2">
        <span class="px-2 py-0.5 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 text-xs font-bold rounded-lg uppercase tracking-widest border border-neutral-200 dark:border-neutral-700">
          {{ project.category }}
        </span>
        <span
          v-if="!project.is_active"
          class="rounded-lg border border-status-danger/30 bg-status-danger/10 px-2 py-0.5 text-xs font-bold uppercase tracking-widest text-status-danger"
        >
          {{ t('dashboard_panel.projects.status.inactive') }}
        </span>
        <span
          v-if="project.media_status && project.media_status !== 'completed'"
          :title="project.media_error || undefined"
          class="inline-flex items-center gap-1.5 rounded-lg border px-2 py-1 text-xs font-bold uppercase tracking-wide"
          :class="project.media_status === 'failed'
            ? 'border-status-danger/30 bg-status-danger/10 text-status-danger'
            : 'border-status-warning/30 bg-status-warning/10 text-status-warning'"
        >
          <span
            class="size-2 rounded-full"
            :class="project.media_status === 'failed' ? 'bg-status-danger' : 'animate-pulse bg-status-warning'"
          />
          {{ {
            pending: 'Subida pendiente',
            processing: 'Procesando imágenes',
            failed: 'Falló la subida',
          }[project.media_status] || project.media_status }}
        </span>
        <span class="text-xs font-bold text-neutral-300 dark:text-neutral-700 tracking-[0.2em] ml-auto">
          #{{ String(project.id).padStart(3, '0') }}
        </span>
      </div>

      <h3 class="text-sm font-black text-neutral-900 dark:text-white tracking-tight uppercase line-clamp-1">
        {{ project.name }}
      </h3>

      <p class="text-sm text-neutral-500 dark:text-neutral-400 line-clamp-2 leading-relaxed flex-1">
        {{ project.desc }}
      </p>

      <!-- Tech badges -->
      <div
        v-if="project.technologies && project.technologies.length > 0"
        class="flex flex-wrap gap-1.5"
      >
        <span
          v-for="tech in project.technologies"
          :key="tech.id"
          class="text-xs font-bold bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 px-2 py-0.5 border border-neutral-200 dark:border-neutral-700 rounded-lg uppercase tracking-widest flex items-center gap-1"
        >
          <img
            v-if="tech.logo_url"
            :src="tech.logo_url"
            class="w-2.5 h-2.5 object-contain"
            loading="lazy"
          >
          {{ tech.name }}
        </span>
      </div>

      <!-- Actions -->
      <div class="flex items-center justify-end gap-2 pt-3 border-t border-neutral-100 dark:border-neutral-800 mt-auto">
        <TooltipProvider :delay-duration="150">
          <Tooltip>
            <TooltipTrigger as-child>
              <Button
                size="icon"
                :disabled="updatingStatusId === project.id"
                :aria-label="project.is_active ? 'Desactivar proyecto' : 'Activar proyecto'"
                variant="ghost"
                :class="[
                  'size-11 rounded-xl border transition-all [&>svg]:size-3.5',
                  project.is_active
                    ? 'border-status-success/30 text-status-success hover:bg-status-success/10'
                    : 'border-neutral-200 text-neutral-400 hover:bg-neutral-100 dark:border-neutral-700 dark:hover:bg-neutral-800',
                ]"
                @click="$emit('status', project)"
              >
                <Power :class="updatingStatusId === project.id && 'animate-pulse'" />
              </Button>
            </TooltipTrigger>
            <TooltipContent
              side="top"
              :side-offset="8"
            >
              {{ project.is_active ? 'Desactivar proyecto' : 'Activar proyecto' }}
            </TooltipContent>
          </Tooltip>

          <Tooltip>
            <TooltipTrigger as-child>
              <Button
                size="icon"
                variant="outline"
                :aria-label="`Editar ${project.name}`"
                class="size-11 rounded-xl border-neutral-200 bg-white transition-all hover:bg-neutral-100 dark:border-neutral-800 dark:bg-neutral-900 dark:hover:bg-neutral-800 [&>svg]:size-3.5"
                @click="$emit('edit', project)"
              >
                <Pencil />
              </Button>
            </TooltipTrigger>
            <TooltipContent
              side="top"
              :side-offset="8"
            >
              Editar proyecto
            </TooltipContent>
          </Tooltip>

          <Tooltip>
            <TooltipTrigger as-child>
              <Button
                size="icon"
                variant="ghost"
                :aria-label="`Eliminar ${project.name}`"
                class="size-11 rounded-xl border border-status-danger/30 text-status-danger transition-all hover:bg-status-danger/10 [&>svg]:size-3.5"
                @click="$emit('delete', project)"
              >
                <Trash2 />
              </Button>
            </TooltipTrigger>
            <TooltipContent
              side="top"
              :side-offset="8"
            >
              Eliminar proyecto
            </TooltipContent>
          </Tooltip>

          <Tooltip v-if="project.project_url">
            <TooltipTrigger as-child>
              <a
                :href="project.project_url"
                target="_blank"
                rel="noopener noreferrer"
                :aria-label="`Abrir ${project.name} en una pestaña nueva`"
                class="flex size-11 items-center justify-center rounded-xl bg-black text-white transition-transform hover:scale-[1.02] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black focus-visible:ring-offset-2 dark:bg-white dark:text-black dark:focus-visible:ring-white [&>svg]:size-3.5"
              >
                <ExternalLink />
              </a>
            </TooltipTrigger>
            <TooltipContent
              side="top"
              :side-offset="8"
            >
              Abrir proyecto
            </TooltipContent>
          </Tooltip>
        </TooltipProvider>
      </div>
    </div>
  </div>
</template>
