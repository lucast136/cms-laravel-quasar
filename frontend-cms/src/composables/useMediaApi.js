import { ref } from 'vue'
import api from '@/services/api'

/**
 * Composable para consumo de endpoints de Biblioteca Multimedia (Laravel Orion + MediaService)
 * CMS IESTP DEPERU MASTER
 */
export function useMediaApi() {
  const loading = ref(false)
  const error = ref(null)

  async function getMediaListRequest(params = {}) {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/admin/media', {
        params: {
          sort: '-created_at',
          limit: 50,
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

  async function uploadMediaFileRequest(file, altText = '') {
    loading.value = true
    error.value = null
    try {
      const formData = new FormData()
      formData.append('file', file)
      if (altText) {
        formData.append('alt_text', altText)
      }
      const response = await api.post('/admin/media/upload', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
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

  async function deleteMediaRequest(id) {
    loading.value = true
    error.value = null
    try {
      await api.delete(`/admin/media/${id}`)
      return true
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
    getMediaListRequest,
    uploadMediaFileRequest,
    deleteMediaRequest,
  }
}
