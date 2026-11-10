import { defineRouter } from '#q-app'
import { routes, handleHotUpdate } from 'vue-router/auto-routes'
import {
  createMemoryHistory,
  createRouter,
  createWebHashHistory,
  createWebHistory,
} from 'vue-router'
import { SANCTUM_TOKEN_KEY } from '@/services/api'
import { usePublicCmsStore } from '@/stores/public-cms'

/*
 * If not building with SSR mode, you can
 * directly export the Router instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Router instance.
 */

export default defineRouter(({ store /*, ssrContext */ }) => {
  const createHistory = import.meta.env.QUASAR_SERVER
    ? createMemoryHistory
    : import.meta.env.QUASAR_VUE_ROUTER_MODE === 'history'
      ? createWebHistory
      : createWebHashHistory

  const Router = createRouter({
    scrollBehavior: () => ({ left: 0, top: 0 }),
    routes,

    // Leave this as is and make changes in quasar.conf.js instead!
    // quasar.conf.js -> build -> vueRouterMode
    // quasar.conf.js -> build -> publicPath
    history: createHistory(import.meta.env.QUASAR_VUE_ROUTER_BASE),
  })

  // Guardia de navegación para proteger el Panel Administrativo (/admin/*) y activar el Loader Público
  Router.beforeEach((to, from) => {
    if (typeof window === 'undefined') return true

    if (!to.path.startsWith('/admin') && to.fullPath !== from.fullPath) {
      const publicCmsStore = usePublicCmsStore(store)
      publicCmsStore.setRouteTransitioning(true)
    }

    const hasToken = Boolean(localStorage.getItem(SANCTUM_TOKEN_KEY))

    if (to.path.startsWith('/admin') && to.path !== '/admin/login' && !hasToken) {
      return '/admin/login'
    }

    if (to.path === '/admin/login' && hasToken) {
      return '/admin'
    }

    return true
  })

  Router.onError(() => {
    if (typeof window !== 'undefined') {
      const publicCmsStore = usePublicCmsStore(store)
      publicCmsStore.setRouteTransitioning(false)
      publicCmsStore.setPageRendering(false)
      publicCmsStore.dismissInitialHtmlLoader()
    }
  })

  // enable HMR for it
  if (import.meta.hot) {
    handleHotUpdate(Router)
  }

  return Router
})
