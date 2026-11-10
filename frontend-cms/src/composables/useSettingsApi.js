import { ref } from 'vue'
import api from '@/services/api'

/**
 * Composable para consumo de endpoints de Configuración Global y Estadísticas del Dashboard
 * (SiteSettingService + DashboardController)
 * CMS IESTP DEPERU MASTER
 */
export function useSettingsApi() {
  const loading = ref(false)
  const error = ref(null)

  async function getDashboardStatsRequest() {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/admin/dashboard/stats')
      return response.data?.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function getSettingsRequest() {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/admin/settings')
      return response.data?.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function updateSettingsRequest(payload) {
    loading.value = true
    error.value = null
    try {
      const response = await api.put('/admin/settings', payload)
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
    getDashboardStatsRequest,
    getSettingsRequest,
    updateSettingsRequest,
  }
}
