import { watch, nextTick, onUnmounted } from 'vue';

/**
 * Force-clean every trace reka-ui leaves behind on the DOM.
 *
 * reka-ui's DismissableLayer has a cleanup ordering bug: when a dialog
 * closes, two watchEffect cleanups run LIFO. The first removes the layer
 * from the internal set; the second checks whether it was the *last* layer
 * before restoring `body.style.pointerEvents`. Since the layer is already
 * gone, the check fails and `pointer-events` is never restored — leaving
 * the entire page unclickable.
 *
 * Additionally, reka-ui's Presence delays unmount of the overlay until the
 * exit animation finishes (~200ms). If something interrupts the animation
 * (e.g. Inertia navigation), the overlay stays in the DOM, blocking all clicks.
 */
export function forceRadixCleanup() {
    // Body styles & attributes
    document.body.removeAttribute('data-scroll-locked');
    document.body.removeAttribute('data-reka-scroll-locked');
    document.body.style.removeProperty('pointer-events');
    document.body.style.removeProperty('overflow');
    document.body.style.removeProperty('padding-right');
    document.body.style.removeProperty('position');
    document.body.style.removeProperty('top');
    document.body.style.removeProperty('left');
    document.body.style.removeProperty('right');

    // Nuke any leftover reka-ui overlay/dialog elements stuck in the DOM
    document.querySelectorAll('[data-reka-dialog-overlay]').forEach(el => el.remove());
    document.querySelectorAll('[data-reka-dialog-content]').forEach(el => el.remove());
}

/**
 * Reactive composable that watches one or more dialog-open refs and
 * runs `forceRadixCleanup` whenever ALL of them become false.
 *
 * @param {...import('vue').Ref<boolean>} openRefs - One or more reactive refs
 *   that control dialog open states.
 *
 * @example
 * ```js
 * const isCreateOpen = ref(false);
 * const isEditOpen = ref(false);
 *
 * useRekaCleanup(isCreateOpen, isEditOpen);
 * ```
 */
export function useRekaCleanup(...openRefs) {
    // Fire cleanup whenever ALL modals are closed
    watch(
        openRefs,
        (vals) => {
            if (vals.every(v => !v)) {
                // immediate: remove styles reka-ui already set
                nextTick(() => forceRadixCleanup());
                // delayed: reka-ui may re-apply styles during the closing animation
                setTimeout(() => forceRadixCleanup(), 300);
            }
        },
    );

    // When Inertia unmounts this component (after a form POST), reka-ui's
    // onUnmounted hooks may never fire → ensure cleanup here too.
    onUnmounted(() => forceRadixCleanup());
}
