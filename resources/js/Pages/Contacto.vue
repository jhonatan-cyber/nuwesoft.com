<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import PublicGridBackground from '@/Components/PublicGridBackground.vue';
import PublicSiteHeader from '@/Components/PublicSiteHeader.vue';
import PublicSiteFooter from '@/Components/PublicSiteFooter.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Textarea } from '@/Components/ui/textarea';
import { Label } from '@/Components/ui/label';
import {
  Card,
  CardHeader,
  CardTitle,
  CardDescription,
  CardContent,
} from '@/Components/ui/card';
import { 
    Mail, 
    Clock, 
    Send, 
    Twitter, 
    Linkedin, 
    Github, 
    MessageSquare,
    Globe
} from 'lucide-vue-next';

const { t } = useI18n();

const form = useForm({
    name: '',
    email: '',
    message: '',
});

const isSubmitting = ref(false);
const showSuccess = ref(false);
const showErrors = ref(false);

const submit = () => {
    isSubmitting.value = true;
    showErrors.value = false;
    form.post(route('contacto.store'), {
        onSuccess: () => {
            isSubmitting.value = false;
            showSuccess.value = true;
            form.reset();
            setTimeout(() => showSuccess.value = false, 5000);
        },
        onError: () => {
            isSubmitting.value = false;
            showErrors.value = true;
            setTimeout(() => showErrors.value = false, 5000);
        },
    });
};

const isVisible = ref(false);
onMounted(() => {
    isVisible.value = true;
});

const socialLinks = [
    { name: 'Twitter', icon: Twitter, href: '#', color: 'hover:bg-brutalist-blue' },
    { name: 'LinkedIn', icon: Linkedin, href: '#', color: 'hover:bg-brutalist-pink' },
    { name: 'GitHub', icon: Github, href: '#', color: 'hover:bg-brutalist-yellow hover:text-black' },
];
</script>

<template>
    <Head :title="t('contacto.head_title')" />
    <div class="min-h-screen overflow-x-hidden bg-white font-sans text-black selection:bg-brutalist-yellow selection:text-black dark:bg-black dark:text-white">
        <PublicGridBackground />
        <PublicSiteHeader />

        <main class="relative z-10">
            <!-- Hero Section -->
            <section class="pt-40 pb-20 px-6 border-b-8 border-black dark:border-white bg-brutalist-yellow dark:bg-brutalist-blue text-black dark:text-white">
                <div class="max-w-[1400px] mx-auto">
                    <div class="inline-block bg-black text-white dark:bg-white dark:text-black px-4 py-2 font-black uppercase tracking-[0.2em] italic mb-8 transform -rotate-2">
                        {{ t('contacto.badge') }}
                    </div>
                    
                    <h1 class="text-[8vw] md:text-[10rem] font-display font-black leading-[0.8] uppercase italic tracking-tighter mb-12">
                        <span class="block">{{ t('contacto.title1') }}</span>
                        <span class="block text-black drop-shadow-[8px_8px_0px_rgba(255,255,255,0.35)] dark:text-white dark:drop-shadow-[8px_8px_0px_rgba(0,0,0,0.45)]">{{ t('contacto.title2') }}</span>
                        <span class="block">{{ t('contacto.title3') }}</span>
                    </h1>

                    <p class="text-3xl md:text-5xl font-black uppercase italic tracking-tighter max-w-4xl leading-none">
                        {{ t('contacto.subtitle') }}
                    </p>
                </div>
            </section>

            <!-- Contact Grid -->
            <section class="max-w-[1400px] mx-auto px-6 py-24 grid grid-cols-1 lg:grid-cols-12 gap-12">
                
                <!-- Info Column -->
                <div class="lg:col-span-5 space-y-12">
                    <div class="p-10 border-4 border-black dark:border-white shadow-brutalist dark:shadow-brutalist-white bg-white dark:bg-black relative group">
                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-brutalist-pink border-4 border-black dark:border-white flex items-center justify-center transform rotate-12 group-hover:rotate-0 transition-transform">
                            <MessageSquare class="w-12 h-12 text-white" />
                        </div>
                        
                        <h2 class="text-4xl font-display font-black uppercase italic mb-10 underline decoration-brutalist-yellow decoration-8 underline-offset-4 text-black dark:text-white">
                            {{ t('contacto.info.title') }}
                        </h2>

                        <div class="space-y-8">
                            <div class="flex items-start space-x-6">
                                <div class="w-12 h-12 bg-brutalist-blue border-4 border-black dark:border-white flex items-center justify-center shrink-0">
                                    <Mail class="w-6 h-6 text-black" />
                                </div>
                                <div>
                                    <span class="block text-sm font-black uppercase text-gray-500">{{ t('contacto.info.email_label') }}</span>
                                    <a href="mailto:hello@nuwesoft.com" class="text-2xl font-black uppercase italic hover:text-brutalist-pink transition-colors text-black dark:text-white">HELLO@NUWESOFT.COM</a>
                                </div>
                            </div>

                            <div class="flex items-start space-x-6">
                                <div class="w-12 h-12 bg-brutalist-yellow border-4 border-black dark:border-white flex items-center justify-center shrink-0">
                                    <Globe class="w-6 h-6 text-black" />
                                </div>
                                <div>
                                    <span class="block text-sm font-black uppercase text-gray-500">{{ t('contacto.info.location_label') }}</span>
                                    <span class="text-2xl font-black uppercase italic text-black dark:text-white">{{ t('contacto.info.location_value') }}</span>
                                </div>
                            </div>

                            <div class="flex items-start space-x-6">
                                <div class="w-12 h-12 bg-brutalist-pink border-4 border-black dark:border-white flex items-center justify-center shrink-0">
                                    <Clock class="w-6 h-6 text-white" />
                                </div>
                                <div>
                                    <span class="block text-sm font-black uppercase text-gray-500">{{ t('contacto.info.hours_label') }}</span>
                                    <span class="text-2xl font-black uppercase italic text-black dark:text-white">{{ t('contacto.info.hours_value') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 pt-12 border-t-4 border-black dark:border-white">
                            <span class="block text-sm font-black uppercase text-gray-500 mb-6">{{ t('contacto.info.social_label') }}</span>
                            <div class="flex space-x-4">
                                <a v-for="social in socialLinks" :key="social.name" :href="social.href" 
                                    class="w-16 h-16 border-4 border-black dark:border-white flex items-center justify-center transition-all shadow-brutalist dark:shadow-brutalist-white hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] text-black dark:text-white"
                                    :class="social.color">
                                    <component :is="social.icon" class="w-8 h-8" />
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Decoration Card -->
                    <div class="hidden lg:block p-8 bg-brutalist-blue border-4 border-black dark:border-white transform -rotate-2 shadow-brutalist dark:shadow-brutalist-white">
                         <p class="text-3xl font-black uppercase italic leading-none text-black">
                            "{{ t('manifesto.quote') }}"
                         </p>
                    </div>
                </div>

                <!-- Form Column -->
                <div class="lg:col-span-7">
                    <Card class="bg-white dark:bg-black rounded-none border-4 border-black dark:border-white shadow-brutalist dark:shadow-brutalist-white overflow-hidden">
                        <CardHeader class="p-10 border-b-4 border-black dark:border-white bg-black dark:bg-white text-white dark:text-black">
                            <CardTitle class="text-5xl font-display font-black uppercase italic tracking-tighter leading-none mb-4">
                                {{ t('contacto.card_title') }} <span class="bg-brutalist-pink text-white px-2">{{ t('contacto.card_title_span') }}</span>
                            </CardTitle>
                            <CardDescription class="text-white/80 dark:text-black/80 font-black uppercase italic tracking-widest text-lg">
                                {{ t('contacto.card_desc') }}
                            </CardDescription>
                        </CardHeader>
                        
                        <CardContent class="p-10">
                            <form @submit.prevent="submit" class="space-y-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-4">
                                        <Label for="nombre" class="text-lg font-black uppercase italic tracking-widest flex items-center space-x-2 text-black dark:text-white">
                                            <span>01. {{ t('contacto.label_name') }}</span>
                                        </Label>
                                        <div v-if="form.errors.name" class="text-sm text-red-500 font-bold">{{ form.errors.name }}</div>
                                        <Input v-model="form.name" id="nombre" 
                                            class="h-16 bg-white dark:bg-black border-4 border-black dark:border-white rounded-none px-6 focus-visible:ring-0 focus-visible:bg-brutalist-yellow/20 text-xl font-bold uppercase italic text-black dark:text-white transition-colors" 
                                            :placeholder="t('contacto.placeholder_name')" required />
                                    </div>
                                    <div class="space-y-4">
                                        <Label for="email" class="text-lg font-black uppercase italic tracking-widest flex items-center space-x-2 text-black dark:text-white">
                                            <span>02. {{ t('contacto.label_email') }}</span>
                                        </Label>
                                        <div v-if="form.errors.email" class="text-sm text-red-500 font-bold">{{ form.errors.email }}</div>
                                        <Input v-model="form.email" id="email" type="email" 
                                            class="h-16 bg-white dark:bg-black border-4 border-black dark:border-white rounded-none px-6 focus-visible:ring-0 focus-visible:bg-brutalist-blue/20 text-xl font-bold uppercase italic text-black dark:text-white transition-colors" 
                                            :placeholder="t('contacto.placeholder_email')" required />
                                    </div>
                                </div>
                                <div class="space-y-4">                                        <Label for="mensaje" class="text-lg font-black uppercase italic tracking-widest flex items-center space-x-2 text-black dark:text-white">
                                            <span>03. {{ t('contacto.label_message') }}</span>
                                        </Label>
                                        <div v-if="form.errors.message" class="text-sm text-red-500 font-bold">{{ form.errors.message }}</div>
                                        <Textarea v-model="form.message" id="mensaje" rows="6" 
                                        class="bg-white dark:bg-black border-4 border-black dark:border-white rounded-none px-6 py-4 focus-visible:ring-0 focus-visible:bg-brutalist-pink/20 text-xl font-bold uppercase italic resize-none text-black dark:text-white transition-colors" 
                                        :placeholder="t('contacto.placeholder_message')" required />
                                </div>

                                <div class="relative">
                                    <Button type="submit" :disabled="isSubmitting"
                                        class="w-full bg-brutalist-pink text-white font-black h-auto py-8 text-3xl border-4 border-black dark:border-white rounded-none shadow-brutalist dark:shadow-brutalist-white hover:shadow-brutalist-hover hover:translate-x-[4px] hover:translate-y-[4px] transition-all uppercase italic group overflow-hidden">
                                        <span v-if="!isSubmitting" class="flex items-center justify-center space-x-4">
                                            <span>{{ t('contacto.submit') }}</span>
                                            <Send class="w-8 h-8 group-hover:translate-x-2 group-hover:-translate-y-2 transition-transform" />
                                        </span>
                                        <span v-else class="flex items-center space-x-4">
                                            <span class="animate-pulse">PROCESSING...</span>
                                        </span>
                                    </Button>

                                    <!-- Success Feedback -->
                                    <div v-if="showSuccess" class="absolute inset-0 bg-brutalist-yellow border-4 border-black flex items-center justify-center z-20 animate-in fade-in zoom-in duration-300">
                                        <span class="text-black font-black text-2xl uppercase italic flex items-center space-x-4">
                                            <ArrowRight class="w-8 h-8" />
                                            <span>{{ t('contacto.alert') }}</span>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </CardContent>
                    </Card>
                </div>
            </section>
        </main>

        <PublicSiteFooter />
    </div>
</template>

<style>
.font-display { font-family: 'Space Grotesk', sans-serif; }
body { 
    @apply bg-white dark:bg-black transition-colors duration-500;
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}
</style>
