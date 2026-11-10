<template>
  <AdminLayout>
    <q-page class="q-pa-lg">
      <!-- Encabezado de la Sección -->
      <div class="row items-center justify-between q-mb-lg">
        <div>
          <div class="row items-center q-gutter-x-sm">
            <q-breadcrumbs class="text-caption text-grey-7">
              <q-breadcrumbs-el label="Dashboard" icon="dashboard" to="/admin" />
              <q-breadcrumbs-el label="Páginas Web" icon="layers" />
            </q-breadcrumbs>
          </div>
          <h1 class="text-h4 text-weight-bold text-dark q-my-xs">
            Gestión de Páginas Institucionales
          </h1>
          <div class="text-subtitle2 text-grey-7">
            Crea, edita y organiza las páginas del sitio web usando el constructor por bloques.
          </div>
        </div>

        <div>
          <q-btn
            color="primary"
            unelevated
            icon="add"
            label="Crear Nueva Página"
            no-caps
            @click="openCreateDialog"
          />
        </div>
      </div>

      <!-- Filtros y Búsqueda -->
      <q-card flat bordered class="q-mb-md rounded-borders">
        <q-card-section class="q-py-sm">
          <div class="row items-center justify-between q-col-gutter-md">
            <div class="col-12 col-sm-6 col-md-4">
              <q-input
                v-model="pagesStore.searchQuery"
                dense
                outlined
                placeholder="Buscar por título o slug..."
                clearable
              >
                <template #prepend>
                  <q-icon name="search" color="grey" />
                </template>
              </q-input>
            </div>

            <div class="col-12 col-sm-6 col-md-4 text-right">
              <q-btn-toggle
                v-model="pagesStore.statusFilter"
                dense
                unelevated
                toggle-color="primary"
                color="grey-3"
                text-color="grey-8"
                no-caps
                :options="[
                  { label: `Todas (${pagesStore.totalPages})`, value: 'all' },
                  { label: `Publicadas (${pagesStore.publishedPagesCount})`, value: 'published' },
                  { label: `Borrador (${pagesStore.draftPagesCount})`, value: 'draft' },
                ]"
              />
            </div>
          </div>
        </q-card-section>
      </q-card>

      <!-- Tabla Principal de Páginas -->
      <q-card flat bordered class="rounded-borders">
        <q-table
          flat
          :rows="pagesStore.filteredPages"
          :columns="columns"
          row-key="id"
          :loading="pagesStore.loading"
          :pagination="{ rowsPerPage: 10 }"
        >
          <!-- Celda de Título con Icono -->
          <template #body-cell-title="props">
            <q-td :props="props">
              <div class="row items-center no-wrap">
                <q-avatar
                  size="36px"
                  color="blue-1"
                  text-color="primary"
                  icon="description"
                  class="q-mr-md"
                />
                <div>
                  <div class="text-weight-bold text-subtitle2 text-dark">{{ props.row.title }}</div>
                  <div class="text-caption text-grey-6 row items-center">
                    <span class="q-mr-xs">URL:</span>
                    <q-badge color="grey-3" text-color="grey-8" class="text-lowercase font-mono">
                      /{{ props.row.slug }}
                    </q-badge>
                  </div>
                </div>
              </div>
            </q-td>
          </template>

          <!-- Celda de Plantilla -->
          <template #body-cell-template="props">
            <q-td :props="props">
              <q-badge color="accent" outline :label="props.row.template" />
            </q-td>
          </template>

          <!-- Celda de Secciones / Bloques -->
          <template #body-cell-sections="props">
            <q-td :props="props" class="text-center">
              <q-chip
                size="sm"
                dense
                color="blue-1"
                text-color="primary"
                icon="extension"
                class="text-weight-bold"
              >
                {{ props.row.sections?.length || 0 }} bloques
              </q-chip>
            </q-td>
          </template>

          <!-- Celda de Estado -->
          <template #body-cell-status="props">
            <q-td :props="props">
              <q-chip
                size="sm"
                dense
                :color="props.row.status === 'published' ? 'positive' : 'warning'"
                text-color="white"
                :icon="props.row.status === 'published' ? 'check_circle' : 'pending'"
              >
                {{ props.row.status === 'published' ? 'Publicado' : 'Borrador' }}
              </q-chip>
            </q-td>
          </template>

          <!-- Celda de Última Modificación -->
          <template #body-cell-updated_at="props">
            <q-td :props="props" class="text-caption text-grey-7">
              {{ props.row.updated_at }}
            </q-td>
          </template>

          <!-- Celda de Acciones -->
          <template #body-cell-actions="props">
            <q-td :props="props" class="text-right">
              <!-- Botón Principal: Diseñar con Page Builder -->
              <q-btn
                color="primary"
                unelevated
                dense
                size="sm"
                icon="brush"
                label="Constructor"
                no-caps
                class="q-mr-xs q-px-sm"
                :to="`/admin/pages/${props.row.id}/builder`"
              >
                <q-tooltip>Diseñar secciones con Page Builder</q-tooltip>
              </q-btn>

              <!-- Botón Editar Metadatos -->
              <q-btn
                flat
                round
                dense
                size="sm"
                icon="edit"
                color="grey-8"
                @click="openEditDialog(props.row)"
              >
                <q-tooltip>Editar título y configuración</q-tooltip>
              </q-btn>

              <!-- Botón Duplicar Página -->
              <q-btn
                flat
                round
                dense
                size="sm"
                icon="content_copy"
                color="grey-8"
                @click="handleDuplicate(props.row.id)"
              >
                <q-tooltip>Duplicar página</q-tooltip>
              </q-btn>

              <!-- Botón Ver en Vivo -->
              <q-btn
                flat
                round
                dense
                size="sm"
                icon="visibility"
                color="grey-8"
                :href="props.row.slug === 'inicio' ? '/' : `/${props.row.slug}`"
                target="_blank"
              >
                <q-tooltip>Ver en la web pública</q-tooltip>
              </q-btn>

              <!-- Botón Eliminar Página -->
              <q-btn
                flat
                round
                dense
                size="sm"
                icon="delete"
                color="negative"
                @click="handleDelete(props.row)"
              >
                <q-tooltip>Eliminar página</q-tooltip>
              </q-btn>
            </q-td>
          </template>
        </q-table>
      </q-card>

      <!-- Diálogo Modal para Crear o Editar Página -->
      <q-dialog v-model="pageDialog.open" persistent>
        <q-card style="min-width: 480px; max-width: 600px" class="rounded-borders">
          <q-card-section class="row items-center justify-between q-pb-none">
            <div class="text-h6 text-weight-bold">
              {{ pageDialog.isEditing ? 'Editar Configuración de Página' : 'Crear Nueva Página' }}
            </div>
            <q-btn flat round dense icon="close" v-close-popup />
          </q-card-section>

          <q-card-section class="q-pt-md">
            <q-form @submit="savePageForm" class="q-gutter-md">
              <q-input
                v-model="pageForm.title"
                label="Título de la Página *"
                outlined
                dense
                lazy-rules
                :rules="[(val) => !!val || 'El título es obligatorio']"
              />

              <q-input
                v-model="pageForm.slug"
                label="Slug URL *"
                outlined
                dense
                prefix="/"
                hint="Identificador amigable único para la dirección web"
                lazy-rules
                :rules="[(val) => !!val || 'El slug es obligatorio']"
              />

              <div class="row q-col-gutter-sm">
                <div class="col-12 col-sm-6">
                  <q-select
                    v-model="pageForm.template"
                    label="Plantilla de Diseño"
                    outlined
                    dense
                    :options="[
                      { label: 'Página Completa (Landing)', value: 'landing' },
                      { label: 'Ancho Completo (Full-Width)', value: 'full-width' },
                      { label: 'Con Barra Lateral (With Sidebar)', value: 'with-sidebar' },
                    ]"
                    emit-value
                    map-options
                  />
                </div>
                <div class="col-12 col-sm-6">
                  <q-select
                    v-model="pageForm.status"
                    label="Estado de Publicación"
                    outlined
                    dense
                    :options="[
                      { label: 'Publicado', value: 'published' },
                      { label: 'Borrador', value: 'draft' },
                    ]"
                    emit-value
                    map-options
                  />
                </div>
              </div>

              <q-input
                v-model="pageForm.meta_description"
                label="Descripción SEO (Meta Excerpt)"
                type="textarea"
                rows="2"
                outlined
                dense
                hint="Texto breve para motores de búsqueda (Google)"
              />

              <div class="row justify-end q-gutter-sm q-pt-md">
                <q-btn flat label="Cancelar" color="grey-7" v-close-popup no-caps />
                <q-btn
                  unelevated
                  :label="pageDialog.isEditing ? 'Guardar Cambios' : 'Crear Página'"
                  color="primary"
                  type="submit"
                  no-caps
                />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>
    </q-page>
  </AdminLayout>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { useAdminPagesStore } from '@/stores/admin-pages'
import { useNotification } from '@/composables/useNotification'

const router = useRouter()
const pagesStore = useAdminPagesStore()
const notify = useNotification()

const columns = [
  { name: 'title', label: 'Título & Ruta', field: 'title', align: 'left', sortable: true },
  { name: 'template', label: 'Plantilla', field: 'template', align: 'left' },
  { name: 'sections', label: 'Bloques', field: 'sections', align: 'center' },
  { name: 'status', label: 'Estado', field: 'status', align: 'center', sortable: true },
  {
    name: 'updated_at',
    label: 'Última Edición',
    field: 'updated_at',
    align: 'left',
    sortable: true,
  },
  { name: 'actions', label: 'Acciones', align: 'right' },
]

const pageDialog = reactive({
  open: false,
  isEditing: false,
  editingId: null,
})

const pageForm = reactive({
  title: '',
  slug: '',
  template: 'full-width',
  status: 'draft',
  meta_description: '',
})

function openCreateDialog() {
  pageDialog.isEditing = false
  pageDialog.editingId = null
  pageForm.title = ''
  pageForm.slug = ''
  pageForm.template = 'full-width'
  pageForm.status = 'draft'
  pageForm.meta_description = ''
  pageDialog.open = true
}

function openEditDialog(page) {
  pageDialog.isEditing = true
  pageDialog.editingId = page.id
  pageForm.title = page.title
  pageForm.slug = page.slug
  pageForm.template = page.template
  pageForm.status = page.status
  pageForm.meta_description = page.meta_description || ''
  pageDialog.open = true
}

async function savePageForm() {
  if (pageDialog.isEditing) {
    await pagesStore.updatePage(pageDialog.editingId, pageForm)
    notify.success('Página actualizada correctamente')
  } else {
    const created = await pagesStore.createPage(pageForm)
    notify.success('Página creada exitosamente')
    if (created?.id) {
      router.push(`/admin/pages/${created.id}/builder`)
    }
  }
  pageDialog.open = false
}

async function handleDuplicate(id) {
  const cloned = await pagesStore.duplicatePage(id)
  if (cloned) {
    notify.info(`Página duplicada: "${cloned.title}"`)
  }
}

async function handleDelete(page) {
  const ok = await notify.confirm({
    title: '¿Eliminar página?',
    message: `¿Estás seguro de que deseas eliminar permanentemente "${page.title}"?`,
    ok: 'Eliminar',
    color: 'negative',
  })
  if (ok) {
    await pagesStore.deletePage(page.id)
    notify.success('Página eliminada correctamente')
  }
}
</script>

<style scoped>
.rounded-borders {
  border-radius: 12px;
}
.font-mono {
  font-family: monospace;
}
</style>
