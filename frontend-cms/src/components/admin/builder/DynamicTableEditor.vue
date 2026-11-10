<template>
  <div class="dynamic-table-editor">
    <!-- Encabezado y Opciones Generales de la Tabla -->
    <div class="q-mb-md">
      <div class="row q-col-gutter-sm">
        <div class="col-12 col-md-6">
          <q-input
            v-model="localData.title"
            label="Título de la Tabla"
            outlined
            dense
            @update:model-value="emitChange"
          />
        </div>
        <div class="col-12 col-md-6">
          <q-select
            v-model="localData.style"
            label="Estilo de Tabla"
            outlined
            dense
            :options="[
              { label: 'Filas Alternadas (Striped)', value: 'striped' },
              { label: 'Bordeada (Bordered)', value: 'bordered' },
              { label: 'Limpia / Minimalista', value: 'clean' },
            ]"
            emit-value
            map-options
            @update:model-value="emitChange"
          />
        </div>
        <div class="col-12">
          <q-input
            v-model="localData.subtitle"
            label="Subtítulo / Instrucciones"
            outlined
            dense
            @update:model-value="emitChange"
          />
        </div>
      </div>
    </div>

    <!-- Pestañas: Columnas vs Filas -->
    <q-card flat bordered class="q-mb-md rounded-borders">
      <q-tabs
        v-model="tab"
        dense
        active-color="primary"
        indicator-color="primary"
        align="left"
        class="bg-grey-1"
      >
        <q-tab name="rows" icon="table_rows" label="Filas de Datos" no-caps />
        <q-tab name="columns" icon="view_column" label="Definición de Columnas" no-caps />
      </q-tabs>
      <q-separator />

      <!-- Pestaña 1: Filas de Datos (Contenido) -->
      <q-tab-panel name="rows" class="q-pa-sm">
        <div class="row items-center justify-between q-mb-sm">
          <span class="text-caption text-weight-bold text-grey-8">
            {{ localData.rows?.length || 0 }} registros en la tabla
          </span>
          <q-btn
            size="sm"
            color="primary"
            icon="add"
            label="Agregar Fila"
            unelevated
            no-caps
            @click="addRow"
          />
        </div>

        <div
          v-for="(row, rIdx) in localData.rows"
          :key="rIdx"
          class="row-card q-pa-sm q-mb-sm bg-grey-1 rounded-borders"
        >
          <div class="row items-center justify-between q-mb-xs">
            <q-badge color="primary" :label="`Fila #${rIdx + 1}`" />
            <div class="row q-gutter-x-xs">
              <q-btn
                flat
                round
                dense
                size="xs"
                icon="keyboard_arrow_up"
                :disable="rIdx === 0"
                @click="moveRow(rIdx, -1)"
              />
              <q-btn
                flat
                round
                dense
                size="xs"
                icon="keyboard_arrow_down"
                :disable="rIdx === localData.rows.length - 1"
                @click="moveRow(rIdx, 1)"
              />
              <q-btn
                flat
                round
                dense
                size="xs"
                icon="delete"
                color="negative"
                @click="removeRow(rIdx)"
              />
            </div>
          </div>

          <!-- Inputs por cada columna definida -->
          <div class="row q-col-gutter-xs">
            <div v-for="col in localData.columns" :key="col.key" class="col-12 col-sm-6">
              <!-- Si la columna es de tipo estado/badge -->
              <div v-if="col.key === 'estado'" class="row q-col-gutter-xs items-center">
                <div class="col-7">
                  <q-input
                    v-model="row.estado.badge"
                    label="Texto Badge"
                    dense
                    outlined
                    bg-color="white"
                    @update:model-value="emitChange"
                  />
                </div>
                <div class="col-5">
                  <q-select
                    v-model="row.estado.color"
                    label="Color"
                    dense
                    outlined
                    bg-color="white"
                    :options="['positive', 'warning', 'negative', 'primary', 'grey']"
                    @update:model-value="emitChange"
                  />
                </div>
              </div>

              <!-- Input de texto estándar -->
              <q-input
                v-else
                v-model="row[col.key]"
                :label="col.label"
                dense
                outlined
                bg-color="white"
                @update:model-value="emitChange"
              />
            </div>
          </div>
        </div>
      </q-tab-panel>

      <!-- Pestaña 2: Definición de Columnas -->
      <q-tab-panel name="columns" class="q-pa-sm">
        <div class="row items-center justify-between q-mb-sm">
          <span class="text-caption text-weight-bold text-grey-8">
            Estructura de Encabezados ({{ localData.columns?.length || 0 }})
          </span>
          <q-btn
            size="sm"
            color="primary"
            icon="add"
            label="Agregar Columna"
            unelevated
            no-caps
            @click="addColumn"
          />
        </div>

        <q-list separator>
          <q-item v-for="(col, cIdx) in localData.columns" :key="cIdx" dense class="q-py-sm">
            <q-item-section>
              <div class="row q-col-gutter-xs items-center">
                <div class="col-4">
                  <q-input
                    v-model="col.label"
                    label="Nombre / Encabezado"
                    dense
                    outlined
                    @update:model-value="emitChange"
                  />
                </div>
                <div class="col-3">
                  <q-input
                    v-model="col.key"
                    label="Clave (key)"
                    dense
                    outlined
                    @update:model-value="emitChange"
                  />
                </div>
                <div class="col-3">
                  <q-select
                    v-model="col.align"
                    label="Alineación"
                    dense
                    outlined
                    :options="['left', 'center', 'right']"
                    @update:model-value="emitChange"
                  />
                </div>
                <div class="col-2 text-right">
                  <q-btn
                    flat
                    round
                    dense
                    size="sm"
                    icon="delete"
                    color="negative"
                    @click="removeColumn(cIdx)"
                  />
                </div>
              </div>
            </q-item-section>
          </q-item>
        </q-list>
      </q-tab-panel>
    </q-card>

    <!-- Nota al pie de tabla -->
    <q-input
      v-model="localData.footer_note"
      label="Nota Aclaratoria al Pie (Opcional)"
      type="textarea"
      rows="2"
      outlined
      dense
      @update:model-value="emitChange"
    />
  </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue'

const props = defineProps({
  modelValue: { type: Object, required: true },
})

const emit = defineEmits(['update:modelValue'])

const tab = ref('rows')

// Copia reactiva local profunda
const localData = reactive(
  JSON.parse(
    JSON.stringify(
      props.modelValue || {
        title: 'Tabla Dinámica',
        subtitle: '',
        style: 'striped',
        columns: [
          { key: 'col1', label: 'Columna 1', align: 'left', width: '50%' },
          { key: 'col2', label: 'Columna 2', align: 'center', width: '50%' },
        ],
        rows: [],
        footer_note: '',
      },
    ),
  ),
)

watch(
  () => props.modelValue,
  (newVal) => {
    if (newVal) {
      Object.assign(localData, JSON.parse(JSON.stringify(newVal)))
    }
  },
  { deep: true },
)

function emitChange() {
  emit('update:modelValue', JSON.parse(JSON.stringify(localData)))
}

function addColumn() {
  const count = (localData.columns?.length || 0) + 1
  const newKey = `col_${Date.now().toString().slice(-4)}`
  if (!localData.columns) localData.columns = []
  localData.columns.push({
    key: newKey,
    label: `Nueva Columna ${count}`,
    align: 'left',
    width: 'auto',
  })
  emitChange()
}

function removeColumn(index) {
  localData.columns.splice(index, 1)
  emitChange()
}

function addRow() {
  if (!localData.rows) localData.rows = []
  const newRow = {}
  localData.columns?.forEach((c) => {
    if (c.key === 'estado') {
      newRow[c.key] = { badge: 'Pendiente', color: 'grey' }
    } else {
      newRow[c.key] = ''
    }
  })
  localData.rows.push(newRow)
  emitChange()
}

function removeRow(index) {
  localData.rows.splice(index, 1)
  emitChange()
}

function moveRow(index, offset) {
  const target = index + offset
  if (target < 0 || target >= localData.rows.length) return
  const [moved] = localData.rows.splice(index, 1)
  localData.rows.splice(target, 0, moved)
  emitChange()
}
</script>

<style scoped>
.rounded-borders {
  border-radius: 8px;
}
.row-card {
  border: 1px solid #e2e8f0;
}
</style>
