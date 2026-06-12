<script setup>
import { ref, computed, onMounted } from 'vue'
import { useLocalFeatureFlags } from '@/composables/useLocalFeatureFlags'
import { usePostHog } from '@/composables/usePostHog'
import { Flag, Plus, Trash2, RotateCcw, Info } from 'lucide-vue-next'

const { flags, clear, importFromPostHog } = useLocalFeatureFlags()
const { allFlags } = usePostHog()
const newFlagName = ref('')
const newFlagValue = ref(true)

// Known flags that exist in the system (for autocomplete / discovery)
const knownFlags = computed(() => {
    const serverFlags = allFlags()
    const local = { ...flags }
    const merged = { ...serverFlags }
    Object.keys(local).forEach((k) => {
        merged[k] = local[k]
    })
    // Merge local overrides into display
    return Object.keys(merged).map((key) => ({
        key,
        serverValue: key in serverFlags ? serverFlags[key] : null,
        localValue: key in flags ? flags[key] : null,
        effectiveValue: key in flags ? flags[key] : (serverFlags[key] ?? false),
    }))
})

const isAdding = ref(false)

const hasLocalOverrides = computed(() => Object.keys(flags).length > 0)

function addFlag() {
    const name = newFlagName.value.trim()
    if (!name) return
    flags[name] = newFlagValue.value
    newFlagName.value = ''
    newFlagValue.value = true
    isAdding.value = false
}

function removeFlag(key) {
    delete flags[key]
}

function toggleFlag(key) {
    flags[key] = !flags[key]
}

function syncFromServer() {
    importFromPostHog(allFlags())
}

// No auto-sync on mount: flags localStorage starts empty
// so the UI only shows "LOCAL OVERRIDE" badges for actual overrides.
</script>

<template>
    <div class="bg-white dark:bg-black border border-neutral-200 dark:border-neutral-800 rounded-2xl overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-neutral-100 dark:border-neutral-800">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-brutalist-purple/10 flex items-center justify-center">
                    <Flag class="w-4 h-4 text-brutalist-purple" />
                </div>
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-tight">LOCAL FEATURE FLAGS</h4>
                    <p class="text-[10px] text-neutral-400 uppercase tracking-wider">Overrides de localStorage</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button
                    v-if="hasLocalOverrides"
                    @click="clear"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-neutral-200 dark:border-neutral-800 text-[10px] font-bold uppercase tracking-widest text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500 focus-visible:ring-offset-2"
                >
                    <RotateCcw class="w-3 h-3" />
                    Reset
                </button>
                <button
                    @click="syncFromServer"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-neutral-200 dark:border-neutral-800 text-[10px] font-bold uppercase tracking-widest text-neutral-500 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2"
                    title="Sincronizar desde PostHog"
                >
                    <RotateCcw class="w-3 h-3" />
                    Sync
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="p-4 space-y-3">
            <!-- Info box -->
            <div class="flex items-start gap-3 p-3 rounded-xl bg-brutalist-purple/5 border border-brutalist-purple/10">
                <Info class="w-4 h-4 text-brutalist-purple shrink-0 mt-0.5" />
                <p class="text-[10px] font-medium text-neutral-500 leading-relaxed">
                    Los flags locales sobreescriben los valores de PostHog mientras estén activos. 
                    Útil para testing sin depender del servidor.
                </p>
            </div>

            <!-- Flag list -->
            <div v-if="knownFlags.length > 0" class="space-y-1.5">
                <div
                    v-for="item in knownFlags"
                    :key="item.key"
                    class="flex items-center justify-between gap-3 p-2.5 rounded-xl hover:bg-neutral-50 dark:hover:bg-neutral-900 transition-colors group"
                >
                    <div class="flex items-center gap-2 min-w-0 flex-1">
                        <button
                            @click="toggleFlag(item.key)"
                            :class="[
                                'w-9 h-5 rounded-full relative transition-colors shrink-0',
                                item.effectiveValue ? 'bg-black dark:bg-white' : 'bg-neutral-200 dark:bg-neutral-700'
                            ]"
                        >
                            <span
                                :class="[
                                    'absolute top-0.5 w-4 h-4 rounded-full bg-white dark:bg-black shadow-sm transition-transform',
                                    item.effectiveValue ? 'translate-x-4' : 'translate-x-0.5'
                                ]"
                            ></span>
                        </button>
                        <div class="min-w-0">
                            <p class="text-xs font-bold truncate">{{ item.key }}</p>
                            <p v-if="item.localValue !== null" class="text-[9px] text-brutalist-purple font-bold uppercase tracking-wider">
                                LOCAL OVERRIDE
                            </p>
                            <p v-else class="text-[9px] text-neutral-400 font-medium">
                                Desde PostHog
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span
                            :class="[
                                'text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md',
                                item.effectiveValue
                                    ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300'
                                    : 'bg-neutral-100 dark:bg-neutral-800 text-neutral-500'
                            ]"
                        >
                            {{ item.effectiveValue ? 'ON' : 'OFF' }}
                        </span>
                        <button
                            v-if="item.localValue !== null"
                            @click="removeFlag(item.key)"
                            class="p-1 rounded-lg text-neutral-300 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 opacity-0 group-hover:opacity-100 transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500 focus-visible:ring-offset-2 focus-visible:opacity-100"
                        >
                            <Trash2 class="w-3.5 h-3.5" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty state -->
            <div v-else class="py-8 text-center">
                <Flag class="w-8 h-8 mx-auto mb-3 text-neutral-300 dark:text-neutral-700" />
                <p class="text-xs font-bold text-neutral-400 uppercase tracking-wider">Sin flags configurados</p>
                <p class="text-[10px] text-neutral-400 mt-1">Agregá un flag manual o sincronizá desde PostHog</p>
            </div>

            <!-- Add flag form -->
            <div v-if="isAdding" class="space-y-3 p-3 rounded-xl bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800">
                <div>
                    <label class="text-[10px] font-bold text-neutral-500 uppercase tracking-wider mb-1.5 block">Nombre del flag</label>
                    <input
                        v-model="newFlagName"
                        type="text"
                        placeholder="ej: show_beta_banner"
                        class="w-full px-3 py-2 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-black text-xs font-bold uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white"
                        @keyup.enter="addFlag"
                    />
                </div>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" v-model="newFlagValue" class="rounded border-neutral-300" />
                        <span class="text-[10px] font-bold uppercase tracking-wider">ACTIVO</span>
                    </label>
                    <div class="flex-1"></div>
                    <button
                        @click="isAdding = false"
                        class="px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest text-neutral-500 hover:bg-neutral-200 dark:hover:bg-neutral-800 transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="addFlag"
                        :disabled="!newFlagName.trim()"
                        class="px-4 py-1.5 rounded-lg bg-black dark:bg-white text-white dark:text-black text-[10px] font-bold uppercase tracking-widest hover:bg-neutral-800 dark:hover:bg-neutral-200 transition-all disabled:opacity-40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2"
                    >
                        Agregar
                    </button>
                </div>
            </div>

            <!-- Add button -->
            <button
                v-else
                @click="isAdding = true"
                class="w-full flex items-center justify-center gap-2 p-3 rounded-xl border-2 border-dashed border-neutral-200 dark:border-neutral-700 text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-black dark:hover:text-white hover:border-black dark:hover:border-white transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-black dark:focus-visible:ring-white focus-visible:ring-offset-2"
            >
                <Plus class="w-4 h-4" />
                Agregar Flag Manual
            </button>
        </div>
    </div>
</template>
