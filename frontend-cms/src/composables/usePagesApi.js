import { ref } from 'vue'
import api from '@/services/api'

/**
 * Composable para consumo de endpoints de Páginas, Tipos de Componentes y Page Builder
 * (Laravel Orion + ContentService)
 * CMS IESTP DEPERU MASTER
 */
export function usePagesApi() {
  const loading = ref(false)
  const error = ref(null)

  async function getPagesRequest(params = {}) {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/admin/pages', {
        params: {
          include: 'sections.componentType,author',
          sort: 'sort_order',
          ...params,
        },
      })
      return response.data?.data || []
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function getPageByIdRequest(id) {
    loading.value = true
    error.value = null
    try {
      const response = await api.get(`/admin/pages/${id}`, {
        params: {
          include: 'sections.componentType,author',
        },
      })
      return response.data?.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function createPageRequest(payload) {
    loading.value = true
    error.value = null
    try {
      const response = await api.post('/admin/pages', payload)
      return response.data?.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function updatePageRequest(id, payload) {
    loading.value = true
    error.value = null
    try {
      const response = await api.put(`/admin/pages/${id}`, payload)
      return response.data?.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function deletePageRequest(id) {
    loading.value = true
    error.value = null
    try {
      await api.delete(`/admin/pages/${id}`)
      return true
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function syncPageSectionsRequest(contentId, sections) {
    loading.value = true
    error.value = null
    try {
      const response = await api.put(`/admin/contents/${contentId}/sections`, {
        sections,
      })
      return response.data?.data || []
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function getComponentTypesRequest() {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/admin/component-types', {
        params: {
          limit: 50,
        },
      })
      return response.data?.data || []
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    loading,
    error,
    getPagesRequest,
    getPageByIdRequest,
    createPageRequest,
    updatePageRequest,
    deletePageRequest,
    syncPageSectionsRequest,
    getComponentTypesRequest,
  }
}
