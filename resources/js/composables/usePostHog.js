import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import posthog from 'posthog-js';

/**
 * Read local feature flag overrides from localStorage.
 * These override PostHog server-side flags for local testing.
 */
function getLocalFlags() {
    if (typeof localStorage === 'undefined') return {};
    try {
        return JSON.parse(localStorage.getItem('local_feature_flags') || '{}');
    } catch {
        return {};
    }
}

/**
 * Composable for interacting with PostHog analytics and feature flags.
 * Usage:
 *   const { isFeatureEnabled, capture, identify, getFeatureFlag, allFlags } = usePostHog();
 *
 * Local flags (from localStorage) override server-side PostHog flags.
 */
export function usePostHog() {
    const page = usePage();
    const isLoaded = computed(() => typeof posthog?.__loaded !== 'undefined');

    /**
     * Merge server-side flags with local overrides.
     * Local flags take priority.
     */
    function getMergedFlags() {
        const serverFlags = page.props.posthog_flags || {};
        const localFlags = getLocalFlags();
        return { ...serverFlags, ...localFlags };
    }

    /**
     * Check if a feature flag is enabled.
     * Priority: local flags > PostHog client-side > server-side flags.
     */
    function isFeatureEnabled(flag) {
        // 1. Check localStorage override first
        const localFlags = getLocalFlags();
        if (flag in localFlags) {
            return localFlags[flag];
        }

        // 2. Check PostHog client-side (if loaded)
        if (isLoaded.value) {
            try {
                const val = posthog.isFeatureEnabled(flag);
                if (val !== null && val !== undefined) return val;
            } catch {
                // Fall through
            }
        }

        // 3. Fallback to server-side evaluated flags
        const flags = page.props.posthog_flags || {};
        return flags[flag] ?? false;
    }

    /**
     * Get the value/payload of a feature flag.
     * Priority: local flags > PostHog client-side > server-side flags.
     */
    function getFeatureFlag(flag) {
        // 1. Check localStorage override first
        const localFlags = getLocalFlags();
        if (flag in localFlags) {
            return localFlags[flag];
        }

        // 2. Check PostHog client-side
        if (isLoaded.value) {
            try {
                const val = posthog.getFeatureFlag(flag);
                if (val !== null && val !== undefined) return val;
            } catch {
                // Fall through
            }
        }

        // 3. Fallback to server-side
        const flags = page.props.posthog_flags || {};
        return flags[flag] ?? null;
    }

    /**
     * Get all feature flags (merged: server + local overrides).
     */
    function allFlags() {
        return getMergedFlags();
    }

    /**
     * Capture a custom event.
     */
    function capture(event, properties = {}) {
        if (!import.meta.env.VITE_POSTHOG_KEY) return;
        try {
            posthog.capture(event, properties);
        } catch (e) {
            console.warn('[PostHog] Failed to capture event:', e);
        }
    }

    /**
     * Identify (or update) a user.
     */
    function identify(distinctId, properties = {}) {
        if (!import.meta.env.VITE_POSTHOG_KEY) return;
        try {
            posthog.identify(distinctId, properties);
        } catch (e) {
            console.warn('[PostHog] Failed to identify:', e);
        }
    }

    /**
     * Reset the user identity (useful on logout).
     */
    function reset() {
        try {
            posthog.reset();
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
    };
}
