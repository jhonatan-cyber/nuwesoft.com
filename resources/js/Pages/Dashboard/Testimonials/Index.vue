<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { ref } from 'vue'
import { useSkeletonLoader } from '@/composables/useSkeletonLoader'
import { Star, Plus, Edit, Trash2, Quote, X, Check, Ban, Filter } from 'lucide-vue-next'
import ConfirmDialog from '@/Components/ConfirmDialog.vue'

const { t } = useI18n()

const { skeletonReady } = useSkeletonLoader()

const props = defineProps({
    testimonials: { type: Object, default: () => ({}) },
    pendingCount: { type: Number, default: 0 },
    currentStatus: { type: String, default: 'all' },
    currentRating: { type: [String, Number, null], default: null },
})

const ratingFilters = [
    { value: null, label: 'TODAS' },
    { value: 5, label: '5★' },
    { value: 4, label: '4★' },
    { value: 3, label: '3★' },
    { value: 2, label: '2★' },
    { value: 1, label: '1★' },
]

const filterByRating = (rating) => {
    const params = { status: props.currentStatus }
    if (rating !== null) params.rating = rating
    router.get(route('testimonials.index', params), {}, { preserveState: true })
}

const filterByStatus = (status) => {
    const params = { status }
    if (props.currentRating !== null) params.rating = props.currentRating
    router.get(route('testimonials.index', params), {}, { preserveState: true })
}

const statusFilters = [
    { value: 'all', label: 'TODOS' },
    { value: 'pending', label: 'PENDIENTES' },
    { value: 'approved', label: 'APROBADOS' },
    { value: 'rejected', label: 'RECHAZADOS' },
]

const approveTestimonial = (id) => {
    router.post(route('testimonials.approve', id))
}

const rejectTestimonial = (id) => {
    router.post(route('testimonials.reject', id))
}

const showForm = ref(false)

useRekaCleanup(showForm)

const editingTestimonial = ref(null)

const form = useForm({
    client_name: '',
    client_role: '',
    client_company: '',
    content: '',
    rating: 5,
    status: 'approved',
    is_active: true,
    sort_order: 0,
})

const openCreate = () => {
    editingTestimonial.value = null
    form.reset()
    form.clearErrors()
    showForm.value = true
}

const openEdit = (testimonial) => {
    editingTestimonial.value = testimonial
    form.client_name = testimonial.client_name
    form.client_role = testimonial.client_role || ''
    form.client_company = testimonial.client_company || ''
    form.content = testimonial.content
    form.rating = testimonial.rating
    form.status = testimonial.status || 'approved'
    form.is_active = testimonial.is_active
    form.sort_order = testimonial.sort_order
    showForm.value = true
}

const closeForm = () => {
    showForm.value = false
    editingTestimonial.value = null
}

const submit = () => {
    if (editingTestimonial.value) {
        form.put(route('testimonials.update', editingTestimonial.value.id), {
            onSuccess: () => closeForm(),
        })
    } else {
        form.post(route('testimonials.store'), {
            onSuccess: () => closeForm(),
        })
    }
}

// ── Delete confirmation ──
const isDeleteOpen = ref(false)
const deleteTarget = ref(null)
const isDeleting = ref(false)

const openDelete = (testimonial) => {
    deleteTarget.value = testimonial
    isDeleteOpen.value = true
}

const confirmDelete = () => {
    if (!deleteTarget.value) return
    isDeleting.value = true
    form.delete(route('testimonials.destroy', deleteTarget.value.id), {
        onFinish: () => {
            isDeleting.value = false
            isDeleteOpen.value = false
            deleteTarget.value = null
        },
    })
}
</script>

<template>
    <Head title="Testimonios | Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-display font-bold tracking-tight text-neutral-900 dark:text-white uppercase italic">
                        TESTIMONIOS
                    </h2>
                    <p class="text-xs font-bold text-neutral-400 uppercase tracking-[0.2em] mt-1">
                        CLIENTES / FEEDBACK
                        <span v-if="pendingCount > 0"
                            class="ml-2 inline-flex items-center justify-center w-5 h-5 text-xs font-black bg-brutalist-pink text-white rounded-full">
                            {{ pendingCount }}
                        </span>
                    </p>
                </div>
                <button @click="openCreate"
                    class="flex items-center gap-2 bg-black dark:bg-white text-white dark:text-black px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-neutral-800 dark:hover:bg-neutral-200 transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2">
                    <Plus class="w-4 h-4" />
                    NUEVO TESTIMONIO
                </button>
            </div>
        </template>

        <div class="space-y-6">
            <Transition name="fade" mode="out-in">
                <div v-if="!skeletonReady" key="skeleton" class="space-y-4">
                    <div v-for="i in 4" :key="'skel-' + i"
                        class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-2xl p-6 overflow-hidden relative pointer-events-none select-none">
                        <div class="absolute inset-0 shimmer-sweep z-10"></div>
                        <div class="relative z-20 space-y-3">
                            <div class="flex items-center gap-1.5">
                                <div v-for="s in 5" :key="s" class="w-4 h-4 rounded skeleton-bg"></div>
                            </div>
                            <div class="h-4 w-full skeleton-bg"></div>
                            <div class="h-4 w-5/6 skeleton-bg"></div>
                            <div class="flex items-center gap-3">
                                <div class="h-3 w-32 skeleton-bg"></div>
                                <div class="h-3 w-24 skeleton-bg"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else key="content">
                    <!-- Filters -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
                        <!-- Status -->
                        <div class="flex items-center gap-2">
                            <Filter class="w-4 h-4 text-neutral-400 flex-shrink-0" />
                            <button v-for="filter in statusFilters" :key="filter.value"
                                @click="filterByStatus(filter.value)"
                                :class="[
                                    'px-3 py-1.5 text-xs font-bold uppercase tracking-wider rounded-lg transition-all',
                                    currentStatus === filter.value
                                        ? 'bg-black dark:bg-white text-white dark:text-black'
                                        : 'bg-neutral-100 dark:bg-neutral-800 text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300'
                                ]">
                                {{ filter.label }}
                                <span v-if="filter.value === 'pending' && pendingCount > 0"
                                    class="ml-1 text-brutalist-pink">({{ pendingCount }})</span>
                            </button>
                        </div>

                        <span class="hidden sm:block w-px h-4 bg-neutral-200 dark:bg-neutral-700"></span>

                        <!-- Rating -->
                        <div class="flex items-center gap-1.5">
                            <Star class="w-4 h-4 text-neutral-400 flex-shrink-0" />
                            <button v-for="filter in ratingFilters" :key="String(filter.value)"
                                @click="filterByRating(filter.value)"
                                :class="[
                                    'px-2.5 py-1.5 text-xs font-bold tracking-wider rounded-lg transition-all flex items-center gap-1',
                                    (currentRating === null && filter.value === null) || Number(currentRating) === filter.value
                                        ? 'bg-brutalist-yellow text-black'
                                        : 'bg-neutral-100 dark:bg-neutral-800 text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300'
                                ]">
                                <template v-if="filter.value !== null">
                                    <Star class="w-3 h-3 fill-current" />
                                </template>
                                {{ filter.label }}
                            </button>
                        </div>
                    </div>

                    <!-- List -->
                    <div v-if="testimonials.data?.length" class="grid gap-4">
                <div v-for="item in testimonials.data" :key="item.id"
                    class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <Quote class="w-5 h-5 text-brutalist-pink" />
                                <span class="flex items-center gap-0.5">
                                    <Star v-for="i in 5" :key="i"
                                        :class="i <= item.rating ? 'text-brutalist-yellow fill-brutalist-yellow' : 'text-neutral-300'"
                                        class="w-4 h-4" />
                                </span>
                                <span v-if="item.status === 'pending'"
                                    class="rounded bg-status-warning/10 px-2 py-0.5 text-xs font-bold uppercase tracking-wider text-status-warning">
                                    PENDIENTE
                                </span>
                                <span v-else-if="item.status === 'approved'"
                                    class="rounded bg-status-success/10 px-2 py-0.5 text-xs font-bold uppercase tracking-wider text-status-success">
                                    APROBADO
                                </span>
                                <span v-else-if="item.status === 'rejected'"
                                    class="rounded bg-status-danger/10 px-2 py-0.5 text-xs font-bold uppercase tracking-wider text-status-danger">
                                    RECHAZADO
                                </span>
                                <span v-if="!item.is_active"
                                    class="px-2 py-0.5 bg-neutral-100 dark:bg-neutral-800 text-neutral-400 text-xs font-bold uppercase tracking-wider rounded">
                                    INACTIVO
                                </span>
                            </div>
                            <p class="text-sm font-bold leading-relaxed text-neutral-700 dark:text-neutral-300 italic mb-3">
                                "{{ item.content }}"
                            </p>
                            <div class="flex items-center gap-3 text-xs">
                                <span class="font-bold uppercase text-neutral-900 dark:text-white">{{ item.client_name }}</span>
                                <span v-if="item.client_role" class="text-neutral-400">— {{ item.client_role }}</span>
                                <span v-if="item.client_company" class="text-neutral-400">{{ item.client_company }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 ml-4">
                            <button v-if="item.status === 'pending'" @click="approveTestimonial(item.id)"
                                class="rounded-xl border border-status-success/30 p-2 text-status-success transition-all hover:bg-status-success/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-status-success focus-visible:ring-offset-2"
                                title="Aprobar">
                                <Check class="w-4 h-4" />
                            </button>
                            <button v-if="item.status === 'pending'" @click="rejectTestimonial(item.id)"
                                class="rounded-xl border border-status-danger/30 p-2 text-status-danger transition-all hover:bg-status-danger/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-status-danger focus-visible:ring-offset-2"
                                title="Rechazar">
                                <Ban class="w-4 h-4" />
                            </button>
                            <button @click="openEdit(item)"
                                class="p-2 border border-neutral-200 dark:border-neutral-700 rounded-xl hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2">
                                <Edit class="w-4 h-4" />
                            </button>
                            <button @click="openDelete(item)"
                                class="rounded-xl border border-status-danger/30 p-2 text-status-danger transition-all hover:bg-status-danger/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-status-danger focus-visible:ring-offset-2">
                                <Trash2 class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else
                class="bg-white dark:bg-black border border-dashed border-neutral-200 dark:border-neutral-800 rounded-2xl p-12 text-center">
                <Quote class="w-12 h-12 text-neutral-300 dark:text-neutral-600 mx-auto mb-4" />
                <p class="text-lg font-bold uppercase tracking-tight text-neutral-400">NO HAY TESTIMONIOS</p>
                <p class="text-xs font-bold uppercase tracking-widest text-neutral-400 mt-2">
                    Agregá el primer feedback de cliente
                </p>
                <button @click="openCreate"
                    class="inline-flex items-center gap-2 mt-6 bg-black dark:bg-white text-white dark:text-black px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest">
                    <Plus class="w-4 h-4" />
                    AGREGAR TESTIMONIO
                </button>
            </div>

            <Dialog v-model:open="showForm">
                <DialogContent class="dashboard-dialog-enter sm:max-w-lg !rounded-none !border-4 border-black dark:border-white !bg-white dark:!bg-black p-6 sm:p-8 shadow-brutalist dark:shadow-brutalist-white">
                    <DialogClose class="absolute right-4 top-4 rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100 disabled:pointer-events-none">
                        <X class="h-5 w-5" />
                        <span class="sr-only">Cerrar</span>
                    </DialogClose>

                    <DialogHeader class="mb-6 pr-8">
                        <DialogTitle class="text-2xl font-black uppercase italic text-neutral-900 dark:text-white">
                            {{ editingTestimonial ? 'EDITAR TESTIMONIO' : 'NUEVO TESTIMONIO' }}
                        </DialogTitle>
                        <DialogDescription class="text-xs font-bold uppercase tracking-widest text-neutral-400">
                            {{ editingTestimonial ? 'Editá los datos del testimonio' : 'Agregá un nuevo testimonio de cliente' }}
                        </DialogDescription>
                    </DialogHeader>

                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="text-xs font-bold uppercase tracking-widest text-neutral-500">CLIENTE</label>
                            <input v-model="form.client_name" required
                                class="w-full bg-transparent border-2 border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3 font-bold uppercase text-xs focus:border-black dark:focus:border-white focus:outline-none" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold uppercase tracking-widest text-neutral-500">ROL</label>
                                <input v-model="form.client_role"
                                    class="w-full bg-transparent border-2 border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3 font-bold uppercase text-xs focus:border-black dark:focus:border-white focus:outline-none" />
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase tracking-widest text-neutral-500">EMPRESA</label>
                                <input v-model="form.client_company"
                                    class="w-full bg-transparent border-2 border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3 font-bold uppercase text-xs focus:border-black dark:focus:border-white focus:outline-none" />
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold uppercase tracking-widest text-neutral-500">TEXTO</label>
                            <textarea v-model="form.content" required rows="4"
                                class="w-full bg-transparent border-2 border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3 font-bold uppercase text-xs focus:border-black dark:focus:border-white focus:outline-none resize-none"></textarea>
                        </div>
                        <div>
                            <label class="text-xs font-bold uppercase tracking-widest text-neutral-500">RATING (1-5)</label>
                            <select v-model="form.rating"
                                class="w-full bg-transparent border-2 border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3 font-bold uppercase text-xs">
                                <option :value="i" v-for="i in 5" :key="i">{{ i }} estrella{{ i > 1 ? 's' : '' }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold uppercase tracking-widest text-neutral-500">ESTADO</label>
                            <select v-model="form.status"
                                class="w-full bg-transparent border-2 border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3 font-bold uppercase text-xs">
                                <option value="pending">PENDIENTE</option>
                                <option value="approved">APROBADO</option>
                                <option value="rejected">RECHAZADO</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" v-model="form.is_active" id="form_active"
                                class="w-5 h-5 border-2 border-neutral-300 rounded" />
                            <label for="form_active" class="text-xs font-bold uppercase tracking-wider text-neutral-500">VISIBLE EN WEB</label>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="submit" :disabled="form.processing"
                                class="flex-1 bg-black dark:bg-white text-white dark:text-black py-3 rounded-xl font-bold text-xs uppercase tracking-widest disabled:opacity-50">
                                {{ form.processing ? 'GUARDANDO...' : 'GUARDAR' }}
                            </button>
                            <button type="button" @click="closeForm"
                                class="px-6 py-3 border-2 border-neutral-300 rounded-xl font-bold text-xs uppercase tracking-widest text-neutral-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2">
                                CANCELAR
                            </button>
                        </div>
                    </form>
                </DialogContent>
            </Dialog>

            <!-- Delete Confirmation -->
            <ConfirmDialog
                v-model:open="isDeleteOpen"
                :description="t('messages.actions.delete_confirm', { name: deleteTarget?.client_name })"
                :loading="isDeleting"
                @confirm="confirmDelete"
            />
                </div>
            </Transition>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.font-display { font-family: 'Space Grotesk', sans-serif; }
</style>
