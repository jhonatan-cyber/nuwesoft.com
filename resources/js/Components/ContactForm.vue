<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Textarea } from '@/Components/ui/textarea'
import { Label } from '@/Components/ui/label'
import {
    Card,
    CardHeader,
    CardTitle,
    CardDescription,
    CardContent,
} from '@/Components/ui/card'
import { Send, ArrowRight, CheckCircle, Paperclip, FileText, X } from 'lucide-vue-next'
import { useInView } from '@/composables/useInView'

const { t } = useI18n()
const { el: sectionRef, isVisible } = useInView(0.15)

const props = defineProps({
    antiSpamToken: { type: String, default: '' },
})

const form = useForm({
    nombre: '',
    email: '',
    mensaje: '',
    attachment: null,
    // Anti-spam: honeypot (must stay empty) + timing token
    website_url: '',
    form_token: props.antiSpamToken,
})

const attachmentFile = ref(null)
const attachmentPreview = ref(null)

const handleAttachment = (e) => {
    const file = e.target.files?.[0]
    if (!file) return
    attachmentFile.value = file
    attachmentPreview.value = {
        name: file.name,
        size: (file.size / 1024).toFixed(1) + ' KB',
        type: file.type,
    }
    form.attachment = file
}

const removeAttachment = () => {
    attachmentFile.value = null
    attachmentPreview.value = null
    form.attachment = null
}

import { usePostHog } from '@/composables/usePostHog'

const { capture } = usePostHog()

const showSuccess = ref(false)

const submit = () => {
    form.post(route('contacto.send'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            capture('contact_form_success')
            form.reset()
            showSuccess.value = true
            setTimeout(() => showSuccess.value = false, 5000)
        },
        onError: () => {
            capture('contact_form_error', {
                errors: Object.keys(form.errors),
            })
        },
    })
}
</script>

<template>
    <div ref="sectionRef">
        <Card
            :class="[
                'overflow-hidden rounded-none border-4 border-black shadow-brutalist transition-all duration-700 dark:border-white dark:shadow-brutalist-white',
                isVisible ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0',
            ]"
        >
            <CardHeader
                class="border-b-4 border-black bg-black p-10 text-white dark:border-white dark:bg-white dark:text-black"
            >
                <CardTitle
                    class="font-display text-5xl font-black uppercase italic leading-none tracking-tighter"
                >
                    {{ t('contacto.card_title') }}
                    <span class="bg-brutalist-pink px-2 text-white">{{ t('contacto.card_title_span') }}</span>
                </CardTitle>
                <CardDescription
                    class="mt-4 text-lg font-black uppercase italic tracking-widest text-white/80 dark:text-black/80"
                >
                    {{ t('contacto.card_desc') }}
                </CardDescription>
            </CardHeader>

            <CardContent class="relative p-10">
                <!-- Success overlay -->
                <Transition name="success-fade">
                    <div
                        v-if="showSuccess"
                        class="absolute inset-0 z-20 flex items-center justify-center border-4 border-black bg-brutalist-yellow"
                    >
                        <span class="flex items-center gap-4 text-2xl font-black uppercase italic text-black">
                            <CheckCircle class="h-10 w-10" />
                            <span>{{ t('contacto.alert') }}</span>
                        </span>
                    </div>
                </Transition>

                <form @submit.prevent="submit" class="space-y-8">
                    <!-- Honeypot: invisible to humans, bots auto-fill this -->
                    <div class="absolute" :style="{ position: 'absolute', left: '-9999px', opacity: 0, height: 0, width: 0, overflow: 'hidden' }" aria-hidden="true">
                        <label for="website_url">Do not fill this</label>
                        <input
                            id="website_url"
                            v-model="form.website_url"
                            type="text"
                            name="website_url"
                            tabindex="-1"
                            autocomplete="off"
                        />
                    </div>
                    <!-- Anti-spam timing token -->
                    <input type="hidden" name="form_token" :value="form.form_token" />
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <!-- Name -->
                        <div class="space-y-4">
                            <Label
                                for="nombre"
                                class="flex items-center gap-2 text-sm md:text-lg font-black uppercase italic tracking-widest text-black dark:text-white"
                            >
                                <span class="break-words">01. {{ t('contacto.label_name') }}</span>
                            </Label>
                            <Input
                                id="nombre"
                                v-model="form.nombre"
                                :placeholder="t('contacto.placeholder_name')"
                                required
                                :class="[
                                    'h-16 rounded-none border-4 border-black bg-white px-6 text-xl font-bold uppercase italic text-black transition-colors placeholder:text-gray-400 focus-visible:ring-0 dark:bg-black dark:text-white dark:placeholder:text-gray-600',
                                    form.errors.nombre
                                        ? 'border-brutalist-pink bg-brutalist-pink/10'
                                        : 'focus-visible:bg-brutalist-yellow/20',
                                ]"
                            />
                            <p v-if="form.errors.nombre" class="text-sm font-black uppercase italic text-brutalist-pink">
                                {{ form.errors.nombre }}
                            </p>
                        </div>

                        <!-- Email -->
                        <div class="space-y-4">
                            <Label
                                for="email"
                                class="flex items-center gap-2 text-sm md:text-lg font-black uppercase italic tracking-widest text-black dark:text-white"
                            >
                                <span class="break-words">02. {{ t('contacto.label_email') }}</span>
                            </Label>
                            <Input
                                id="email"
                                v-model="form.email"
                                type="email"
                                :placeholder="t('contacto.placeholder_email')"
                                required
                                :class="[
                                    'h-16 rounded-none border-4 border-black bg-white px-6 text-xl font-bold uppercase italic text-black transition-colors placeholder:text-gray-400 focus-visible:ring-0 dark:bg-black dark:text-white dark:placeholder:text-gray-600',
                                    form.errors.email
                                        ? 'border-brutalist-pink bg-brutalist-pink/10'
                                        : 'focus-visible:bg-brutalist-blue/20',
                                ]"
                            />
                            <p v-if="form.errors.email" class="text-sm font-black uppercase italic text-brutalist-pink">
                                {{ form.errors.email }}
                            </p>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="space-y-4">
                        <Label
                            for="mensaje"
                            class="flex items-center gap-2 text-sm md:text-lg font-black uppercase italic tracking-widest text-black dark:text-white"
                        >
                            <span class="break-words">03. {{ t('contacto.label_message') }}</span>
                        </Label>
                        <Textarea
                            id="mensaje"
                            v-model="form.mensaje"
                            rows="6"
                            :placeholder="t('contacto.placeholder_message')"
                            required
                            :class="[
                                'rounded-none border-4 border-black bg-white px-6 py-4 text-xl font-bold uppercase italic text-black transition-colors placeholder:text-gray-400 focus-visible:ring-0 resize-none dark:bg-black dark:text-white dark:placeholder:text-gray-600',
                                form.errors.mensaje
                                    ? 'border-brutalist-pink bg-brutalist-pink/10'
                                    : 'focus-visible:bg-brutalist-pink/20',
                            ]"
                        />
                        <p v-if="form.errors.mensaje" class="text-sm font-black uppercase italic text-brutalist-pink">
                            {{ form.errors.mensaje }}
                        </p>
                    </div>

                    <!-- File Attachment -->
                    <div class="space-y-4">
                        <Label
                            class="flex items-center gap-2 text-sm md:text-lg font-black uppercase italic tracking-widest text-black dark:text-white"
                        >
                            <span class="break-words">04. {{ t('contacto.label_attachment') }}</span>
                            <span class="text-[10px] font-bold normal-case tracking-normal text-neutral-400">(Opcional)</span>
                        </Label>

                        <!-- No file selected -->
                        <div v-if="!attachmentPreview" class="relative">
                            <label
                                class="flex cursor-pointer items-center gap-4 rounded-none border-4 border-dashed border-black bg-white px-6 py-5 transition-colors hover:bg-brutalist-yellow/10 dark:border-white dark:bg-black dark:hover:bg-white/5"
                            >
                                <div class="flex h-12 w-12 items-center justify-center rounded-none border-2 border-black bg-neutral-100 dark:border-white dark:bg-neutral-900">
                                    <Paperclip class="h-6 w-6 text-neutral-500" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-black uppercase italic text-black dark:text-white">
                                        {{ t('contacto.attachment_prompt') }}
                                    </p>
                                    <p class="mt-1 text-[10px] font-bold uppercase tracking-widest text-neutral-400">
                                        PDF, DOC, DOCX — {{ t('contacto.attachment_max_size') }}
                                    </p>
                                </div>
                                <input
                                    type="file"
                                    accept=".pdf,.doc,.docx"
                                    class="hidden"
                                    @change="handleAttachment"
                                />
                            </label>
                        </div>

                        <!-- File selected -->
                        <div
                            v-else
                            class="flex items-center gap-4 rounded-none border-4 border-black bg-white px-6 py-4 dark:border-white dark:bg-black"
                        >
                            <div class="flex h-12 w-12 items-center justify-center rounded-none border-2 border-brutalist-pink bg-brutalist-pink/10">
                                <FileText class="h-6 w-6 text-brutalist-pink" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-black uppercase italic text-black dark:text-white truncate">
                                    {{ attachmentPreview.name }}
                                </p>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">
                                    {{ attachmentPreview.size }}
                                </p>
                            </div>
                            <button
                                type="button"
                                @click="removeAttachment"
                                class="flex h-8 w-8 items-center justify-center rounded-none border-2 border-black text-black transition-colors hover:bg-brutalist-pink hover:text-white dark:border-white dark:text-white"
                            >
                                <X class="h-4 w-4" />
                            </button>
                        </div>

                        <p v-if="form.errors.attachment" class="text-sm font-black uppercase italic text-brutalist-pink">
                            {{ form.errors.attachment }}
                        </p>
                    </div>

                    <!-- Submit -->
                    <div class="relative">
                        <Button
                            type="submit"
                            :disabled="form.processing"
                            class="group h-auto w-full rounded-none border-4 border-black bg-brutalist-pink py-6 md:py-8 text-xl md:text-3xl font-black uppercase italic text-white shadow-brutalist transition-all hover:translate-x-[4px] hover:translate-y-[4px] hover:bg-brutalist-yellow hover:text-black hover:shadow-brutalist-hover dark:border-white dark:shadow-brutalist-white dark:hover:bg-white dark:hover:text-black dark:hover:shadow-[4px_4px_0px_0px_rgba(255,255,255,1)]"
                        >
                            <span v-if="!form.processing" class="flex items-center justify-center gap-3 md:gap-4">
                                <span>{{ t('contacto.submit') }}</span>
                                <Send class="h-6 w-6 md:h-8 md:w-8 transition-transform group-hover:translate-x-2 group-hover:-translate-y-2" />
                            </span>
                            <span v-else class="flex items-center gap-4">
                                <span class="flex gap-1">
                                    <span class="h-3 w-3 animate-bounce rounded-full bg-white" style="animation-delay: 0s"></span>
                                    <span class="h-3 w-3 animate-bounce rounded-full bg-white" style="animation-delay: 0.15s"></span>
                                    <span class="h-3 w-3 animate-bounce rounded-full bg-white" style="animation-delay: 0.3s"></span>
                                </span>
                                <span>PROCESSING...</span>
                            </span>
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>

<style scoped>
.success-fade-enter-active {
    transition: all 0.3s ease-out;
}
.success-fade-leave-active {
    transition: all 0.4s ease-in;
}
.success-fade-enter-from {
    opacity: 0;
    transform: scale(0.9);
}
.success-fade-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}
</style>
