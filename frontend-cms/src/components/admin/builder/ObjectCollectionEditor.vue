<template>
  <div class="object-collection-editor">
    <!-- Cabecera de la Colección -->
    <div class="row items-center justify-between q-mb-sm">
      <div class="row items-center q-gutter-x-xs">
        <q-icon name="layers" color="primary" size="18px" />
        <span class="text-subtitle2 text-weight-bold text-grey-9">
          {{ label }}
        </span>
        <q-badge color="blue-1" text-color="primary" class="text-weight-bold">
          {{ items.length }}
        </q-badge>
      </div>

      <q-btn
        outline
        dense
        color="primary"
        icon="add"
        :label="addButtonLabel"
        no-caps
        size="sm"
        class="q-px-sm"
        @click="addItem"
      />
    </div>

    <!-- Estado Vacío -->
    <q-banner
      v-if="items.length === 0"
      rounded
      class="bg-grey-1 text-grey-7 border-dashed text-center q-py-md"
    >
      <q-icon name="playlist_add" size="28px" color="grey-5" class="q-mb-xs" />
      <div class="text-caption">
        No hay elementos registrados en <strong>{{ label }}</strong
        >. Haz clic en <strong>"{{ addButtonLabel }}"</strong> para crear el primero.
      </div>
    </q-banner>

    <!-- Lista de Objetos (Acordeones) -->
    <q-list v-else bordered separator class="rounded-borders bg-white">
      <q-expansion-item
        v-for="(item, idx) in items"
        :key="item.id || idx"
        :default-opened="idx === 0"
        header-class="bg-grey-1 text-weight-medium q-py-xs"
        expand-icon-class="text-grey-7"
      >
        <template #header>
          <q-item-section avatar style="min-width: 40px">
            <!-- Miniatura de imagen si el objeto tiene imagen -->
            <q-avatar
              v-if="itemImageKey && item[itemImageKey]"
              rounded
              size="34px"
              class="bg-grey-3 border-light"
            >
              <img :src="item[itemImageKey]" style="object-fit: cover" />
            </q-avatar>
            <!-- Ícono si el objeto tiene clase de ícono -->
            <q-avatar
              v-else-if="itemIconKey && item[itemIconKey]"
              rounded
              size="32px"
              color="blue-1"
              text-color="primary"
            >
              <i :class="item[itemIconKey]" style="font-size: 15px"></i>
            </q-avatar>
            <!-- Número de orden por defecto -->
            <q-avatar
              v-else
              rounded
              size="28px"
              color="primary"
              text-color="white"
              class="text-caption text-weight-bold"
            >
              {{ idx + 1 }}
            </q-avatar>
          </q-item-section>

          <q-item-section>
            <q-item-label class="text-body2 text-weight-bold text-dark ellipsis">
              {{ getItemTitle(item, idx) }}
            </q-item-label>
            <q-item-label
              v-if="getItemSubtitle(item)"
              caption
              class="ellipsis text-grey-7"
              style="max-width: 280px"
            >
              {{ getItemSubtitle(item) }}
            </q-item-label>
          </q-item-section>

          <!-- Botones de Acción del Objeto (Subir, Bajar, Duplicar, Eliminar) -->
          <q-item-section side>
            <div class="row items-center no-wrap q-gutter-x-xs" @click.stop>
              <q-btn
                flat
                round
                dense
                size="xs"
                icon="arrow_upward"
                color="grey-7"
                :disable="idx === 0"
                @click="moveItem(idx, -1)"
              >
                <q-tooltip>Subir posición</q-tooltip>
              </q-btn>
              <q-btn
                flat
                round
                dense
                size="xs"
                icon="arrow_downward"
                color="grey-7"
                :disable="idx === items.length - 1"
                @click="moveItem(idx, 1)"
              >
                <q-tooltip>Bajar posición</q-tooltip>
              </q-btn>
              <q-btn
                flat
                round
                dense
                size="xs"
                icon="content_copy"
                color="primary"
                @click="duplicateItem(idx)"
              >
                <q-tooltip>Duplicar elemento</q-tooltip>
              </q-btn>
              <q-btn
                flat
                round
                dense
                size="xs"
                icon="delete_outline"
                color="negative"
                @click="removeItem(idx)"
              >
                <q-tooltip>Eliminar elemento</q-tooltip>
              </q-btn>
            </div>
          </q-item-section>
        </template>

        <!-- Cuerpo de Edición de Propiedades del Objeto -->
        <q-card>
          <q-card-section class="q-pa-md bg-white">
            <div class="row q-col-gutter-sm">
              <div
                v-for="(fieldDef, fieldKey) in itemSchema"
                :key="fieldKey"
                :class="`col-12 col-sm-${fieldDef.col || 12}`"
              >
                <!-- Tipo: Imagen con Selector de Medios -->
                <MediaPickerInput
                  v-if="fieldDef.type === 'image'"
                  :model-value="item[fieldKey] || ''"
                  :label="fieldDef.label || fieldKey"
                  @update:model-value="updateField(idx, fieldKey, $event)"
                />

                <!-- Tipo: Área de Texto -->
                <q-input
                  v-else-if="fieldDef.type === 'textarea'"
                  :model-value="item[fieldKey] || ''"
                  :label="fieldDef.label || fieldKey"
                  type="textarea"
                  rows="2"
                  outlined
                  dense
                  @update:model-value="updateField(idx, fieldKey, $event)"
                />

                <!-- Tipo: Número -->
                <q-input
                  v-else-if="fieldDef.type === 'number'"
                  :model-value="item[fieldKey] ?? 0"
                  :label="fieldDef.label || fieldKey"
                  type="number"
                  outlined
                  dense
                  @update:model-value="updateField(idx, fieldKey, Number($event))"
                />

                <!-- Tipo: Ícono con Selector Visual Rápido -->
                <q-input
                  v-else-if="fieldDef.type === 'icon'"
                  :model-value="item[fieldKey] || ''"
                  :label="fieldDef.label || fieldKey"
                  outlined
                  dense
                  @update:model-value="updateField(idx, fieldKey, $event)"
                >
                  <template #prepend>
                    <i
                      v-if="item[fieldKey]"
                      :class="item[fieldKey]"
                      class="text-primary"
                      style="font-size: 16px"
                    ></i>
                    <q-icon v-else name="emoji_symbols" size="18px" color="grey-6" />
                  </template>
                  <template #append>
                    <q-btn flat round dense icon="apps" size="xs" color="primary">
                      <q-tooltip>Elegir ícono predefinido</q-tooltip>
                      <q-menu max-width="300px">
                        <div class="q-pa-sm">
                          <div class="text-caption text-weight-bold text-grey-8 q-mb-xs">
                            Íconos Institucionales (Themify / FontAwesome)
                          </div>
                          <div class="row q-gutter-xs">
                            <q-btn
                              v-for="ic in COMMON_ICONS"
                              :key="ic"
                              v-close-popup
                              flat
                              dense
                              class="q-pa-xs bg-grey-2"
                              @click="updateField(idx, fieldKey, ic)"
                            >
                              <i :class="ic" style="font-size: 16px"></i>
                              <q-tooltip>{{ ic }}</q-tooltip>
                            </q-btn>
                          </div>
                        </div>
                      </q-menu>
                    </q-btn>
                  </template>
                </q-input>

                <!-- Tipo: Texto Simple por defecto -->
                <q-input
                  v-else
                  :model-value="item[fieldKey] ?? ''"
                  :label="fieldDef.label || fieldKey"
                  outlined
                  dense
                  @update:model-value="updateField(idx, fieldKey, $event)"
                />
              </div>
            </div>
          </q-card-section>
        </q-card>
      </q-expansion-item>
    </q-list>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import MediaPickerInput from './MediaPickerInput.vue'

const props = defineProps({
  modelValue: {
    type: Array,
    default: () => [],
  },
  label: {
    type: String,
    default: 'Elementos',
  },
  addButtonLabel: {
    type: String,
    default: 'Agregar Elemento',
  },
  itemSchema: {
    type: Object,
    required: true,
  },
  itemLabelKey: {
    type: String,
    default: 'title',
  },
  itemSubtitleKey: {
    type: String,
    default: '',
  },
  itemImageKey: {
    type: String,
    default: '',
  },
  itemIconKey: {
    type: String,
    default: '',
  },
  defaultNewItem: {
    type: Object,
    default: () => ({}),
  },
})

const emit = defineEmits(['update:modelValue'])

const COMMON_ICONS = [
  'ti-rocket',
  'ti-world',
  'ti-user',
  'ti-medall',
  'ti-book',
  'ti-cup',
  'ti-check',
  'ti-mobile',
  'ti-email',
  'ti-search',
  'ti-comments',
  'ti-pencil-alt',
  'ti-desktop',
  'ti-Briefcase',
  'ti-light-bulb',
  'ti-shield',
  'ti-bar-chart',
  'fas fa-graduation-cap',
  'fas fa-laptop-code',
  'fas fa-network-wired',
  'fas fa-award',
  'fas fa-users',
  'fas fa-certificate',
]

const items = computed(() => (Array.isArray(props.modelValue) ? props.modelValue : []))

function cloneItems() {
  return JSON.parse(JSON.stringify(items.value))
}

function getItemTitle(item, idx) {
  const val = item?.[props.itemLabelKey] || item?.title || item?.name || item?.label
  return val ? String(val) : `Elemento #${idx + 1}`
}

function getItemSubtitle(item) {
  if (!props.itemSubtitleKey) return ''
  const val = item?.[props.itemSubtitleKey]
  if (val === undefined || val === null || val === '') return ''
  return String(val)
}

function updateField(idx, fieldKey, value) {
  const updated = cloneItems()
  if (!updated[idx]) return
  updated[idx][fieldKey] = value
  emit('update:modelValue', updated)
}

function addItem() {
  const updated = cloneItems()
  const blank = {}

  Object.entries(props.itemSchema).forEach(([key, def]) => {
    if (props.defaultNewItem[key] !== undefined) {
      blank[key] = props.defaultNewItem[key]
    } else if (def.type === 'number') {
      blank[key] = 0
    } else if (def.type === 'icon') {
      blank[key] = 'ti-check'
    } else if (def.type === 'image') {
      blank[key] = '/img/content/courses-01.jpg'
    } else {
      blank[key] = ''
    }
  })

  if (props.itemLabelKey && !blank[props.itemLabelKey]) {
    blank[props.itemLabelKey] = `Nuevo Elemento ${updated.length + 1}`
  }

  updated.push(blank)
  emit('update:modelValue', updated)
}

function duplicateItem(idx) {
  const updated = cloneItems()
  const copy = JSON.parse(JSON.stringify(updated[idx]))
  if (copy.id) copy.id = Date.now()
  if (props.itemLabelKey && copy[props.itemLabelKey]) {
    copy[props.itemLabelKey] = `${copy[props.itemLabelKey]} (Copia)`
  }
  updated.splice(idx + 1, 0, copy)
  emit('update:modelValue', updated)
}

function moveItem(idx, delta) {
  const target = idx + delta
  if (target < 0 || target >= items.value.length) return
  const updated = cloneItems()
  const [moved] = updated.splice(idx, 1)
  updated.splice(target, 0, moved)
  emit('update:modelValue', updated)
}

function removeItem(idx) {
  const updated = cloneItems()
  updated.splice(idx, 1)
  emit('update:modelValue', updated)
}
</script>

<style scoped>
.border-dashed {
  border: 1px dashed #cbd5e1;
}
.border-light {
  border: 1px solid #e2e8f0;
}
</style>

