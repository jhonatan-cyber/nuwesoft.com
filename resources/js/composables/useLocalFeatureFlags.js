import { reactive, watch } from 'vue';

const STORAGE_KEY = 'local_feature_flags';

/**
 * Read flags from localStorage.
 */
function readFlags() {
    if (typeof localStorage === 'undefined') return {};
    try {
        return JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');
    } catch {
        return {};
    }
}

/**
 * Write flags to localStorage.
 */
function writeFlags(flags) {
    if (typeof localStorage === 'undefined') return;
    try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(flags));
    } catch {
        // Storage full or unavailable — ignore
    }
}

/**
 * Reactive store of local feature flags persisted in localStorage.
 * These flags override PostHog server-side flags when present.
 *
 * Usage:
 *   const localFlags = useLocalFeatureFlags();
 *   localFlags.flags.show_beta_banner = true; // reactive + persisted
 *   delete localFlags.flags.some_flag;          // removes override
 *   localFlags.clear();
 */
export function useLocalFeatureFlags() {
    const state = reactive({
        flags: readFlags(),
    });

    // Persist whenever flags change
    watch(
        () => state.flags,
        (val) => writeFlags(val),
        { deep: true }
    );

    /**
     * Remove all local overrides.
     */
    function clear() {
        Object.keys(state.flags).forEach((key) => {
            delete state.flags[key];
        });
    }

    /**
     * Import flags from PostHog (pre-fill known flags with current state).
     */
    function importFromPostHog(serverFlags) {
        if (!serverFlags) return;
        Object.entries(serverFlags).forEach(([key, val]) => {
            if (!(key in state.flags)) {
                state.flags[key] = val;
            }
        });
    }

    return {
        flags: state.flags,
        clear,
        importFromPostHog,
    };
}
