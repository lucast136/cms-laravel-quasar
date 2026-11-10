import { ref } from 'vue'
import api from '@/services/api'

/**
 * Composable para consumo de endpoints de Noticias & Blog (Laravel Orion)
 * CMS IESTP DEPERU MASTER
 */
export function usePostsApi() {
  const loading = ref(false)
  const error = ref(null)

  async function getPostsRequest(params = {}) {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/admin/posts', {
        params: {
          include: 'author,terms',
          sort: '-updated_at',
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

  async function createPostRequest(payload) {
    loading.value = true
    error.value = null
    try {
      const response = await api.post('/admin/posts', payload)
      return response.data?.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function updatePostRequest(id, payload) {
    loading.value = true
    error.value = null
    try {
      const response = await api.put(`/admin/posts/${id}`, payload)
      return response.data?.data || null
    } catch (err) {
      error.value = err.response?.data?.message || err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function deletePostRequest(id) {
    loading.value = true
    error.value = null
    try {
      await api.delete(`/admin/posts/${id}`)
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
    getPostsRequest,
    createPostRequest,
    updatePostRequest,
    deletePostRequest,
  }
}
