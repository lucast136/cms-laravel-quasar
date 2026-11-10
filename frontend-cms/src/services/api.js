import axios from 'axios'

export const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1'
export const SANCTUM_TOKEN_KEY = 'cms_dpm_sanctum_token'

const api = axios.create({
  baseURL: API_BASE_URL,
  timeout: 15000,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
})

// Interceptor de Petición: Inyecta Token Bearer de Laravel Sanctum
api.interceptors.request.use(
  (config) => {
    if (typeof window !== 'undefined') {
      const token = localStorage.getItem(SANCTUM_TOKEN_KEY)
      if (token) {
        config.headers.Authorization = `Bearer ${token}`
      }
    }
    return config
  },
  (error) => Promise.reject(error),
)

// Interceptor de Respuesta: Manejo centralizado de errores
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401 && typeof window !== 'undefined') {
      localStorage.removeItem(SANCTUM_TOKEN_KEY)
      const isPathAdmin =
        window.location.pathname.startsWith('/admin') &&
        !window.location.pathname.startsWith('/admin/login')
      const isHashAdmin =
        window.location.hash.includes('/admin') && !window.location.hash.includes('/admin/login')

      if (isPathAdmin) {
        window.location.href = '/admin/login'
      } else if (isHashAdmin) {
        window.location.hash = '#/admin/login'
      }
    }
    return Promise.reject(error)
  },
)

export default api
