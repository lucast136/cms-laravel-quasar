import { ref } from 'vue'
import api from '@/services/api'

/**
 * Composable para consumo de endpoints de Usuarios y Roles RBAC (Laravel Orion)
 * CMS IESTP DEPERU MASTER
 */
export function useUsersApi() {
  const loading = ref(false)
  const error = ref(null)

  async function getUsersRequest(params = {}) {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/admin/users', {
        params: {
          include: 'roles',
          sort: 'id',
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

  async function createUserRequest(payload) {
    loading.value = true
    error.value = null
    try {
      const response = await api.post('/admin/users', payload)
      return response.data?.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function updateUserRequest(id, payload) {
    loading.value = true
    error.value = null
    try {
      const response = await api.put(`/admin/users/${id}`, payload)
      return response.data?.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function deleteUserRequest(id) {
    loading.value = true
    error.value = null
    try {
      await api.delete(`/admin/users/${id}`)
      return true
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function getRolesRequest() {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/admin/roles')
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
    getUsersRequest,
    createUserRequest,
    updateUserRequest,
    deleteUserRequest,
    getRolesRequest,
  }
}
