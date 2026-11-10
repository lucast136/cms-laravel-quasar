<template>
  <div class="block-configurator column full-height bg-white">
    <!-- Estado vacío si no hay sección seleccionada -->
    <div v-if="!section" class="col column flex-center text-grey-5 q-pa-xl text-center">
      <q-icon name="touch_app" size="56px" class="q-mb-md text-grey-4" />
      <div class="text-subtitle1 text-weight-bold text-grey-7">Ningún bloque seleccionado</div>
      <div class="text-caption q-mt-xs" style="max-width: 260px">
        Selecciona una sección de la columna izquierda para editar su contenido, objetos, tablas o
        estilos.
      </div>
    </div>

    <template v-else>
      <!-- Cabecera del Inspector -->
      <div class="q-px-md q-py-sm bg-grey-1 border-bottom row items-center justify-between">
        <div class="row items-center q-gutter-x-sm">
          <q-avatar
            size="32px"
            color="blue-1"
            text-color="primary"
            :icon="resolvedComponentType?.icon || 'widgets'"
          />
          <div>
            <div class="text-subtitle2 text-weight-bold text-dark">
              {{ resolvedComponentType?.name || section.component_name }}
            </div>
            <div class="text-caption text-grey-6 font-mono">
              Código: {{ section.component_code }} | Orden #{{ section.sort_order }}
            </div>
          </div>
        </div>

        <q-badge
          :color="section.is_active ? 'positive' : 'grey-6'"
          :label="section.is_active ? 'Visible' : 'Oculto'"
        />
      </div>

      <!-- Pestañas: Contenido & Objetos vs Diseño vs Vista Previa vs JSON -->
      <q-tabs
        v-model="activeTab"
        dense
        class="text-grey-7 bg-grey-1 border-bottom"
        active-color="primary"
        indicator-color="primary"
        align="left"
        narrow-indicator
      >
        <q-tab name="content" icon="edit_note" label="Contenido y Objetos" no-caps />
        <q-tab name="settings" icon="palette" label="Diseño y Espaciado" no-caps />
        <q-tab name="preview" icon="visibility" label="Vista Previa del Bloque" no-caps />
        <q-tab name="json" icon="data_object" label="JSON (ERD)" no-caps />
      </q-tabs>

      <!-- Cuerpo desplazable del Inspector -->
      <div class="col overflow-auto">
        <q-tab-panels v-model="activeTab" animated class="bg-transparent">
          <!-- ===================================================== -->
          <!-- TAB 1: CONTENIDO Y MANTENIMIENTO DE OBJETOS (data)    -->
          <!-- ===================================================== -->
          <q-tab-panel name="content" class="q-pa-md q-gutter-y-md">
            <!-- CASO A: TABLA DINÁMICA (data_table) -->
            <template v-if="section.component_code === 'data_table'">
              <q-card flat bordered class="q-pa-md bg-grey-1 rounded-borders">
                <div class="text-caption text-weight-bold text-primary text-uppercase q-mb-sm">
                  Encabezado y Configuración de Tabla
                </div>
                <div class="row q-col-gutter-sm">
                  <div class="col-12 col-sm-6">
                    <q-input
                      v-model="localData.title"
                      label="Título de la Tabla"
                      outlined
                      dense
                      bg-color="white"
                      @update:model-value="emitDataChange"
                    />
                  </div>
                  <div class="col-12 col-sm-6">
                    <q-select
                      v-model="localData.style"
                      :options="['striped', 'bordered', 'clean']"
                      label="Estilo de Tabla"
                      outlined
                      dense
                      bg-color="white"
                      @update:model-value="emitDataChange"
                    />
                  </div>
                  <div class="col-12">
                    <q-input
                      v-model="localData.subtitle"
                      label="Subtítulo o Descripción"
                      outlined
                      dense
                      bg-color="white"
                      @update:model-value="emitDataChange"
                    />
                  </div>
                </div>
              </q-card>

              <DynamicTableEditor
                :columns="localData.columns || []"
                :rows="localData.rows || []"
                @update:columns="handleUpdateTableColumns"
                @update:rows="handleUpdateTableRows"
              />

              <q-input
                v-model="localData.footer_note"
                label="Nota aclaratoria al pie de la tabla"
                type="textarea"
                rows="2"
                outlined
                dense
                @update:model-value="emitDataChange"
              />
            </template>

            <!-- CASO B: TEXTO ENRIQUECIDO WYSIWYG (rich_text) -->
            <template v-else-if="section.component_code === 'rich_text'">
              <q-input
                v-model="localData.title"
                label="Título de la Sección (Opcional)"
                outlined
                dense
                class="q-mb-sm"
                @update:model-value="emitDataChange"
              />

              <div class="row items-center justify-between q-mb-xs">
                <span class="text-caption text-weight-bold text-grey-8">
                  Contenido Enriquecido (Misión, Visión, Comunicados)
                </span>
                <q-btn-toggle
                  v-model="richTextMode"
                  dense
                  unelevated
                  size="sm"
                  toggle-color="primary"
                  color="grey-2"
                  text-color="grey-8"
                  no-caps
                  :options="[
                    { label: 'Editor Visual', value: 'wysiwyg' },
                    { label: 'Código HTML', value: 'html' },
                  ]"
                />
              </div>

              <q-editor
                v-if="richTextMode === 'wysiwyg'"
                :model-value="localData.content_html || ''"
                min-height="220px"
                :toolbar="[
                  ['left', 'center', 'right', 'justify'],
                  ['bold', 'italic', 'underline', 'strike'],
                  ['quote', 'unordered', 'ordered'],
                  ['undo', 'redo'],
                  ['viewsource'],
                ]"
                @update:model-value="handleRichTextChange"
              />

              <q-input
                v-else
                v-model="localData.content_html"
                label="Código HTML"
                type="textarea"
                rows="10"
                outlined
                dense
                class="font-mono"
                @update:model-value="emitDataChange"
              />
            </template>

            <!-- CASO C: MOTOR BASADO EN ESQUEMA PARA LOS 13 BLOQUES DE PORTADA Y COLECCIONES DE OBJETOS -->
            <template v-else>
              <!-- 1. Propiedades Escalares de Cabecera / Configuración del Bloque -->
              <q-card
                v-if="scalarProperties.length > 0"
                flat
                bordered
                class="q-pa-md bg-grey-1 rounded-borders"
              >
                <div class="row items-center q-gutter-x-xs q-mb-sm">
                  <q-icon name="tune" color="primary" size="18px" />
                  <span class="text-caption text-weight-bold text-primary text-uppercase">
                    Datos Generales de la Sección
                  </span>
                </div>

                <div class="row q-col-gutter-sm">
                  <div
                    v-for="prop in scalarProperties"
                    :key="prop.key"
                    :class="getScalarColClass(prop)"
                  >
                    <!-- Boolean Toggle -->
                    <div
                      v-if="prop.def.type === 'boolean'"
                      class="bg-white q-px-sm q-py-xs rounded-borders border-light full-height flex items-center"
                    >
                      <q-toggle
                        v-model="localData[prop.key]"
                        :label="prop.def.label || prop.key"
                        color="primary"
                        dense
                        @update:model-value="emitDataChange"
                      />
                    </div>

                    <!-- Number Input -->
                    <q-input
                      v-else-if="prop.def.type === 'number'"
                      v-model.number="localData[prop.key]"
                      :label="prop.def.label || prop.key"
                      type="number"
                      outlined
                      dense
                      bg-color="white"
                      @update:model-value="emitDataChange"
                    />

                    <!-- Image Picker -->
                    <MediaPickerInput
                      v-else-if="prop.def.type === 'image'"
                      :model-value="localData[prop.key] || ''"
                      :label="prop.def.label || prop.key"
                      @update:model-value="handleScalarUpdate(prop.key, $event)"
                    />

                    <!-- Select -->
                    <q-select
                      v-else-if="prop.def.type === 'select'"
                      v-model="localData[prop.key]"
                      :options="prop.def.options || []"
                      :label="prop.def.label || prop.key"
                      outlined
                      dense
                      bg-color="white"
                      @update:model-value="emitDataChange"
                    />

                    <!-- Textarea -->
                    <q-input
                      v-else-if="prop.def.type === 'textarea'"
                      v-model="localData[prop.key]"
                      :label="prop.def.label || prop.key"
                      type="textarea"
                      rows="2"
                      outlined
                      dense
                      bg-color="white"
                      @update:model-value="emitDataChange"
                    />

                    <!-- Text por defecto -->
                    <q-input
                      v-else
                      v-model="localData[prop.key]"
                      :label="prop.def.label || prop.key"
                      outlined
                      dense
                      bg-color="white"
                      @update:model-value="emitDataChange"
                    />
                  </div>
                </div>
              </q-card>

              <!-- 2. Colecciones de Objetos (Arrays con CRUD completo: Agregar, Editar, Mover, Duplicar, Eliminar) -->
              <div v-for="arrProp in arrayProperties" :key="arrProp.key" class="q-mt-md">
                <ObjectCollectionEditor
                  :model-value="localData[arrProp.key] || []"
                  :label="arrProp.def.label || arrProp.key"
                  :add-button-label="`Agregar a ${arrProp.def.label || arrProp.key}`"
                  :item-schema="arrProp.def.itemSchema || {}"
                  :item-label-key="arrProp.def.itemLabelKey || 'title'"
                  :item-subtitle-key="arrProp.def.itemSubtitleKey || ''"
                  :item-image-key="arrProp.def.itemImageKey || ''"
                  :item-icon-key="arrProp.def.itemIconKey || ''"
                  @update:model-value="handleArrayUpdate(arrProp.key, $event)"
                />
              </div>
            </template>
          </q-tab-panel>

          <!-- ===================================================== -->
          <!-- TAB 2: DISEÑO Y ESPACIADO (settings JSON)             -->
          <!-- ===================================================== -->
          <q-tab-panel name="settings" class="q-pa-md q-gutter-y-md">
            <div class="text-subtitle2 text-weight-bold text-grey-8">
              Espaciado Vertical (Paddings)
            </div>
            <div class="row q-col-gutter-md">
              <div class="col-6">
                <q-input
                  v-model.number="localSettings.padding_top"
                  type="number"
                  label="Padding Superior (px)"
                  outlined
                  dense
                  suffix="px"
                  @update:model-value="emitSettingsChange"
                />
              </div>
              <div class="col-6">
                <q-input
                  v-model.number="localSettings.padding_bottom"
                  type="number"
                  label="Padding Inferior (px)"
                  outlined
                  dense
                  suffix="px"
                  @update:model-value="emitSettingsChange"
                />
              </div>
            </div>

            <q-separator />

            <div class="text-subtitle2 text-weight-bold text-grey-8">Apariencia del Contenedor</div>
            <div class="row q-col-gutter-md items-center">
              <div class="col-12 col-sm-6">
                <q-input
                  v-model="localSettings.background_color"
                  label="Color de Fondo (HEX)"
                  outlined
                  dense
                  @update:model-value="emitSettingsChange"
                >
                  <template #append>
                    <q-icon name="colorize" class="cursor-pointer">
                      <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                        <q-color
                          v-model="localSettings.background_color"
                          @update:model-value="emitSettingsChange"
                        />
                      </q-popup-proxy>
                    </q-icon>
                  </template>
                </q-input>
              </div>
              <div class="col-12 col-sm-6">
                <q-toggle
                  v-model="localSettings.full_width"
                  label="Ancho Completo (Full Bleed)"
                  color="primary"
                  @update:model-value="emitSettingsChange"
                />
              </div>
            </div>

            <q-separator />

            <div class="text-subtitle2 text-weight-bold text-grey-8">Atributos Avanzados</div>
            <div class="row q-col-gutter-sm">
              <div class="col-12 col-sm-6">
                <q-input
                  v-model="localSettings.custom_id"
                  label="ID HTML Ancla (ej. #admision)"
                  outlined
                  dense
                  @update:model-value="emitSettingsChange"
                />
              </div>
              <div class="col-12 col-sm-6">
                <q-input
                  v-model="localSettings.custom_class"
                  label="Clases CSS Adicionales"
                  outlined
                  dense
                  @update:model-value="emitSettingsChange"
                />
              </div>
            </div>
          </q-tab-panel>

          <!-- ===================================================== -->
          <!-- TAB 3: VISTA PREVIA EN VIVO DEL BLOQUE                -->
          <!-- ===================================================== -->
          <q-tab-panel name="preview" class="q-pa-none">
            <div class="q-pa-sm bg-blue-1 text-primary text-caption row items-center justify-between">
              <div>
                <q-icon name="visibility" class="q-mr-xs" />
                <strong>Vista Previa Reactiva:</strong> Así se verá este bloque en la página pública
                con los objetos actuales.
              </div>
            </div>
            <div class="preview-stage q-pa-sm bg-grey-3 overflow-auto">
              <div class="bg-white shadow-2 rounded-borders overflow-hidden">
                <component
                  :is="previewComponent"
                  v-if="previewComponent"
                  :section-data="localData"
                  :section-settings="localSettings"
                />
              </div>
            </div>
          </q-tab-panel>

          <!-- ===================================================== -->
          <!-- TAB 4: INSPECTOR JSON (CONTRATO ERD LARAVEL 13)       -->
          <!-- ===================================================== -->
          <q-tab-panel name="json" class="q-pa-md">
            <q-banner rounded class="bg-blue-1 text-primary q-mb-md text-caption">
              Estructura exacta del registro <code>content_sections</code> que se sincroniza con
              Laravel 13 mediante <code>PUT /api/v1/admin/pages/{id}/sections/sync</code>.
            </q-banner>
            <pre class="json-preview q-pa-md rounded-borders bg-dark text-green-4">{{
              formattedJsonPayload
            }}</pre>
          </q-tab-panel>
        </q-tab-panels>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import { COMPONENT_TYPES } from '@/constants/componentTypes'
import DynamicTableEditor from './DynamicTableEditor.vue'
import ObjectCollectionEditor from './ObjectCollectionEditor.vue'
import MediaPickerInput from './MediaPickerInput.vue'

// Componentes públicos para la Vista Previa en Vivo
import HeroSlider from '@/components/public/home/HeroSlider.vue'
import FeatureCards from '@/components/public/home/FeatureCards.vue'
import AboutSection from '@/components/public/home/AboutSection.vue'
import CoursesSection from '@/components/public/home/CoursesSection.vue'
import CategoriesGrid from '@/components/public/home/CategoriesGrid.vue'
import InstructorsGrid from '@/components/public/home/InstructorsGrid.vue'
import WhyChooseUs from '@/components/public/home/WhyChooseUs.vue'
import StatsCounter from '@/components/public/home/StatsCounter.vue'
import TestimonialsSlider from '@/components/public/home/TestimonialsSlider.vue'
import LearningProcess from '@/components/public/home/LearningProcess.vue'
import UpcomingEvents from '@/components/public/home/UpcomingEvents.vue'
import LatestBlog from '@/components/public/home/LatestBlog.vue'
import PartnersSlider from '@/components/public/home/PartnersSlider.vue'
import DataTableBlock from '@/components/public/home/DataTableBlock.vue'
import RichTextBlock from '@/components/public/home/RichTextBlock.vue'
import ContactSection from '@/components/public/home/ContactSection.vue'

const props = defineProps({
  section: {
    type: Object,
    default: null,
  },
  componentType: {
    type: Object,
    default: null,
  },
})

const emit = defineEmits(['update-section-data', 'update-section-settings'])

const activeTab = ref('content')
const richTextMode = ref('wysiwyg')
const localData = ref({})
const localSettings = ref({})

const PREVIEW_COMPONENT_MAP = {
  hero_slider: HeroSlider,
  feature_cards: FeatureCards,
  about_section: AboutSection,
  courses_section: CoursesSection,
  categories_grid: CategoriesGrid,
  instructors_grid: InstructorsGrid,
  why_choose_us: WhyChooseUs,
  stats_counter: StatsCounter,
  testimonials_slider: TestimonialsSlider,
  learning_process: LearningProcess,
  upcoming_events: UpcomingEvents,
  latest_blog: LatestBlog,
  partners_slider: PartnersSlider,
  data_table: DataTableBlock,
  rich_text: RichTextBlock,
  contact_section: ContactSection,
}

// Combinar la definición del backend con el esquema enriquecido local de COMPONENT_TYPES
const resolvedComponentType = computed(() => {
  const code = props.section?.component_code
  const localDef = COMPONENT_TYPES.find((c) => c.code === code)
  if (!localDef) return props.componentType
  if (!props.componentType) return localDef

  return {
    ...props.componentType,
    schema: localDef.schema || props.componentType.schema,
    default_data: localDef.default_data || props.componentType.default_data,
    default_settings: localDef.default_settings || props.componentType.default_settings,
  }
})

const schemaProperties = computed(() => {
  return resolvedComponentType.value?.schema?.properties || {}
})

const scalarProperties = computed(() => {
  return Object.entries(schemaProperties.value)
    .filter(([, def]) => def.type !== 'array' && def.type !== 'table_columns' && def.type !== 'table_rows')
    .map(([key, def]) => ({ key, def }))
})

const arrayProperties = computed(() => {
  return Object.entries(schemaProperties.value)
    .filter(([, def]) => def.type === 'array')
    .map(([key, def]) => ({ key, def }))
})

const previewComponent = computed(() => {
  const code = props.section?.component_code
  return PREVIEW_COMPONENT_MAP[code] || null
})

// Sincronizar copias locales cuando cambia la sección seleccionada
watch(
  () => props.section,
  (newSec) => {
    if (newSec) {
      const defaults = JSON.parse(JSON.stringify(resolvedComponentType.value?.default_data || {}))
      const currentData = JSON.parse(JSON.stringify(newSec.data || {}))
      const merged = { ...defaults, ...currentData }

      Object.keys(defaults).forEach((k) => {
        if (Array.isArray(defaults[k]) && !Array.isArray(merged[k])) {
          merged[k] = defaults[k]
        }
      })

      localData.value = merged
      localSettings.value = JSON.parse(
        JSON.stringify({
          ...(resolvedComponentType.value?.default_settings || {}),
          ...(newSec.settings || {}),
        }),
      )
    } else {
      localData.value = {}
      localSettings.value = {}
    }
  },
  { immediate: true, deep: true },
)

function getScalarColClass(prop) {
  if (prop.def.type === 'textarea' || prop.def.type === 'image') {
    return 'col-12'
  }
  return 'col-12 col-sm-6'
}

function emitDataChange() {
  if (!props.section) return
  emit('update-section-data', props.section.id, JSON.parse(JSON.stringify(localData.value)))
}

function emitSettingsChange() {
  if (!props.section) return
  emit('update-section-settings', props.section.id, JSON.parse(JSON.stringify(localSettings.value)))
}

function handleScalarUpdate(key, value) {
  localData.value[key] = value
  emitDataChange()
}

function handleArrayUpdate(key, newArray) {
  localData.value[key] = newArray
  emitDataChange()
}

function handleRichTextChange(val) {
  localData.value.content_html = val
  emitDataChange()
}

function handleUpdateTableColumns(newCols) {
  localData.value.columns = newCols
  emitDataChange()
}

function handleUpdateTableRows(newRows) {
  localData.value.rows = newRows
  emitDataChange()
}

const formattedJsonPayload = computed(() => {
  if (!props.section) return ''
  return JSON.stringify(
    {
      id: props.section.id,
      component_type_id: props.section.component_type_id,
      component_name: props.section.component_name,
      sort_order: props.section.sort_order,
      is_active: props.section.is_active,
      settings: localSettings.value,
      data: localData.value,
    },
    null,
    2,
  )
})
</script>

<style scoped>
.border-bottom {
  border-bottom: 1px solid #e2e8f0;
}
.border-light {
  border: 1px solid #cbd5e1;
}
.font-mono {
  font-family: monospace;
  font-size: 11px;
}
.json-preview {
  font-family: 'Fira Code', monospace;
  font-size: 12px;
  overflow-x: auto;
  margin: 0;
}
.preview-stage {
  max-height: calc(100vh - 220px);
}
</style>
