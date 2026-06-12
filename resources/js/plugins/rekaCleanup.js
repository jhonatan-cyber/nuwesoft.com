import { router } from '@inertiajs/vue3';
import { forceRadixCleanup } from '@/composables/useRekaCleanup';

/**
 * Vue plugin that automatically cleans up reka-ui's DismissableLayer
 * artifacts whenever Inertia navigates between pages.
 *
 * reka-ui has a cleanup ordering bug where `body.style.pointerEvents`
 * is never restored after a dismissable layer (Dialog, DropdownMenu,
 * Select, etc.) closes. This plugin hooks Inertia's router events to
 * force-clean the DOM on every navigation.
 *
 * Usage in app.js:
 *   import rekaCleanupPlugin from './plugins/rekaCleanup';
 *   app.use(rekaCleanupPlugin);
 */
export default {
    install() {
        // Clean up BEFORE navigation starts (catches any open overlays
        // on the current page before they unmount mid-animation).
        router.on('navigate', () => {
            forceRadixCleanup();
        });

        // Clean up AFTER the new page has loaded (belt-and-suspenders
        // for any styles re-applied during the page transition).
        router.on('success', () => {
            forceRadixCleanup();
        });
    },
};
