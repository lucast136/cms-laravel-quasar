<template>
  <div class="footer-bar bg-dark text-white q-py-md">
    <div class="container-custom">
      <div class="row items-center justify-between text-caption font-weight-500">
        <div class="col-12 col-md-auto text-center text-md-left q-mb-xs q-mb-md-none">
          &copy; {{ currentYear }} <strong>IESTP DEPERU MASTER</strong>. Todos los derechos reservados.
        </div>
        <div class="col-12 col-md-auto text-center text-md-right q-gutter-x-md legal-links">
          <router-link to="/terminos-y-politicas" class="text-grey-4 hover-secondary">
            Términos y Condiciones
          </router-link>
          <span class="text-grey-7">|</span>
          <router-link to="/terminos-y-politicas#privacidad" class="text-grey-4 hover-secondary">
            Privacidad (Ley 29733)
          </router-link>
          <span class="text-grey-7">|</span>
          <router-link to="/terminos-y-politicas#cookies" class="text-grey-4 hover-secondary">
            Cookies y Google Analytics
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const currentYear = new Date().getFullYear()
</script>

<style scoped lang="scss">
@import '@/css/template/variables';

.footer-bar {
  border-top: 1px solid rgba(255, 255, 255, 0.08);
}

.hover-secondary {
  text-decoration: none;
  transition: color 0.2s ease;
  &:hover {
    color: $theme-secondary !important;
  }
}
</style>
