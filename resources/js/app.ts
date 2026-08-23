import '../css/app.css';
import '../css/lightbox-animations.css';
import './bootstrap';

import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h, DefineComponent } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import i18n from './i18n';
import posthog from 'posthog-js';
import rekaCleanupPlugin from '@/plugins/rekaCleanup';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Declare global window properties
declare global {
    interface Window {
        __nuwesoft_navStart?: number;
        Echo?: any;
        Pusher?: any;
    }
}

// ── Navigation Timestamp (for skeleton loading timing) ──
router.on('navigate', (event) => {
    window.__nuwesoft_navStart = Date.now();

    // Scroll to top on page navigation (unless preserveScroll is set)
    if (!event.detail?.visit?.preserveScroll) {
        const scrollable = document.querySelector('.scrollbar-imperceptible');
        if (scrollable) {
            scrollable.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
});

// ── PostHog Analytics ──
if (import.meta.env.VITE_POSTHOG_KEY) {
    posthog.init(import.meta.env.VITE_POSTHOG_KEY, {
        api_host: import.meta.env.VITE_POSTHOG_HOST || 'https://us.i.posthog.com',
        person_profiles: 'identified_only',
        capture_pageview: false, // We handle pageviews manually via Inertia
        capture_pageleave: true,
        autocapture: true,
    });

    // Track page views + identify user after successful navigation
    router.on('success', (event) => {
        const ph = posthog as any;
        if (!ph.__loaded) return;

        const page = event.detail?.page;

        // Pageview (window.location.href is already the new URL after success)
        posthog.capture('$pageview', { $current_url: window.location.href });

        // Identify authenticated users
        if (page?.props?.auth?.user) {
            const user = page.props.auth.user as any;
            posthog.identify(user.email, {
                email: user.email,
                name: user.name,
            });
        }
    });
}

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(i18n)
            .use(rekaCleanupPlugin);

        const mountedApp = app.mount(el);

        if (window.Echo) {
            window.Echo.channel('public-updates')
                .listen('.entity.updated', (event: any) => {
                    console.log('Real-time sync event received:', event);
                    router.reload();
                });
        }

        return mountedApp;
    },
    progress: {
        color: '#FF2E63',
        delay: 200,
        showSpinner: false,
    },
});
