<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed, nextTick, onMounted, onUnmounted, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import PublicGridBackground from '@/Components/PublicGridBackground.vue';
import PublicSiteHeader from '@/Components/PublicSiteHeader.vue';
import PublicSiteFooter from '@/Components/PublicSiteFooter.vue';
import PortfolioProjectHero from '@/Components/PortfolioProjectHero.vue';
import PortfolioProjectGallery from '@/Components/PortfolioProjectGallery.vue';
import PortfolioProjectInfo from '@/Components/PortfolioProjectInfo.vue';
import { Button } from '@/Components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogTitle } from '@/Components/ui/dialog';
import { useRekaCleanup } from '@/composables/useRekaCleanup';
import { useCspNonce } from '@/composables/useCspNonce';
import {
    ArrowLeft,
    ChevronLeft,
    ChevronRight,
    ExternalLink
} from 'lucide-vue-next';
import BlurImage from '@/Components/BlurImage.vue'

const props = defineProps({
    project: { type: Object, required: true },
});

const { t } = useI18n();

const page = usePage();
const cspNonce = useCspNonce();
const settings = computed(() => page.props.settings || {});
const siteName = computed(() => settings.value.site_name || 'NUWESOFT');
const pageUrl = computed(() => window.location.href);

const projectJsonLd = computed(() => ({
    '@context': 'https://schema.org',
    '@type': 'CreativeWork',
    name: props.project.name,
    description: props.project.desc,
    url: pageUrl.value,
    dateCreated: props.project.created_at,
    dateModified: props.project.updated_at,
    keywords: props.project.technologies?.map(t => t.name).join(', ') || '',
    about: {
        '@type': 'Thing',
        name: props.project.category,
    },
    author: {
        '@type': 'Organization',
        name: siteName.value,
    },
    image: allImages.value.length > 0 ? allImages.value[0].image_url : undefined,
}))
// ── Lightbox ──
const lightboxOpen = ref(false);
const lightboxIndex = ref(0);
const lightboxViewport = ref(null);

useRekaCleanup(lightboxOpen);

const openLightbox = (index = 0) => {
    lightboxIndex.value = index;
    lightboxOpen.value = true;
};

const closeLightbox = () => {
    lightboxOpen.value = false;
};

const showNext = () => {
    const images = props.project.images || [];
    lightboxIndex.value = (lightboxIndex.value + 1) % images.length;
};

const showPrev = () => {
    const images = props.project.images || [];
    lightboxIndex.value = (lightboxIndex.value - 1 + images.length) % images.length;
};

watch(lightboxIndex, async () => {
    await nextTick();
    lightboxViewport.value?.scrollTo({ top: 0, behavior: 'smooth' });
});

const handleKeydown = (e) => {
    if (!lightboxOpen.value) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowRight') showNext();
    if (e.key === 'ArrowLeft') showPrev();
};

onMounted(() => window.addEventListener('keydown', handleKeydown));
onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
});

const allImages = computed(() => {
    if (props.project.images?.length) return props.project.images;
    return [];
});

</script>

<template>
  <Head :title="`${project.name} | ${siteName} Engineering`">
    <meta
      name="description"
      :content="project.desc"
    >
    <meta
      property="og:title"
      :content="`${project.name} | ${siteName} Engineering`"
    >
    <meta
      property="og:description"
      :content="project.desc"
    >
    <meta
      property="og:type"
      content="article"
    >
    <meta
      property="og:url"
      :content="pageUrl"
    >
    <meta
      v-if="allImages.length > 0"
      property="og:image"
      :content="allImages[0].image_url"
    >
    <meta
      name="twitter:card"
      content="summary_large_image"
    >
    <meta
      name="twitter:title"
      :content="`${project.name} | ${siteName} Engineering`"
    >
    <meta
      name="twitter:description"
      :content="project.desc"
    >
    <meta
      v-if="allImages.length > 0"
      name="twitter:image"
      :content="allImages[0].image_url"
    >
    <link
      rel="canonical"
      :href="pageUrl"
    >
  </Head>

  <Teleport to="head">
    <!-- JSON-LD serialized with JSON.stringify(); the slot renders no raw markup -->
    <!-- eslint-disable vue/no-v-html, vue/no-v-text-v-html-on-component -->
    <component
      :is="'script'"
      :nonce="cspNonce"
      type="application/ld+json"
      v-html="JSON.stringify(projectJsonLd)"
    />
    <!-- eslint-enable vue/no-v-html, vue/no-v-text-v-html-on-component -->
  </Teleport>
  <div class="min-h-screen overflow-x-hidden bg-white font-sans text-black selection:bg-brutalist-yellow selection:text-black dark:bg-black dark:text-white portfolio-detail">
    <PublicGridBackground />
    <PublicSiteHeader />

    <main
      id="main-content"
      class="relative z-10 pt-32"
    >
      <!-- ═══ Content ═══ -->
      <div>
        <PortfolioProjectHero
          :project="project"
          :all-images="allImages"
        />

        <PortfolioProjectGallery
          :project="project"
          :all-images="allImages"
          @open="openLightbox"
        />

        <PortfolioProjectInfo
          :project="project"
          :images="allImages"
        />

        <!-- ═══ CTA ═══ -->
        <section class="relative border-y-8 border-black bg-black py-20 text-white dark:border-white dark:bg-white dark:text-black">
          <div
            class="absolute inset-0"
            style="background-image: repeating-linear-gradient(0deg, transparent, transparent 40px, rgba(255,255,255,0.02) 40px, rgba(255,255,255,0.02) 41px);"
            aria-hidden="true"
          />
          <div class="max-w-[1400px] mx-auto px-6 relative z-10">
            <div class="flex flex-col items-center text-center gap-8">
              <span class="inline-flex h-5 w-5 rotate-45 border-2 border-white bg-brutalist-yellow dark:border-black" />
              <h2 class="text-[clamp(2rem,4vw,3.5rem)] font-display font-black uppercase italic leading-[0.9]">
                {{ t('portafolio.cta_title') || 'EXPLORA MAS PROYECTOS' }}
              </h2>
              <p class="text-lg font-black uppercase italic text-white/70 dark:text-black/70 max-w-lg">
                {{ t('portafolio.cta_desc') || 'Cada proyecto cuenta una historia diferente. Volvé al portfolio y descubrí más trabajos.' }}
              </p>
              <Link :href="route('portafolio')">
                <span class="inline-flex items-center gap-3 border-4 border-white bg-white px-10 py-4 text-sm font-black uppercase italic tracking-[0.2em] text-black transition-all hover:translate-x-[4px] hover:translate-y-[4px] dark:border-black dark:bg-black dark:text-white">
                  {{ t('portafolio.back') || 'VOLVER AL PORTAFOLIO' }}
                  <ArrowLeft class="w-5 h-5" />
                </span>
              </Link>
            </div>
          </div>
        </section>
      </div>
    </main>

    <PublicSiteFooter />

    <!-- ═══ Lightbox ═══ -->
    <Dialog v-model:open="lightboxOpen">
      <DialogContent
        class="!left-0 !top-0 !block !h-dvh !w-screen !max-w-none !translate-x-0 !translate-y-0 !gap-0 overflow-hidden !rounded-none !border-0 !bg-black !text-white !p-0 lightbox-dialog-enter"
      >
        <DialogTitle class="sr-only">
          {{ project.name }}
        </DialogTitle>
        <DialogDescription class="sr-only">
          {{ project.desc }}
        </DialogDescription>
        <div class="grid h-full grid-rows-[minmax(0,1fr)_14rem] lg:grid-cols-[minmax(0,1fr)_22rem] lg:grid-rows-1">
          <div
            ref="lightboxViewport"
            class="relative min-h-0 overflow-y-auto overflow-x-hidden bg-zinc-950 custom-scrollbar"
          >
            <transition
              name="lightbox-image"
              mode="out-in"
            >
              <img
                :key="lightboxIndex"
                :src="allImages[lightboxIndex]?.image_url"
                :alt="project.name"
                class="block h-auto w-full object-contain object-top"
                loading="lazy"
              >
            </transition>

            <button
              v-if="allImages.length > 1"
              type="button"
              class="absolute left-4 top-1/2 -translate-y-1/2 border-2 border-white bg-black/80 p-3 text-white transition-all hover:bg-brutalist-yellow hover:text-black hover:scale-110"
              :aria-label="'Previous image ' + (lightboxIndex) + ' of ' + allImages.length"
              @click.stop="showPrev"
            >
              <ChevronLeft class="h-6 w-6" />
            </button>
            <button
              v-if="allImages.length > 1"
              type="button"
              class="absolute right-4 top-1/2 -translate-y-1/2 border-2 border-white bg-black/80 p-3 text-white transition-all hover:bg-brutalist-yellow hover:text-black hover:scale-110"
              :aria-label="'Next image ' + (lightboxIndex + 2) + ' of ' + allImages.length"
              @click.stop="showNext"
            >
              <ChevronRight class="h-6 w-6" />
            </button>

            <div class="absolute bottom-4 left-4 border-2 border-white/30 bg-black/70 px-3 py-1.5 text-xs font-black uppercase tracking-wider">
              {{ lightboxIndex + 1 }} / {{ allImages.length }}
            </div>
          </div>

          <aside class="flex min-h-0 flex-col border-t-4 border-white bg-black p-4 lg:border-l-4 lg:border-t-0">
            <div class="mb-3 text-[11px] font-black uppercase tracking-[0.28em] text-brutalist-yellow flex items-center gap-2">
              <span class="inline-block w-6 h-px bg-brutalist-yellow" />
              {{ t('portafolio.project_label') }}
            </div>

            <div class="flex items-end justify-between gap-3">
              <h3 class="text-xl font-display font-black uppercase italic leading-none">
                {{ project.name }}
              </h3>
              <span class="shrink-0 border-2 border-white px-2 py-1 text-[10px] font-black">{{ lightboxIndex + 1 }} / {{ allImages.length }}</span>
            </div>
            <p class="sr-only">
              {{ project.desc }}
            </p>

            <div
              v-if="allImages.length > 1"
              class="mt-4 flex min-h-0 flex-1 flex-col"
            >
              <div class="mb-3 text-[10px] font-black uppercase tracking-[0.24em] text-white/50">
                {{ t('portafolio.gallery_label') }}
              </div>
              <div class="grid min-h-0 grid-cols-4 gap-2 overflow-y-auto pr-1 custom-scrollbar lg:grid-cols-1">
                <button
                  v-for="(image, idx) in allImages"
                  :key="image.id"
                  type="button"
                  class="overflow-hidden border-2 transition-all duration-200 hover:scale-105"
                  :class="idx === lightboxIndex ? 'border-brutalist-yellow' : 'border-white/20 hover:border-white/50'"
                  @click="lightboxIndex = idx"
                >
                  <BlurImage
                    :src="image.image_url"
                    :alt="`${project.name} - imagen ${idx + 1}`"
                    :width="320"
                    :height="180"
                    class="h-16 w-full lg:h-28"
                  />
                </button>
              </div>
            </div>

            <a
              v-if="project.project_url"
              :href="project.project_url"
              target="_blank"
              class="mt-3 hidden lg:block"
            >
              <Button class="h-auto w-full rounded-none border-4 border-white bg-white py-4 font-black uppercase italic text-black transition-all hover:bg-brutalist-pink hover:text-white group/btn">
                <span class="flex items-center justify-center gap-2">
                  {{ t('portafolio.view_project') }}
                  <ExternalLink class="h-4 w-4 group-hover/btn:translate-x-1 group-hover/btn:-translate-y-1 transition-transform" />
                </span>
              </Button>
            </a>
          </aside>
        </div>

        <!-- DialogClose button (X) positioned by DialogContent -->
      </DialogContent>
    </Dialog>
  </div>
</template>

<style>
.font-display { font-family: 'Space Grotesk', sans-serif; }

body {
    @apply bg-white dark:bg-black transition-colors duration-500;
}

</style>
