<template>
  <section
    :id="sectionSettings?.custom_id || undefined"
    class="section-padding bg-very-light-gray position-relative"
    :class="sectionSettings?.custom_class"
    :style="sectionStyle"
  >
    <div class="container-custom">
      <div class="section-heading">
        <span class="sub-title">{{ subtitle }}</span>
        <h2>{{ title }}</h2>
      </div>

      <div class="row q-col-gutter-xl">
        <div v-for="(post, idx) in posts" :key="post.id || idx" class="col-12 col-md-4">
          <div class="blog-card bg-white rounded-borders overflow-hidden shadow-2">
            <div class="blog-img image-hover">
              <img :src="post.image" :alt="post.title" class="w-100 full-width" />
              <div class="blog-date-badge">
                <span class="day">{{ post.day || '25' }}</span>
                <span class="month">{{ post.month || 'Oct' }}</span>
              </div>
            </div>
            <div class="q-pa-lg">
              <div class="row items-center q-gutter-x-md text-caption text-grey-7 q-mb-sm">
                <div><i class="far fa-user text-secondary q-mr-xs"></i> {{ post.author }}</div>
                <div>
                  <i class="far fa-comments text-secondary q-mr-xs"></i>
                  {{ post.comments ?? 0 }} comentarios
                </div>
              </div>
              <h4 class="text-subtitle1 font-weight-700 q-mb-sm">
                <router-link :to="resolvePostLink(post)" class="text-dark">
                  {{ post.title }}
                </router-link>
              </h4>
              <p class="text-grey-7 text-caption q-mb-md">
                {{ post.excerpt }}
              </p>
              <router-link :to="resolvePostLink(post)" class="butn-style1">
                Leer Más +
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <div class="text-center q-mt-xl">
        <router-link to="/noticias" class="butn">
          <i class="fas fa-plus-circle icon-arrow before"></i>
          <span class="label">Ver Más Noticias</span>
          <i class="fas fa-plus-circle icon-arrow after"></i>
        </router-link>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  sectionData: {
    type: Object,
    default: () => ({}),
  },
  sectionSettings: {
    type: Object,
    default: () => ({}),
  },
})

const DEFAULT_POSTS = [
  {
    id: 1,
    image: '/img/blog/blog-01.jpg',
    day: '25',
    month: 'Oct',
    author: 'Admin',
    comments: 4,
    title: 'Tendencias en Inteligencia Artificial y su impacto en el trabajo técnico',
    excerpt:
      'Descubre cómo la IA está redefiniendo los perfiles profesionales y qué habilidades son más demandadas.',
    link: '/blog/1',
  },
  {
    id: 2,
    image: '/img/blog/blog-02.jpg',
    day: '18',
    month: 'Oct',
    author: 'Coordinación',
    comments: 8,
    title: 'Nuevos convenios de empleabilidad con empresas del sector TI',
    excerpt:
      'El IESTP DEPERU MASTER firma alianzas estratégicas para facilitar pasantías remuneradas a sus egresados.',
    link: '/blog/2',
  },
  {
    id: 3,
    image: '/img/blog/blog-03.jpg',
    day: '10',
    month: 'Oct',
    author: 'Docentes',
    comments: 2,
    title: '5 Consejos para optimizar tu aprendizaje en modalidad virtual y presencial',
    excerpt:
      'Mejora tu concentración y gestión del tiempo durante tus estudios técnicos superiores.',
    link: '/blog/3',
  },
]

const subtitle = computed(() => props.sectionData?.subtitle || 'Noticias y Actualidad')
const title = computed(() => props.sectionData?.title || 'Nuestro Blog y Artículos')

const posts = computed(() => {
  const list =
    Array.isArray(props.sectionData?.posts) && props.sectionData.posts.length > 0
      ? props.sectionData.posts
      : DEFAULT_POSTS
  const limit = Math.min(Number(props.sectionData?.limit) || 5, 5)
  return list.slice(0, limit)
})

function resolvePostLink(post) {
  if (post.slug) return `/noticias/${post.slug}`
  if (post.link && !post.link.startsWith('/blog/')) return post.link
  return '/noticias/inicio-proceso-admision-2026-i'
}

const sectionStyle = computed(() => {
  const s = props.sectionSettings || {}
  return {
    paddingTop: s.padding_top !== undefined ? `${s.padding_top}px` : undefined,
    paddingBottom: s.padding_bottom !== undefined ? `${s.padding_bottom}px` : undefined,
    backgroundColor: s.background_color || undefined,
  }
})
</script>

<style scoped lang="scss">
@import '@/css/template/variables';

.blog-card {
  transition:
    transform 0.3s ease,
    box-shadow 0.3s ease;
  height: 100%;

  &:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
  }
}

.blog-img {
  position: relative;
  height: 220px;

  img {
    height: 100%;
    object-fit: cover;
  }

  .blog-date-badge {
    position: absolute;
    bottom: 15px;
    left: 15px;
    background-color: $theme-secondary;
    color: #ffffff;
    padding: 6px 12px;
    border-radius: 4px;
    text-align: center;
    line-height: 1.1;

    .day {
      font-size: 16px;
      font-weight: 800;
      display: block;
    }
    .month {
      font-size: 11px;
      text-transform: uppercase;
      display: block;
    }
  }
}
</style>
