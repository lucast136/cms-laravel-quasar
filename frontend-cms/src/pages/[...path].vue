<template>
  <PublicLayout>
    <section class="section-padding bg-very-light-gray text-center">
      <div class="container-custom q-py-xl">
        <div class="text-h1 font-weight-800 text-primary q-mb-sm">404</div>
        <h2 class="text-h4 font-weight-700 text-dark q-mb-md">Página no encontrada</h2>
        <p class="text-body1 text-grey-7 q-mb-xl" style="max-width: 520px; margin: 0 auto">
          Lo sentimos, la dirección solicitada no existe o ha sido movida en el portal oficial del
          IESTP DEPERU MASTER.
        </p>
        <router-link to="/" class="butn">
          <i class="fas fa-home icon-arrow before"></i>
          <span class="label">Volver al Inicio</span>
          <i class="fas fa-home icon-arrow after"></i>
        </router-link>
      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { useMeta } from 'quasar'
import PublicLayout from '@/layouts/PublicLayout.vue'

useMeta({
  title: 'Página no encontrada (404) | IESTP DEPERU MASTER',
})
</script>
