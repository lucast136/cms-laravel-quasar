<template>
  <aside class="blog-sidebar">
    <!-- Widget 1: Buscador -->
    <div class="widget">
      <div class="widget-title">
        <h3>Buscar Noticias</h3>
      </div>
      <form class="search-form" @submit.prevent="handleSearch">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Buscar noticias o comunicados..."
        />
        <button type="submit" aria-label="Buscar">
          <i class="fas fa-search"></i>
        </button>
      </form>
    </div>

    <!-- Widget 2: Categorías (Máximo 5 + Ver más) -->
    <div class="widget">
      <div class="widget-title">
        <h3>Categorías</h3>
      </div>
      <ul class="category-list list-none q-pa-none q-ma-none">
        <li v-for="cat in visibleCategories" :key="cat.slug">
          <router-link :to="{ path: '/noticias', query: { category: cat.slug } }">
            <span class="cat-label">
              <i class="fas fa-angle-right q-mr-sm text-secondary"></i>
              {{ cat.name }}
            </span>
            <span class="cat-badge">{{ formatCount(cat.posts_count ?? 1) }}</span>
          </router-link>
        </li>
      </ul>
      <div class="q-mt-md text-right">
        <router-link to="/noticias" class="view-more-link">
          Ver todas las categorías +
        </router-link>
      </div>
    </div>

    <!-- Widget 3: Artículos Recientes (Máximo 5 + Ver más) -->
    <div class="widget">
      <div class="widget-title">
        <h3>Artículos Recientes</h3>
      </div>
      <div class="recent-articles-list">
        <div
          v-for="item in visibleRecentPosts"
          :key="item.id || item.slug"
          class="recent-media row no-wrap items-center q-mb-md"
        >
          <router-link :to="`/noticias/${item.slug}`" class="recent-thumb-link q-mr-md">
            <img
              :src="item.featured_image || '/img/blog/blog-01.jpg'"
              :alt="item.title"
              class="recent-thumb"
            />
          </router-link>
          <div class="col">
            <h4 class="recent-title q-ma-none q-mb-xs">
              <router-link :to="`/noticias/${item.slug}`">
                {{ item.title }}
              </router-link>
            </h4>
            <span class="recent-date text-caption text-grey-7 font-weight-700">
              <i class="far fa-calendar-alt text-secondary q-mr-xs"></i>
              {{ formatDate(item.published_at || item.created_at) }}
            </span>
          </div>
        </div>
      </div>
      <div class="q-mt-sm text-right">
        <router-link to="/noticias" class="view-more-link">
          Ver más noticias +
        </router-link>
      </div>
    </div>

    <!-- Widget 4: Etiquetas Populares (Máximo 5 + Ver más) -->
    <div class="widget">
      <div class="widget-title">
        <h3>Etiquetas Populares</h3>
      </div>
      <ul class="course-tags list-none q-pa-none q-ma-none">
        <li v-for="tag in visibleTags" :key="tag.slug">
          <router-link :to="{ path: '/noticias', query: { search: tag.name } }">
            {{ tag.name }}
          </router-link>
        </li>
      </ul>
    </div>

    <!-- Widget 5: Compartir / Redes Sociales -->
    <div class="widget q-mb-none">
      <div class="widget-title">
        <h3>Síguenos y Comparte</h3>
      </div>
      <ul class="social-icons list-none q-pa-none q-ma-none row q-gutter-xs">
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
          <a href="https://youtube.com" target="_blank" rel="noopener" aria-label="YouTube">
            <i class="fab fa-youtube"></i>
          </a>
        </li>
        <li>
          <a href="https://linkedin.com" target="_blank" rel="noopener" aria-label="LinkedIn">
            <i class="fab fa-linkedin-in"></i>
          </a>
        </li>
      </ul>
    </div>
  </aside>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

const props = defineProps({
  sidebarData: {
    type: Object,
    default: () => ({}),
  },
})

const router = useRouter()
const searchQuery = ref('')

const MAX_ITEMS = 5

const DEFAULT_CATEGORIES = [
  { name: 'Admisión', slug: 'admision', posts_count: 4 },
  { name: 'Tecnología', slug: 'tecnologia', posts_count: 6 },
  { name: 'Convenios', slug: 'convenios', posts_count: 3 },
  { name: 'Vida Estudiantil', slug: 'vida-estudiantil', posts_count: 5 },
  { name: 'Institucional', slug: 'institucional', posts_count: 8 },
]

const DEFAULT_RECENT_POSTS = [
  {
    id: 1,
    title: 'Inicio del Proceso de Admisión 2026-I en el IESTP DEPERU MASTER',
    slug: 'inicio-proceso-admision-2026-i',
    featured_image: '/img/blog/blog-01.jpg',
    published_at: '2026-10-28',
  },
  {
    id: 2,
    title: 'Nuevos Convenios Empresariales para Prácticas Preprofesionales',
    slug: 'nuevos-convenios-empresariales-practicas',
    featured_image: '/img/blog/blog-02.jpg',
    published_at: '2026-10-25',
  },
  {
    id: 3,
    title: 'Inauguración de Laboratorios de Desarrollo de Software y Redes',
    slug: 'inauguracion-laboratorios-software-redes',
    featured_image: '/img/blog/blog-03.jpg',
    published_at: '2026-10-20',
  },
]

const DEFAULT_TAGS = [
  { name: 'MINEDU', slug: 'minedu' },
  { name: 'Admisión 2026', slug: 'admision-2026' },
  { name: 'Empleabilidad', slug: 'empleabilidad' },
  { name: 'Becas', slug: 'becas' },
  { name: 'Innovación TI', slug: 'innovacion-ti' },
]

const visibleCategories = computed(() => {
  const list =
    Array.isArray(props.sidebarData?.categories) && props.sidebarData.categories.length > 0
      ? props.sidebarData.categories
      : DEFAULT_CATEGORIES
  return list.slice(0, MAX_ITEMS)
})

const visibleRecentPosts = computed(() => {
  const list =
    Array.isArray(props.sidebarData?.recent_posts) && props.sidebarData.recent_posts.length > 0
      ? props.sidebarData.recent_posts
      : DEFAULT_RECENT_POSTS
  return list.slice(0, MAX_ITEMS)
})

const visibleTags = computed(() => {
  const list =
    Array.isArray(props.sidebarData?.tags) && props.sidebarData.tags.length > 0
      ? props.sidebarData.tags
      : DEFAULT_TAGS
  return list.slice(0, MAX_ITEMS)
})

function handleSearch() {
  const q = searchQuery.value.trim()
  router.push({ path: '/noticias', query: q ? { search: q } : {} })
}

function formatCount(num) {
  const n = Number(num || 0)
  return n < 10 ? `0${n}` : String(n)
}

function formatDate(dateStr) {
  if (!dateStr) return 'Reciente'
  const d = new Date(dateStr)
  return d.toLocaleDateString('es-PE', { day: '2-digit', month: 'short', year: 'numeric' })
}
</script>

<style scoped lang="scss">
@import '@/css/template/variables';

.widget {
  background: #ffffff;
  padding: 30px;
  border-radius: 8px;
  box-shadow: 0 0 25px rgba(0, 0, 0, 0.06);
  margin-bottom: 30px;
  border: 1px solid #f1f5f9;
}

.widget-title {
  margin-bottom: 22px;
  position: relative;

  h3 {
    font-size: 20px;
    font-weight: 800;
    color: $theme-dark;
    margin: 0;
    padding-left: 18px;
    line-height: 1.2;
    position: relative;

    &::before {
      content: '';
      position: absolute;
      left: 0;
      top: 2px;
      height: 18px;
      width: 3px;
      background-color: $theme-secondary;
    }

    &::after {
      content: '';
      position: absolute;
      left: 6px;
      top: 2px;
      height: 18px;
      width: 3px;
      background-color: $theme-secondary;
    }
  }
}

/* Buscador */
.search-form {
  position: relative;
  display: flex;

  input {
    width: 100%;
    height: 48px;
    padding: 10px 48px 10px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 5px;
    font-size: 14px;
    outline: none;
    transition: border-color 0.25s ease;

    &:focus {
      border-color: $theme-primary;
    }
  }

  button {
    position: absolute;
    right: 0;
    top: 0;
    height: 48px;
    width: 48px;
    border: none;
    background: transparent;
    color: $theme-primary;
    cursor: pointer;
    font-size: 16px;
    transition: color 0.2s ease;

    &:hover {
      color: $theme-secondary;
    }
  }
}

/* Lista de Categorías */
.category-list {
  li {
    border-bottom: 1px solid #f1f5f9;
    padding: 11px 0;

    &:first-child {
      padding-top: 0;
    }
    &:last-child {
      border-bottom: none;
      padding-bottom: 0;
    }

    a {
      display: flex;
      align-items: center;
      justify-content: space-between;
      text-decoration: none;
      color: #475569;
      font-weight: 600;
      font-size: 15px;
      transition: color 0.25s ease;

      .cat-badge {
        background-color: $theme-secondary;
        color: #ffffff;
        font-size: 12px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 4px;
        min-width: 28px;
        text-align: center;
        transition: background-color 0.25s ease;
      }

      &:hover {
        color: $theme-primary;

        .cat-badge {
          background-color: $theme-primary;
        }
      }
    }
  }
}

/* Artículos Recientes */
.recent-media {
  &:last-child {
    margin-bottom: 0 !important;
  }

  .recent-thumb-link {
    display: block;
    flex-shrink: 0;
  }

  .recent-thumb {
    width: 78px;
    height: 72px;
    object-fit: cover;
    border-radius: 6px;
  }

  .recent-title {
    font-size: 15px;
    font-weight: 700;
    line-height: 1.35;

    a {
      color: $theme-dark;
      text-decoration: none;
      transition: color 0.2s ease;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;

      &:hover {
        color: $theme-primary;
      }
    }
  }
}

/* Etiquetas Populares */
.course-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;

  li a {
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
}

/* Botones de Redes Sociales */
.social-icons {
  li a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 5px;
    background-color: $theme-primary;
    color: #ffffff;
    text-decoration: none;
    font-size: 14px;
    transition: background-color 0.25s ease;

    &:hover {
      background-color: $theme-secondary;
    }
  }
}

.view-more-link {
  font-size: 13px;
  font-weight: 700;
  color: $theme-primary;
  text-decoration: none;
  transition: color 0.2s ease;

  &:hover {
    color: $theme-secondary;
  }
}
</style>
