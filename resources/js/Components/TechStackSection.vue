<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { Badge } from '@/Components/ui/badge'
import { useInView } from '@/composables/useInView'

const { t } = useI18n()
const { el, isVisible } = useInView(0.05)

const props = defineProps({
    technologies: {
        type: Array,
        default: () => [],
    },
})

const CATEGORY_ORDER = [
    'languages', 'frontend', 'backend', 'mobile',
    'database', 'infrastructure', 'automation', 'tools', 'ui',
]

const techCategories = computed(() => {
    const grouped = {}
    for (const tech of props.technologies) {
        if (!grouped[tech.category]) grouped[tech.category] = []
        grouped[tech.category].push({
            name: tech.name,
            icon: tech.logo_url,
            invert_dark: tech.invert_dark,
        })
    }
    return CATEGORY_ORDER
        .filter(key => grouped[key]?.length)
        .map(key => ({ key, name: key, items: grouped[key] }))
})

const categoryAccents = {
    languages:       { border: 'hover:border-brutalist-yellow',  bg: 'hover:bg-brutalist-yellow/10',  badge: 'border-brutalist-yellow',  diamond: 'bg-brutalist-yellow' },
    frontend:        { border: 'hover:border-brutalist-pink',    bg: 'hover:bg-brutalist-pink/10',    badge: 'border-brutalist-pink',    diamond: 'bg-brutalist-pink' },
    backend:         { border: 'hover:border-brutalist-blue',    bg: 'hover:bg-brutalist-blue/10',    badge: 'border-brutalist-blue',    diamond: 'bg-brutalist-blue' },
    mobile:          { border: 'hover:border-brutalist-pink',    bg: 'hover:bg-brutalist-pink/10',    badge: 'border-brutalist-pink',    diamond: 'bg-brutalist-pink' },
    database:        { border: 'hover:border-brutalist-yellow',  bg: 'hover:bg-brutalist-yellow/10',  badge: 'border-brutalist-yellow',  diamond: 'bg-brutalist-yellow' },
    infrastructure:  { border: 'hover:border-brutalist-blue',    bg: 'hover:bg-brutalist-blue/10',    badge: 'border-brutalist-blue',    diamond: 'bg-brutalist-blue' },
    automation:      { border: 'hover:border-brutalist-pink',    bg: 'hover:bg-brutalist-pink/10',    badge: 'border-brutalist-pink',    diamond: 'bg-brutalist-pink' },
    tools:           { border: 'hover:border-brutalist-yellow',  bg: 'hover:bg-brutalist-yellow/10',  badge: 'border-brutalist-yellow',  diamond: 'bg-brutalist-yellow' },
    ui:              { border: 'hover:border-brutalist-pink',    bg: 'hover:bg-brutalist-pink/10',    badge: 'border-brutalist-pink',    diamond: 'bg-brutalist-pink' },
}

const isMarqueeCategory = (cat) => cat.items.length >= 7
</script>

<template>
    <section ref="el" class="relative mt-40 mb-20 overflow-hidden">
        <!-- Decorative blobs -->
        <div class="pointer-events-none absolute -left-32 top-1/4 h-72 w-72 rounded-full bg-brutalist-pink/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-32 bottom-0 h-80 w-80 rounded-full bg-brutalist-blue/10 blur-3xl"></div>

        <div class="relative z-10">
            <!-- Header -->
            <div
                :class="[
                    'mb-20 transition-all duration-700',
                    isVisible ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0',
                ]"
            >
                <Badge class="-rotate-1 mb-8 inline-block border-4 border-black bg-brutalist-yellow px-4 py-2 text-xl font-black uppercase text-black">
                    {{ t('skills.badge') }}
                </Badge>
                <h2 class="text-[clamp(3rem,8vw,6rem)] font-display font-black uppercase italic leading-[0.8] tracking-tighter">
                    {{ t('skills.title1') }} <br/>
                    <span class="text-brutalist-pink">{{ t('skills.title2') }}</span>
                </h2>
            </div>

            <!-- Grid categories -->
            <div
                v-for="(category, catIdx) in techCategories.filter(c => !isMarqueeCategory(c))"
                :key="category.key"
                :style="{ transitionDelay: `${catIdx * 120}ms` }"
                :class="[
                    'mb-16 transition-all duration-700 last:mb-0',
                    isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0',
                ]"
            >
                <!-- Category header -->
                <div class="mb-6 flex items-center gap-4">
                    <span
                        :class="[
                            'inline-flex h-4 w-4 rotate-45 border-2 border-black dark:border-white',
                            categoryAccents[category.key]?.diamond,
                        ]"
                    ></span>
                    <span class="inline-block border-2 border-black bg-white px-4 py-2 text-xs font-black uppercase tracking-[0.24em] text-black shadow-brutalist dark:border-white dark:bg-zinc-900 dark:text-white">
                        {{ t(`technologies.categories.${category.key}`) }}
                    </span>
                    <span class="h-px flex-1 bg-black/30 dark:bg-white/30"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">{{ category.items.length }} TECHS</span>
                </div>

                <!-- Tech grid -->
                <div class="grid grid-cols-3 gap-3 sm:grid-cols-5 md:grid-cols-7 lg:grid-cols-9">
                    <div
                        v-for="tech in category.items"
                        :key="tech.name"
                        :class="[
                            'group flex flex-col items-center justify-center border-2 border-black bg-white p-3 shadow-brutalist transition-all duration-200 hover:-translate-y-1 hover:shadow-brutalist-hover dark:border-white dark:bg-zinc-950',
                            categoryAccents[category.key]?.border,
                            categoryAccents[category.key]?.bg,
                        ]"
                    >
                        <img
                            :src="tech.icon"
                            :alt="tech.name"
                            class="h-8 w-8 object-contain transition-all duration-200 group-hover:scale-110 md:h-10 md:w-10"
                        />
                        <span class="mt-2 text-[9px] font-black uppercase leading-tight text-center text-black/70 transition-colors group-hover:text-black dark:text-white/70 dark:group-hover:text-white md:text-[10px] break-words max-w-full">
                            {{ tech.name }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Marquee categories -->
            <div
                v-for="(category, catIdx) in techCategories.filter(c => isMarqueeCategory(c))"
                :key="category.key"
                :style="{ transitionDelay: `${(catIdx + 5) * 120}ms` }"
                :class="[
                    'mb-16 transition-all duration-700 last:mb-0',
                    isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0',
                ]"
            >
                <!-- Category header -->
                <div class="mb-6 flex items-center gap-4">
                    <span
                        :class="[
                            'inline-flex h-4 w-4 rotate-45 border-2 border-black dark:border-white',
                            categoryAccents[category.key]?.diamond,
                        ]"
                    ></span>
                    <span class="inline-block border-2 border-black bg-white px-4 py-2 text-xs font-black uppercase tracking-[0.24em] text-black shadow-brutalist dark:border-white dark:bg-zinc-900 dark:text-white">
                        {{ t(`technologies.categories.${category.key}`) }}
                    </span>
                    <span class="h-px flex-1 bg-black/30 dark:bg-white/30"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">{{ category.items.length }} TECHS</span>
                </div>

                <!-- Marquee strip -->
                <div class="overflow-hidden border-2 border-black dark:border-white">
                    <div class="flex animate-[marquee_25s_linear_infinite] gap-6 py-4">
                        <div v-for="n in 2" :key="n" class="flex shrink-0 items-center gap-6">
                            <div
                                v-for="tech in category.items"
                                :key="tech.name + n"
                                class="flex items-center gap-3 border-r-2 border-black/20 px-6 dark:border-white/20"
                            >
                                <img
                                    :src="tech.icon"
                                    :alt="tech.name"
                                    :class="[
                                        'h-7 w-7 object-contain transition-all duration-200 hover:scale-110 md:h-9 md:w-9',
                                        tech.invert_dark ? 'dark:invert' : '',
                                    ]"
                                />
                                <span class="text-[10px] font-black uppercase whitespace-nowrap text-black/70 dark:text-white/70 md:text-[11px]">{{ tech.name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
