<script setup lang="ts">
import { computed } from 'vue'
import { marked } from 'marked'
import { markedHighlight } from 'marked-highlight'
import hljs from 'highlight.js'
import 'highlight.js/styles/github-dark.css'

const props = defineProps<{
    content: string
}>()

// Configure marked with marked-highlight extension
marked.use(markedHighlight({
    langPrefix: 'hljs language-',
    highlight(code: string, lang: string) {
        if (lang && hljs.getLanguage(lang)) {
            try {
                return hljs.highlight(code, { language: lang }).value
            } catch (_) {}
        }
        try {
            return hljs.highlightAuto(code).value
        } catch (_) {}
        return code
    },
}))

marked.setOptions({
    breaks: true,
    gfm: true,
})

const renderedHTML = computed(() => {
    if (!props.content) return '<p class="text-neutral-400 italic">Sin contenido aún...</p>'
    try {
        return marked.parse(props.content) as string
    } catch (e) {
        return `<p class="text-red-500">Error al renderizar markdown</p>`
    }
})
</script>

<template>
    <div
        class="prose prose-sm dark:prose-invert max-w-none min-h-[200px] p-6 rounded-xl border-2 border-neutral-200 dark:border-neutral-700 bg-white dark:bg-black/50 overflow-y-auto"
        v-html="renderedHTML"
    ></div>
</template>

<style scoped>
/* Custom prose overrides for brutalist design */
.prose :deep(h1),
.prose :deep(h2),
.prose :deep(h3),
.prose :deep(h4) {
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 900;
    text-transform: uppercase;
    font-style: italic;
    letter-spacing: -0.02em;
}

.prose :deep(h1) {
    font-size: 2.5rem;
    line-height: 0.85;
}

.prose :deep(h2) {
    font-size: 1.75rem;
    line-height: 0.9;
    border-bottom: 4px solid #000;
    padding-bottom: 0.5rem;
}

.dark .prose :deep(h2) {
    border-bottom-color: #fff;
}

.prose :deep(h3) {
    font-size: 1.25rem;
}

.prose :deep(strong) {
    font-weight: 900;
}

.prose :deep(blockquote) {
    border-left: 8px solid #FF4400;
    font-weight: 900;
    font-style: italic;
    text-transform: uppercase;
    padding-left: 1.5rem;
}

.prose :deep(code) {
    font-weight: 600;
    background: #f0f0f0;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.85em;
}

.dark .prose :deep(code):not(pre code) {
    background: #27272a;
}

.prose :deep(pre) {
    border: 2px solid #000;
    border-radius: 12px;
    padding: 1.25rem;
    overflow-x: auto;
}

.dark .prose :deep(pre) {
    border-color: #fff;
}

.prose :deep(pre code) {
    background: none;
    padding: 0;
    border-radius: 0;
}

.prose :deep(img) {
    border: 4px solid #000;
    border-radius: 12px;
}

.dark .prose :deep(img) {
    border-color: #fff;
}

.prose :deep(ul) {
    list-style: none;
    padding-left: 0;
}

.prose :deep(ul li) {
    position: relative;
    padding-left: 1.5rem;
}

.prose :deep(ul li::before) {
    content: '';
    position: absolute;
    left: 0;
    top: 0.65em;
    width: 8px;
    height: 8px;
    transform: rotate(45deg);
    background: #FF4400;
}

.dark .prose :deep(ul li::before) {
    background: #FF4400;
}

.prose :deep(ol) {
    padding-left: 1.5rem;
    list-style: decimal;
}

.prose :deep(ol li) {
    padding-left: 0.5rem;
}

.prose :deep(a) {
    color: #FF2E63;
    font-weight: 900;
    text-decoration: underline;
    text-decoration-thickness: 2px;
    text-underline-offset: 2px;
}

.prose :deep(a:hover) {
    color: #FF4400;
}

.prose :deep(hr) {
    border: none;
    border-top: 4px solid #000;
    margin: 2rem 0;
}

.dark .prose :deep(hr) {
    border-top-color: #fff;
}

.prose :deep(table) {
    border-collapse: collapse;
    width: 100%;
}

.prose :deep(th),
.prose :deep(td) {
    border: 2px solid #000;
    padding: 0.75rem;
    font-weight: 700;
}

.dark .prose :deep(th),
.dark .prose :deep(td) {
    border-color: #fff;
}

.prose :deep(th) {
    background: #000;
    color: #fff;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.1em;
}

.dark .prose :deep(th) {
    background: #fff;
    color: #000;
}
</style>
