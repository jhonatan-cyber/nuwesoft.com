<template>
    <div class="group relative overflow-hidden pointer-events-none select-none">
        <!-- Shimmer overlay (global) -->
        <div class="absolute inset-0 shimmer-sweep z-10"></div>

        <!-- Offset Background Layer -->
        <div class="absolute inset-0 bg-black dark:bg-white translate-x-2 translate-y-2"></div>

        <!-- Card -->
        <div class="relative rounded-none border-4 border-black dark:border-white bg-white dark:bg-black h-full flex flex-col overflow-hidden">
            <!-- Image Area -->
            <div class="min-h-[18rem] w-full border-b-4 border-black dark:border-white bg-zinc-200 dark:bg-zinc-800 relative overflow-hidden">
                <!-- Number Indicator placeholder -->
                <div class="absolute top-4 left-4">
                    <div class="w-16 h-12 skeleton-bg rounded-none"></div>
                </div>

                <!-- Category Badge placeholder -->
                <div class="absolute top-4 right-4">
                    <div class="w-24 h-7 skeleton-bg rounded-none"></div>
                </div>

                <!-- Image Count placeholder -->
                <div class="absolute bottom-20 left-4">
                    <div class="w-28 h-7 skeleton-bg rounded-none"></div>
                </div>

                <!-- Case Label placeholder -->
                <div class="absolute bottom-4 left-4">
                    <div class="w-36 h-7 skeleton-bg rounded-none"></div>
                </div>
            </div>

            <!-- Thumbnail Strip placeholder -->
            <div class="grid grid-cols-4 gap-0 border-b-4 border-black dark:border-white">
                <div
                    v-for="i in 4"
                    :key="i"
                    class="h-20 border-r-2 border-black bg-zinc-200 dark:bg-zinc-800 last:border-r-0"
                    :class="i < 4 ? 'border-black dark:border-white' : ''"
                ></div>
            </div>

            <!-- Card Body -->
            <div class="flex flex-col flex-1 p-6">
                <!-- Tech Badges placeholder -->
                <div class="flex flex-wrap gap-1.5 mb-4">
                    <div v-for="i in 4" :key="i">
                        <div class="w-12 h-9 skeleton-bg rounded-none"></div>
                    </div>
                </div>

                <!-- Label -->
                <div class="mb-3 flex items-center gap-2">
                    <div class="w-6 h-px bg-zinc-300 dark:bg-zinc-600"></div>
                    <div class="w-24 h-3 skeleton-bg rounded-none"></div>
                </div>

                <!-- Title -->
                <div class="space-y-2 mb-6">
                    <div class="w-3/4 h-7 skeleton-bg rounded-none"></div>
                    <div class="w-1/2 h-7 skeleton-bg rounded-none"></div>
                </div>

                <!-- Description -->
                <div class="space-y-2 mb-8">
                    <div class="w-full h-4 skeleton-bg rounded-none"></div>
                    <div class="w-5/6 h-4 skeleton-bg rounded-none"></div>
                    <div class="w-2/3 h-4 skeleton-bg rounded-none"></div>
                </div>

                <!-- Button placeholder -->
                <div class="mt-auto">
                    <div class="w-full h-12 skeleton-bg rounded-none"></div>
                </div>
            </div>
        </div>
    </div>
</template>
