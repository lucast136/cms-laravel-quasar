import { ref, computed } from 'vue'
import api from '@/services/api'

/**
 * Composable para consumo de endpoints Públicos del CMS (SSR / SEO)
 * CMS IESTP DEPERU MASTER
 */
export function usePublicCmsApi() {
  const pendingRequests = ref(0)
  const loading = computed(() => pendingRequests.value > 0)
  const error = ref(null)

  async function getPublicPageBySlugRequest(slug) {
    pendingRequests.value++
    error.value = null
    try {
      const response = await api.get(`/pages/${slug}`)
      return response.data?.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      pendingRequests.value = Math.max(0, pendingRequests.value - 1)
    }
  }

  async function getPublicPostsRequest(params = {}) {
    pendingRequests.value++
    error.value = null
    try {
      const response = await api.get('/posts', { params })
      return response.data || { data: [], meta: {} }
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      pendingRequests.value = Math.max(0, pendingRequests.value - 1)
    }
  }

  async function getPublicPostBySlugRequest(slug) {
    pendingRequests.value++
    error.value = null
    try {
      const response = await api.get(`/posts/${slug}`)
      return response.data?.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      pendingRequests.value = Math.max(0, pendingRequests.value - 1)
    }
  }

  async function getPublicSettingsRequest() {
    pendingRequests.value++
    error.value = null
    try {
      const response = await api.get('/settings/public')
      return response.data?.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      pendingRequests.value = Math.max(0, pendingRequests.value - 1)
    }
  }

  async function getPublicMenuByLocationRequest(location) {
    pendingRequests.value++
    error.value = null
    try {
      const response = await api.get(`/menus/${location}`)
      return response.data?.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      pendingRequests.value = Math.max(0, pendingRequests.value - 1)
    }
  }

  async function submitPublicFormRequest(payload) {
    pendingRequests.value++
    error.value = null
    try {
      const response = await api.post('/forms/submit', payload)
      return response.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      pendingRequests.value = Math.max(0, pendingRequests.value - 1)
    }
  }

  return {
    loading,
    error,
    getPublicPageBySlugRequest,
    getPublicPostsRequest,
    getPublicPostBySlugRequest,
    getPublicSettingsRequest,
    getPublicMenuByLocationRequest,
    submitPublicFormRequest,
  }
}
