import { ref } from 'vue'
import api from '@/services/api'

/**
 * Composable para consumo de endpoints de Autenticación (Laravel Sanctum)
 * CMS IESTP DEPERU MASTER
 */
export function useAuthApi() {
  const loading = ref(false)
  const error = ref(null)

  async function loginRequest(credentials) {
    loading.value = true
    error.value = null
    try {
      const response = await api.post('/auth/login', credentials)
      return response.data?.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchProfileRequest() {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/auth/me')
      return response.data?.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function logoutRequest() {
    loading.value = true
    error.value = null
    try {
      await api.post('/auth/logout')
      return true
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      return false
    } finally {
      loading.value = false
    }
  }

  return {
    loading,
    error,
    loginRequest,
    fetchProfileRequest,
    logoutRequest,
  }
}
