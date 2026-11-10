<template>
  <q-header elevated class="bg-primary text-white">
    <q-toolbar class="q-px-md">
      <!-- Botón de apertura/cierre del Sidebar -->
      <q-btn flat dense round icon="menu" aria-label="Menu" @click="$emit('toggle-drawer')">
        <q-tooltip>Alternar Menú Lateral</q-tooltip>
      </q-btn>

      <!-- Título institucional y breadcrumbs -->
      <q-toolbar-title class="row items-center q-gutter-x-sm text-subtitle1">
        <span class="text-weight-bold text-uppercase gt-xs">DEPERU MASTER</span>
        <q-badge color="secondary" text-color="white" label="ADMIN" class="text-weight-bold" />
        <span class="text-caption text-grey-4 gt-sm">| CMS Institucional v1.0</span>
      </q-toolbar-title>

      <q-space />

      <!-- Acciones de barra superior -->
      <div class="row items-center q-gutter-x-sm">
        <!-- Ver Sitio Web Público en nueva pestaña -->
        <q-btn flat round dense icon="open_in_new" href="/" target="_blank">
          <q-tooltip>Abrir Web Pública</q-tooltip>
        </q-btn>

        <!-- Toggle Modo Oscuro -->
        <q-btn
          flat
          round
          dense
          :icon="$q.dark.isActive ? 'light_mode' : 'dark_mode'"
          @click="$q.dark.toggle()"
        >
          <q-tooltip>{{ $q.dark.isActive ? 'Modo Claro' : 'Modo Oscuro' }}</q-tooltip>
        </q-btn>

        <!-- Menú de Notificaciones del Sistema -->
        <q-btn flat round dense icon="notifications">
          <q-badge color="secondary" floating rounded>3</q-badge>
          <q-menu anchor="bottom end" self="top end">
            <q-list style="min-width: 300px">
              <q-item-label header class="text-weight-bold bg-grey-2">
                Notificaciones del CMS
              </q-item-label>
              <q-item clickable v-close-popup to="/admin/pages">
                <q-item-section avatar>
                  <q-avatar icon="edit_note" color="primary" text-color="white" size="sm" />
                </q-item-section>
                <q-item-section>
                  <q-item-label>Página Principal modificada</q-item-label>
                  <q-item-label caption>Hace 15 minutos</q-item-label>
                </q-item-section>
              </q-item>
              <q-item clickable v-close-popup to="/admin/media">
                <q-item-section avatar>
                  <q-avatar icon="cloud_upload" color="positive" text-color="white" size="sm" />
                </q-item-section>
                <q-item-section>
                  <q-item-label>3 nuevas imágenes subidas</q-item-label>
                  <q-item-label caption>Hace 1 hora</q-item-label>
                </q-item-section>
              </q-item>
              <q-separator />
              <q-item clickable v-close-popup class="text-center text-primary text-weight-bold">
                <q-item-section>Ver toda la actividad</q-item-section>
              </q-item>
            </q-list>
          </q-menu>
        </q-btn>

        <q-separator vertical dark inset class="q-mx-xs" />

        <!-- Menú de Usuario / Perfil -->
        <q-btn-dropdown flat no-caps dense class="q-pl-sm">
          <template #label>
            <div class="row items-center no-wrap">
              <q-avatar size="32px" class="q-mr-sm">
                <img
                  :src="authStore.user?.avatar_url || 'https://cdn.quasar.dev/img/avatar.png'"
                  alt="Avatar"
                />
              </q-avatar>
              <div class="text-left gt-sm">
                <div class="text-weight-medium text-body2 leading-tight">
                  {{ authStore.user?.name || 'Administrador' }}
                </div>
                <div class="text-caption text-grey-4">Super Admin</div>
              </div>
            </div>
          </template>

          <q-list style="min-width: 220px">
            <q-item class="bg-grey-2">
              <q-item-section>
                <q-item-label class="text-weight-bold">{{ authStore.user?.name }}</q-item-label>
                <q-item-label caption>{{ authStore.user?.email }}</q-item-label>
                <q-badge color="positive" label="RBAC: Super Admin" class="q-mt-xs self-start" />
              </q-item-section>
            </q-item>
            <q-separator />
            <q-item clickable v-close-popup to="/admin/users">
              <q-item-section avatar><q-icon name="manage_accounts" /></q-item-section>
              <q-item-section>Usuarios & Roles</q-item-section>
            </q-item>
            <q-item clickable v-close-popup to="/admin/settings">
              <q-item-section avatar><q-icon name="settings" /></q-item-section>
              <q-item-section>Configuración CMS</q-item-section>
            </q-item>
            <q-separator />
            <q-item clickable v-close-popup class="text-negative" @click="handleLogout">
              <q-item-section avatar><q-icon name="logout" color="negative" /></q-item-section>
              <q-item-section>Cerrar Sesión</q-item-section>
            </q-item>
          </q-list>
        </q-btn-dropdown>
      </div>
    </q-toolbar>
  </q-header>
</template>

<script setup>
import { useQuasar } from 'quasar'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

defineEmits(['toggle-drawer'])

const $q = useQuasar()
const router = useRouter()
const authStore = useAuthStore()

async function handleLogout() {
  await authStore.logout()
  $q.notify({
    type: 'info',
    message: 'Sesión cerrada correctamente',
  })
  router.push('/admin/login')
}
</script>

<style scoped>
.leading-tight {
  line-height: 1.2;
}
</style>
