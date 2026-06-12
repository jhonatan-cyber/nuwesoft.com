import { onMounted, onUnmounted, ref } from 'vue'
import { usePostHog } from '@/composables/usePostHog'

/**
 * Tracks scroll depth and time on page via PostHog events.
 * Call in any page/component's setup to enrich analytics data.
 *
 * Events fired:
 * - scroll_depth: at 25%, 50%, 75%, 90%, 100% scroll
 * - time_on_page: at 30s, 60s, 120s intervals
 *
 * Usage:
 *   import { usePageTracking } from '@/composables/usePageTracking'
 *   usePageTracking()  // in setup()
 */
export function usePageTracking() {
    const { capture } = usePostHog()
    const startTime = ref(Date.now())
    const trackedDepths = new Set()
    const trackedTimes = new Set()
    let scrollTimer = null
    let timeInterval = null

    // ── Scroll Depth ──
    const SCROLL_THRESHOLDS = [25, 50, 75, 90, 100]

    function handleScroll() {
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
                    })
                }
            }
        })
    }

    // ── Time on Page ──
    const TIME_THRESHOLDS = [30, 60, 120, 300] // seconds

    function checkTimeOnPage() {
        const elapsed = Math.round((Date.now() - startTime.value) / 1000)
        for (const threshold of TIME_THRESHOLDS) {
            if (elapsed >= threshold && !trackedTimes.has(threshold)) {
                trackedTimes.add(threshold)
                capture('time_on_page', {
                    seconds: threshold,
                    page_url: window.location.href,
                })
            }
        }
    }

    // ── Lifecycle ──
    onMounted(() => {
        window.addEventListener('scroll', handleScroll, { passive: true })
        timeInterval = setInterval(checkTimeOnPage, 10_000) // check every 10s
    })

    onUnmounted(() => {
        window.removeEventListener('scroll', handleScroll)
        if (timeInterval) clearInterval(timeInterval)
        if (scrollTimer) cancelAnimationFrame(scrollTimer)
    })
}
