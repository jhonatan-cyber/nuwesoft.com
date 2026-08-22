import { ref, onMounted, type Ref } from 'vue'

interface SkeletonLoaderOptions {
    /** Minimum skeleton duration after an Inertia navigation (ms). Default: 300 */
    minSkeletonMs?: number
    /** Skeleton duration on initial page load / direct URL (ms). Default: 200 */
    initialLoadMs?: number
}

interface UseSkeletonLoaderReturn {
    skeletonReady: Ref<boolean>
}

export function useSkeletonLoader(options: SkeletonLoaderOptions = {}): UseSkeletonLoaderReturn {
    const { minSkeletonMs = 300, initialLoadMs = 200 } = options

    const skeletonReady = ref(false)

    onMounted(() => {
        const navStart = (window as any).__nuwesoft_navStart as number | null
        ;(window as any).__nuwesoft_navStart = null

        if (navStart) {
            // Arrived via Inertia navigation — calculate remaining time
            const elapsed = Date.now() - navStart
            const remaining = Math.max(0, minSkeletonMs - elapsed)

            if (remaining > 0) {
                setTimeout(() => { skeletonReady.value = true }, remaining)
            } else {
                requestAnimationFrame(() => { skeletonReady.value = true })
            }
        } else {
            // Initial load (direct URL / refresh)
            setTimeout(() => { skeletonReady.value = true }, initialLoadMs)
        }
    })

    return { skeletonReady }
}
