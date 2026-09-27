<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import { usePageTracking } from '@/composables/usePageTracking'
import { useSkeletonLoader } from '@/composables/useSkeletonLoader'
import PublicGridBackground from '@/Components/PublicGridBackground.vue'
import PublicSiteHeader from '@/Components/PublicSiteHeader.vue'
import PublicSiteFooter from '@/Components/PublicSiteFooter.vue'
import NewsletterForm from '@/Components/NewsletterForm.vue'
import SkeletonPostCard from '@/Components/SkeletonPostCard.vue'
import LazyLoad from '@/Components/LazyLoad.vue'
import BlogPostCard from '@/Components/BlogPostCard.vue'
import { Badge } from '@/Components/ui/badge'
import {
    Pagination,
    PaginationEllipsis,
    PaginationFirst,
    PaginationLast,
    PaginationList,
    PaginationListItem,
    PaginationNext,
    PaginationPrev,
} from '@/Components/ui/pagination'
import { Search, X } from 'lucide-vue-next'
import { safeJsonLd } from '@/utils/safeJsonLd'
import { useCspNonce } from '@/composables/useCspNonce'

const page = usePage()
const cspNonce = useCspNonce()
const settings = computed(() => page.props.settings || {})
const siteName = computed(() => settings.value.site_name || 'NUWESOFT')
const pageUrl = computed(() => window.location.href)

usePageTracking()

const { skeletonReady } = useSkeletonLoader()

const props = defineProps({
    posts: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
})

const categories = [
    { key: null, label: 'ALL' },
    { key: 'case-study', label: 'CASE STUDY' },
    { key: 'technical', label: 'TECHNICAL' },
    { key: 'news', label: 'NEWS' },
    { key: 'insights', label: 'INSIGHTS' },
]

const activeCategory = ref(props.filters?.category || null)
const searchQuery = ref(props.filters?.search || '')

const filterByCategory = (category) => {
    activeCategory.value = category
    const params = {}
    if (category) params.category = category
    if (searchQuery.value) params.search = searchQuery.value
    router.get(route('blog.index'), params, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
}

const applySearch = () => {
    const params = {}
    if (activeCategory.value) params.category = activeCategory.value
    if (searchQuery.value) params.search = searchQuery.value
    router.get(route('blog.index'), params, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
}

const clearSearch = () => {
    searchQuery.value = ''
    applySearch()
}

const handlePageChange = (newPage) => {
    const params = {}
    if (activeCategory.value) params.category = activeCategory.value
    if (searchQuery.value) params.search = searchQuery.value
    params.page = newPage

    router.get(route('blog.index'), params, {
        preserveState: true,
        preserveScroll: true,
    })
}

// ── JSON-LD Structured Data ──
const blogJsonLd = computed(() => {
    const postList = (props.posts?.data || []).map((post, index) => ({
        '@type': 'ListItem',
        'position': (props.posts?.current_page - 1) * props.posts?.per_page + index + 1,
        'item': {
            '@type': 'BlogPosting',
            'headline': post.title,
            'description': post.excerpt || post.title,
            'url': `${window.location.origin}/blog/${post.slug}`,
            'datePublished': post.published_at,
            'author': {
                '@type': 'Person',
                'name': post.author_name || siteName.value,
            },
            ...(post.cover_image ? { 'image': post.cover_image } : {}),
        },
    }))

    return [{
        '@context': 'https://schema.org',
        '@type': 'Blog',
        'name': `${siteName.value} — Blog`,
        'description': 'Casos de estudio, artículos técnicos e insights de NUWESOFT Engineering',
        'url': window.location.href,
        'blogPost': postList.map(item => item.item),
    }, {
        '@context': 'https://schema.org',
        '@type': 'ItemList',
        'name': 'Blog Posts',
        'numberOfItems': postList.length,
        'itemListElement': postList,
    }]
})
</script>

<template>
  <Head :title="`Blog | ${siteName}`">
    <meta
      name="description"
      content="Casos de estudio, artículos técnicos e insights de NUWESOFT Engineering"
    >
    <meta
      property="og:title"
      :content="`Blog | ${siteName}`"
    >
    <meta
      property="og:description"
      content="Casos de estudio, artículos técnicos e insights de NUWESOFT Engineering"
    >
    <meta
      property="og:type"
      content="website"
    >
    <meta
      property="og:url"
      :content="pageUrl"
    >
    <meta
      name="twitter:card"
      content="summary_large_image"
    >
    <meta
      name="twitter:title"
      :content="`Blog | ${siteName}`"
    >
    <meta
      name="twitter:description"
      content="Casos de estudio, artículos técnicos e insights de NUWESOFT Engineering"
    >
    <link
      rel="canonical"
      :href="pageUrl"
    >
  </Head>

  <Teleport to="head">
    <!-- JSON-LD serialized by safeJsonLd(); the slot renders no raw markup -->
    <!-- eslint-disable vue/no-v-html, vue/no-v-text-v-html-on-component -->
    <component
      :is="'script'"
      v-for="(schema, idx) in blogJsonLd"
      :key="idx"
      :nonce="cspNonce"
      type="application/ld+json"
      v-html="safeJsonLd(schema)"
    />
    <!-- eslint-enable vue/no-v-html, vue/no-v-text-v-html-on-component -->
  </Teleport>

  <div class="min-h-screen overflow-x-hidden bg-white font-sans text-black selection:bg-brutalist-yellow selection:text-black dark:bg-black dark:text-white">
    <PublicGridBackground />
    <PublicSiteHeader />

    <main
      id="main-content"
      class="relative pt-40 pb-24"
    >
      <div class="pointer-events-none absolute -left-32 top-1/4 h-72 w-72 rounded-full bg-brutalist-pink/10 blur-3xl" />
      <div class="pointer-events-none absolute -right-32 bottom-1/4 h-80 w-80 rounded-full bg-brutalist-blue/10 blur-3xl" />

      <div class="relative z-10 mx-auto max-w-[1400px] px-6">
        <!-- Header -->
        <div class="mb-12">
          <Badge class="-rotate-1 mb-6 inline-block border-4 border-black bg-brutalist-yellow px-4 py-2 text-xl font-black uppercase text-black">
            BLOG
          </Badge>
          <h1 class="text-[clamp(3rem,8vw,6rem)] font-display font-black uppercase italic leading-[0.8] tracking-tighter">
            CASOS DE <br>
            <span class="text-brutalist-pink">ESTUDIO</span>
          </h1>
          <p class="mt-6 max-w-2xl text-xl font-black uppercase leading-tight text-black/70 dark:text-zinc-300">
            Proyectos reales, decisiones técnicas y resultados concretos. Sin marketing vacío.
          </p>
        </div>

        <!-- Category Filters + Search -->
        <div class="mb-12 space-y-4">
          <div class="flex flex-wrap gap-3">
            <button
              v-for="cat in categories"
              :key="cat.key ?? 'all'"
              class="border-4 px-5 py-2.5 text-[11px] font-black uppercase tracking-[0.2em] transition-all duration-300"
              :class="activeCategory === cat.key
                ? 'border-black bg-black text-white dark:border-white dark:bg-white dark:text-black shadow-brutalist'
                : 'border-black/20 dark:border-white/20 bg-transparent hover:border-black dark:hover:border-white hover:bg-black/5 dark:hover:bg-white/5'"
              @click="filterByCategory(cat.key)"
            >
              {{ cat.label }}
            </button>
          </div>

          <!-- Search bar -->
          <div class="relative max-w-xl">
            <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400" />
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Buscar artículos..."
              class="w-full border-4 border-black/20 dark:border-white/20 bg-white dark:bg-black px-12 py-3 text-sm font-bold placeholder:text-neutral-400 focus:border-black dark:focus:border-white focus:outline-none transition-all"
              @keyup.enter="applySearch"
            >
            <button
              v-if="searchQuery"
              class="absolute right-4 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-black dark:hover:text-white transition-colors"
              @click="clearSearch"
            >
              <X class="w-5 h-5" />
            </button>
          </div>
        </div>

        <!-- Skeleton Grid -->
        <Transition
          name="fade"
          mode="out-in"
        >
          <div
            v-if="!skeletonReady"
            key="skeleton"
            class="grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3"
          >
            <SkeletonPostCard
              v-for="i in 6"
              :key="'skel-' + i"
            />
          </div>

          <div
            v-else
            key="content"
          >
            <!-- Posts Grid -->
            <div
              v-if="posts?.data?.length"
              class="grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3"
            >
              <LazyLoad
                v-for="post in posts.data"
                :key="post.id"
                root-margin="300px"
              >
                <BlogPostCard :post="post" />
              </LazyLoad>
            </div>

            <!-- Empty State -->
            <div
              v-else
              class="border-4 border-black dark:border-white p-16 text-center shadow-brutalist dark:shadow-brutalist-white"
            >
              <p class="text-2xl font-display font-black uppercase italic">
                {{ searchQuery ? 'SIN RESULTADOS' : activeCategory ? 'SIN RESULTADOS' : 'PRÓXIMAMENTE' }}
              </p>
              <p class="mt-4 text-sm font-black uppercase tracking-wider text-neutral-500">
                {{ searchQuery
                  ? 'No encontramos artículos para "' + searchQuery + '". Probá con otras palabras.'
                  : activeCategory
                    ? 'No hay artículos en esta categoría. Probá con otro filtro.'
                    : 'Estamos preparando casos de estudio y artículos técnicos. Volvé pronto.'
                }}
              </p>
              <div class="flex items-center justify-center gap-3 mt-6">
                <button
                  v-if="searchQuery"
                  class="border-4 border-black dark:border-white px-6 py-3 text-[11px] font-black uppercase tracking-[0.2em] hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition-all"
                  @click="clearSearch"
                >
                  LIMPIAR BÚSQUEDA
                </button>
                <button
                  v-if="activeCategory && !searchQuery"
                  class="border-4 border-black dark:border-white px-6 py-3 text-[11px] font-black uppercase tracking-[0.2em] hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition-all"
                  @click="filterByCategory(null)"
                >
                  VER TODOS
                </button>
              </div>
            </div>

            <!-- Pagination -->
            <div
              v-if="posts?.last_page > 1"
              class="mt-16 flex flex-col items-center gap-4"
            >
              <p class="text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">
                MOSTRANDO {{ posts.from }}–{{ posts.to }} DE {{ posts.total }} ARTÍCULOS
              </p>
              <Pagination
                :total="posts.total"
                :sibling-count="1"
                :items-per-page="posts.per_page"
                :default-page="posts.current_page"
                @update:page="handlePageChange"
              >
                <PaginationList
                  v-slot="{ items }"
                  class="flex items-center gap-2 bg-white dark:bg-black border-4 border-black dark:border-white p-2 shadow-brutalist"
                >
                  <PaginationFirst />
                  <PaginationPrev />
                  <template v-for="(item, index) in items">
                    <PaginationListItem
                      v-if="item.type === 'page'"
                      :key="index"
                      :value="item.value"
                      :as-child="true"
                    >
                      <button
                        class="h-10 w-10 flex items-center justify-center text-[11px] font-black uppercase transition-all"
                        :class="item.value === posts.current_page
                          ? 'bg-black text-white dark:bg-white dark:text-black'
                          : 'hover:bg-black/5 dark:hover:bg-white/5'"
                      >
                        {{ item.value }}
                      </button>
                    </PaginationListItem>
                    <PaginationEllipsis
                      v-else
                      :key="item.type"
                      :index="index"
                    />
                  </template>
                  <PaginationNext />
                  <PaginationLast />
                </PaginationList>
              </Pagination>
            </div>
          </div>
        </Transition>
      </div>
    </main>

    <!-- Newsletter -->
    <section class="relative z-10 mx-auto max-w-2xl px-6 py-16">
      <NewsletterForm source="blog" />
    </section>

    <PublicSiteFooter />
  </div>
</template>

<style>
.font-display { font-family: 'Space Grotesk', sans-serif; }
</style>
