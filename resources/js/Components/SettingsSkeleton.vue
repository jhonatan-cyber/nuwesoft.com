<template>
  <div
    class="space-y-8"
  >
    <div
      v-for="i in 3"
      :key="'card-' + i"
      class="rounded-3xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-black overflow-hidden pointer-events-none select-none relative"
    >
      <div class="absolute inset-0 shimmer-sweep z-10" />
      <div class="p-6 border-b border-neutral-100 dark:border-neutral-800">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl skeleton-bg shrink-0" />
          <div class="space-y-2 flex-1">
            <div class="h-5 w-48 rounded skeleton-bg" />
            <div class="h-3 w-32 rounded skeleton-bg" />
          </div>
        </div>
      </div>
      <div class="p-6 space-y-5">
        <div
          v-for="j in 3"
          :key="'field-' + j"
          class="space-y-2"
        >
          <div class="h-3 w-24 rounded skeleton-bg" />
          <div class="h-12 rounded-xl skeleton-bg" />
        </div>
      </div>
    </div>
    <div class="flex justify-end">
      <div class="h-14 w-36 rounded-xl skeleton-bg" />
    </div>
  </div>
</template>
