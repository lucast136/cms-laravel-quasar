<template>
  <PublicLayout>
    <!-- Estado de Carga -->
    <div
      v-if="publicCmsStore.loading && !currentPage"
      class="text-center q-pa-xl"
      style="min-height: 55vh"
    >
      <q-spinner-dots color="primary" size="56px" class="q-mt-xl" />
      <div class="text-subtitle2 text-grey-7 q-mt-md">Cargando página institucional...</div>
    </div>

    <!-- Renderizado Dinámico de Página CMS y sus Bloques (content_sections) -->
    <template v-else-if="currentPage">
      <PageHeader
        v-if="currentPage.template !== 'landing'"
        :title="currentPage.title"
        :subtitle="currentPage.excerpt || 'IESTP DEPERU MASTER'"
        :bg-image="currentPage.featured_image || '/img/bg/bg-04.jpg'"
      />

      <DynamicPageSections :slug="currentSlug" />
    </template>

    <!-- Estado 404 Institucional cuando el slug no existe en la BD -->
    <section v-else class="section-padding bg-very-light-gray text-center">
      <div class="container-custom q-py-xl">
        <q-icon name="find_in_page" size="84px" color="primary" class="q-mb-md" />
        <h1 class="text-h3 font-weight-800 text-primary q-mb-sm">Página no encontrada</h1>
        <p class="text-body1 text-grey-7 q-mb-xl" style="max-width: 540px; margin: 0 auto">
          La dirección <strong>/{{ currentSlug }}</strong> no existe o aún no ha sido publicada en el
          portal institucional del IESTP DEPERU MASTER.
        </p>
        <router-link to="/" class="butn">
          <i class="fas fa-home icon-arrow before"></i>
          <span class="label">Volver al Inicio</span>
          <i class="fas fa-home icon-arrow after"></i>
        </router-link>
      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useMeta } from 'quasar'
import PublicLayout from '@/layouts/PublicLayout.vue'
import PageHeader from '@/components/public/common/PageHeader.vue'
import DynamicPageSections from '@/components/public/common/DynamicPageSections.vue'
import { usePublicCmsStore } from '@/stores/public-cms'

// Hook oficial de Quasar (SSR y navegación cliente en router.beforeResolve)
defineOptions({
  async preFetch({ store, currentRoute }) {
    const slug = currentRoute.params.slug
    const publicCmsStore = usePublicCmsStore(store)
    await Promise.all([
      publicCmsStore.bootstrapPublicSite(),
      slug ? publicCmsStore.fetchPage(String(slug)) : Promise.resolve(),
    ])
  },
})

const route = useRoute()
const publicCmsStore = usePublicCmsStore()

const currentSlug = computed(() => String(route.params.slug || 'inicio'))
const currentPage = computed(() => publicCmsStore.pagesBySlug[currentSlug.value] || null)

// Inyección SSR y Reactiva de Meta Etiquetas SEO 100% en Español
useMeta(() => {
  const page = currentPage.value
  if (!page) {
    return {
      title: 'Página Institucional | IESTP DEPERU MASTER',
    }
  }

  const metas = page.metas || []
  const metaTitle =
    metas.find((m) => m.meta_key === 'meta_title')?.meta_value ||
    `${page.title} | IESTP DEPERU MASTER`
  const metaDesc =
    metas.find((m) => m.meta_key === 'meta_description')?.meta_value ||
    page.excerpt ||
    'Instituto de Educación Superior Tecnológico Privado DEPERU MASTER.'
  const metaKeywords =
    metas.find((m) => m.meta_key === 'meta_keywords')?.meta_value ||
    'iestp deperu master, carreras tecnicas, educacion superior, admision 2026'

  return {
    title: metaTitle,
    meta: {
      description: { name: 'description', content: metaDesc },
      keywords: { name: 'keywords', content: metaKeywords },
      ogTitle: { property: 'og:title', content: metaTitle },
      ogDescription: { property: 'og:description', content: metaDesc },
      ogImage: { property: 'og:image', content: page.featured_image || '/img/logo/logo-dark.png' },
    },
  }
})
</script>
