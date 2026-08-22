<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { ref } from 'vue'
import { useSkeletonLoader } from '@/composables/useSkeletonLoader'
import { Plus, FileText, Edit, Trash2, Eye, EyeOff } from 'lucide-vue-next'
import ConfirmDialog from '@/Components/ConfirmDialog.vue'

const { t } = useI18n()

const { skeletonReady } = useSkeletonLoader()

defineProps({
    posts: { type: Object, default: () => ({}) },
})

// ── Delete confirmation ──
const isDeleteOpen = ref(false)
const deleteTarget = ref(null)
const isDeleting = ref(false)

const openDelete = (post) => {
    deleteTarget.value = post
    isDeleteOpen.value = true
}

const confirmDelete = () => {
    if (!deleteTarget.value) return
    isDeleting.value = true
    router.delete(route('posts.destroy', deleteTarget.value.id), {
        onFinish: () => {
            isDeleting.value = false
            isDeleteOpen.value = false
            deleteTarget.value = null
        },
    })
}
</script>

<template>
    <Head title="Blog | Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-display font-bold tracking-tight text-neutral-900 dark:text-white uppercase italic">
                        BLOG / CASOS DE ESTUDIO
                    </h2>
                    <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-[0.2em] mt-1">
                        GESTIÓN DE CONTENIDO
                    </p>
                </div>
                <Link :href="route('posts.create')"
                    class="flex items-center gap-2 bg-black dark:bg-white text-white dark:text-black px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-neutral-800 dark:hover:bg-neutral-200 transition-all">
                    <Plus class="w-4 h-4" />
                    NUEVO POST
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <Transition name="fade" mode="out-in">
                <div v-if="!skeletonReady" key="skeleton" class="space-y-4">
                    <div v-for="i in 5" :key="'skel-' + i"
                        class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-2xl p-6 overflow-hidden relative pointer-events-none select-none">
                        <div class="absolute inset-0 shimmer-sweep z-10"></div>
                        <div class="relative z-20 space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded skeleton-bg"></div>
                                <div class="h-3 w-24 skeleton-bg"></div>
                                <div class="h-4 w-14 rounded skeleton-bg"></div>
                            </div>
                            <div class="h-6 w-3/4 skeleton-bg"></div>
                            <div class="h-3 w-full skeleton-bg"></div>
                            <div class="flex items-center gap-4">
                                <div class="h-3 w-28 skeleton-bg"></div>
                                <div class="h-3 w-20 skeleton-bg"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else key="content">
                    <div v-if="posts.data?.length" class="grid gap-4">
                <div v-for="post in posts.data" :key="post.id"
                    class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <FileText class="w-5 h-5 text-neutral-400" />
                                <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-500">
                                    {{ post.category || 'Sin categoría' }}
                                </span>
                                <span v-if="post.is_published"
                                    class="px-2 py-0.5 bg-emerald-500/10 text-emerald-600 text-[9px] font-bold uppercase tracking-wider rounded">
                                    <Eye class="w-3 h-3 inline-block mr-1" />
                                    PUBLICADO
                                </span>
                                <span v-else
                                    class="px-2 py-0.5 bg-neutral-100 dark:bg-neutral-800 text-neutral-400 text-[9px] font-bold uppercase tracking-wider rounded">
                                    <EyeOff class="w-3 h-3 inline-block mr-1" />
                                    BORRADOR
                                </span>
                            </div>
                            <h3 class="text-lg md:text-xl font-bold uppercase tracking-tight text-neutral-900 dark:text-white break-words">
                                {{ post.title }}
                            </h3>
                            <p v-if="post.excerpt" class="mt-1 text-xs text-neutral-500 dark:text-neutral-400 line-clamp-2">
                                {{ post.excerpt }}
                            </p>
                            <div class="flex items-center gap-4 mt-3 text-[10px] font-bold uppercase tracking-wider text-neutral-400">
                                <span>{{ post.author_name }}</span>
                                <span>{{ post.created_at }}</span>
                                <span v-if="post.tags?.length">{{ post.tags.join(', ') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 ml-4">
                            <Link :href="route('posts.edit', post.id)"
                                class="p-2 border border-neutral-200 dark:border-neutral-700 rounded-xl hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-all">
                                <Edit class="w-4 h-4" />
                            </Link>
                            <button @click="openDelete(post)"
                                class="p-2 border border-neutral-200 dark:border-neutral-700 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-all text-red-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2">
                                <Trash2 class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else
                class="bg-white dark:bg-black border border-dashed border-neutral-200 dark:border-neutral-800 rounded-2xl p-12 text-center">
                <FileText class="w-12 h-12 text-neutral-300 dark:text-neutral-600 mx-auto mb-4" />
                <p class="text-lg font-bold uppercase tracking-tight text-neutral-400">NO HAY POSTS</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 mt-2">
                    Comenzá escribiendo tu primer caso de estudio o artículo
                </p>
                <Link :href="route('posts.create')"
                    class="inline-flex items-center gap-2 mt-6 bg-black dark:bg-white text-white dark:text-black px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest">
                    <Plus class="w-4 h-4" />
                    CREAR PRIMER POST
                </Link>
            </div>
                </div>
            </Transition>
        </div>

        <!-- Delete Confirmation -->
        <ConfirmDialog
            v-model:open="isDeleteOpen"
            :description="t('messages.actions.delete_confirm', { name: deleteTarget?.title })"
            :loading="isDeleting"
            @confirm="confirmDelete"
        />
    </AuthenticatedLayout>
</template>

<style>
.font-display { font-family: 'Space Grotesk', sans-serif; }
</style>
