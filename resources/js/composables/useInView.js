import { ref, onMounted, onUnmounted } from 'vue'

export function useInView(threshold = 0.1) {
    const el = ref(null)
    const isVisible = ref(false)

    onMounted(() => {
        const target = el.value
        if (!target) return

        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting) {
                    isVisible.value = true
                    observer.disconnect()
                }
            },
            { threshold }
        )
        observer.observe(target)
        onUnmounted(() => observer.disconnect())
    })

    return { el, isVisible }
}
