<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { ArrowLeft, Save, Eye, Edit3 } from 'lucide-vue-next'
import { ref } from 'vue'
import MarkdownPreview from '@/Components/MarkdownPreview.vue'

const { t } = useI18n()

const props = defineProps({
    post: { type: Object, default: null },
})

const form = useForm({
    title: props.post?.title || '',
    excerpt: props.post?.excerpt || '',
    content: props.post?.content || '',
    category: props.post?.category || 'case-study',
    tags: props.post?.tags || [],
    is_published: props.post?.is_published ?? true,
    author_name: props.post?.author_name || 'NUWESOFT',
})

const isEditing = !!props.post

const submit = () => {
    if (isEditing) {
        form.put(route('posts.update', props.post.id))
    } else {
        form.post(route('posts.store'))
    }
}

const previewMode = ref(false)
const tagInput = ref('')
const addTag = () => {
    if (tagInput.value && !form.tags.includes(tagInput.value)) {
        form.tags = [...form.tags, tagInput.value]
        tagInput.value = ''
    }
}
const removeTag = (tag) => {
    form.tags = form.tags.filter(t => t !== tag)
}
</script>

<template>
    <Head :title="isEditing ? 'Editar Post' : 'Nuevo Post'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link :href="route('posts.index')"
                        class="p-2 border border-neutral-200 dark:border-neutral-700 rounded-xl hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-all">
                        <ArrowLeft class="w-4 h-4" />
                    </Link>
                    <div>
                        <h2 class="text-3xl font-display font-bold tracking-tight text-neutral-900 dark:text-white uppercase italic">
                            {{ isEditing ? 'EDITAR POST' : 'NUEVO POST' }}
                        </h2>
                        <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-[0.2em] mt-1">
                            {{ isEditing ? 'ACTUALIZAR' : 'CREAR' }} CASO DE ESTUDIO / ARTÍCULO
                        </p>
                    </div>
                </div>
                <button @click="submit"
                    :disabled="form.processing"
                    class="flex items-center gap-2 bg-black dark:bg-white text-white dark:text-black px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-neutral-800 dark:hover:bg-neutral-200 transition-all disabled:opacity-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2">
                    <Save class="w-4 h-4" />
                    {{ form.processing ? 'GUARDANDO...' : 'GUARDAR' }}
                </button>
            </div>
        </template>

        <div class="max-w-4xl">
            <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-2xl p-8 shadow-sm space-y-8">
                <!-- Title -->
                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">TÍTULO</label>
                    <input v-model="form.title"
                        class="w-full bg-transparent border-2 border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3 text-lg font-bold uppercase tracking-tight focus:border-black dark:focus:border-white focus:outline-none transition-colors"
                        placeholder="EJ. PLATAFORMA DE GESTIÓN LOGÍSTICA" />
                    <p v-if="form.errors.title" class="text-[10px] font-bold text-red-500">{{ form.errors.title }}</p>
                </div>

                <!-- Category + Author -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">CATEGORÍA</label>
                        <select v-model="form.category"
                            class="w-full bg-transparent border-2 border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3 font-bold uppercase text-xs tracking-wider focus:border-black dark:focus:border-white focus:outline-none transition-colors">
                            <option value="case-study">CASO DE ESTUDIO</option>
                            <option value="technical">TÉCNICO</option>
                            <option value="news">NOTICIAS</option>
                            <option value="insights">INSIGHTS</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">AUTOR</label>
                        <input v-model="form.author_name"
                            class="w-full bg-transparent border-2 border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3 font-bold uppercase text-xs focus:border-black dark:focus:border-white focus:outline-none transition-colors"
                            placeholder="NUWESOFT" />
                    </div>
                </div>

                <!-- Excerpt -->
                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">EXTRACTO</label>
                    <textarea v-model="form.excerpt" rows="2"
                        class="w-full bg-transparent border-2 border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3 font-bold uppercase text-xs focus:border-black dark:focus:border-white focus:outline-none transition-colors resize-none"
                        placeholder="BREVE DESCRIPCIÓN DEL ARTÍCULO..."></textarea>
                </div>

                <!-- Content with Live Preview -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">CONTENIDO (MARKDOWN)</label>
                        <!-- Tab switcher -->
                        <div class="flex items-center gap-1 bg-neutral-100 dark:bg-neutral-800 rounded-xl p-1 border border-neutral-200 dark:border-neutral-700">
                            <button
                                type="button"
                                @click="previewMode = false"
                                :class="[
                                    'flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all',
                                    !previewMode
                                        ? 'bg-white dark:bg-black text-black dark:text-white shadow-sm'
                                        : 'text-neutral-400 hover:text-black dark:hover:text-white'
                                ]"
                            >
                                <Edit3 class="w-3 h-3" />
                                EDITAR
                            </button>
                            <button
                                type="button"
                                @click="previewMode = true"
                                :class="[
                                    'flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all',
                                    previewMode
                                        ? 'bg-white dark:bg-black text-black dark:text-white shadow-sm'
                                        : 'text-neutral-400 hover:text-black dark:hover:text-white'
                                ]"
                            >
                                <Eye class="w-3 h-3" />
                                PREVIEW
                            </button>
                        </div>
                    </div>

                    <Transition name="fade" mode="out-in">
                        <!-- Editor -->
                        <textarea
                            v-if="!previewMode"
                            v-model="form.content"
                            rows="15"
                            class="w-full bg-transparent border-2 border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3 font-mono text-sm focus:border-black dark:focus:border-white focus:outline-none transition-colors"
                            placeholder="# Título&#10;&#10;Contenido del artículo..."
                        ></textarea>

                        <!-- Live Preview -->
                        <MarkdownPreview
                            v-else
                            :content="form.content"
                        />
                    </Transition>
                </div>

                <!-- Tags -->
                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">TAGS</label>
                    <div class="flex items-center gap-2">
                        <input v-model="tagInput" @keydown.enter.prevent="addTag"
                            class="flex-1 bg-transparent border-2 border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3 font-bold uppercase text-xs focus:border-black dark:focus:border-white focus:outline-none transition-colors"
                            placeholder="AGREGAR TAG..." />
                        <button @click="addTag"
                            class="px-4 py-3 bg-black dark:bg-white text-white dark:text-black rounded-xl font-bold text-xs uppercase tracking-widest focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2">
                            +
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span v-for="tag in form.tags" :key="tag"
                            class="flex items-center gap-1 px-3 py-1 bg-neutral-100 dark:bg-neutral-800 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                            {{ tag }}
                            <button @click="removeTag(tag)" class="text-red-500 hover:text-red-700 ml-1 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 rounded">&times;</button>
                        </span>
                    </div>
                </div>

                <!-- Published -->
                <div class="flex items-center gap-3">
                    <input type="checkbox" v-model="form.is_published" id="is_published"
                        class="w-5 h-5 border-2 border-neutral-300 rounded" />
                    <label for="is_published" class="text-xs font-bold uppercase tracking-wider text-neutral-500">
                        PUBLICAR INMEDIATAMENTE
                    </label>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.font-display { font-family: 'Space Grotesk', sans-serif; }
</style>
