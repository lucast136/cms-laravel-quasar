<template>
  <q-layout view="lHh LpR lFf" class="admin-layout bg-grey-1">
    <!-- Barra Superior -->
    <AdminHeader @toggle-drawer="drawerOpen = !drawerOpen" />

    <!-- Barra Lateral de Navegación -->
    <AdminSidebar v-model="drawerOpen" />

    <!-- Contenedor Dinámico de Páginas -->
    <q-page-container class="admin-container">
      <slot />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import AdminHeader from '@/components/admin/layout/AdminHeader.vue'
import AdminSidebar from '@/components/admin/layout/AdminSidebar.vue'
import { useAuthStore } from '@/stores/auth'
import { useAdminPagesStore } from '@/stores/admin-pages'

const router = useRouter()
const drawerOpen = ref(true)
const authStore = useAuthStore()
const pagesStore = useAdminPagesStore()

onMounted(async () => {
  if (!authStore.token) {
    router.replace('/admin/login')
    return
  }
  const user = await authStore.fetchMe()
  if (!user) {
    await authStore.logout()
    router.replace('/admin/login')
    return
  }
  await pagesStore.fetchPages()
})
</script>

<style scoped>
.admin-layout {
  min-height: 100vh;
}
.admin-container {
  background-color: #f8fafc;
}
</style>
