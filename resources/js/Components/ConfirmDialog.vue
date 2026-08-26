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
    /** Label shown while the confirmation request is running */
    loadingLabel: { type: String, default: '' },
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
        iconBg: 'bg-status-danger/10',
        iconColor: 'text-status-danger',
        confirmBtn: 'bg-status-danger hover:bg-status-danger/90 shadow-lg shadow-status-danger/20',
    },
    warning: {
        iconBg: 'bg-status-warning/10',
        iconColor: 'text-status-warning',
        confirmBtn: 'bg-status-warning hover:bg-status-warning/90 shadow-lg shadow-status-warning/20',
    },
    info: {
        iconBg: 'bg-status-info/10',
        iconColor: 'text-status-info',
        confirmBtn: 'bg-status-info hover:bg-status-info/90 shadow-lg shadow-status-info/20',
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
                    <span v-if="loading" class="animate-pulse">
                        {{ loadingLabel || t('messages.actions.deleting') }}
                    </span>
                    <span v-else>{{ confirmLabel || t('actions.delete') }}</span>
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
