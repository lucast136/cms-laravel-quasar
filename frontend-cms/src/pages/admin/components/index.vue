<template>
  <AdminLayout>
    <q-page class="q-pa-lg">
      <!-- Encabezado -->
      <div class="row items-center justify-between q-mb-lg">
        <div>
          <q-breadcrumbs class="text-caption text-grey-7">
            <q-breadcrumbs-el label="Dashboard" icon="dashboard" to="/admin" />
            <q-breadcrumbs-el label="Catálogo de Componentes" icon="extension" />
          </q-breadcrumbs>
          <h1 class="text-h4 text-weight-bold text-dark q-my-xs">
            Catálogo Central de Bloques (component_types)
          </h1>
          <div class="text-subtitle2 text-grey-7">
            Bloques y secciones disponibles para el constructor visual con validación de datos en
            formato JSON.
          </div>
        </div>

        <q-btn
          color="primary"
          unelevated
          icon="add"
          label="Registrar Nuevo Bloque"
          no-caps
          @click="openNewBlockNotice"
        />
      </div>

      <!-- Filtros por Categoría -->
      <q-card flat bordered class="q-mb-lg rounded-borders">
        <q-card-section class="q-py-sm">
          <div class="row items-center justify-between q-col-gutter-sm">
            <div class="col-12 col-md-5">
              <q-input
                v-model="search"
                dense
                outlined
                placeholder="Filtrar por nombre o código..."
                clearable
              >
                <template #prepend><q-icon name="search" /></template>
              </q-input>
            </div>
            <div class="col-12 col-md-7 text-right">
              <q-tabs
                v-model="activeCategory"
                dense
                active-color="primary"
                indicator-color="primary"
                align="right"
                class="text-grey-7"
              >
                <q-tab
                  v-for="cat in COMPONENT_CATEGORIES"
                  :key="cat.id"
                  :name="cat.id"
                  :label="cat.label"
                  no-caps
                />
              </q-tabs>
            </div>
          </div>
        </q-card-section>
      </q-card>

      <!-- Grilla de Componentes -->
      <div class="row q-col-gutter-md">
        <div v-for="comp in filteredComponents" :key="comp.code" class="col-12 col-sm-6 col-lg-4">
          <q-card
            flat
            bordered
            class="component-box full-height column justify-between rounded-borders"
          >
            <q-card-section>
              <div class="row items-center justify-between q-mb-sm">
                <q-avatar :icon="comp.icon" color="blue-1" text-color="primary" size="44px" />
                <q-badge
                  :color="comp.is_system ? 'secondary' : 'positive'"
                  :label="comp.is_system ? 'Landing Vue SFC' : 'Dinámico (JSON)'"
                />
              </div>

              <div class="text-subtitle1 text-weight-bold text-dark q-mb-xs">
                {{ comp.name }}
              </div>
              <div class="text-caption font-mono text-primary q-mb-sm">code: {{ comp.code }}</div>
              <p class="text-caption text-grey-7">
                {{ comp.description }}
              </p>
            </q-card-section>

            <q-separator />

            <q-card-actions align="between" class="bg-grey-1 q-px-md">
              <span class="text-caption text-grey-6 font-mono"> Cat: {{ comp.category }} </span>
              <q-btn
                flat
                color="primary"
                size="sm"
                label="Ver JSON Schema"
                icon="code"
                no-caps
                @click="viewSchema(comp)"
              />
            </q-card-actions>
          </q-card>
        </div>
      </div>

      <!-- Diálogo de Visualización del Schema -->
      <q-dialog v-model="schemaDialog.open">
        <q-card style="min-width: 550px; max-width: 700px" class="rounded-borders">
          <q-card-section class="row items-center justify-between q-pb-xs bg-primary text-white">
            <div class="text-subtitle1 text-weight-bold">
              Esquema JSON: {{ schemaDialog.component?.name }}
            </div>
            <q-btn flat round dense icon="close" color="white" v-close-popup />
          </q-card-section>

          <q-card-section>
            <div class="text-caption text-grey-8 q-mb-sm">
              Estructura tipada que valida los datos almacenados en
              <code>content_sections.data</code>:
            </div>
            <q-input
              :model-value="JSON.stringify(schemaDialog.component?.schema || {}, null, 2)"
              type="textarea"
              rows="14"
              readonly
              outlined
              dense
              class="font-mono text-caption"
            />
          </q-card-section>
        </q-card>
      </q-dialog>
    </q-page>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, reactive, onMounted } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { COMPONENT_CATEGORIES, COMPONENT_TYPES } from '@/constants/componentTypes'
import { useNotification } from '@/composables/useNotification'
import { usePagesApi } from '@/composables/usePagesApi'

const notify = useNotification()
const { getComponentTypesRequest } = usePagesApi()

const componentsList = ref([...COMPONENT_TYPES])
const search = ref('')
const activeCategory = ref('all')

const schemaDialog = reactive({
  open: false,
  component: null,
})

onMounted(async () => {
  try {
    const list = await getComponentTypesRequest()
    if (Array.isArray(list) && list.length > 0) {
      componentsList.value = list
    }
  } catch {
    // Mantiene COMPONENT_TYPES locales si el servidor está iniciando
  }
})

const filteredComponents = computed(() => {
  const q = (search.value || '').toLowerCase()
  return componentsList.value.filter((comp) => {
    const matchCat = activeCategory.value === 'all' || comp.category === activeCategory.value
    const matchSearch =
      comp.name.toLowerCase().includes(q) ||
      comp.code.toLowerCase().includes(q) ||
      (comp.description || '').toLowerCase().includes(q)
    return matchCat && matchSearch
  })
})

function viewSchema(comp) {
  schemaDialog.component = comp
  schemaDialog.open = true
}

function openNewBlockNotice() {
  notify.info(
    'Para registrar un nuevo bloque: agrega el componente Vue en "src/components/" y defínelo en "src/constants/componentTypes.js".',
  )
}
</script>

<style scoped>
.rounded-borders {
  border-radius: 12px;
}
.component-box {
  transition: all 0.2s ease;
}
.component-box:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(0, 50, 120, 0.08);
}
.font-mono {
  font-family: monospace;
}
</style>
