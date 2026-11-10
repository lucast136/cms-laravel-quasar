<template>
  <header :class="['main-header', { 'is-sticky': isScrolled }]">
    <!-- Top Bar informativa -->
    <div class="top-bar bg-dark text-white q-py-xs gt-sm">
      <div class="container-custom row items-center justify-between">
        <div class="row items-center q-gutter-x-md text-caption">
          <div>
            <q-icon name="call" size="xs" color="secondary" class="q-mr-xs" />
            {{ contactInfo.phone || contactInfo.contact_phone || '(01) 456-7890' }}
          </div>
          <div>
            <q-icon name="mail" size="xs" color="secondary" class="q-mr-xs" />
            {{ contactInfo.email || contactInfo.contact_email || 'informes@deperumaster.edu.pe' }}
          </div>
          <div>
            <q-icon name="place" size="xs" color="secondary" class="q-mr-xs" />
            {{ contactInfo.address || 'Lircay, Angaraes - Huancavelica' }}
          </div>
        </div>
        <div class="row items-center q-gutter-x-sm">
          <a
            v-if="socialLinks.facebook"
            :href="socialLinks.facebook"
            target="_blank"
            class="top-social-link"
          >
            <q-icon name="fab fa-facebook-f" size="xs" />
          </a>
          <a
            v-if="socialLinks.instagram"
            :href="socialLinks.instagram"
            target="_blank"
            class="top-social-link"
          >
            <q-icon name="fab fa-instagram" size="xs" />
          </a>
          <a
            v-if="socialLinks.youtube"
            :href="socialLinks.youtube"
            target="_blank"
            class="top-social-link"
          >
            <q-icon name="fab fa-youtube" size="xs" />
          </a>
        </div>
      </div>
    </div>

    <!-- Navegación Principal Dinámica -->
    <nav class="navbar-default">
      <div class="container-custom row items-center justify-between no-wrap">
        <!-- Logo -->
        <router-link to="/" class="navbar-brand row items-center no-wrap">
          <img
            v-if="resolvedLogo"
            :src="resolvedLogo"
            :alt="identity.site_name || identity.site_title || 'IESTP DEPERU MASTER'"
            class="header-logo"
          />
        </router-link>

        <!-- Menú Desktop Dinámico (con soporte de Dropdowns / Submenús) -->
        <ul
          class="navbar-nav gt-md row items-center no-wrap q-gutter-x-lg list-none q-my-none q-py-none"
        >
          <li v-for="item in navItems" :key="item.url || item.title" class="nav-item-wrapper">
            <!-- Caso con Submenús (Dropdown que abre tanto por hover como por click) -->
            <div
              v-if="item.children && item.children.length > 0"
              class="nav-dropdown-trigger row items-center no-wrap cursor-pointer"
            >
              <router-link :to="item.url" class="nav-link" :exact="item.url === '/'">
                {{ item.title }}
              </router-link>
              <q-icon name="keyboard_arrow_down" size="xs" color="grey-7" class="q-ml-xs dropdown-caret" />

              <q-menu
                fit
                anchor="bottom left"
                self="top left"
                class="header-dropdown-menu shadow-4 rounded-borders"
                auto-close
              >
                <q-list dense class="q-py-xs" style="min-width: 210px">
                  <q-item
                    v-for="sub in item.children"
                    :key="sub.url || sub.title"
                    v-close-popup
                    clickable
                    :to="sub.url"
                    :target="sub.target || '_self'"
                    class="dropdown-subitem q-py-sm"
                  >
                    <q-item-section avatar v-if="sub.icon_class" style="min-width: 28px">
                      <q-icon :name="sub.icon_class" size="16px" color="secondary" />
                    </q-item-section>
                    <q-item-section class="text-body2 font-weight-600 text-dark">
                      {{ sub.title }}
                    </q-item-section>
                  </q-item>
                </q-list>
              </q-menu>

              <!-- Dropdown CSS nativo para apertura instantánea al pasar el cursor (Hover) -->
              <div class="desktop-hover-dropdown shadow-4 rounded-borders bg-white">
                <ul class="list-none q-pa-xs q-ma-none" style="min-width: 210px">
                  <li v-for="sub in item.children" :key="sub.url || sub.title">
                    <router-link
                      :to="sub.url"
                      :target="sub.target || '_self'"
                      class="hover-dropdown-link row items-center no-wrap q-py-sm q-px-md"
                    >
                      <q-icon v-if="sub.icon_class" :name="sub.icon_class" size="16px" color="secondary" class="q-mr-sm" />
                      <span class="text-body2 font-weight-600 text-dark">{{ sub.title }}</span>
                    </router-link>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Caso Enlace Simple -->
            <router-link
              v-else
              :to="item.url"
              class="nav-link"
              :exact="item.url === '/'"
              :target="item.target || '_self'"
            >
              {{ item.title }}
            </router-link>
          </li>
        </ul>

        <!-- Acciones a la derecha -->
        <div class="row items-center no-wrap q-gutter-x-sm">
          <!-- Botón de Búsqueda -->
          <q-btn
            flat
            round
            dense
            icon="search"
            color="dark"
            aria-label="Buscar"
            @click="toggleSearch"
          />

          <!-- Botón CTA Admisión -->
          <router-link to="/admision" class="butn sm gt-xs">
            <i class="fas fa-plus-circle icon-arrow before"></i>
            <span class="label">Admisión 2026</span>
            <i class="fas fa-plus-circle icon-arrow after"></i>
          </router-link>

          <!-- Toggle Menú Móvil -->
          <q-btn
            flat
            dense
            round
            icon="menu"
            color="primary"
            class="lt-lg"
            aria-label="Abrir menú"
            @click="emit('toggleMobileMenu')"
          />
        </div>
      </div>
    </nav>

    <!-- Barra de búsqueda superior -->
    <TopSearchBar v-model:is-open="isSearchOpen" @search="onSearch" />
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import TopSearchBar from './TopSearchBar.vue'
import { usePublicCmsStore } from '@/stores/public-cms'

const emit = defineEmits(['toggleMobileMenu'])
const publicCmsStore = usePublicCmsStore()

const isScrolled = ref(false)
const isSearchOpen = ref(false)

const DEFAULT_NAV = [
  { title: 'Inicio', url: '/' },
  { title: 'Nosotros', url: '/nosotros' },
  { title: 'Carreras', url: '/carreras' },
  { title: 'Admisión', url: '/admision' },
  { title: 'Docentes', url: '/docentes' },
  { title: 'Eventos', url: '/eventos' },
  { title: 'Noticias', url: '/noticias' },
  { title: 'Contacto', url: '/contacto' },
]

const navItems = computed(() => {
  const menu = publicCmsStore.menusByLocation.header_main
  if (menu?.items && Array.isArray(menu.items) && menu.items.length > 0) {
    return menu.items.map((i) => ({
      title: i.title,
      url: i.url || `/${i.slug || ''}`,
      target: i.target || '_self',
      icon_class: i.icon_class || null,
      children: Array.isArray(i.children)
        ? i.children.map((c) => ({
            title: c.title,
            url: c.url || `/${c.slug || ''}`,
            target: c.target || '_self',
            icon_class: c.icon_class || null,
          }))
        : [],
    }))
  }
  return DEFAULT_NAV
})

const contactInfo = computed(() => publicCmsStore.publicSettings?.contact || {})
const socialLinks = computed(() => publicCmsStore.publicSettings?.social || {})
const identity = computed(() => publicCmsStore.publicSettings?.identity || {})
const resolvedLogo = computed(() => publicCmsStore.resolvedSiteLogo)

const toggleSearch = () => {
  isSearchOpen.value = !isSearchOpen.value
}

const onSearch = (query) => {
  console.log('Búsqueda ejecutada:', query)
}

const handleScroll = () => {
  if (typeof window !== 'undefined') {
    isScrolled.value = window.scrollY > 40
  }
}

onMounted(() => {
  publicCmsStore.fetchPublicSettings()
  publicCmsStore.fetchMenu('header_main')
  if (typeof window !== 'undefined') {
    window.addEventListener('scroll', handleScroll, { passive: true })
  }
})

onUnmounted(() => {
  if (typeof window !== 'undefined') {
    window.removeEventListener('scroll', handleScroll)
  }
})
</script>

<style scoped lang="scss">
@import '@/css/template/variables';

.main-header {
  position: sticky;
  top: 0;
  left: 0;
  right: 0;
  z-index: 999;
  background-color: #ffffff;
  transition: all 0.3s ease;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);

  &.is-sticky {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  }
}

.top-bar {
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);

  .top-social-link {
    color: rgba(255, 255, 255, 0.7);
    padding: 0 4px;
    transition: color 0.2s ease;
    &:hover {
      color: $theme-secondary;
    }
  }
}

.navbar-default {
  padding: 14px 0;
}

.header-logo {
  max-height: 48px;
  width: auto;
  object-fit: contain;
}

.navbar-nav {
  list-style: none;

  .nav-item-wrapper {
    position: relative;
    display: flex;
    align-items: center;
  }

  .nav-dropdown-trigger {
    position: relative;
    display: inline-flex;
    align-items: center;

    &:hover {
      .dropdown-caret {
        transform: rotate(180deg);
        color: $theme-secondary;
      }
      .nav-link {
        color: $theme-secondary;
      }
      .desktop-hover-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        pointer-events: auto;
      }
    }
  }

  .desktop-hover-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    margin-top: 6px;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s ease;
    border-top: 3px solid $theme-secondary;
    border-radius: 0 0 8px 8px;
    background-color: #ffffff;
    pointer-events: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);

    .hover-dropdown-link {
      text-decoration: none;
      transition: background-color 0.2s ease, padding-left 0.2s ease;
      border-radius: 4px;

      &:hover {
        background-color: #f1f5f9;
        padding-left: 20px;
        span {
          color: $theme-primary !important;
        }
      }
    }
  }

  .dropdown-caret {
    transition: transform 0.25s ease, color 0.25s ease;
  }

  .nav-link {
    font-size: 15px;
    font-weight: 700;
    color: $theme-dark;
    text-transform: capitalize;
    position: relative;
    padding: 6px 0;
    transition: color 0.3s ease;

    &::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 2px;
      background-color: $theme-secondary;
      transition: width 0.3s ease;
    }

    &:hover,
    &.active,
    &.router-link-active,
    &.router-link-exact-active {
      color: $theme-secondary;
      &::after {
        width: 100%;
      }
    }
  }
}
</style>
