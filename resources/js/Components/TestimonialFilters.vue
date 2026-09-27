<script setup>
import { router } from '@inertiajs/vue3'
import { Filter, Star } from 'lucide-vue-next'

const props = defineProps({
    currentStatus: { type: String, default: 'all' },
    currentRating: { type: [String, Number, null], default: null },
    pendingCount: { type: Number, default: 0 },
})

const statusFilters = [
    { value: 'all', label: 'TODOS' },
    { value: 'pending', label: 'PENDIENTES' },
    { value: 'approved', label: 'APROBADOS' },
    { value: 'rejected', label: 'RECHAZADOS' },
]

const ratingFilters = [
    { value: null, label: 'TODAS' },
    { value: 5, label: '5★' },
    { value: 4, label: '4★' },
    { value: 3, label: '3★' },
    { value: 2, label: '2★' },
    { value: 1, label: '1★' },
]

const filterByStatus = (status) => {
    const params = { status }
    if (props.currentRating !== null) params.rating = props.currentRating
    router.get(route('testimonials.index', params), {}, { preserveState: true })
}

const filterByRating = (rating) => {
    const params = { status: props.currentStatus }
    if (rating !== null) params.rating = rating
    router.get(route('testimonials.index', params), {}, { preserveState: true })
}
</script>

<template>
  <!-- Filters -->
  <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
    <!-- Status -->
    <div class="flex items-center gap-2">
      <Filter class="w-4 h-4 text-neutral-400 flex-shrink-0" />
      <button
        v-for="filter in statusFilters"
        :key="filter.value"
        :class="[
          'px-3 py-1.5 text-xs font-bold uppercase tracking-wider rounded-lg transition-all',
          currentStatus === filter.value
            ? 'bg-black dark:bg-white text-white dark:text-black'
            : 'bg-neutral-100 dark:bg-neutral-800 text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300'
        ]"
        @click="filterByStatus(filter.value)"
      >
        {{ filter.label }}
        <span
          v-if="filter.value === 'pending' && pendingCount > 0"
          class="ml-1 text-brutalist-pink"
        >({{ pendingCount }})</span>
      </button>
    </div>

    <span class="hidden sm:block w-px h-4 bg-neutral-200 dark:bg-neutral-700" />

    <!-- Rating -->
    <div class="flex items-center gap-1.5">
      <Star class="w-4 h-4 text-neutral-400 flex-shrink-0" />
      <button
        v-for="filter in ratingFilters"
        :key="String(filter.value)"
        :class="[
          'px-2.5 py-1.5 text-xs font-bold tracking-wider rounded-lg transition-all flex items-center gap-1',
          (currentRating === null && filter.value === null) || Number(currentRating) === filter.value
            ? 'bg-brutalist-yellow text-black'
            : 'bg-neutral-100 dark:bg-neutral-800 text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300'
        ]"
        @click="filterByRating(filter.value)"
      >
        <template v-if="filter.value !== null">
          <Star class="w-3 h-3 fill-current" />
        </template>
        {{ filter.label }}
      </button>
    </div>
  </div>
</template>
