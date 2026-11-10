import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { usePublicCmsApi } from '@/composables/usePublicCmsApi'

/**
 * Pre-carga una imagen en el navegador con timeout de seguridad
 * para asegurar que el logo institucional esté decodificado antes de retirar el loader.
 */
function preloadImageOnClient(url, timeoutMs = 1500) {
  if (typeof window === 'undefined' || !url) {
    return Promise.resolve()
  }
  return new Promise((resolve) => {
    const img = new window.Image()
    const timer = window.setTimeout(resolve, timeoutMs)
    img.onload = () => {
      window.clearTimeout(timer)
      resolve()
    }
    img.onerror = () => {
      window.clearTimeout(timer)
      resolve()
    }
    img.src = url
  })
}

/**
 * Store Público del CMS (SSR / SEO)
 * Consume los endpoints públicos de Laravel 13 mediante `usePublicCmsApi`
 * Incluye caché en memoria y deduplicación de promesas en curso (In-Flight Deduplication)
 * para evitar peticiones HTTP duplicadas entre `preFetch` (SSR/Router) y componentes hijos.
 */
export const usePublicCmsStore = defineStore('public-cms', () => {
  const {
    loading,
    getPublicPageBySlugRequest,
    getPublicPostsRequest,
    getPublicPostBySlugRequest,
    getPublicSettingsRequest,
    getPublicMenuByLocationRequest,
    submitPublicFormRequest,
  } = usePublicCmsApi()

  const pagesBySlug = ref({})
  const publicSettings = ref({})
  const menusByLocation = ref({})
  const postsList = ref([])
  const postsMeta = ref({})
  const postsBySlug = ref({})
  const currentPost = ref(null)
  const lastPostsQueryKey = ref('')

  // Control del Loader General Institucional (carga inicial + transiciones de página)
  const isInitialBootReady = ref(false)
  const isRouteTransitioning = ref(false)
  const isPageRendering = ref(true)

  // Logo institucional resuelto sin parpadeo del logo por defecto antes de responder el backend
  const resolvedSiteLogo = computed(() => {
    const backendLogo =
      publicSettings.value?.identity?.site_logo ||
      publicSettings.value?.appearance?.logo_url ||
      ''
    if (backendLogo) return backendLogo
    return isInitialBootReady.value ? '/img/logos/logo-inner.png' : ''
  })

  // Mapa interno para deduplicar peticiones concurrentes (evita doble disparo HTTP)
  const inFlightRequests = new Map()

  async function fetchPage(slug = 'inicio', options = {}) {
    const cleanSlug = String(slug || 'inicio').trim()
    const force = Boolean(options?.force)

    // 1. Retornar desde caché si ya fue cargado y no se fuerza recarga
    if (!force && pagesBySlug.value[cleanSlug]) {
      return pagesBySlug.value[cleanSlug]
    }

    // 2. Si ya hay una petición en vuelo para este slug, reutilizar la misma promesa
    const requestKey = `page:${cleanSlug}`
    if (inFlightRequests.has(requestKey)) {
      return inFlightRequests.get(requestKey)
    }

    const promise = (async () => {
      try {
        const page = await getPublicPageBySlugRequest(cleanSlug)
        if (page) {
          pagesBySlug.value[cleanSlug] = page
          if (page.slug && page.slug !== cleanSlug) {
            pagesBySlug.value[page.slug] = page
          }
          return page
        }
      } catch {
        // Mantiene fallback si el backend no responde o devuelve 404
      } finally {
        inFlightRequests.delete(requestKey)
      }
      return pagesBySlug.value[cleanSlug] || null
    })()

    inFlightRequests.set(requestKey, promise)
    return promise
  }

  async function fetchPosts(params = {}, options = {}) {
    const force = Boolean(options?.force)
    const queryKey = JSON.stringify(params || {})

    if (!force && postsList.value.length > 0 && lastPostsQueryKey.value === queryKey) {
      return postsList.value
    }

    const requestKey = `posts:${queryKey}`
    if (inFlightRequests.has(requestKey)) {
      return inFlightRequests.get(requestKey)
    }

    const promise = (async () => {
      try {
        const res = await getPublicPostsRequest(params)
        if (res && res.data) {
          postsList.value = res.data
          postsMeta.value = res.meta || {}
          lastPostsQueryKey.value = queryKey
          return res.data
        }
      } catch {
        // Fallback
      } finally {
        inFlightRequests.delete(requestKey)
      }
      return postsList.value
    })()

    inFlightRequests.set(requestKey, promise)
    return promise
  }

  async function fetchPostBySlug(slug, options = {}) {
    const cleanSlug = String(slug || '').trim()
    if (!cleanSlug) return null
    const force = Boolean(options?.force)

    if (!force && postsBySlug.value[cleanSlug]) {
      currentPost.value = postsBySlug.value[cleanSlug]
      return postsBySlug.value[cleanSlug]
    }

    const requestKey = `post:${cleanSlug}`
    if (inFlightRequests.has(requestKey)) {
      return inFlightRequests.get(requestKey)
    }

    const promise = (async () => {
      try {
        const post = await getPublicPostBySlugRequest(cleanSlug)
        if (post) {
          postsBySlug.value[cleanSlug] = post
          if (post.slug && post.slug !== cleanSlug) {
            postsBySlug.value[post.slug] = post
          }
          currentPost.value = post
          return post
        }
      } catch {
        // Fallback
      } finally {
        inFlightRequests.delete(requestKey)
      }
      return postsBySlug.value[cleanSlug] || currentPost.value
    })()

    inFlightRequests.set(requestKey, promise)
    return promise
  }

  async function fetchPublicSettings(options = {}) {
    const force = Boolean(options?.force)
    if (!force && Object.keys(publicSettings.value).length > 0) {
      return publicSettings.value
    }

    const requestKey = 'settings:public'
    if (inFlightRequests.has(requestKey)) {
      return inFlightRequests.get(requestKey)
    }

    const promise = (async () => {
      try {
        const settings = await getPublicSettingsRequest()
        if (settings) {
          publicSettings.value = settings
        }
      } catch {
        // Fallback
      } finally {
        inFlightRequests.delete(requestKey)
      }
      return publicSettings.value
    })()

    inFlightRequests.set(requestKey, promise)
    return promise
  }

  async function fetchMenu(location = 'header_main', options = {}) {
    const force = Boolean(options?.force)
    if (!force && menusByLocation.value[location]) {
      return menusByLocation.value[location]
    }

    const requestKey = `menu:${location}`
    if (inFlightRequests.has(requestKey)) {
      return inFlightRequests.get(requestKey)
    }

    const promise = (async () => {
      try {
        const menu = await getPublicMenuByLocationRequest(location)
        if (menu) {
          menusByLocation.value[location] = menu
        }
      } catch {
        // Fallback
      } finally {
        inFlightRequests.delete(requestKey)
      }
      return menusByLocation.value[location] || null
    })()

    inFlightRequests.set(requestKey, promise)
    return promise
  }

  /**
   * Inicializa en paralelo todos los recursos globales del layout público
   * (Ajustes institucionales, logo y menús de cabecera/pie de página)
   */
  async function bootstrapPublicSite(options = {}) {
    const force = Boolean(options?.force)
    if (
      !force &&
      isInitialBootReady.value &&
      Object.keys(publicSettings.value).length > 0 &&
      menusByLocation.value.header_main
    ) {
      return
    }

    const requestKey = 'bootstrap:site'
    if (inFlightRequests.has(requestKey)) {
      return inFlightRequests.get(requestKey)
    }

    const promise = (async () => {
      try {
        await Promise.allSettled([
          fetchPublicSettings({ force }),
          fetchMenu('header_main', { force }),
          fetchMenu('footer_quick_links', { force }),
          fetchMenu('footer_courses', { force }),
        ])

        const logoUrl =
          publicSettings.value?.identity?.site_logo ||
          publicSettings.value?.appearance?.logo_url ||
          '/img/logos/logo-inner.png'
        await preloadImageOnClient(logoUrl)
      } finally {
        isInitialBootReady.value = true
        inFlightRequests.delete(requestKey)
      }
    })()

    inFlightRequests.set(requestKey, promise)
    return promise
  }

  /**
   * Espera a que todas las peticiones en curso del store público terminen
   */
  async function waitForPendingRequests() {
    while (inFlightRequests.size > 0) {
      await Promise.allSettled(Array.from(inFlightRequests.values()))
    }
  }

  function setRouteTransitioning(state) {
    isRouteTransitioning.value = Boolean(state)
    if (state) {
      isPageRendering.value = true
    }
  }

  function setPageRendering(state) {
    isPageRendering.value = Boolean(state)
  }

  /**
   * Desvanece y retira el loader HTML nativo (#dpm-initial-loader de index.html)
   * una vez que la página está 100% hidratada y renderizada.
   */
  function dismissInitialHtmlLoader() {
    if (typeof document === 'undefined') return
    const el = document.getElementById('dpm-initial-loader')
    if (!el || el.classList.contains('is-hidden')) return
    el.classList.add('is-hidden')
    window.setTimeout(() => {
      if (el.parentNode) {
        el.parentNode.removeChild(el)
      }
    }, 360)
  }

  /**
   * Invalida la caché cuando el administrador actualiza páginas, menús o ajustes
   */
  function invalidatePageCache(slug = null) {
    if (slug) {
      delete pagesBySlug.value[slug]
    } else {
      pagesBySlug.value = {}
    }
  }

  return {
    loading,
    isInitialBootReady,
    isRouteTransitioning,
    isPageRendering,
    resolvedSiteLogo,
    pagesBySlug,
    publicSettings,
    menusByLocation,
    postsList,
    postsMeta,
    postsBySlug,
    currentPost,
    fetchPage,
    fetchPosts,
    fetchPostBySlug,
    fetchPublicSettings,
    fetchMenu,
    bootstrapPublicSite,
    waitForPendingRequests,
    setRouteTransitioning,
    setPageRendering,
    dismissInitialHtmlLoader,
    invalidatePageCache,
    submitPublicFormRequest,
  }
})
