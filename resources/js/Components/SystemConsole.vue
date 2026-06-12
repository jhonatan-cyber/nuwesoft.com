<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { Terminal, Cpu } from 'lucide-vue-next';

const { t } = useI18n();

const bootLines = ref([]);
const showStack = ref(false);
const bootComplete = ref(false);

const highlights = [
    { key: 'h1' },
    { key: 'h2' },
    { key: 'h3' },
];

const bootSequence = [
    { text: 'INITIALIZING_CORE_SYSTEMS...', type: 'system', delay: 350 },
    ...highlights.map((h, i) => ({
        text: `✓ ${t(`hero.highlights.${h.key}.title`)}`,
        subtitle: t(`hero.highlights.${h.key}.desc`).toLowerCase(),
        type: 'success',
        delay: 650 + i * 100,
    })),
    { text: '', type: 'sep', delay: 250 },
    { text: '→ PIPELINE: ACTIVE', type: 'info', delay: 500 },
    { text: '→ STATUS: READY_FOR_DEPLOYMENT', type: 'info', delay: 500 },
    { text: '', type: 'sep', delay: 200 },
];

const stackChips = [
    'Laravel 13', 'Vue 3', 'Inertia.js',
    'Tailwind', 'PostgreSQL', 'Redis',
    'Cloudinary', 'PHP 8.4',
];

onMounted(() => {
    let acc = 0;
    bootSequence.forEach((line, i) => {
        acc += line.delay;
        setTimeout(() => {
            bootLines.value.push({ id: i, ...line });
            if (i === bootSequence.length - 1) {
                setTimeout(() => {
                    showStack.value = true;
                    setTimeout(() => { bootComplete.value = true; }, 450);
                }, 300);
            }
        }, acc);
    });
});
</script>

<template>
    <div
        class="group relative"
        :class="[
            'transition-all duration-700 delay-150 transform',
            'translate-y-0 opacity-100',
        ]"
    >
        <!-- Brutalist shadow layer -->
        <div class="absolute inset-0 translate-x-3 translate-y-3 border-4 border-black bg-brutalist-yellow transition-transform duration-300 group-hover:translate-x-4 group-hover:translate-y-4 dark:border-white"></div>

        <!-- Terminal window -->
        <div class="relative border-4 border-black bg-[#0a0a0b] font-mono text-sm leading-relaxed shadow-brutalist dark:border-white dark:shadow-brutalist-white">
            
            <!-- Title bar -->
            <div class="flex items-center justify-between border-b-4 border-black bg-zinc-800 px-5 py-3 dark:border-white">
                <div class="flex items-center gap-3">
                    <span class="h-3.5 w-3.5 rounded-full bg-red-500 shadow-[0_0_6px_rgba(239,68,68,0.6)]"></span>
                    <span class="h-3.5 w-3.5 rounded-full bg-amber-400 shadow-[0_0_6px_rgba(251,191,36,0.6)]"></span>
                    <span class="h-3.5 w-3.5 rounded-full bg-emerald-400 shadow-[0_0_6px_rgba(52,211,153,0.6)]"></span>
                </div>
                <div class="flex items-center gap-2 text-zinc-500">
                    <Terminal class="h-4 w-4" />
                    <span class="text-[11px] font-black uppercase tracking-[0.2em]">nuwesoft engine v3.0</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1.5 rounded border border-zinc-700 px-2 py-1 text-[10px] font-black uppercase tracking-widest text-emerald-400">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_6px_rgba(52,211,153,0.8)]" :class="bootComplete ? '' : 'animate-pulse'"></span>
                        {{ bootComplete ? 'ready' : 'booting' }}
                    </span>
                </div>
            </div>

            <!-- Terminal body -->
            <div class="p-6 md:p-8 min-h-[340px] md:min-h-[400px]">
                <!-- Boot lines -->
                <div class="space-y-2.5">
                    <div
                        v-for="line in bootLines"
                        :key="line.id"
                        class="flex items-start gap-2 animate-terminal-line"
                    >
                        <!-- System prompt prefix -->
                        <span v-if="line.type === 'system'" class="mt-0.5 shrink-0 font-black text-brutalist-blue">⚡</span>
                        <span v-else-if="line.type === 'success'" class="mt-0.5 shrink-0 font-black text-emerald-400">▸</span>
                        <span v-else-if="line.type === 'info'" class="mt-0.5 shrink-0 font-black text-brutalist-yellow">▸</span>
                        <span v-else class="hidden"></span>

                        <span v-if="line.type !== 'sep'">
                            <span
                                v-if="line.type === 'system'"
                                class="font-black uppercase tracking-wider text-zinc-300"
                            >{{ line.text }}</span>
                            <span
                                v-else-if="line.type === 'success'"
                                class="font-black uppercase tracking-wider text-emerald-300"
                            >{{ line.text }}</span>
                            <span
                                v-else-if="line.type === 'info'"
                                class="font-black uppercase tracking-wider text-brutalist-yellow"
                            >{{ line.text }}</span>

                            <!-- Subtitle for success lines -->
                            <span v-if="line.subtitle" class="ml-2 text-[11px] uppercase tracking-widest text-zinc-600">
                                // {{ line.subtitle }}
                            </span>
                        </span>

                        <!-- Blinking cursor on last line during boot -->
                        <span
                            v-if="line.id === bootLines.length - 1 && !showStack"
                            class="ml-1 inline-block h-5 w-2.5 bg-zinc-300 animate-cursor-blink"
                        ></span>
                    </div>
                </div>

                <!-- Stack grid (appears after boot) -->
                <transition
                    enter-active-class="transition-all duration-500 ease-out"
                    enter-from-class="opacity-0 translate-y-3"
                    enter-to-class="opacity-100 translate-y-0"
                >
                    <div v-if="showStack" class="mt-8 border-t-2 border-zinc-800 pt-6">
                        <div class="mb-4 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.25em] text-zinc-600">
                            <Cpu class="h-3.5 w-3.5" />
                            <span>stack_detected</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="chip in stackChips"
                                :key="chip"
                                class="animate-stack-pop border border-zinc-700 bg-zinc-900 px-3 py-1.5 text-xs font-black uppercase tracking-wider text-zinc-300 transition-all hover:border-brutalist-yellow hover:text-brutalist-yellow"
                                :style="{ transitionDelay: `${stackChips.indexOf(chip) * 40}ms` }"
                            >
                                {{ chip }}
                            </span>
                        </div>
                        
                        <!-- Final status line -->
                        <div v-if="bootComplete" class="mt-6 flex items-center gap-2 text-xs font-black uppercase tracking-[0.22em] text-emerald-400/80 animate-stack-pop">
                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            system ready — all modules operational
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes terminal-line-in {
    0% {
        opacity: 0;
        transform: translateY(6px) scale(0.98);
        filter: blur(2px);
    }
    60% {
        filter: blur(0);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes cursor-blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0; }
}

@keyframes stack-pop {
    0% {
        opacity: 0;
        transform: translateY(8px) scale(0.95);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.animate-terminal-line {
    animation: terminal-line-in 0.35s ease-out forwards;
}

.animate-cursor-blink {
    animation: cursor-blink 1s step-end infinite;
}

.animate-stack-pop {
    animation: stack-pop 0.4s ease-out forwards;
}
</style>
