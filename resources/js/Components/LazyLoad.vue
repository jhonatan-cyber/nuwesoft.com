<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    /**
     * Root margin for IntersectionObserver (CSS-like value).
     * Positive values start loading BEFORE the element enters the viewport.
     * Example: '200px' starts loading 200px before it's visible.
     */
    rootMargin: { type: String, default: '200px' },
    /**
     * Intersection threshold (0-1). 0 = triggers as soon as any part is visible.
     */
    threshold: { type: Number, default: 0 },
    /**
     * Fallback timeout (ms). If the observer doesn't fire within this time,
     * the content is shown anyway. Useful for headless/iframe contexts.
     */
    fallbackMs: { type: Number, default: 1500 },
})

const target = ref(null)
const isVisible = ref(false)
let observer = null
let fallbackTimer = null

onMounted(() => {
    if (!target.value) return

    // Fallback: show content if IntersectionObserver doesn't fire
    // This handles headless browsers, iframes, and print contexts
    fallbackTimer = setTimeout(() => {
        if (!isVisible.value) {
            isVisible.value = true
            observer?.disconnect()
        }
    }, props.fallbackMs)

    observer = new IntersectionObserver(
        ([entry]) => {
            if (entry.isIntersecting) {
                isVisible.value = true
                clearTimeout(fallbackTimer)
                observer.disconnect()
            }
        },
        { rootMargin: props.rootMargin, threshold: props.threshold }
    )
    observer.observe(target.value)
})

onUnmounted(() => {
    clearTimeout(fallbackTimer)
    observer?.disconnect()
})
</script>

<template>
    <div ref="target" class="contents">
        <slot v-if="isVisible" />
        <!-- Placeholder slot shown while not loaded -->
        <slot v-else name="placeholder" />
    </div>
</template>
