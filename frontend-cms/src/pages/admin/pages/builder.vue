<template>
  <div class="fullscreen flex flex-center bg-grey-2">
    <q-spinner-dots color="primary" size="48px" />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

onMounted(() => {
  // Redirigir por defecto a la edición de la Página Principal (ID 1)
  router.replace('/admin/pages/1/builder')
})
</script>
