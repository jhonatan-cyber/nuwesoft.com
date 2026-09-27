<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { ref } from 'vue'
import { useSkeletonLoader } from '@/composables/useSkeletonLoader'
import { useRekaCleanup } from '@/composables/useRekaCleanup'
import { Plus, Quote, X } from 'lucide-vue-next'
import ConfirmDialog from '@/Components/ConfirmDialog.vue'
import TestimonialFilters from '@/Components/TestimonialFilters.vue'
import TestimonialItemCard from '@/Components/TestimonialItemCard.vue'

const { t } = useI18n()

const { skeletonReady } = useSkeletonLoader()

defineProps({
    testimonials: { type: Object, default: () => ({}) },
    pendingCount: { type: Number, default: 0 },
    currentStatus: { type: String, default: 'all' },
    currentRating: { type: [String, Number, null], default: null },
})

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
            <span
              v-if="pendingCount > 0"
              class="ml-2 inline-flex items-center justify-center w-5 h-5 text-xs font-black bg-brutalist-pink text-white rounded-full"
            >
              {{ pendingCount }}
            </span>
          </p>
        </div>
        <button
          class="flex items-center gap-2 bg-black dark:bg-white text-white dark:text-black px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-neutral-800 dark:hover:bg-neutral-200 transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2"
          @click="openCreate"
        >
          <Plus class="w-4 h-4" />
          NUEVO TESTIMONIO
        </button>
      </div>
    </template>

    <div class="space-y-6">
      <Transition
        name="fade"
        mode="out-in"
      >
        <div
          v-if="!skeletonReady"
          key="skeleton"
          class="space-y-4"
        >
          <div
            v-for="i in 4"
            :key="'skel-' + i"
            class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-2xl p-6 overflow-hidden relative pointer-events-none select-none"
          >
            <div class="absolute inset-0 shimmer-sweep z-10" />
            <div class="relative z-20 space-y-3">
              <div class="flex items-center gap-1.5">
                <div
                  v-for="s in 5"
                  :key="s"
                  class="w-4 h-4 rounded skeleton-bg"
                />
              </div>
              <div class="h-4 w-full skeleton-bg" />
              <div class="h-4 w-5/6 skeleton-bg" />
              <div class="flex items-center gap-3">
                <div class="h-3 w-32 skeleton-bg" />
                <div class="h-3 w-24 skeleton-bg" />
              </div>
            </div>
          </div>
        </div>

        <div
          v-else
          key="content"
        >
          <TestimonialFilters
            :current-status="currentStatus"
            :current-rating="currentRating"
            :pending-count="pendingCount"
          />

          <!-- List -->
          <div
            v-if="testimonials.data?.length"
            class="grid gap-4"
          >
            <TestimonialItemCard
              v-for="item in testimonials.data"
              :key="item.id"
              :item="item"
              @approve="approveTestimonial"
              @reject="rejectTestimonial"
              @edit="openEdit"
              @delete="openDelete"
            />
          </div>

          <div
            v-else
            class="bg-white dark:bg-black border border-dashed border-neutral-200 dark:border-neutral-800 rounded-2xl p-12 text-center"
          >
            <Quote class="w-12 h-12 text-neutral-300 dark:text-neutral-600 mx-auto mb-4" />
            <p class="text-lg font-bold uppercase tracking-tight text-neutral-400">
              NO HAY TESTIMONIOS
            </p>
            <p class="text-xs font-bold uppercase tracking-widest text-neutral-400 mt-2">
              Agregá el primer feedback de cliente
            </p>
            <button
              class="inline-flex items-center gap-2 mt-6 bg-black dark:bg-white text-white dark:text-black px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest"
              @click="openCreate"
            >
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

              <form
                class="space-y-4"
                @submit.prevent="submit"
              >
                <div>
                  <label class="text-xs font-bold uppercase tracking-widest text-neutral-500">CLIENTE</label>
                  <input
                    v-model="form.client_name"
                    required
                    class="w-full bg-transparent border-2 border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3 font-bold uppercase text-xs focus:border-black dark:focus:border-white focus:outline-none"
                  >
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="text-xs font-bold uppercase tracking-widest text-neutral-500">ROL</label>
                    <input
                      v-model="form.client_role"
                      class="w-full bg-transparent border-2 border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3 font-bold uppercase text-xs focus:border-black dark:focus:border-white focus:outline-none"
                    >
                  </div>
                  <div>
                    <label class="text-xs font-bold uppercase tracking-widest text-neutral-500">EMPRESA</label>
                    <input
                      v-model="form.client_company"
                      class="w-full bg-transparent border-2 border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3 font-bold uppercase text-xs focus:border-black dark:focus:border-white focus:outline-none"
                    >
                  </div>
                </div>
                <div>
                  <label class="text-xs font-bold uppercase tracking-widest text-neutral-500">TEXTO</label>
                  <textarea
                    v-model="form.content"
                    required
                    rows="4"
                    class="w-full bg-transparent border-2 border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3 font-bold uppercase text-xs focus:border-black dark:focus:border-white focus:outline-none resize-none"
                  />
                </div>
                <div>
                  <label class="text-xs font-bold uppercase tracking-widest text-neutral-500">RATING (1-5)</label>
                  <select
                    v-model="form.rating"
                    class="w-full bg-transparent border-2 border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3 font-bold uppercase text-xs"
                  >
                    <option
                      v-for="i in 5"
                      :key="i"
                      :value="i"
                    >
                      {{ i }} estrella{{ i > 1 ? 's' : '' }}
                    </option>
                  </select>
                </div>
                <div>
                  <label class="text-xs font-bold uppercase tracking-widest text-neutral-500">ESTADO</label>
                  <select
                    v-model="form.status"
                    class="w-full bg-transparent border-2 border-neutral-200 dark:border-neutral-700 rounded-xl px-4 py-3 font-bold uppercase text-xs"
                  >
                    <option value="pending">
                      PENDIENTE
                    </option>
                    <option value="approved">
                      APROBADO
                    </option>
                    <option value="rejected">
                      RECHAZADO
                    </option>
                  </select>
                </div>
                <div class="flex items-center gap-3">
                  <input
                    id="form_active"
                    v-model="form.is_active"
                    type="checkbox"
                    class="w-5 h-5 border-2 border-neutral-300 rounded"
                  >
                  <label
                    for="form_active"
                    class="text-xs font-bold uppercase tracking-wider text-neutral-500"
                  >VISIBLE EN WEB</label>
                </div>

                <div class="flex gap-4 pt-4">
                  <button
                    type="submit"
                    :disabled="form.processing"
                    class="flex-1 bg-black dark:bg-white text-white dark:text-black py-3 rounded-xl font-bold text-xs uppercase tracking-widest disabled:opacity-50"
                  >
                    {{ form.processing ? 'GUARDANDO...' : 'GUARDAR' }}
                  </button>
                  <button
                    type="button"
                    class="px-6 py-3 border-2 border-neutral-300 rounded-xl font-bold text-xs uppercase tracking-widest text-neutral-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2"
                    @click="closeForm"
                  >
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
