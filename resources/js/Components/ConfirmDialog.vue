<script setup>
import { useI18n } from 'vue-i18n';
import { Trash2, AlertTriangle, Info } from 'lucide-vue-next';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter,
} from '@/Components/ui/dialog';
import { Button } from '@/Components/ui/button';
import { useRekaCleanup } from '@/composables/useRekaCleanup';
import { ref, watch } from 'vue';

const { t } = useI18n();

const props = defineProps({
    /** Controls dialog visibility via v-model */
    open: { type: Boolean, default: false },
    /** Dialog title text */
    title: { type: String, default: '' },
    /** Dialog description / body text */
    description: { type: String, default: '' },
    /** Show loading spinner on confirm button */
    loading: { type: Boolean, default: false },
    /** Confirm button label */
    confirmLabel: { type: String, default: '' },
    /** Cancel button label */
    cancelLabel: { type: String, default: '' },
    /** Icon component to show (default: Trash2) */
    icon: { type: [Object, null], default: null },
    /** Color variant: 'danger' | 'warning' | 'info' */
    variant: { type: String, default: 'danger' },
});

const emit = defineEmits(['update:open', 'confirm', 'cancel']);

const internalOpen = ref(props.open);

watch(() => props.open, (val) => { internalOpen.value = val; });
watch(internalOpen, (val) => { emit('update:open', val); });

useRekaCleanup(internalOpen);

const handleClose = () => {
    internalOpen.value = false;
    emit('cancel');
};

const handleConfirm = () => {
    emit('confirm');
};

const resolvedIcon = props.icon || Trash2;

const variantClasses = {
    danger: {
        iconBg: 'bg-red-50 dark:bg-red-500/10',
        iconColor: 'text-red-500',
        confirmBtn: 'bg-red-500 hover:bg-red-600 shadow-lg shadow-red-500/20',
    },
    warning: {
        iconBg: 'bg-amber-50 dark:bg-amber-500/10',
        iconColor: 'text-amber-500',
        confirmBtn: 'bg-amber-500 hover:bg-amber-600 shadow-lg shadow-amber-500/20',
    },
    info: {
        iconBg: 'bg-blue-50 dark:bg-blue-500/10',
        iconColor: 'text-blue-500',
        confirmBtn: 'bg-blue-500 hover:bg-blue-600 shadow-lg shadow-blue-500/20',
    },
};

const v = variantClasses[props.variant] || variantClasses.danger;
</script>

<template>
    <Dialog v-model:open="internalOpen">
        <DialogContent class="sm:max-w-[420px] !rounded-[2rem] border border-neutral-200 dark:border-neutral-800 !bg-white dark:!bg-black shadow-2xl p-8 dashboard-dialog-enter">
            <DialogHeader>
                <div :class="['mx-auto flex h-14 w-14 items-center justify-center rounded-2xl mb-4', v.iconBg]">
                    <component :is="resolvedIcon" :class="['h-7 w-7', v.iconColor]" />
                </div>
                <DialogTitle class="text-xl font-black uppercase text-center tracking-tight text-neutral-900 dark:text-white">
                    {{ title || t('actions.confirm_delete') }}
                </DialogTitle>
                <DialogDescription class="text-center text-sm text-neutral-500 dark:text-neutral-400 mt-2">
                    {{ description }}
                </DialogDescription>
            </DialogHeader>
            <DialogFooter class="flex flex-col-reverse sm:flex-row gap-3 sm:justify-center pt-4">
                <Button variant="outline" @click="handleClose"
                    class="rounded-xl border-neutral-200 dark:border-neutral-700 font-bold text-xs uppercase tracking-widest">
                    {{ cancelLabel || t('actions.cancel') }}
                </Button>
                <Button @click="handleConfirm" :disabled="loading"
                    :class="['rounded-xl text-white font-bold uppercase text-[10px] tracking-widest', v.confirmBtn]">
                    <span v-if="loading" class="animate-pulse">{{ t('messages.actions.deleting') }}</span>
                    <span v-else>{{ confirmLabel || t('actions.delete') }}</span>
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
