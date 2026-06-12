<script setup>
import { ref, computed } from 'vue'
import { cloudinaryThumb, cloudinaryBlurUrl } from '@/lib/cloudinary'

const props = defineProps({
    src: { type: String, default: '' },
    alt: { type: String, default: '' },
    width: { type: Number, default: 600 },
    height: { type: Number, default: 400 },
    /**
     * If true, render the full-res image URL instead of a thumbnail.
     * Use for lightbox/large hero images where quality matters.
     */
    fullRes: { type: Boolean, default: false },
    /**
     * Extra CSS classes for the inner <img> element (e.g., hover effects).
     */
    imgClass: { type: [String, Object, Array], default: '' },
})

const loaded = ref(false)
const hasError = ref(false)

const imageUrl = computed(() => {
    if (!props.src) return ''
    if (props.fullRes) return props.src
    return cloudinaryThumb(props.src, props.width, props.height)
})

const blurUrl = computed(() => cloudinaryBlurUrl(props.src))

const handleLoad = () => {
    loaded.value = true
}

const handleError = () => {
    hasError.value = true
    loaded.value = true
}
</script>

<template>
    <div
        class="relative overflow-hidden bg-zinc-100 dark:bg-zinc-900"
    >
        <!-- Blur placeholder (shown while loading) -->
        <img
            v-if="blurUrl && !loaded"
            :src="blurUrl"
            :alt="alt"
            class="absolute inset-0 w-full h-full object-cover scale-110 blur-lg transition-opacity duration-700"
            :class="loaded ? 'opacity-0' : 'opacity-100'"
            aria-hidden="true"
        />        <!-- Full image -->
        <img
            v-if="imageUrl"
            :src="imageUrl"
            :alt="alt"
            class="w-full h-full object-cover transition-all duration-700"
            :class="[imgClass, loaded || hasError ? 'opacity-100' : 'opacity-0']"
            @load="handleLoad"
            @error="handleError"
            loading="lazy"
        />

        <!-- Slot for overlays (badges, buttons, etc.) -->
        <slot />
    </div>
</template>
