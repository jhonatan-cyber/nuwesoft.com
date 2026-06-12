<script setup>
defineProps({
    service: {
        type: Object,
        required: true,
    },
    index: {
        type: Number,
        required: true,
    },
})
</script>

<template>
    <div
        :id="service.slug"
        class="group relative flex h-full flex-col overflow-hidden rounded-none border-4 border-black bg-white shadow-brutalist transition-all duration-300 hover:-translate-x-2 hover:-translate-y-2 hover:shadow-brutalist-hover-lg dark:border-white dark:bg-black dark:shadow-brutalist-white dark:hover:shadow-brutalist-white-lg"
    >
        <!-- Decorative circle -->
        <div
            :class="[
                'pointer-events-none absolute -right-12 -top-12 h-32 w-32 rounded-full opacity-10 transition-all duration-700 group-hover:scale-[2.5]',
                service.color,
            ]"
        ></div>

        <!-- Header: Eyebrow + Number -->
        <div class="relative z-10 flex items-start justify-between p-6 pb-0">
            <span class="border-2 border-current px-3 py-1 text-[10px] font-black uppercase tracking-[0.28em] break-words max-w-[70%]">
                {{ service.eyebrow }}
            </span>
            <span class="select-none text-5xl font-display font-black italic leading-none opacity-10 transition-opacity group-hover:opacity-30">
                {{ index + 1 }}
            </span>
        </div>

        <!-- Content -->
        <div class="relative z-10 flex flex-grow flex-col p-6 pt-4">
            <!-- Title -->
            <h3 class="mb-3 inline-block text-xl sm:text-2xl font-display font-black uppercase italic leading-none tracking-tight transition-colors group-hover:bg-brutalist-yellow group-hover:text-black md:text-3xl break-words">
                {{ service.title }}
            </h3>

            <!-- Description -->
            <p class="mb-4 text-sm font-black uppercase leading-relaxed text-black/70 dark:text-white/70 md:text-base break-words">
                {{ service.description }}
            </p>

            <!-- Bullets -->
            <div class="mb-5 space-y-1.5">
                <div
                    v-for="bullet in service.bullets"
                    :key="bullet"
                    class="flex items-start gap-2"
                >
                    <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rotate-45 bg-black dark:bg-white"></span>
                    <span class="text-[10px] font-black uppercase leading-snug text-black/60 dark:text-white/60 md:text-[11px] break-words">{{ bullet }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
