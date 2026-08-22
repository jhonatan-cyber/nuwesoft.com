import { ref, onMounted, onUnmounted, type Ref } from 'vue'

export function useInView(threshold = 0.1): { el: Ref<HTMLElement | null>; isVisible: Ref<boolean> } {
    const el = ref<HTMLElement | null>(null)
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
