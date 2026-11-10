<template>
  <q-layout view="lHh Lpr lFf">
    <!-- Header / Navbar Principal -->
    <MainNavbar @toggle-mobile-menu="mobileMenuOpen = !mobileMenuOpen" />

    <!-- Drawer Móvil Dinámico en Español -->
    <q-drawer
      v-model="mobileMenuOpen"
      side="left"
      overlay
      behavior="mobile"
      bordered
      class="bg-dark text-white"
    >
      <div class="q-pa-md row items-center justify-between border-bottom">
        <img
          v-if="siteLogo"
          :src="siteLogo"
          :alt="siteName"
          style="max-height: 40px; filter: brightness(0) invert(1)"
        />
        <q-btn flat round dense icon="close" color="white" @click="mobileMenuOpen = false" />
      </div>

      <q-list class="q-py-md">
        <template v-for="item in mobileNavItems" :key="item.url || item.title">
          <!-- Item con Submenús (Acordeón desplegable en móvil) -->
          <q-expansion-item
            v-if="item.children && item.children.length > 0"
            :icon="item.icon"
            :label="item.title"
            header-class="text-white font-weight-600"
            expand-icon-class="text-white"
          >
            <q-list class="q-pl-md bg-dark-sub">
              <q-item
                v-for="sub in item.children"
                :key="sub.url || sub.title"
                v-ripple
                clickable
                :to="sub.url"
                @click="mobileMenuOpen = false"
              >
                <q-item-section avatar v-if="sub.icon">
                  <q-icon :name="sub.icon" color="secondary" size="xs" />
                </q-item-section>
                <q-item-section class="text-caption text-grey-3">
                  {{ sub.title }}
                </q-item-section>
              </q-item>
            </q-list>
          </q-expansion-item>

          <!-- Item Normal Simple -->
          <q-item
            v-else
            v-ripple
            clickable
            :to="item.url"
            :exact="item.url === '/'"
            @click="mobileMenuOpen = false"
          >
            <q-item-section avatar>
              <q-icon :name="item.icon" color="secondary" />
            </q-item-section>
            <q-item-section>{{ item.title }}</q-item-section>
          </q-item>
        </template>
      </q-list>

      <div class="q-pa-md">
        <router-link
          to="/admision"
          class="butn w-100 full-width text-center"
          @click="mobileMenuOpen = false"
        >
          <span class="label">Postula Ahora</span>
        </router-link>
      </div>
    </q-drawer>

    <!-- Contenido de las páginas -->
    <q-page-container>
      <slot />
    </q-page-container>

    <!-- Footer -->
    <MainFooter />

    <!-- Banner de Consentimiento de Cookies, Google Analytics y Privacidad (Primera Visita) -->
    <CookieConsentBanner />

    <!-- Botón Scroll to Top Nativo Quasar -->
    <q-page-scroller position="bottom-right" :scroll-offset="200" :offset="[18, 18]">
      <q-btn
        fab
        icon="keyboard_arrow_up"
        color="primary"
        text-color="white"
        class="shadow-4"
        aria-label="Volver arriba"
      />
    </q-page-scroller>
  </q-layout>
</template>

<script setup>
import { ref, computed, watch, onMounted, nextTick } from 'vue'
import { useRoute } from 'vue-router'
import MainNavbar from '@/components/public/header/MainNavbar.vue'
import MainFooter from '@/components/public/footer/MainFooter.vue'
import CookieConsentBanner from '@/components/public/common/CookieConsentBanner.vue'
import { usePublicCmsStore } from '@/stores/public-cms'

const route = useRoute()
const mobileMenuOpen = ref(false)
const publicCmsStore = usePublicCmsStore()

const siteLogo = computed(() => publicCmsStore.resolvedSiteLogo)
const siteName = computed(
  () => publicCmsStore.publicSettings?.identity?.site_name || 'Logo IESTP DEPERU MASTER',
)

function waitForAnimationFrame() {
  if (typeof window === 'undefined' || typeof window.requestAnimationFrame !== 'function') {
    return Promise.resolve()
  }
  return new Promise((resolve) => {
    window.requestAnimationFrame(() => {
      window.requestAnimationFrame(resolve)
    })
  })
}

function waitForVisibleDomImages(timeoutMs = 1200) {
  if (typeof document === 'undefined' || typeof window === 'undefined') {
    return Promise.resolve()
  }
  const images = Array.from(document.querySelectorAll('header img, .q-page-container img')).slice(
    0,
    6,
  )
  const pending = images.filter((img) => img.src && !img.complete)
  if (pending.length === 0) {
    return Promise.resolve()
  }

  return new Promise((resolve) => {
    let settled = false
    const done = () => {
      if (!settled) {
        settled = true
        resolve()
      }
    }
    const timer = window.setTimeout(done, timeoutMs)
    Promise.allSettled(
      pending.map(
        (img) =>
          new Promise((res) => {
            img.addEventListener('load', res, { once: true })
            img.addEventListener('error', res, { once: true })
          }),
      ),
    ).then(() => {
      window.clearTimeout(timer)
      done()
    })
  })
}

async function syncPageRenderState() {
  publicCmsStore.setPageRendering(true)
  try {
    await publicCmsStore.bootstrapPublicSite()
    await publicCmsStore.waitForPendingRequests()
    await nextTick()
    await publicCmsStore.waitForPendingRequests()
    await nextTick()
    await waitForVisibleDomImages()
    await waitForAnimationFrame()
  } finally {
    publicCmsStore.setPageRendering(false)
    publicCmsStore.setRouteTransitioning(false)
    publicCmsStore.dismissInitialHtmlLoader()
  }
}

onMounted(() => {
  syncPageRenderState()
})

watch(
  () => route.fullPath,
  () => {
    syncPageRenderState()
  },
)

const DEFAULT_MOBILE_NAV = [
  { title: 'Inicio', url: '/', icon: 'home' },
  { title: 'Nosotros', url: '/nosotros', icon: 'info' },
  { title: 'Carreras', url: '/carreras', icon: 'school' },
  { title: 'Admisión', url: '/admision', icon: 'assignment_turned_in' },
  { title: 'Docentes', url: '/docentes', icon: 'groups' },
  { title: 'Eventos', url: '/eventos', icon: 'event' },
  { title: 'Noticias', url: '/noticias', icon: 'article' },
  { title: 'Contacto', url: '/contacto', icon: 'contact_mail' },
]

const ICON_BY_URL = {
  '/': 'home',
  '/nosotros': 'info',
  '/carreras': 'school',
  '/admision': 'assignment_turned_in',
  '/docentes': 'groups',
  '/eventos': 'event',
  '/noticias': 'article',
  '/contacto': 'contact_mail',
}

const mobileNavItems = computed(() => {
  const menu = publicCmsStore.menusByLocation.header_main
  if (menu?.items && Array.isArray(menu.items) && menu.items.length > 0) {
    return menu.items.map((i) => {
      const url = i.url || `/${i.slug || ''}`
      return {
        title: i.title,
        url,
        icon: i.icon || ICON_BY_URL[url] || 'chevron_right',
        children: Array.isArray(i.children)
          ? i.children.map((c) => {
              const subUrl = c.url || `/${c.slug || ''}`
              return {
                title: c.title,
                url: subUrl,
                icon: c.icon || ICON_BY_URL[subUrl] || 'subdirectory_arrow_right',
              }
            })
          : [],
      }
    })
  }
  return DEFAULT_MOBILE_NAV
})
</script>

<style scoped>
.border-bottom {
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}
</style>
