<script setup>
import { Link } from '@inertiajs/vue3'
import BlurImage from '@/Components/BlurImage.vue'
import { ArrowRight, Calendar, User } from 'lucide-vue-next'

defineProps({ post: { type: Object, required: true } })
</script>

<template>
  <Link
    :href="route('blog.show', post.slug)"
    class="group relative bg-white dark:bg-black border-4 border-black dark:border-white shadow-brutalist dark:shadow-brutalist-white transition-all hover:-translate-x-2 hover:-translate-y-2 hover:shadow-brutalist-hover-lg dark:hover:shadow-brutalist-white-lg overflow-hidden flex flex-col"
  >
    <!-- Category Ribbon -->
    <div class="absolute top-4 right-4 z-10 bg-black dark:bg-white px-3 py-1">
      <span class="text-[9px] font-black uppercase tracking-widest text-white dark:text-black">{{ post.category }}</span>
    </div>

    <!-- Cover Image -->
    <div class="h-48 relative overflow-hidden">
      <BlurImage
        v-if="post.cover_image"
        :src="post.cover_image"
        :alt="post.title"
        width="600"
        height="300"
        class="h-full w-full transition-transform duration-500 group-hover:scale-110"
        img-class="object-cover"
      />
      <div
        v-else
        class="h-full bg-gradient-to-br from-brutalist-pink/20 via-brutalist-yellow/10 to-brutalist-blue/20 flex items-center justify-center"
      >
        <div
          class="absolute inset-0 opacity-[0.04]"
          style="background-image: repeating-linear-gradient(0deg, transparent, transparent 2px, #000 2px, #000 3px); background-size: 40px 40px;"
        />
        <span class="text-6xl font-display font-black italic text-black/10 dark:text-white/10 select-none">//</span>
      </div>
    </div>

    <div class="p-6 flex flex-col flex-1">
      <!-- Meta -->
      <div class="flex items-center gap-4 text-[10px] font-black uppercase tracking-widest text-neutral-500 mb-4">
        <span class="flex items-center gap-1.5">
          <Calendar class="w-3 h-3" />
          {{ post.published_at }}
        </span>
        <span class="flex items-center gap-1.5">
          <User class="w-3 h-3" />
          {{ post.author_name }}
        </span>
      </div>

      <!-- Title -->
      <h2 class="text-lg md:text-xl font-display font-black uppercase italic leading-tight mb-3 group-hover:text-brutalist-pink transition-colors break-words">
        {{ post.title }}
      </h2>

      <!-- Excerpt -->
      <p
        v-if="post.excerpt"
        class="text-xs font-black uppercase leading-relaxed text-neutral-600 dark:text-neutral-400 mb-6 flex-1"
      >
        {{ post.excerpt }}
      </p>

      <!-- Tags -->
      <div
        v-if="post.tags?.length"
        class="flex flex-wrap gap-2 mb-4"
      >
        <span
          v-for="tag in post.tags"
          :key="tag"
          class="px-2 py-1 border-2 border-black/20 dark:border-white/20 text-[8px] font-black uppercase tracking-wider"
        >
          {{ tag }}
        </span>
      </div>

      <!-- Read More -->
      <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest group-hover:text-brutalist-pink transition-colors mt-auto">
        LEER CASO
        <ArrowRight class="w-4 h-4 transition-transform group-hover:translate-x-1" />
      </div>
    </div>
  </Link>
</template>
