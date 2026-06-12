<script setup>
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
</script>

<template>
    <section class="overflow-hidden border-y-4 border-black bg-black py-8 dark:border-white dark:bg-white">
        <div class="flex whitespace-nowrap animate-[marquee_20s_linear_infinite] will-change-transform">
            <div v-for="n in 10" :key="n" class="flex items-center space-x-12 px-6">
                <span class="text-2xl font-black uppercase italic text-white dark:text-black">{{ t('marquee.clean_code') }}</span>
                <span class="text-3xl text-brutalist-yellow">★</span>
                <span class="text-2xl font-black uppercase italic text-white dark:text-black">{{ t('marquee.scalable') }}</span>
                <span class="text-3xl text-brutalist-pink">★</span>
                <span class="text-2xl font-black uppercase italic text-white dark:text-black">{{ t('marquee.support') }}</span>
                <span class="text-3xl text-brutalist-blue">★</span>
                <span class="text-2xl font-black uppercase italic text-white dark:text-black">{{ t('marquee.agile') }}</span>
                <span class="text-3xl text-brutalist-lime">★</span>
                <span class="text-2xl font-black uppercase italic text-white dark:text-black">{{ t('marquee.innovation') }}</span>
                <span class="text-3xl text-brutalist-purple">★</span>
            </div>
        </div>
    </section>
</template>

<style>
@keyframes marquee {
    from { transform: translateX(0); }
    to { transform: translateX(-50%); }
}
</style>
