import { ref } from 'vue'
import api from '@/services/api'

/**
 * Composable para consumo de endpoints de Menús Institucionales (Laravel Orion + MenuService)
 * CMS IESTP DEPERU MASTER
 */
export function useMenusApi() {
  const loading = ref(false)
  const error = ref(null)

  async function getMenusRequest() {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/admin/menus', {
        params: {
          include: 'rootItems.children',
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

  async function createMenuRequest(payload) {
    loading.value = true
    error.value = null
    try {
      const response = await api.post('/admin/menus', payload)
      return response.data?.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function updateMenuRequest(menuId, payload) {
    loading.value = true
    error.value = null
    try {
      const response = await api.put(`/admin/menus/${menuId}`, payload)
      return response.data?.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function deleteMenuRequest(menuId) {
    loading.value = true
    error.value = null
    try {
      const response = await api.delete(`/admin/menus/${menuId}`)
      return response.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function syncMenuItemsRequest(menuId, items) {
    loading.value = true
    error.value = null
    try {
      const response = await api.put(`/admin/menus/${menuId}/items`, {
        items,
      })
      return response.data?.data || null
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
    getMenusRequest,
    createMenuRequest,
    updateMenuRequest,
    deleteMenuRequest,
    syncMenuItemsRequest,
  }
}
