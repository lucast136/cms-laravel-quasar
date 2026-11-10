<template>
  <AdminLayout>
    <q-page class="builder-page column full-height no-scroll">
      <!-- Barra Superior de Herramientas del Page Builder -->
      <div
        class="builder-toolbar q-px-md q-py-sm row items-center justify-between bg-white border-bottom"
      >
        <!-- Izquierda: Retorno y Nombre de la Página -->
        <div class="row items-center q-gutter-x-sm">
          <q-btn flat round dense icon="arrow_back" to="/admin/pages">
            <q-tooltip>Volver a la lista de páginas</q-tooltip>
          </q-btn>

          <div>
            <div class="row items-center q-gutter-x-xs">
              <span class="text-subtitle1 text-weight-bold text-dark">
                {{ builderStore.currentPage?.title || 'Constructor de Página' }}
              </span>
              <q-badge
                v-if="builderStore.currentPage?.slug"
                color="blue-1"
                text-color="primary"
                class="font-mono"
              >
                /{{ builderStore.currentPage.slug }}
              </q-badge>
              <q-badge
                v-if="builderStore.isDirty"
                color="warning"
                text-color="dark"
                label="Cambios sin guardar"
                class="text-weight-bold"
              />
            </div>
            <div class="text-caption text-grey-6">
              Plantilla: {{ builderStore.currentPage?.template }} |
              {{ builderStore.sections.length }} bloques
            </div>
          </div>
        </div>

        <!-- Centro: Deshacer / Rehacer, Modo de Trabajo y Dispositivo -->
        <div class="row items-center q-gutter-x-xs gt-xs">
          <q-btn
            flat
            round
            dense
            icon="undo"
            :disable="!builderStore.canUndo"
            @click="builderStore.undo"
          >
            <q-tooltip>Deshacer cambio</q-tooltip>
          </q-btn>
          <q-btn
            flat
            round
            dense
            icon="redo"
            :disable="!builderStore.canRedo"
            @click="builderStore.redo"
          >
            <q-tooltip>Rehacer cambio</q-tooltip>
          </q-btn>

          <q-separator vertical inset class="q-mx-xs" />

          <q-btn-toggle
            v-model="viewMode"
            dense
            unelevated
            no-caps
            toggle-color="primary"
            color="grey-2"
            text-color="grey-8"
            :options="[
              { icon: 'build', label: 'Editor de Bloques', value: 'editor' },
              { icon: 'preview', label: 'Vista Previa Página', value: 'preview' },
            ]"
          />

          <q-separator vertical inset class="q-mx-xs" />

          <q-btn-toggle
            v-model="builderStore.previewDevice"
            dense
            unelevated
            toggle-color="primary"
            color="grey-2"
            text-color="grey-8"
            :options="[
              { icon: 'desktop_windows', value: 'desktop' },
              { icon: 'tablet_mac', value: 'tablet' },
              { icon: 'smartphone', value: 'mobile' },
            ]"
          >
            <q-tooltip>Simular resolución de pantalla</q-tooltip>
          </q-btn-toggle>
        </div>

        <!-- Derecha: Guardar y Vista Previa Web -->
        <div class="row items-center q-gutter-x-sm">
          <q-btn
            flat
            dense
            icon="open_in_new"
            label="Ver en Vivo"
            no-caps
            color="grey-8"
            :href="publicUrl"
            target="_blank"
          />

          <q-btn
            unelevated
            color="primary"
            icon="save"
            label="Guardar Página"
            no-caps
            :loading="builderStore.isSaving"
            @click="handleSave"
          />
        </div>
      </div>

      <!-- MODO 1: Área de Trabajo Dividida (Splitter Nativo Quasar) -->
      <div v-if="viewMode === 'editor'" class="col overflow-hidden relative-position">
        <q-splitter v-model="splitterModel" :limits="[25, 55]" class="full-height">
          <!-- Panel Izquierdo: Lista de Secciones -->
          <template #before>
            <div class="full-height overflow-hidden">
              <SectionList
                :sections="builderStore.sections"
                :selected-id="builderStore.selectedSectionId"
                @select-section="handleSelectSection"
                @open-palette="paletteOpen = true"
                @move-section="handleMoveSection"
                @toggle-active="handleToggleActive"
                @duplicate-section="handleDuplicateSection"
                @remove-section="handleRemoveSection"
              />
            </div>
          </template>

          <!-- Panel Derecho: Configurador de Propiedades y Objetos del Bloque -->
          <template #after>
            <div class="full-height bg-grey-2 q-pa-sm overflow-hidden">
              <q-card flat bordered class="full-height rounded-borders overflow-hidden column">
                <BlockConfigurator
                  :section="builderStore.selectedSection"
                  :component-type="builderStore.selectedComponentType"
                  @update-section-data="builderStore.updateSectionData"
                  @update-section-settings="builderStore.updateSectionSettings"
                />
              </q-card>
            </div>
          </template>
        </q-splitter>
      </div>

      <!-- MODO 2: Lienzo de Vista Previa Completa de la Página (Desktop / Tablet / Mobile) -->
      <div v-else class="col full-height bg-grey-3 overflow-auto q-pa-md flex justify-center">
        <div
          class="bg-white shadow-4 rounded-borders overflow-hidden transition-generic"
          :style="previewCanvasStyle"
        >
          <template v-for="sec in activePreviewSections" :key="sec.id">
            <div
              class="preview-section-wrapper relative-position"
              :class="{
                'preview-section--selected': sec.id === builderStore.selectedSectionId,
              }"
              @click="selectSectionFromPreview(sec.id)"
            >
              <component
                :is="resolvePreviewComponent(sec)"
                v-if="resolvePreviewComponent(sec)"
                :section-data="sec.data"
                :section-settings="sec.settings"
              />
            </div>
          </template>
        </div>
      </div>

      <!-- Diálogo Modal: Paleta de Componentes (15 bloques disponibles) -->
      <ComponentPaletteDialog v-model="paletteOpen" @add-component="handleAddBlock" />
    </q-page>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import AdminLayout from '@/layouts/AdminLayout.vue'
import SectionList from '@/components/admin/builder/SectionList.vue'
import BlockConfigurator from '@/components/admin/builder/BlockConfigurator.vue'
import ComponentPaletteDialog from '@/components/admin/builder/ComponentPaletteDialog.vue'
import { useAdminBuilderStore } from '@/stores/admin-builder'
import { useNotification } from '@/composables/useNotification'

// Componentes públicos para el lienzo de vista previa
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

const route = useRoute()
const builderStore = useAdminBuilderStore()
const notify = useNotification()

const splitterModel = ref(34) // 34% izquierda, 66% derecha para mayor comodidad al editar objetos
const paletteOpen = ref(false)
const viewMode = ref('editor')

const PREVIEW_MAP = {
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

const publicUrl = computed(() => {
  const slug = builderStore.currentPage?.slug
  if (!slug || slug === 'inicio') return '/#/'
  return `/#/${slug}`
})

const activePreviewSections = computed(() =>
  builderStore.sections.filter((s) => s.is_active !== false),
)

const previewCanvasStyle = computed(() => {
  if (builderStore.previewDevice === 'mobile') {
    return { width: '390px', maxWidth: '100%' }
  }
  if (builderStore.previewDevice === 'tablet') {
    return { width: '768px', maxWidth: '100%' }
  }
  return { width: '100%' }
})

onMounted(() => {
  const pageId = route.params.id || 1
  const loaded = builderStore.loadPage(pageId)
  if (!loaded) {
    notify.error('No se pudo encontrar la página solicitada')
  }
})

function resolvePreviewComponent(sec) {
  return PREVIEW_MAP[sec.component_code] || null
}

function selectSectionFromPreview(id) {
  builderStore.selectedSectionId = id
  viewMode.value = 'editor'
}

function handleSelectSection(id) {
  builderStore.selectedSectionId = id
}

function handleAddBlock(code) {
  const added = builderStore.addSection(code)
  if (added) {
    notify.success(`Bloque "${added.component_name}" agregado`)
  }
}

function handleMoveSection(index, direction) {
  builderStore.moveSection(index, direction)
}

function handleToggleActive(id) {
  builderStore.toggleSectionActive(id)
}

function handleDuplicateSection(id) {
  const dup = builderStore.duplicateSection(id)
  if (dup) {
    notify.info('Bloque duplicado')
  }
}

async function handleRemoveSection(id) {
  const ok = await notify.confirm({
    title: '¿Eliminar bloque?',
    message: 'Esta sección será removida de la página.',
    ok: 'Eliminar',
    color: 'negative',
  })
  if (ok) {
    builderStore.removeSection(id)
    notify.info('Bloque eliminado')
  }
}

async function handleSave() {
  const ok = await builderStore.savePageSections()
  if (ok) {
    notify.success('Página y secciones guardadas exitosamente')
  } else {
    notify.error('Ocurrió un error al guardar las secciones')
  }
}
</script>

<style scoped>
.builder-page {
  height: calc(100vh - 50px);
}
.border-bottom {
  border-bottom: 1px solid #e2e8f0;
}
.rounded-borders {
  border-radius: 8px;
}
.font-mono {
  font-family: monospace;
}
.preview-section-wrapper {
  cursor: pointer;
  transition: outline 0.15s ease;
}
.preview-section-wrapper:hover {
  outline: 2px dashed #003278;
  outline-offset: -2px;
}
</style>
