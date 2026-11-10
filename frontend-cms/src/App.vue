<template>
  <GlobalPageLoader :visible="showGlobalLoader" />
  <router-view />
</template>

<script setup>
import { computed, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import GlobalPageLoader from '@/components/public/common/GlobalPageLoader.vue'
import { usePublicCmsStore } from '@/stores/public-cms'

const route = useRoute()
const publicCmsStore = usePublicCmsStore()

const isAdminRoute = computed(() => route.path.startsWith('/admin'))

const showGlobalLoader = computed(() => {
  if (isAdminRoute.value) return false
  return (
    !publicCmsStore.isInitialBootReady ||
    publicCmsStore.isRouteTransitioning ||
    publicCmsStore.loading ||
    publicCmsStore.isPageRendering
  )
})

onMounted(() => {
  if (isAdminRoute.value) {
    publicCmsStore.setPageRendering(false)
    publicCmsStore.setRouteTransitioning(false)
    publicCmsStore.dismissInitialHtmlLoader()
  }
})

watch(isAdminRoute, (isAdmin) => {
  if (isAdmin) {
    publicCmsStore.setPageRendering(false)
    publicCmsStore.setRouteTransitioning(false)
    publicCmsStore.dismissInitialHtmlLoader()
  }
})
</script>
