import { reactive, watch, type UnwrapNestedRefs } from 'vue'

interface FeatureFlags {
    [key: string]: boolean | string
}

const STORAGE_KEY = 'local_feature_flags'

function readFlags(): FeatureFlags {
    if (typeof localStorage === 'undefined') return {}
    try {
        return JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}')
    } catch {
        return {}
    }
}

function writeFlags(flags: FeatureFlags): void {
    if (typeof localStorage === 'undefined') return
    try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(flags))
    } catch {
        // Storage full or unavailable — ignore
    }
}

interface UseLocalFeatureFlagsReturn {
    flags: UnwrapNestedRefs<FeatureFlags>
    clear: () => void
    importFromPostHog: (serverFlags: FeatureFlags | null | undefined) => void
}

/**
 * Reactive store of local feature flags persisted in localStorage.
 * These flags override PostHog server-side flags when present.
 *
 * @example
 * ```ts
 * const localFlags = useLocalFeatureFlags()
 * localFlags.flags.show_beta_banner = true  // reactive + persisted
 * delete localFlags.flags.some_flag          // removes override
 * localFlags.clear()
 * ```
 */
export function useLocalFeatureFlags(): UseLocalFeatureFlagsReturn {
    const state = reactive<{ flags: FeatureFlags }>({
        flags: readFlags(),
    })

    // Persist whenever flags change
    watch(
        () => state.flags,
        (val) => writeFlags(val),
        { deep: true }
    )

    function clear(): void {
        Object.keys(state.flags).forEach((key) => {
            delete state.flags[key]
        })
    }

    function importFromPostHog(serverFlags: FeatureFlags | null | undefined): void {
        if (!serverFlags) return
        Object.entries(serverFlags).forEach(([key, val]) => {
            if (!(key in state.flags)) {
                state.flags[key] = val
            }
        })
    }

    return {
        flags: state.flags,
        clear,
        importFromPostHog,
    }
}
