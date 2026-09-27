<template>
  <div class="relative overflow-hidden rounded-3xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-black pointer-events-none select-none">
    <!-- Shimmer sweep -->
    <div class="absolute inset-0 shimmer-sweep z-10" />

    <!-- Image area placeholder -->
    <div class="w-full h-40 bg-neutral-100 dark:bg-neutral-800 skeleton-bg" />

    <!-- Body -->
    <div class="p-4 space-y-4">
      <!-- Badge row -->
      <div class="flex items-center justify-between">
        <div class="h-4 w-16 rounded-lg skeleton-bg" />
        <div class="h-3 w-10 rounded skeleton-bg" />
      </div>

      <!-- Title -->
      <div class="space-y-2">
        <div class="h-4 w-3/4 rounded skeleton-bg" />
        <div class="h-4 w-1/2 rounded skeleton-bg" />
      </div>

      <!-- Description lines -->
      <div class="space-y-1.5 pt-1">
        <div class="h-3 w-full rounded skeleton-bg" />
        <div class="h-3 w-5/6 rounded skeleton-bg" />
      </div>

      <!-- Tech badges -->
      <div class="flex gap-1.5 pt-1">
        <div
          v-for="i in 3"
          :key="i"
          class="h-4 w-10 rounded-lg skeleton-bg"
        />
      </div>

      <!-- Actions row -->
      <div class="flex gap-2 pt-3 border-t border-neutral-100 dark:border-neutral-800">
        <div class="flex-1 h-8 rounded-xl skeleton-bg" />
        <div class="h-8 w-8 rounded-xl skeleton-bg" />
        <div class="h-8 w-8 rounded-xl skeleton-bg" />
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Shimmer classes are global in app.css */
</style>
