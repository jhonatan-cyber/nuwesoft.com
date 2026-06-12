import { vi } from 'vitest'
import { config } from '@vue/test-utils'
import { ref, h } from 'vue'

// ── Mock vue-i18n ──
vi.mock('vue-i18n', () => ({
    useI18n: () => ({
        t: (key: string) => key, // return the key itself for easy assertion
        locale: { value: 'es' },
        locales: { value: ['es', 'en'] },
    }),
    createI18n: () => ({
        global: {
            install: (app: any) => {
                app.config.globalProperties.$t = (key: string) => key
            },
        },
    }),
}))

// ── Mock @inertiajs/vue3 ──
vi.mock('@inertiajs/vue3', () => ({
    usePage: () => ({
        props: {
            settings: { site_name: 'NUWESOFT' },
        },
    }),
    Link: {
        name: 'Link',
        props: {
            href: { type: String, default: '#' },
        },
        setup(props: any, { slots }: any) {
            return () =>
                h('a', { href: props.href || '#', class: 'mock-link' }, slots.default?.())
        },
    },
    Head: {
        name: 'Head',
        props: {
            title: { type: String, default: '' },
        },
        setup(_: any, { slots }: any) {
            return () => slots.head?.() || h('head')
        },
    },
    router: {
        get: vi.fn(),
        post: vi.fn(),
        put: vi.fn(),
        delete: vi.fn(),
    },
}))

// ── Mock @/composables/useInView ──
vi.mock('@/composables/useInView', () => ({
    useInView: () => ({
        el: ref(null),
        isVisible: ref(true), // always visible in tests for consistent snapshots
    }),
}))

// ── Mock @/composables/usePageTracking ──
vi.mock('@/composables/usePageTracking', () => ({
    usePageTracking: () => ({}),
}))

// ── Mock @/composables/useSkeletonLoader ──
vi.mock('@/composables/useSkeletonLoader', () => ({
    useSkeletonLoader: () => ({
        skeletonReady: true,
    }),
}))

// ── Mock @/composables/usePostHog ──
vi.mock('@/composables/usePostHog', () => ({
    usePostHog: () => ({
        capture: vi.fn(),
    }),
}))

// ── Mock @/Components/ui/badge ──
vi.mock('@/Components/ui/badge', () => ({
    Badge: {
        name: 'Badge',
        props: {
            variant: { type: String, default: 'default' },
        },
        setup(props: any, { slots }: any) {
            return () =>
                h('span', {
                    class: `mock-badge ${props.variant === 'secondary' ? 'mock-badge-secondary' : ''}`,
                }, slots.default?.())
        },
    },
}))

// ── Mock @/Components/ui/button ──
vi.mock('@/Components/ui/button', () => ({
    Button: {
        name: 'Button',
        props: {
            variant: { type: String, default: 'default' },
            asChild: { type: Boolean, default: false },
        },
        setup(props: any, { slots }: any) {
            return () =>
                h('button', {
                    class: `mock-button ${props.variant === 'outline' ? 'mock-button-outline' : ''}`,
                }, slots.default?.())
        },
    },
}))

// ── Mock @/Components/ui/card ──
vi.mock('@/Components/ui/card', () => ({
    Card: {
        name: 'Card',
        setup(_: any, { slots }: any) {
            return () => h('div', { class: 'mock-card' }, slots.default?.())
        },
    },
    CardContent: {
        name: 'CardContent',
        setup(_: any, { slots }: any) {
            return () => h('div', { class: 'mock-card-content' }, slots.default?.())
        },
    },
    CardHeader: {
        name: 'CardHeader',
        setup(_: any, { slots }: any) {
            return () => h('div', { class: 'mock-card-header' }, slots.default?.())
        },
    },
    CardTitle: {
        name: 'CardTitle',
        setup(_: any, { slots }: any) {
            return () => h('h3', { class: 'mock-card-title' }, slots.default?.())
        },
    },
    CardDescription: {
        name: 'CardDescription',
        setup(_: any, { slots }: any) {
            return () => h('p', { class: 'mock-card-description' }, slots.default?.())
        },
    },
}))

// ── Mock window.matchMedia ──
Object.defineProperty(window, 'matchMedia', {
    writable: true,
    value: vi.fn().mockImplementation((query: string) => ({
        matches: false,
        media: query,
        onchange: null,
        addListener: vi.fn(),
        removeListener: vi.fn(),
        addEventListener: vi.fn(),
        removeEventListener: vi.fn(),
        dispatchEvent: vi.fn(),
    })),
})

// ── Mock IntersectionObserver ──
class MockIntersectionObserver {
    observe = vi.fn()
    unobserve = vi.fn()
    disconnect = vi.fn()
}

Object.defineProperty(window, 'IntersectionObserver', {
    writable: true,
    value: MockIntersectionObserver,
})

// ── Mock ResizeObserver ──
class MockResizeObserver {
    observe = vi.fn()
    unobserve = vi.fn()
    disconnect = vi.fn()
}

Object.defineProperty(window, 'ResizeObserver', {
    writable: true,
    value: MockResizeObserver,
})

// ── Mock route() function (Ziggy) ──
// @ts-ignore
window.route = (name: string, params?: any) => {
    return `/mock/${name}${params ? '?' + new URLSearchParams(params).toString() : ''}`
}
