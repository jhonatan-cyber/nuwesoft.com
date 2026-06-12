<template>
    <div class="relative bg-white dark:bg-black border-4 border-black dark:border-white shadow-brutalist dark:shadow-brutalist-white overflow-hidden pointer-events-none select-none flex flex-col">
        <!-- Shimmer overlay -->
        <div class="absolute inset-0 shimmer-sweep z-10"></div>

        <!-- Category ribbon skeleton -->
        <div class="absolute top-4 right-4 z-10 w-20 h-6 skeleton-bg"></div>

        <!-- Cover area skeleton -->
        <div class="h-48 skeleton-bg flex items-center justify-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.04]"
                style="background-image: repeating-linear-gradient(0deg, transparent, transparent 2px, #000 2px, #000 3px); background-size: 40px 40px;">
            </div>
        </div>

        <!-- Card body -->
        <div class="p-6 flex flex-col flex-1 gap-4">
            <!-- Meta row -->
            <div class="flex items-center gap-6">
                <div class="h-3 w-28 rounded skeleton-bg"></div>
                <div class="h-3 w-24 rounded skeleton-bg"></div>
            </div>

            <!-- Title -->
            <div class="space-y-2.5">
                <div class="h-5 w-full rounded skeleton-bg"></div>
                <div class="h-5 w-3/4 rounded skeleton-bg"></div>
            </div>

            <!-- Excerpt -->
            <div class="space-y-1.5 flex-1">
                <div class="h-3 w-full rounded skeleton-bg"></div>
                <div class="h-3 w-5/6 rounded skeleton-bg"></div>
                <div class="h-3 w-4/6 rounded skeleton-bg"></div>
            </div>

            <!-- Tags -->
            <div class="flex gap-2">
                <div class="h-5 w-14 rounded skeleton-bg"></div>
                <div class="h-5 w-20 rounded skeleton-bg"></div>
                <div class="h-5 w-12 rounded skeleton-bg"></div>
            </div>

            <!-- Read more -->
            <div class="flex items-center gap-2">
                <div class="h-3 w-24 rounded skeleton-bg"></div>
                <div class="h-3 w-3 skeleton-bg"></div>
            </div>
        </div>
    </div>
</template>


