import { usePage } from '@inertiajs/vue3'
import { computed, type ComputedRef } from 'vue'
import posthog from 'posthog-js'

interface FeatureFlags {
    [key: string]: boolean | string
}

interface UsePostHogReturn {
    isLoaded: ComputedRef<boolean>
    isFeatureEnabled: (flag: string) => boolean
    getFeatureFlag: (flag: string) => string | boolean | null
    allFlags: () => FeatureFlags
    capture: (event: string, properties?: Record<string, any>) => void
    identify: (distinctId: string, properties?: Record<string, any>) => void
    reset: () => void
}

/**
 * Read local feature flag overrides from localStorage.
 */
function getLocalFlags(): FeatureFlags {
    if (typeof localStorage === 'undefined') return {}
    try {
        return JSON.parse(localStorage.getItem('local_feature_flags') || '{}')
    } catch {
        return {}
    }
}

/**
 * Composable for interacting with PostHog analytics and feature flags.
 *
 * Local flags (from localStorage) override server-side PostHog flags.
 */
export function usePostHog(): UsePostHogReturn {
    const page = usePage()
    const isLoaded = computed(() => typeof (posthog as any).__loaded !== 'undefined')

    function getMergedFlags(): FeatureFlags {
        const serverFlags = (page.props.posthog_flags as FeatureFlags) || {}
        const localFlags = getLocalFlags()
        return { ...serverFlags, ...localFlags }
    }

    function isFeatureEnabled(flag: string): boolean {
        // 1. Check localStorage override first
        const localFlags = getLocalFlags()
        if (flag in localFlags) {
            return Boolean(localFlags[flag])
        }

        // 2. Check PostHog client-side (if loaded)
        if (isLoaded.value) {
            try {
                const val = posthog.isFeatureEnabled(flag)
                if (val !== null && val !== undefined) return val
            } catch {
                // Fall through
            }
        }

        // 3. Fallback to server-side evaluated flags
        const flags = (page.props.posthog_flags as FeatureFlags) || {}
        return Boolean(flags[flag] ?? false)
    }

    function getFeatureFlag(flag: string): string | boolean | null {
        // 1. Check localStorage override first
        const localFlags = getLocalFlags()
        if (flag in localFlags) {
            return localFlags[flag]
        }

        // 2. Check PostHog client-side
        if (isLoaded.value) {
            try {
                const val = posthog.getFeatureFlag(flag)
                if (val !== null && val !== undefined) return val
            } catch {
                // Fall through
            }
        }

        // 3. Fallback to server-side
        const flags = (page.props.posthog_flags as FeatureFlags) || {}
        return flags[flag] ?? null
    }

    function allFlags(): FeatureFlags {
        return getMergedFlags()
    }

    function capture(event: string, properties: Record<string, any> = {}): void {
        if (!import.meta.env.VITE_POSTHOG_KEY) return
        try {
            posthog.capture(event, properties)
        } catch (e) {
            console.warn('[PostHog] Failed to capture event:', e)
        }
    }

    function identify(distinctId: string, properties: Record<string, any> = {}): void {
        if (!import.meta.env.VITE_POSTHOG_KEY) return
        try {
            posthog.identify(distinctId, properties)
        } catch (e) {
            console.warn('[PostHog] Failed to identify:', e)
        }
    }

    function reset(): void {
        try {
            posthog.reset()
        } catch {
            // ignore
        }
    }

    return {
        isLoaded,
        isFeatureEnabled,
        getFeatureFlag,
        allFlags,
        capture,
        identify,
        reset,
    }
}
