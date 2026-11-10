import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { SANCTUM_TOKEN_KEY } from '@/services/api'
import { useAuthApi } from '@/composables/useAuthApi'

/**
 * Store de Autenticación y Control de Accesos (RBAC) - Admin SPA
 * CMS IESTP DEPERU MASTER
 *
 * Durante la fase actual de prototipado y diseño, opera en modo "Acceso Libre"
 * con un usuario simulado con rol Super Admin para permitir navegación completa
 * y revisión de todos los módulos sin fricción de login.
 *
 * =========================================================================
 * @backend-contract CONTRATO CON LARAVEL 13 & SANCTUM RBAC
 * =========================================================================
 *
 * 1. Login Endpoint:
 *    POST /api/v1/auth/login
 *    Headers: { "Content-Type": "application/json", "Accept": "application/json" }
 *    Body Request:
 *    {
 *      "email": "admin@deperumaster.edu.pe",
 *      "password": "secret_password",
 *      "device_name": "QuasarAdminSPA"
 *    }
 *    Response Success (200 OK):
 *    {
 *      "success": true,
 *      "data": {
 *        "token": "1|sanctum_plain_text_token_string...",
 *        "user": {
 *          "id": 1,
 *          "name": "Super Administrador",
 *          "email": "admin@deperumaster.edu.pe",
 *          "avatar_url": "/img/authors/admin-avatar.jpg",
 *          "roles": ["super_admin"],
 *          "permissions": ["*"]
 *        }
 *      }
 *    }
 *
 * 2. Get Authenticated User Profile:
 *    GET /api/v1/auth/me
 *    Headers: { "Authorization": "Bearer {token}", "Accept": "application/json" }
 *
 * 3. Logout Endpoint:
 *    POST /api/v1/auth/logout
 *    Headers: { "Authorization": "Bearer {token}", "Accept": "application/json" }
 *    Elimina el token actual en `personal_access_tokens` de Laravel Sanctum.
 * =========================================================================
 */
export const useAuthStore = defineStore('auth', () => {
  const { loading, loginRequest, fetchProfileRequest, logoutRequest } = useAuthApi()

  const savedToken = typeof window !== 'undefined' ? localStorage.getItem(SANCTUM_TOKEN_KEY) : null

  // Estado limpio: solo existe sesión si hay un token real en localStorage
  const token = ref(savedToken || null)
  const user = ref(null)

  // La autenticación depende estrictamente de tener token activo
  const isAuthenticated = computed(() => Boolean(token.value))
  const isSuperAdmin = computed(() => {
    return (
      user.value?.roles?.some((r) =>
        typeof r === 'string' ? r === 'super_admin' : r.slug === 'super_admin',
      ) || false
    )
  })

  /**
   * Verifica si el usuario actual cuenta con un permiso específico
   * @param {string} permissionSlug - Slug del permiso (ej. 'pages.create', 'media.upload')
   */
  function hasPermission(permissionSlug) {
    if (!isAuthenticated.value) return false
    if (isSuperAdmin.value || user.value?.permissions?.includes('*')) return true
    return user.value?.permissions?.includes(permissionSlug) || false
  }

  /**
   * Autenticación contra API Laravel Sanctum usando el composable useAuthApi
   */
  async function login(credentials = null) {
    try {
      const payload = credentials || {
        email: 'admin@deperumaster.edu.pe',
        password: 'password123#',
        device_name: 'QuasarAdminSPA',
      }
      const data = await loginRequest(payload)
      if (data?.token) {
        token.value = data.token
        user.value = data.user
        if (typeof window !== 'undefined') {
          localStorage.setItem(SANCTUM_TOKEN_KEY, token.value)
        }
        return true
      }
      return false
    } catch {
      return false
    }
  }

  /**
   * Obtiene el perfil del usuario autenticado desde Sanctum
   */
  async function fetchMe() {
    if (!token.value) return null
    try {
      const profile = await fetchProfileRequest()
      if (profile) {
        user.value = profile
      }
      return user.value
    } catch {
      return null
    }
  }

  /**
   * Garantiza una sesión activa con Sanctum en el panel administrativo
   */
  async function ensureDevSession() {
    if (token.value) {
      const profile = await fetchMe()
      if (profile) return true
    }
    return await login()
  }

  /**
   * Cierre de sesión y limpieza de estado
   */
  async function logout() {
    if (token.value) {
      await logoutRequest()
    }
    token.value = null
    user.value = null
    if (typeof window !== 'undefined') {
      localStorage.removeItem(SANCTUM_TOKEN_KEY)
    }
  }

  return {
    token,
    user,
    loading,
    isAuthenticated,
    isSuperAdmin,
    hasPermission,
    login,
    fetchMe,
    ensureDevSession,
    logout,
  }
})
