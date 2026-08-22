import { onMounted, onUnmounted, ref } from 'vue'
import { usePostHog } from '@/composables/usePostHog'

interface ScrollEventProperties {
    depth_percent: number
    page_url: string
    time_elapsed: number
}

interface TimeOnPageProperties {
    seconds: number
    page_url: string
}

/**
 * Tracks scroll depth and time on page via PostHog events.
 *
 * Events fired:
 * - `scroll_depth` at 25%, 50%, 75%, 90%, 100%
 * - `time_on_page` at 30s, 60s, 120s, 300s
 */
export function usePageTracking(): void {
    const { capture } = usePostHog()
    const startTime = ref(Date.now())
    const trackedDepths = new Set<number>()
    const trackedTimes = new Set<number>()
    let scrollTimer: number | null = null
    let timeInterval: ReturnType<typeof setInterval> | null = null

    const SCROLL_THRESHOLDS = [25, 50, 75, 90, 100]
    const TIME_THRESHOLDS = [30, 60, 120, 300]

    function handleScroll(): void {
        if (scrollTimer) return
        scrollTimer = requestAnimationFrame(() => {
            scrollTimer = null
            const scrollTop = window.scrollY
            const docHeight = document.documentElement.scrollHeight - window.innerHeight
            if (docHeight <= 0) return
            const percent = Math.round((scrollTop / docHeight) * 100)

            for (const threshold of SCROLL_THRESHOLDS) {
                if (percent >= threshold && !trackedDepths.has(threshold)) {
                    trackedDepths.add(threshold)
                    capture('scroll_depth', {
                        depth_percent: threshold,
                        page_url: window.location.href,
                        time_elapsed: Math.round((Date.now() - startTime.value) / 1000),
                    } satisfies ScrollEventProperties)
                }
            }
        })
    }

    function checkTimeOnPage(): void {
        const elapsed = Math.round((Date.now() - startTime.value) / 1000)
        for (const threshold of TIME_THRESHOLDS) {
            if (elapsed >= threshold && !trackedTimes.has(threshold)) {
                trackedTimes.add(threshold)
                capture('time_on_page', {
                    seconds: threshold,
                    page_url: window.location.href,
                } satisfies TimeOnPageProperties)
            }
        }
    }

    onMounted(() => {
        window.addEventListener('scroll', handleScroll, { passive: true })
        timeInterval = setInterval(checkTimeOnPage, 10_000)
    })

    onUnmounted(() => {
        window.removeEventListener('scroll', handleScroll)
        if (timeInterval) clearInterval(timeInterval)
        if (scrollTimer) cancelAnimationFrame(scrollTimer)
    })
}
