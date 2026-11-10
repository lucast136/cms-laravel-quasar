<template>
  <q-dialog v-model="modelValueComputed" persistent>
    <q-card style="min-width: 650px; max-width: 850px; width: 90vw" class="rounded-borders">
      <!-- Encabezado del Catálogo -->
      <q-card-section class="row items-center justify-between q-pb-xs bg-primary text-white">
        <div class="row items-center">
          <q-avatar
            icon="extension"
            color="white"
            text-color="primary"
            size="32px"
            class="q-mr-sm"
          />
          <div>
            <div class="text-h6 text-weight-bold">Catálogo de Bloques y Componentes</div>
            <div class="text-caption text-grey-3">
              Selecciona un bloque para agregarlo a la página
            </div>
          </div>
        </div>
        <q-btn flat round dense icon="close" color="white" v-close-popup />
      </q-card-section>

      <!-- Buscador y Categorías -->
      <q-card-section class="q-pb-none q-pt-md">
        <q-input
          v-model="search"
          dense
          outlined
          placeholder="Buscar componente por nombre o descripción..."
          clearable
          class="q-mb-sm"
        >
          <template #prepend>
            <q-icon name="search" />
          </template>
        </q-input>

        <q-tabs
          v-model="selectedCategory"
          dense
          active-color="primary"
          indicator-color="primary"
          align="left"
          narrow-indicator
          class="text-grey-7"
        >
          <q-tab
            v-for="cat in COMPONENT_CATEGORIES"
            :key="cat.id"
            :name="cat.id"
            :label="cat.label"
            :icon="cat.icon"
            no-caps
          />
        </q-tabs>
        <q-separator />
      </q-card-section>

      <!-- Grilla de Componentes -->
      <q-card-section style="max-height: 480px" class="scroll q-pt-md">
        <div class="row q-col-gutter-md">
          <div v-for="comp in filteredComponents" :key="comp.code" class="col-12 col-sm-6">
            <q-card
              flat
              bordered
              class="component-card cursor-pointer"
              @click="selectComponent(comp.code)"
            >
              <q-card-section class="q-pa-md">
                <div class="row items-start no-wrap">
                  <q-avatar
                    :icon="comp.icon"
                    color="blue-1"
                    text-color="primary"
                    size="44px"
                    class="q-mr-md"
                  />
                  <div class="column justify-between full-width">
                    <div class="row items-center justify-between">
                      <span class="text-weight-bold text-subtitle2 text-dark">{{ comp.name }}</span>
                      <q-badge
                        v-if="comp.is_system"
                        color="secondary"
                        outline
                        label="Landing"
                        class="text-weight-bold"
                      />
                      <q-badge v-else color="positive" label="Dinámico" />
                    </div>
                    <p class="text-caption text-grey-7 q-my-xs ellipsis-2-lines">
                      {{ comp.description }}
                    </p>
                    <div class="row items-center justify-between q-mt-xs">
                      <span class="text-caption text-grey-5 font-mono">{{ comp.code }}</span>
                      <q-btn
                        size="sm"
                        unelevated
                        color="primary"
                        label="Agregar"
                        icon="add"
                        no-caps
                        @click.stop="selectComponent(comp.code)"
                      />
                    </div>
                  </div>
                </div>
              </q-card-section>
            </q-card>
          </div>
        </div>

        <div v-if="filteredComponents.length === 0" class="text-center q-pa-xl text-grey-6">
          <q-icon name="search_off" size="48px" />
          <div class="text-subtitle1 q-mt-sm">No se encontraron componentes</div>
          <div class="text-caption">Prueba con otra búsqueda o categoría</div>
        </div>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { ref, computed } from 'vue'
import { COMPONENT_CATEGORIES, COMPONENT_TYPES } from '@/constants/componentTypes'

const props = defineProps({
  modelValue: { type: Boolean, default: false },
})

const emit = defineEmits(['update:modelValue', 'add-component'])

const modelValueComputed = computed({
  get: () => props.modelValue,
  set: (val) => emit('update:modelValue', val),
})

const search = ref('')
const selectedCategory = ref('all')

const filteredComponents = computed(() => {
  return COMPONENT_TYPES.filter((comp) => {
    const matchCat = selectedCategory.value === 'all' || comp.category === selectedCategory.value
    const matchSearch =
      comp.name.toLowerCase().includes(search.value.toLowerCase()) ||
      comp.description.toLowerCase().includes(search.value.toLowerCase()) ||
      comp.code.toLowerCase().includes(search.value.toLowerCase())
    return matchCat && matchSearch
  })
})

function selectComponent(code) {
  emit('add-component', code)
  modelValueComputed.value = false
}
</script>

<style scoped>
.rounded-borders {
  border-radius: 12px;
}
.component-card {
  border-radius: 10px;
  transition: all 0.2s ease;
}
.component-card:hover {
  border-color: #003278;
  transform: translateY(-2px);
  box-shadow: 0 4px 14px rgba(0, 50, 120, 0.12);
}
.font-mono {
  font-family: monospace;
}
</style>
