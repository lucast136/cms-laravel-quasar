<template>
  <q-drawer
    v-model="modelValueComputed"
    show-if-above
    side="left"
    bordered
    :width="260"
    :breakpoint="768"
    class="admin-sidebar bg-white"
  >
    <!-- Branding Header del Sidebar -->
    <div class="sidebar-brand q-pa-md row items-center no-wrap bg-primary text-white">
      <q-avatar
        size="38px"
        color="white"
        text-color="primary"
        class="text-weight-bold shadow-2 q-mr-sm"
      >
        <span style="font-size: 16px; font-weight: 900">DPM</span>
      </q-avatar>
      <div class="column">
        <span class="text-weight-bold text-subtitle2 leading-none">DEPERU MASTER</span>
        <span class="text-caption text-grey-4" style="font-size: 11px"
          >Panel de Administración</span
        >
      </div>
    </div>

    <!-- Navegación Jerárquica Categorizada -->
    <q-scroll-area style="height: calc(100% - 130px)">
      <q-list padding class="text-grey-9">
        <!-- 1. Principal -->
        <q-item-label
          header
          class="text-weight-bold text-uppercase text-grey-6 text-caption q-pt-md"
        >
          Principal
        </q-item-label>

        <q-item clickable v-ripple to="/admin" exact active-class="sidebar-item-active">
          <q-item-section avatar>
            <q-icon name="dashboard" />
          </q-item-section>
          <q-item-section class="text-weight-medium">Dashboard</q-item-section>
        </q-item>

        <q-separator class="q-my-sm" />

        <!-- 2. Contenidos & Page Builder (Módulo 3 ERD) -->
        <q-item-label header class="text-weight-bold text-uppercase text-grey-6 text-caption">
          Contenidos & Builder
        </q-item-label>

        <q-item clickable v-ripple to="/admin/pages" active-class="sidebar-item-active">
          <q-item-section avatar>
            <q-icon name="layers" />
          </q-item-section>
          <q-item-section class="text-weight-medium">Páginas Web</q-item-section>
          <q-item-section side>
            <q-badge color="primary" rounded label="5" />
          </q-item-section>
        </q-item>

        <q-item clickable v-ripple to="/admin/posts" active-class="sidebar-item-active">
          <q-item-section avatar>
            <q-icon name="article" />
          </q-item-section>
          <q-item-section class="text-weight-medium">Noticias & Blog</q-item-section>
        </q-item>

        <q-item clickable v-ripple to="/admin/components" active-class="sidebar-item-active">
          <q-item-section avatar>
            <q-icon name="extension" />
          </q-item-section>
          <q-item-section class="text-weight-medium">Catálogo Bloques</q-item-section>
          <q-item-section side>
            <q-badge color="accent" rounded label="15" />
          </q-item-section>
        </q-item>

        <q-separator class="q-my-sm" />

        <!-- 3. Biblioteca & Archivos (Módulo 5 ERD) -->
        <q-item-label header class="text-weight-bold text-uppercase text-grey-6 text-caption">
          Biblioteca
        </q-item-label>

        <q-item clickable v-ripple to="/admin/media" active-class="sidebar-item-active">
          <q-item-section avatar>
            <q-icon name="photo_library" />
          </q-item-section>
          <q-item-section class="text-weight-medium">Archivos & Medios</q-item-section>
        </q-item>

        <q-separator class="q-my-sm" />

        <!-- 4. Estructura Web (Módulo 6 ERD) -->
        <q-item-label header class="text-weight-bold text-uppercase text-grey-6 text-caption">
          Estructura & Menús
        </q-item-label>

        <q-item clickable v-ripple to="/admin/menus" active-class="sidebar-item-active">
          <q-item-section avatar>
            <q-icon name="menu_open" />
          </q-item-section>
          <q-item-section class="text-weight-medium">Menús & Navegación</q-item-section>
        </q-item>

        <q-separator class="q-my-sm" />

        <!-- 5. Configuración & Identidad (Módulo 1 & 2 ERD) -->
        <q-item-label header class="text-weight-bold text-uppercase text-grey-6 text-caption">
          Seguridad & Ajustes
        </q-item-label>

        <q-item clickable v-ripple to="/admin/users" active-class="sidebar-item-active">
          <q-item-section avatar>
            <q-icon name="manage_accounts" />
          </q-item-section>
          <q-item-section class="text-weight-medium">Usuarios & Roles</q-item-section>
          <q-item-section side>
            <q-badge color="positive" rounded label="RBAC" />
          </q-item-section>
        </q-item>

        <q-item clickable v-ripple to="/admin/settings" active-class="sidebar-item-active">
          <q-item-section avatar>
            <q-icon name="tune" />
          </q-item-section>
          <q-item-section class="text-weight-medium">Ajustes del Sitio</q-item-section>
        </q-item>
      </q-list>
    </q-scroll-area>

    <!-- Footer del Sidebar: Estado del Sistema -->
    <div class="sidebar-footer absolute-bottom q-pa-sm bg-grey-2 border-top">
      <div class="row items-center justify-between text-caption text-grey-7 q-px-xs">
        <div class="row items-center">
          <q-badge color="positive" rounded class="q-mr-xs" />
          <span>Acceso Libre</span>
        </div>
        <span class="text-weight-bold" style="font-size: 10px">LARAVEL 13 API</span>
      </div>
    </div>
  </q-drawer>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: { type: Boolean, default: true },
})

const emit = defineEmits(['update:modelValue'])

const modelValueComputed = computed({
  get: () => props.modelValue,
  set: (val) => emit('update:modelValue', val),
})
</script>

<style scoped>
.leading-none {
  line-height: 1;
}
.sidebar-item-active {
  background-color: rgba(0, 50, 120, 0.08);
  color: #003278 !important;
  font-weight: 700;
  border-right: 4px solid #003278;
}
.border-top {
  border-top: 1px solid #e2e8f0;
}
</style>
