<template>
  <AdminLayout>
    <q-page class="q-pa-lg">
      <!-- Encabezado de Bienvenida y Acciones Rápidas -->
      <div class="row items-center justify-between q-mb-lg">
        <div>
          <h1 class="text-h4 text-weight-bold text-dark q-my-none">Panel de Control</h1>
          <div class="text-subtitle2 text-grey-7 q-mt-xs">
            Bienvenido al CMS Institucional del IESTP DEPERU MASTER.
          </div>
        </div>

        <div class="row q-gutter-sm">
          <q-btn
            color="primary"
            unelevated
            icon="add"
            label="Nueva Página"
            no-caps
            to="/admin/pages"
          />
          <q-btn
            color="secondary"
            outline
            icon="open_in_new"
            label="Ver Web Pública"
            no-caps
            href="/"
            target="_blank"
          />
        </div>
      </div>

      <!-- Tarjetas de Métricas Institucionales (KPIs) -->
      <div class="row q-col-gutter-md q-mb-lg">
        <div class="col-12 col-sm-6 col-md-3">
          <StatCard
            title="Páginas Institucionales"
            :value="pagesStore.totalPages"
            subtitle="Páginas configuradas"
            icon="layers"
            color="primary"
            trend-text="100%"
            trend-icon="check_circle"
            trend-color="positive"
          />
        </div>
        <div class="col-12 col-sm-6 col-md-3">
          <StatCard
            title="Catálogo de Bloques"
            :value="stats.component_types"
            subtitle="Componentes listos para Builder"
            icon="extension"
            color="accent"
            trend-text="+2 nuevos"
            trend-icon="trending_up"
            trend-color="info"
          />
        </div>
        <div class="col-12 col-sm-6 col-md-3">
          <StatCard
            title="Noticias & Comunicados"
            :value="stats.posts"
            subtitle="Artículos en Blog"
            icon="article"
            color="warning"
            trend-text="Activo"
            trend-icon="fiber_manual_record"
            trend-color="warning"
          />
        </div>
        <div class="col-12 col-sm-6 col-md-3">
          <StatCard
            title="Archivos Multimedia"
            :value="stats.media"
            subtitle="Recursos en biblioteca"
            icon="photo_library"
            color="secondary"
            trend-text="Sincronizado"
            trend-icon="arrow_upward"
            trend-color="positive"
          />
        </div>
      </div>

      <!-- Sección Principal: Tabla de Páginas + Accesos Rápidos -->
      <div class="row q-col-gutter-lg">
        <!-- Columna Izquierda: Páginas Activas y Acceso al Page Builder -->
        <div class="col-12 col-lg-8">
          <q-card flat bordered class="rounded-borders">
            <q-card-section class="row items-center justify-between q-pb-none">
              <div class="text-h6 text-weight-bold">Páginas Institucionales</div>
              <q-btn flat color="primary" label="Ver todas" no-caps to="/admin/pages" />
            </q-card-section>

            <q-card-section>
              <q-table
                flat
                :rows="pagesStore.pages"
                :columns="columns"
                row-key="id"
                :pagination="{ rowsPerPage: 5 }"
                hide-pagination
              >
                <!-- Columna Título con Icono -->
                <template #body-cell-title="props">
                  <q-td :props="props">
                    <div class="row items-center no-wrap">
                      <q-avatar
                        size="32px"
                        color="blue-1"
                        text-color="primary"
                        icon="description"
                        class="q-mr-sm"
                      />
                      <div>
                        <div class="text-weight-bold text-dark">{{ props.row.title }}</div>
                        <div class="text-caption text-grey-6">/{{ props.row.slug }}</div>
                      </div>
                    </div>
                  </q-td>
                </template>

                <!-- Columna Plantilla -->
                <template #body-cell-template="props">
                  <q-td :props="props">
                    <q-badge color="grey-3" text-color="grey-9" :label="props.row.template" />
                  </q-td>
                </template>

                <!-- Columna Secciones -->
                <template #body-cell-sections="props">
                  <q-td :props="props" class="text-center">
                    <q-badge color="accent" :label="`${props.row.sections?.length || 0} bloques`" />
                  </q-td>
                </template>

                <!-- Columna Estado -->
                <template #body-cell-status="props">
                  <q-td :props="props">
                    <q-chip
                      size="sm"
                      dense
                      :color="props.row.status === 'published' ? 'positive' : 'warning'"
                      text-color="white"
                      :icon="props.row.status === 'published' ? 'check' : 'edit'"
                    >
                      {{ props.row.status === 'published' ? 'Publicado' : 'Borrador' }}
                    </q-chip>
                  </q-td>
                </template>

                <!-- Columna Acciones -->
                <template #body-cell-actions="props">
                  <q-td :props="props" class="text-right">
                    <q-btn
                      color="primary"
                      dense
                      unelevated
                      size="sm"
                      icon="brush"
                      label="Constructor"
                      no-caps
                      class="q-mr-xs"
                      :to="`/admin/pages/${props.row.id}/builder`"
                    >
                      <q-tooltip>Abrir Page Builder Visual</q-tooltip>
                    </q-btn>
                    <q-btn
                      flat
                      round
                      dense
                      size="sm"
                      icon="visibility"
                      color="grey-7"
                      :href="props.row.slug === 'inicio' ? '/' : `/${props.row.slug}`"
                      target="_blank"
                    >
                      <q-tooltip>Ver en la Web</q-tooltip>
                    </q-btn>
                  </q-td>
                </template>
              </q-table>
            </q-card-section>
          </q-card>
        </div>

        <!-- Columna Derecha: Accesos Directos & Estado Backend -->
        <div class="col-12 col-lg-4">
          <!-- Tarjeta de Acciones Rápidas -->
          <q-card flat bordered class="rounded-borders q-mb-md">
            <q-card-section class="q-pb-sm">
              <div class="text-subtitle1 text-weight-bold">Constructor Rápido</div>
              <div class="text-caption text-grey-7">
                Accesos directos a los módulos más utilizados
              </div>
            </q-card-section>
            <q-separator />
            <q-list separator>
              <q-item clickable v-ripple to="/admin/pages/1/builder">
                <q-item-section avatar>
                  <q-avatar icon="view_quilt" color="primary" text-color="white" size="md" />
                </q-item-section>
                <q-item-section>
                  <q-item-label class="text-weight-bold">Editar Página Principal</q-item-label>
                  <q-item-label caption>Modificar slider, carreras y noticias</q-item-label>
                </q-item-section>
                <q-item-section side>
                  <q-icon name="chevron_right" color="grey" />
                </q-item-section>
              </q-item>

              <q-item clickable v-ripple to="/admin/components">
                <q-item-section avatar>
                  <q-avatar icon="widgets" color="accent" text-color="white" size="md" />
                </q-item-section>
                <q-item-section>
                  <q-item-label class="text-weight-bold">Explorar Catálogo de Bloques</q-item-label>
                  <q-item-label caption>15 componentes disponibles</q-item-label>
                </q-item-section>
                <q-item-section side>
                  <q-icon name="chevron_right" color="grey" />
                </q-item-section>
              </q-item>

              <q-item clickable v-ripple to="/admin/media">
                <q-item-section avatar>
                  <q-avatar icon="cloud_upload" color="positive" text-color="white" size="md" />
                </q-item-section>
                <q-item-section>
                  <q-item-label class="text-weight-bold">Subir Imágenes o Documentos</q-item-label>
                  <q-item-label caption>Formatos WebP, JPG, PNG, PDF</q-item-label>
                </q-item-section>
                <q-item-section side>
                  <q-icon name="chevron_right" color="grey" />
                </q-item-section>
              </q-item>

              <q-item clickable v-ripple to="/admin/settings">
                <q-item-section avatar>
                  <q-avatar icon="palette" color="warning" text-color="white" size="md" />
                </q-item-section>
                <q-item-section>
                  <q-item-label class="text-weight-bold">Identidad Institucional</q-item-label>
                  <q-item-label caption>Logos, colores, redes y contacto</q-item-label>
                </q-item-section>
                <q-item-section side>
                  <q-icon name="chevron_right" color="grey" />
                </q-item-section>
              </q-item>
            </q-list>
          </q-card>

          <!-- Tarjeta de Estado del Sistema y Backend -->
          <q-card flat bordered class="rounded-borders bg-blue-1 border-primary">
            <q-card-section class="q-pb-xs">
              <div class="row items-center justify-between">
                <div class="text-subtitle2 text-weight-bold text-primary">Arquitectura CMS</div>
                <q-badge color="positive" label="RBAC OK" />
              </div>
            </q-card-section>
            <q-card-section class="text-caption text-grey-8">
              <div class="q-mb-xs">
                <strong>Frontend:</strong> Quasar v2 (SPA Admin + SSR Público)
              </div>
              <div class="q-mb-xs"><strong>Backend:</strong> Laravel 13 API REST con Sanctum</div>
              <div class="q-mb-xs">
                <strong>Base de Datos:</strong> 23 tablas normalizadas (ERD)
              </div>
              <div><strong>Patrón:</strong> Service-Repository con Bloques JSON</div>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </q-page>
  </AdminLayout>
</template>

<script setup>
import { reactive, onMounted } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import StatCard from '@/components/admin/common/StatCard.vue'
import { useAdminPagesStore } from '@/stores/admin-pages'
import { useSettingsApi } from '@/composables/useSettingsApi'

const pagesStore = useAdminPagesStore()
const { getDashboardStatsRequest } = useSettingsApi()

const stats = reactive({
  component_types: 15,
  posts: 4,
  media: 6,
  leads: 0,
})

onMounted(async () => {
  try {
    const res = await getDashboardStatsRequest()
    if (res?.counts) {
      stats.component_types = res.counts.component_types ?? 15
      stats.posts = res.counts.posts ?? 4
      stats.media = res.counts.media ?? 6
      stats.leads = res.counts.leads ?? 0
    }
  } catch {
    // Mantiene valores por defecto si el servidor aún está iniciando
  }
})

const columns = [
  { name: 'title', label: 'Página', field: 'title', align: 'left' },
  { name: 'template', label: 'Plantilla', field: 'template', align: 'left' },
  { name: 'sections', label: 'Secciones', field: 'sections', align: 'center' },
  { name: 'status', label: 'Estado', field: 'status', align: 'center' },
  { name: 'actions', label: 'Acciones', align: 'right' },
]
</script>

<style scoped>
.rounded-borders {
  border-radius: 12px;
}
</style>
