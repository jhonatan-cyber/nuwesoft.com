<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { CheckCircle, XCircle, X } from 'lucide-vue-next';

const page = usePage();

const toasts = ref([]);
let toastId = 0;

const addToast = (message, type = 'success') => {
    if (!message) return;

    const id = ++toastId;
    toasts.value.push({ id, message, type });

    // Auto-dismiss after 4 seconds
    setTimeout(() => {
        removeToast(id);
    }, 4000);
};

const removeToast = (id) => {
    const idx = toasts.value.findIndex((t) => t.id === id);
    if (idx !== -1) {
        toasts.value.splice(idx, 1);
    }
};

// Watch flash messages from Inertia
watch(
    () => page.props.flash?.success,
    (msg) => {
        if (msg) addToast(msg, 'success');
    },
    { immediate: true }
);

watch(
    () => page.props.flash?.error,
    (msg) => {
        if (msg) addToast(msg, 'error');
    },
    { immediate: true }
);

// Keyboard dismiss (Escape)
const handleKeydown = (e) => {
    if (e.key === 'Escape' && toasts.value.length > 0) {
        removeToast(toasts.value[toasts.value.length - 1].id);
    }
};

onMounted(() => document.addEventListener('keydown', handleKeydown));
onUnmounted(() => document.removeEventListener('keydown', handleKeydown));
</script>

<template>
    <Teleport to="body">
        <div
            class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 pointer-events-none max-w-sm w-full"
            aria-live="polite"
            aria-label="Notifications"
        >
            <TransitionGroup
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 translate-x-8 scale-95"
                enter-to-class="opacity-100 translate-x-0 scale-100"
                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="opacity-100 translate-x-0 scale-100"
                leave-to-class="opacity-0 translate-x-8 scale-95"
            >
                <div
                    v-for="toast in toasts"
                    :key="toast.id"
                    :class="[
                        'pointer-events-auto flex items-start gap-3 px-5 py-4 rounded-2xl border-2 shadow-xl backdrop-blur-md',
                        toast.type === 'success'
                            ? 'bg-emerald-50/95 dark:bg-emerald-950/95 border-emerald-300 dark:border-emerald-700'
                            : 'bg-rose-50/95 dark:bg-rose-950/95 border-rose-300 dark:border-rose-700',
                    ]"
                    role="alert"
                >
                    <!-- Icon -->
                    <div class="mt-0.5 shrink-0">
                        <CheckCircle
                            v-if="toast.type === 'success'"
                            class="w-5 h-5 text-emerald-600 dark:text-emerald-400"
                        />
                        <XCircle
                            v-else
                            class="w-5 h-5 text-rose-600 dark:text-rose-400"
                        />
                    </div>

                    <!-- Message -->
                    <p
                        :class="[
                            'flex-1 text-sm font-bold leading-snug',
                            toast.type === 'success'
                                ? 'text-emerald-800 dark:text-emerald-200'
                                : 'text-rose-800 dark:text-rose-200',
                        ]"
                    >
                        {{ toast.message }}
                    </p>

                    <!-- Close button -->
                    <button
                        @click="removeToast(toast.id)"
                        :class="[
                            'shrink-0 p-1 rounded-lg transition-colors',
                            toast.type === 'success'
                                ? 'text-emerald-500 hover:bg-emerald-100 dark:hover:bg-emerald-900 dark:text-emerald-400'
                                : 'text-rose-500 hover:bg-rose-100 dark:hover:bg-rose-900 dark:text-rose-400',
                        ]"
                        aria-label="Dismiss notification"
                    >
                        <X class="w-4 h-4" />
                    </button>
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>
