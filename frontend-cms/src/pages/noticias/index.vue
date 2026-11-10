<template>
  <PublicLayout>
    <PageHeader
      title="Noticias, Artículos y Comunicados"
      subtitle="Actualidad Institucional IESTP DEPERU MASTER"
      bg-image="/img/bg/bg-04.jpg"
    />

    <section class="section-padding bg-very-light-gray position-relative">
      <div class="container-custom">
        <!-- Filtros y Buscador de Noticias -->
        <div class="row items-center justify-between q-col-gutter-md q-mb-xl">
          <div class="col-12 col-md-5">
            <q-input
              v-model="searchQuery"
              outlined
              dense
              bg-color="white"
              placeholder="Buscar noticias o comunicados..."
              clearable
              @update:model-value="handleSearch"
            >
              <template #prepend>
                <q-icon name="search" />
              </template>
            </q-input>
          </div>

          <div class="col-12 col-md-7 text-right">
            <div class="row items-center justify-end q-gutter-xs">
              <q-btn
                v-for="cat in categories"
                :key="cat.slug"
                dense
                no-caps
                rounded
                unelevated
                size="sm"
                :color="selectedCategory === cat.slug ? 'primary' : 'white'"
                :text-color="selectedCategory === cat.slug ? 'white' : 'dark'"
                class="q-px-sm border-light"
                :label="cat.name"
                @click="filterByCategory(cat.slug)"
              />
            </div>
          </div>
        </div>

        <!-- Estado de Carga -->
        <div v-if="loading" class="text-center q-pa-xl">
          <q-spinner-dots color="primary" size="48px" />
          <div class="text-caption text-grey-7 q-mt-sm">Cargando noticias institucionales...</div>
        </div>

        <!-- Listado de Artículos (Máximo 5 iniciales + Ver más) -->
        <div v-else-if="visiblePosts.length > 0">
          <div class="row q-col-gutter-xl">
            <div v-for="post in visiblePosts" :key="post.id" class="col-12 col-md-4">
              <div class="blog-card bg-white rounded-borders overflow-hidden shadow-2 column full-height">
                <div class="blog-img image-hover relative-position">
                  <img
                    :src="post.featured_image || '/img/blog/blog-01.jpg'"
                    :alt="post.title"
                    class="w-100 full-width"
                    style="height: 220px; object-fit: cover"
                  />
                  <div v-if="post.terms && post.terms.length > 0" class="category-badge">
                    <q-badge
                      :color="post.terms[0].badge_color || 'primary'"
                      :label="post.terms[0].name"
                      class="q-px-sm q-py-xs text-weight-bold"
                    />
                  </div>
                </div>

                <div class="q-pa-lg col column justify-between">
                  <div>
                    <div class="row items-center q-gutter-x-md text-caption text-grey-7 q-mb-sm">
                      <div>
                        <i class="far fa-user text-secondary q-mr-xs"></i>
                        {{ post.author?.name || 'Administración' }}
                      </div>
                      <div>
                        <i class="far fa-calendar-alt text-secondary q-mr-xs"></i>
                        {{ formatDate(post.published_at || post.created_at) }}
                      </div>
                    </div>

                    <h3 class="text-subtitle1 font-weight-700 q-mb-sm text-dark leading-tight">
                      <router-link :to="`/noticias/${post.slug}`" class="text-dark hover-primary">
                        {{ post.title }}
                      </router-link>
                    </h3>

                    <p class="text-grey-7 text-caption q-mb-md ellipsis-3-lines">
                      {{ post.excerpt }}
                    </p>
                  </div>

                  <div>
                    <router-link :to="`/noticias/${post.slug}`" class="butn-style1">
                      Leer Artículo Completo +
                    </router-link>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div v-if="posts.length > visibleLimit" class="text-center q-mt-xl">
            <button type="button" class="butn" @click="visibleLimit += 5">
              <i class="fas fa-plus-circle icon-arrow before"></i>
              <span class="label">Ver Más Noticias ({{ posts.length - visibleLimit }} restantes)</span>
              <i class="fas fa-plus-circle icon-arrow after"></i>
            </button>
          </div>
        </div>

        <!-- Estado Vacío -->
        <div v-else class="text-center q-pa-xl bg-white rounded-borders shadow-1">
          <q-icon name="article" size="56px" color="grey-4" />
          <div class="text-subtitle1 text-weight-bold text-grey-8 q-mt-sm">
            No se encontraron artículos publicados
          </div>
          <div class="text-caption text-grey-6">
            Intenta con otro término de búsqueda o selecciona otra categoría.
          </div>
        </div>
      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useMeta } from 'quasar'
import PublicLayout from '@/layouts/PublicLayout.vue'
import PageHeader from '@/components/public/common/PageHeader.vue'
import { usePublicCmsStore } from '@/stores/public-cms'

defineOptions({
  async preFetch({ store, currentRoute }) {
    const publicCmsStore = usePublicCmsStore(store)
    const params = {}
    if (currentRoute.query?.search) params.search = String(currentRoute.query.search)
    if (currentRoute.query?.category && currentRoute.query.category !== 'todas') {
      params.category = String(currentRoute.query.category)
    }
    await Promise.all([
      publicCmsStore.bootstrapPublicSite(),
      publicCmsStore.fetchPosts(params),
    ])
  },
})

const route = useRoute()
const publicCmsStore = usePublicCmsStore()

const searchQuery = ref(String(route.query?.search || ''))
const selectedCategory = ref(String(route.query?.category || 'todas'))
const loading = ref(false)
const visibleLimit = ref(5)

const categories = [
  { name: 'Todas', slug: 'todas' },
  { name: 'Admisión', slug: 'admision' },
  { name: 'Tecnología', slug: 'tecnologia' },
  { name: 'Convenios', slug: 'convenios' },
  { name: 'Vida Estudiantil', slug: 'vida-estudiantil' },
  { name: 'Institucional', slug: 'institucional' },
]

const posts = computed(() => publicCmsStore.postsList)
const visiblePosts = computed(() => posts.value.slice(0, visibleLimit.value))

useMeta(() => ({
  title: 'Noticias, Artículos y Comunicados | IESTP DEPERU MASTER',
  meta: {
    description: {
      name: 'description',
      content:
        'Conoce los últimos comunicados institucionales, noticias y novedades académicas del IESTP DEPERU MASTER.',
    },
    keywords: {
      name: 'keywords',
      content: 'noticias deperu master, comunicados oficiales, educacion superior, blog academico',
    },
    ogTitle: { property: 'og:title', content: 'Noticias y Blog | IESTP DEPERU MASTER' },
  },
}))

async function loadData(force = false) {
  loading.value = true
  try {
    const params = {}
    if (searchQuery.value) params.search = searchQuery.value
    if (selectedCategory.value !== 'todas') params.category = selectedCategory.value
    await publicCmsStore.fetchPosts(params, { force })
  } finally {
    loading.value = false
  }
}

function handleSearch() {
  loadData(true)
}

function filterByCategory(slug) {
  selectedCategory.value = slug
  loadData(true)
}

function formatDate(dateStr) {
  if (!dateStr) return 'Reciente'
  const d = new Date(dateStr)
  return d.toLocaleDateString('es-PE', { day: '2-digit', month: 'short', year: 'numeric' })
}
</script>

<style scoped lang="scss">
@import '@/css/template/variables';

.border-light {
  border: 1px solid #e2e8f0;
}
.blog-card {
  transition:
    transform 0.3s ease,
    box-shadow 0.3s ease;
  &:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
  }
}
.category-badge {
  position: absolute;
  top: 15px;
  right: 15px;
}
.hover-primary:hover {
  color: $theme-primary !important;
}
.leading-tight {
  line-height: 1.35;
}
.ellipsis-3-lines {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>

