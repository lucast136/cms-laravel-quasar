<template>
  <div
    class="page-header-banner position-relative text-white text-center q-py-xl"
    :style="{
      backgroundImage: `linear-gradient(-90deg, rgba(39, 48, 68, 0.75), rgba(39, 48, 68, 0.92)), url(${bgImage})`,
      backgroundSize: 'cover',
      backgroundPosition: 'center',
    }"
  >
    <div class="container-custom q-py-lg">
      <h1 class="page-title text-white font-weight-800 q-mb-sm">
        {{ title }}
      </h1>
      <div class="breadcrumb-trail row items-center justify-center q-gutter-x-sm text-caption">
        <router-link to="/" class="text-white">Inicio</router-link>
        <span class="text-secondary">•</span>
        <span class="text-secondary font-weight-700">{{ subtitle || title }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  subtitle: {
    type: String,
    default: '',
  },
  bgImage: {
    type: String,
    default: '/img/bg/bg-04.jpg',
  },
})
</script>

<style scoped lang="scss">
@import '@/css/template/variables';

.page-header-banner {
  background-color: $theme-dark;
  border-bottom: 3px solid $theme-primary;
}

.page-title {
  font-size: 40px;
  @media (max-width: 768px) {
    font-size: 28px;
  }
}

.breadcrumb-trail a {
  text-decoration: none;
  transition: color 0.3s;
  &:hover {
    color: $theme-primary;
  }
}
</style>
