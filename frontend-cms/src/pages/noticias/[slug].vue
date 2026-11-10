<template>
  <PublicLayout>
    <!-- Estado de Carga -->
    <div v-if="loading" class="text-center q-pa-xl" style="min-height: 55vh">
      <q-spinner-dots color="primary" size="48px" class="q-mt-xl" />
      <div class="text-caption text-grey-7 q-mt-sm">Cargando artículo institucional...</div>
    </div>

    <template v-else-if="post">
      <PageHeader
        :title="post.title"
        :subtitle="formatDate(post.published_at || post.created_at)"
        :bg-image="post.featured_image || '/img/bg/bg-04.jpg'"
      />

      <section class="section-padding bg-very-light-gray">
        <div class="container-custom">
          <div class="row q-col-gutter-xl justify-center">
            <!-- Columna Principal del Artículo (8 cols si lleva Aside, 10 cols si es Ancho Completo) -->
            <div :class="hasSidebar ? 'col-12 col-lg-8' : 'col-12 col-lg-10'">
              <article class="posts-wrapper bg-white rounded-borders overflow-hidden shadow-2">
                <!-- Imagen Principal del Artículo -->
                <div v-if="post.featured_image" class="blog-details-img">
                  <img
                    :src="post.featured_image"
                    :alt="post.title"
                    class="w-100 full-width"
                    style="max-height: 460px; object-fit: cover; display: block"
                  />
                </div>

                <!-- Contenido del Post -->
                <div class="post-content q-pa-xl">
                  <!-- Cabecera y Metadatos del Post -->
                  <div class="post-meta blog-detail-separator q-pb-md q-mb-lg">
                    <h2 class="text-h5 font-weight-800 text-dark q-mt-none q-mb-sm leading-tight">
                      {{ post.title }}
                    </h2>

                    <ul class="meta-list list-none q-pa-none q-ma-none row items-center q-gutter-x-lg text-grey-7 text-body2">
                      <li>
                        <i class="fa fa-user text-secondary q-mr-xs"></i>
                        {{ post.author?.name || 'Administración Institucional' }}
                      </li>
                      <li>
                        <i class="fa fa-calendar text-secondary q-mr-xs"></i>
                        {{ formatDate(post.published_at || post.created_at) }}
                      </li>
                      <li v-if="postCategories.length > 0">
                        <i class="fa fa-folder-open text-secondary q-mr-xs"></i>
                        {{ postCategories[0].name }}
                      </li>
                    </ul>
                  </div>

                  <!-- Extracto Destacado -->
                  <p
                    v-if="post.excerpt"
                    class="text-subtitle1 text-grey-9 font-weight-600 q-mb-lg"
                  >
                    {{ post.excerpt }}
                  </p>

                  <!-- Cuerpo HTML del Artículo (Soporta blockquotes y listas estilo blog-details.html) -->
                  <!-- eslint-disable-next-line vue/no-v-html -->
                  <div class="post-content-html text-body1 text-grey-8" v-html="post.content_html"></div>

                  <div class="separator q-my-xl"></div>

                  <!-- Pie del Artículo: Etiquetas Relacionadas y Compartir -->
                  <div class="row items-center justify-between q-col-gutter-md">
                    <div class="col-12 col-md-7">
                      <h5 class="text-subtitle2 font-weight-800 text-dark q-mt-none q-mb-sm">
                        Etiquetas Relacionadas
                      </h5>
                      <ul class="blog-post-tag list-none q-pa-none q-ma-none row q-gutter-xs">
                        <li v-for="tag in relatedTags" :key="tag.slug || tag.name">
                          <router-link :to="{ path: '/noticias', query: { search: tag.name } }">
                            {{ tag.name }}
                          </router-link>
                        </li>
                      </ul>
                    </div>

                    <div class="col-12 col-md-5 text-md-right">
                      <h5 class="text-subtitle2 font-weight-800 text-dark q-mt-none q-mb-sm">
                        Compartir Publicación
                      </h5>
                      <ul
                        class="share-post list-none q-pa-none q-ma-none row items-center justify-start justify-md-end q-gutter-xs"
                      >
                        <li>
                          <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                          </a>
                        </li>
                        <li>
                          <a href="https://twitter.com" target="_blank" rel="noopener" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                          </a>
                        </li>
                        <li>
                          <a href="https://instagram.com" target="_blank" rel="noopener" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                          </a>
                        </li>
                        <li>
                          <a href="https://linkedin.com" target="_blank" rel="noopener" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </article>

              <!-- Tarjeta Institucional del Autor (author-info-wrapper de blog-details.html) -->
              <div class="author-info-wrapper q-mt-xl rounded-borders shadow-2">
                <div class="author-info-avatar">
                  <img
                    :src="post.author?.avatar_url || '/img/avatar/avatar-01.jpg'"
                    :alt="post.author?.name || 'Autor'"
                  />
                </div>
                <div class="author-info-content">
                  <h4 class="text-h6 font-weight-800 text-white q-mt-none q-mb-xs">
                    {{ post.author?.name || 'Dirección Académica - IESTP DEPERU MASTER' }}
                  </h4>
                  <p class="text-grey-3 text-body2 q-mb-md">
                    Equipo académico y de comunicación institucional del Instituto de Educación
                    Superior Tecnológico Privado DEPERU MASTER, comprometidos con la difusión de
                    novedades académicas, tecnológicas y oportunidades de empleabilidad.
                  </p>
                  <ul class="author-social-icons list-none q-pa-none q-ma-none row q-gutter-sm">
                    <li>
                      <a href="https://facebook.com" target="_blank" rel="noopener">
                        <i class="fab fa-facebook-f"></i>
                      </a>
                    </li>
                    <li>
                      <a href="https://instagram.com" target="_blank" rel="noopener">
                        <i class="fab fa-instagram"></i>
                      </a>
                    </li>
                    <li>
                      <a href="https://youtube.com" target="_blank" rel="noopener">
                        <i class="fab fa-youtube"></i>
                      </a>
                    </li>
                    <li>
                      <a href="https://linkedin.com" target="_blank" rel="noopener">
                        <i class="fab fa-linkedin-in"></i>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>

              <!-- Botón de Retorno a Noticias -->
              <div class="q-mt-lg">
                <router-link to="/noticias" class="butn secondary">
                  <i class="fas fa-arrow-left icon-arrow before"></i>
                  <span class="label">Volver a Noticias</span>
                  <i class="fas fa-arrow-left icon-arrow after"></i>
                </router-link>
              </div>
            </div>

            <!-- Columna Derecha: Aside Opcional (Se muestra cuando template !== 'full-width') -->
            <div v-if="hasSidebar" class="col-12 col-lg-4">
              <BlogSidebar :sidebar-data="post.sidebar" />
            </div>
          </div>
        </div>
      </section>
    </template>

    <!-- Estado No Encontrado -->
    <div v-else class="text-center q-pa-xl">
      <q-icon name="error_outline" size="56px" color="negative" />
      <div class="text-h6 text-weight-bold q-mt-sm">Artículo no encontrado</div>
      <router-link to="/noticias" class="q-mt-md inline-block text-primary">
        Ver todas las noticias
      </router-link>
    </div>
  </PublicLayout>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useMeta } from 'quasar'
import PublicLayout from '@/layouts/PublicLayout.vue'
import PageHeader from '@/components/public/common/PageHeader.vue'
import BlogSidebar from '@/components/public/blog/BlogSidebar.vue'
import { usePublicCmsStore } from '@/stores/public-cms'

defineOptions({
  async preFetch({ store, currentRoute }) {
    const slug = currentRoute.params.slug || currentRoute.params.id
    const publicCmsStore = usePublicCmsStore(store)
    await Promise.all([
      publicCmsStore.bootstrapPublicSite(),
      slug ? publicCmsStore.fetchPostBySlug(String(slug)) : Promise.resolve(),
    ])
  },
})

const route = useRoute()
const publicCmsStore = usePublicCmsStore()

const loading = computed(() => publicCmsStore.loading && !post.value)
const currentSlug = computed(() => String(route.params.slug || route.params.id || ''))
const post = computed(() => publicCmsStore.postsBySlug[currentSlug.value] || null)

// El Aside lateral es opcional según el campo `template` de la noticia ('with-sidebar' vs 'full-width')
const hasSidebar = computed(() => {
  return post.value?.template !== 'full-width'
})

const postCategories = computed(() => {
  if (!Array.isArray(post.value?.terms)) return []
  return post.value.terms.filter((t) => !t.taxonomy || t.taxonomy.slug === 'category')
})

const relatedTags = computed(() => {
  if (Array.isArray(post.value?.terms)) {
    const tags = post.value.terms.filter((t) => t.taxonomy?.slug === 'post_tag')
    if (tags.length > 0) return tags.slice(0, 5)
  }
  return [
    { name: 'Educación Superior', slug: 'educacion' },
    { name: 'Admisión 2026', slug: 'admision-2026' },
    { name: 'Tecnología', slug: 'tecnologia' },
  ]
})

useMeta(() => {
  if (!post.value) return { title: 'Noticia | IESTP DEPERU MASTER' }
  const metas = post.value.metas || []
  const metaTitle =
    metas.find((m) => m.meta_key === 'meta_title')?.meta_value ||
    `${post.value.title} | IESTP DEPERU MASTER`
  const metaDesc =
    metas.find((m) => m.meta_key === 'meta_description')?.meta_value ||
    post.value.excerpt ||
    post.value.title
  const metaKeywords =
    metas.find((m) => m.meta_key === 'meta_keywords')?.meta_value ||
    'noticias deperu master, comunicados institucionales, educacion superior'

  return {
    title: metaTitle,
    meta: {
      description: { name: 'description', content: metaDesc },
      keywords: { name: 'keywords', content: metaKeywords },
      ogTitle: { property: 'og:title', content: metaTitle },
      ogDescription: { property: 'og:description', content: metaDesc },
      ogImage: {
        property: 'og:image',
        content: post.value.featured_image || '/img/logo/logo-dark.png',
      },
    },
  }
})

function formatDate(dateStr) {
  if (!dateStr) return 'Reciente'
  const d = new Date(dateStr)
  return d.toLocaleDateString('es-PE', { day: '2-digit', month: 'long', year: 'numeric' })
}
</script>

<style scoped lang="scss">
@import '@/css/template/variables';

.leading-tight {
  line-height: 1.35;
}

.blog-detail-separator {
  border-bottom: 1px dashed #cbd5e1;
}

.separator {
  border-top: 1px dashed #cbd5e1;
}

/* Estilos fieles a blog-details.html para el cuerpo del artículo */
.post-content-html :deep(h1),
.post-content-html :deep(h2),
.post-content-html :deep(h3),
.post-content-html :deep(h4) {
  color: $theme-dark;
  font-weight: 800;
  margin-top: 28px;
  margin-bottom: 14px;
}

.post-content-html :deep(p) {
  line-height: 1.85;
  margin-bottom: 18px;
  color: #475569;
}

/* Blockquote con barra lateral naranja y cita en color primario (igual a blog-details.html) */
.post-content-html :deep(blockquote) {
  position: relative;
  background-color: #f8fafc;
  border-left: 4px solid $theme-secondary;
  padding: 32px 36px;
  margin: 28px 0;
  border-radius: 0 8px 8px 0;
  text-align: center;

  &::before {
    content: '\201C';
    position: absolute;
    left: 24px;
    top: 4px;
    font-size: 96px;
    line-height: 1;
    color: rgba(0, 0, 0, 0.05);
    font-family: Georgia, serif;
    pointer-events: none;
  }

  p {
    font-size: 17px;
    font-weight: 600;
    color: #334155;
    line-height: 1.7;
    margin-bottom: 12px;
    position: relative;
    z-index: 1;
  }

  cite {
    display: block;
    font-style: italic;
    font-weight: 800;
    color: $theme-primary;
    font-size: 15px;
  }
}

.post-content-html :deep(ul) {
  list-style: none;
  padding-left: 0;
  margin-bottom: 20px;

  li {
    position: relative;
    padding-left: 28px;
    margin-bottom: 10px;
    line-height: 1.65;
    color: #475569;
    font-weight: 500;

    &::before {
      content: '\e64c';
      font-family: 'themify';
      position: absolute;
      left: 0;
      top: 1px;
      color: $theme-secondary;
      font-weight: bold;
    }
  }
}

/* Etiquetas y Compartir en pie de post */
.blog-post-tag li a {
  display: inline-block;
  padding: 6px 14px;
  background-color: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 5px;
  color: #475569;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.25s ease;

  &:hover {
    background-color: $theme-primary;
    border-color: $theme-primary;
    color: #ffffff;
  }
}

.share-post li a {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background-color: #f1f5f9;
  color: $theme-dark;
  text-decoration: none;
  font-size: 14px;
  transition: all 0.25s ease;

  &:hover {
    background-color: $theme-secondary;
    color: #ffffff;
  }
}

/* Caja del Autor Institucional (author-info-wrapper de blog-details.html) */
.author-info-wrapper {
  background-color: $theme-dark;
  padding: 36px;
  display: flex;
  align-items: center;
  gap: 28px;

  @media (max-width: 599px) {
    flex-direction: column;
    text-align: center;
  }

  .author-info-avatar {
    flex-shrink: 0;

    img {
      width: 96px;
      height: 96px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid $theme-secondary;
    }
  }

  .author-social-icons li a {
    color: #ffffff;
    font-size: 15px;
    transition: color 0.2s ease;

    &:hover {
      color: $theme-secondary;
    }
  }
}
</style>
