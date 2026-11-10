<template>
  <q-slide-transition>
    <div v-show="isOpen" class="top-search-overlay bg-primary text-white">
      <div class="container-custom q-py-md">
        <form @submit.prevent="handleSearch" class="search-form row no-wrap items-center">
          <q-btn
            flat
            round
            dense
            icon="search"
            color="white"
            type="submit"
            aria-label="Buscar"
            class="q-mr-sm"
          />
          <q-input
            v-model="searchQuery"
            placeholder="¿Qué deseas aprender hoy? Escribe y presiona enter..."
            dark
            borderless
            dense
            autofocus
            class="col search-input"
            color="white"
          />
          <q-btn
            flat
            round
            dense
            icon="close"
            color="white"
            @click="closeSearch"
            aria-label="Cerrar búsqueda"
          />
        </form>
      </div>
    </div>
  </q-slide-transition>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['update:isOpen', 'search'])

const searchQuery = ref('')

const closeSearch = () => {
  emit('update:isOpen', false)
}

const handleSearch = () => {
  if (searchQuery.value.trim()) {
    emit('search', searchQuery.value.trim())
    closeSearch()
  }
}
</script>

<style scoped lang="scss">
.top-search-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

.search-input {
  font-size: 16px;
}
</style>
