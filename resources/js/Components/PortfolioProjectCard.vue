<script setup>
import { useI18n } from 'vue-i18n'
import { Link } from '@inertiajs/vue3'
import { Badge } from '@/Components/ui/badge'
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/Components/ui/tooltip'
import { Eye, ArrowUpRight } from 'lucide-vue-next'
import { usePostHog } from '@/composables/usePostHog'
import BlurImage from '@/Components/BlurImage.vue'

const { t } = useI18n()
const { capture } = usePostHog()

const props = defineProps({
    project: { type: Object, required: true },
    index: { type: Number, default: 0 },
    getTechLogo: { type: Function, default: () => null },
    getIcon: { type: Function, default: () => null },
})

const trackProjectClick = () => {
    capture('project_click', {
        project_id: props.project.id,
        project_name: props.project.name,
        category: props.project.category,
    })
}

const projectHref = () => props.project.slug
    ? route('portafolio.show', props.project.slug)
    : route('portafolio')

// ── 3D Tilt (throttled) ──
let tiltTicking = false

const handleTilt = (e) => {
    const cardEl = e.currentTarget
    if (!cardEl || window.innerWidth < 768) return
    if (tiltTicking) return
    tiltTicking = true

    requestAnimationFrame(() => {
        const rect = cardEl.getBoundingClientRect()
        const x = e.clientX - rect.left
        const y = e.clientY - rect.top
        const centerX = rect.width / 2
        const centerY = rect.height / 2
        const rotateX = ((y - centerY) / centerY) * -6
        const rotateY = ((x - centerX) / centerX) * 6
        cardEl.style.transform =
            `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02,1.02,1.02)`
        tiltTicking = false
    })
}

const resetTilt = (e) => {
    const cardEl = e.currentTarget
    if (!cardEl) return
    cardEl.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1,1,1)'
}
</script>

<template>
    <div class="group relative">
        <!-- Offset Background Layer -->
        <div class="absolute inset-0 bg-black dark:bg-white translate-x-2 translate-y-2 group-hover:translate-x-4 group-hover:translate-y-4 transition-all duration-500 ease-out"></div>

        <!-- Card -->
        <Card
            class="relative z-10 rounded-none border-4 border-black dark:border-white bg-white dark:bg-black h-full flex flex-col overflow-hidden transition-all duration-300 ease-out card-tilt"
            @mousemove="handleTilt"
            @mouseleave="resetTilt"
        >
            <!-- Image Area -->
            <Link
                :href="projectHref()"
                :class="['min-h-[18rem] w-full border-b-4 border-black dark:border-white relative flex items-center justify-center overflow-hidden text-left focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2', project.color || 'bg-gray-100 dark:bg-zinc-900']"
                @click="trackProjectClick"
            >
                <!-- Image with overlay -->
                <div class="relative w-full h-full">                        <BlurImage
                            v-if="project.images && project.images.length > 0"
                            :src="project.images[0].image_url"
                            :alt="project.name"
                            class="w-full h-full"
                            img-class="transition-all duration-700 ease-out group-hover:scale-105"
                        />
                        <div
                            v-else
                            class="w-full h-full flex items-center justify-center bg-slate-50 dark:bg-zinc-900"
                    >
                        <svg viewBox="0 0 400 300" class="w-full h-full text-black/10 dark:text-white/10 absolute inset-0">
                            <defs>
                                <pattern id="grid-brutalist" width="20" height="20" patternUnits="userSpaceOnUse">
                                    <path d="M 20 0 L 0 0 0 20" fill="none" stroke="currentColor" stroke-width="0.5"/>
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#grid-brutalist)" />
                            <path d="M 0 0 L 400 300 M 400 0 L 0 300" stroke="currentColor" stroke-width="1" />
                        </svg>
                        <component :is="getIcon(project.icon)" class="w-20 h-20 text-black/20 dark:text-white/20 absolute m-auto" />
                    </div>
                </div>

                <!-- Number Indicator -->
                <div class="absolute top-4 left-4">
                    <span class="text-black dark:text-white font-display font-black text-5xl italic select-none uppercase opacity-15 transition-all duration-500 group-hover:opacity-30 group-hover:scale-110">
                        #{{ (index + 1).toString().padStart(2, '0') }}
                    </span>
                </div>

                <!-- Category Badge -->
                <div class="absolute top-4 right-4 bg-black text-white dark:bg-white dark:text-black px-3 py-1.5 font-black text-xs uppercase italic border-2 border-current transition-all duration-300 group-hover:bg-brutalist-yellow group-hover:text-black">
                    {{ project.category }}
                </div>

                <!-- Image Count -->
                <div
                    v-if="project.images && project.images.length > 1"
                    class="absolute bottom-20 left-4 border-2 border-black bg-brutalist-yellow px-3 py-1.5 text-[11px] font-black uppercase tracking-[0.24em] text-black dark:border-white transition-all duration-300 group-hover:translate-x-1 group-hover:-translate-y-1"
                >
                    <span class="flex items-center gap-1.5">
                        <Eye class="w-3.5 h-3.5" />
                        {{ project.images.length }} {{ t('portafolio.images_label') }}
                    </span>
                </div>

                <!-- Case Label -->
                <div class="absolute bottom-4 left-4 border-2 border-black bg-white/90 px-3 py-1.5 text-[11px] font-black uppercase tracking-[0.24em] text-black backdrop-blur-sm dark:border-white dark:bg-black/80 dark:text-white transition-all duration-300">
                    {{ t('portafolio.case_label') }} {{ (index + 1).toString().padStart(2, '0') }}
                </div>

                <!-- Hover Overlay -->
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 dark:group-hover:bg-white/5 transition-all duration-500 pointer-events-none"></div>
            </Link>

            <!-- Thumbnail Strip (hidden on mobile, shown on md+) -->
            <div
                v-if="project.images && project.images.length > 1"
                class="hidden md:grid grid-cols-4 gap-0 border-b-4 border-black dark:border-white"
            >
                <div
                    v-for="(image, imageIndex) in project.images.slice(0, 4)"
                    :key="image.id"
                    class="h-20 border-r-2 border-black bg-zinc-100 last:border-r-0 dark:border-white dark:bg-zinc-900 overflow-hidden"
                >
                    <Link :href="projectHref()" class="h-full w-full relative block focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-inset" @click="trackProjectClick">                            <BlurImage
                                :src="image.image_url"
                                :alt="project.name"
                                :width="200"
                                :height="150"
                                class="h-full w-full"
                                img-class="transition-all duration-500 hover:scale-110"
                            />
                    </Link>
                </div>
            </div>

            <!-- Card Body -->
            <Link :href="projectHref()" @click="trackProjectClick" class="flex flex-col flex-1">
                <CardHeader class="p-6 pb-2">
                    <!-- Tech Badges -->
                    <div
                        v-if="project.technologies && project.technologies.length > 0"
                        class="flex flex-wrap gap-1.5 mb-4"
                        @click.stop
                        @pointerdown.stop
                    >
                        <TooltipProvider :delay-duration="0">
                            <Tooltip
                                v-for="tech in project.technologies"
                                :key="tech.id"
                            >
                                <TooltipTrigger as-child>
                                    <Badge
                                        class="bg-transparent text-black dark:text-white font-black border-2 border-black dark:border-white uppercase italic tracking-widest text-[10px] rounded-none px-2 py-1 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] dark:shadow-[2px_2px_0px_0px_rgba(255,255,255,1)] flex items-center justify-center w-14 h-10 transition-all hover:scale-110 hover:rotate-6"
                                    >
                                        <img v-if="tech.logo_url" :src="tech.logo_url" class="w-full h-full object-contain" loading="lazy" />
                                        <span v-else class="text-[10px] font-black truncate max-w-[50px] sm:max-w-none">{{ tech.name }}</span>
                                    </Badge>
                                </TooltipTrigger>
                                <TooltipContent side="top" :side-offset="8">
                                    {{ tech.name }}
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                    </div>

                    <div class="mb-3 text-[10px] font-black uppercase tracking-[0.28em] opacity-55 flex items-center gap-2">
                        <span class="inline-block w-6 h-px bg-current"></span>
                        {{ t('portafolio.project_label') }}
                    </div>

                    <CardTitle class="font-display font-black text-xl sm:text-2xl uppercase italic leading-none break-words transition-all duration-300 group-hover:translate-x-1" :class="project.color ? 'text-brutalist-pink' : 'group-hover:text-brutalist-pink dark:group-hover:text-brutalist-pink'">
                        {{ project.name }}
                    </CardTitle>
                </CardHeader>

                <CardContent class="p-6 pt-2 flex-grow flex flex-col">
                    <CardDescription class="text-sm sm:text-base font-black uppercase leading-tight italic text-black dark:text-white opacity-100 mb-6 flex-grow break-words">
                        {{ project.desc }}
                    </CardDescription>
                </CardContent>
            </Link>

                <!-- Action Button -->
                <Link :href="projectHref()" class="block mt-auto" @click="trackProjectClick">
                    <Button
                        variant="outline"
                        class="w-full h-auto rounded-none border-4 border-black dark:border-white font-black uppercase italic hover:bg-brutalist-pink hover:text-white dark:hover:text-white transition-all group/btn text-sm py-3"
                    >
                        <span class="flex items-center justify-center gap-2">
                            {{ t('portafolio.view_project') }}
                            <ArrowUpRight class="w-4 h-4 group-hover/btn:translate-x-1 group-hover/btn:-translate-y-1 transition-transform" />
                        </span>
                    </Button>
                </Link>
        </Card>
    </div>
</template>
