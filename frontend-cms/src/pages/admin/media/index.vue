<template>
  <AdminLayout>
    <q-page class="q-pa-lg">
      <!-- Encabezado -->
      <div class="row items-center justify-between q-mb-lg">
        <div>
          <q-breadcrumbs class="text-caption text-grey-7">
            <q-breadcrumbs-el label="Dashboard" icon="dashboard" to="/admin" />
            <q-breadcrumbs-el label="Biblioteca de Medios" icon="photo_library" />
          </q-breadcrumbs>
          <h1 class="text-h4 text-weight-bold text-dark q-my-xs">Biblioteca Multimedia (media)</h1>
          <div class="text-subtitle2 text-grey-7">
            Sube y administra imágenes de banners, portadas de carreras y documentos en PDF.
          </div>
        </div>

        <q-btn
          color="primary"
          unelevated
          icon="cloud_upload"
          label="Subir Archivos"
          no-caps
          @click="showUploader = !showUploader"
        />
      </div>

      <!-- Zona Desplegable de Carga Nativa QUploader -->
      <q-slide-transition>
        <div v-show="showUploader" class="q-mb-lg">
          <q-card flat bordered class="rounded-borders bg-blue-1">
            <q-card-section>
              <div class="text-subtitle1 text-weight-bold q-mb-sm text-primary">
                Carga Rápida de Archivos
              </div>
              <q-uploader
                ref="uploaderRef"
                :url="uploadUrl"
                :headers="uploadHeaders"
                field-name="file"
                label="Arrastra imágenes (JPG, PNG, WebP, SVG, ICO) o PDFs aquí"
                multiple
                auto-upload
                style="width: 100%"
                color="primary"
                @uploaded="onUploaded"
                @finish="onFinishUpload"
              />
            </q-card-section>
          </q-card>
        </div>
      </q-slide-transition>

      <!-- Barra de Filtros -->
      <q-card flat bordered class="q-mb-md rounded-borders">
        <q-card-section class="q-py-sm">
          <div class="row q-col-gutter-sm items-center justify-between">
            <div class="col-12 col-sm-6 col-md-4">
              <q-input
                v-model="search"
                dense
                outlined
                placeholder="Buscar por nombre o etiqueta..."
                clearable
              >
                <template #prepend><q-icon name="search" /></template>
              </q-input>
            </div>

            <div class="col-12 col-sm-6 col-md-4 text-right">
              <q-btn-toggle
                v-model="filterType"
                dense
                unelevated
                toggle-color="primary"
                color="grey-2"
                text-color="grey-8"
                :options="[
                  { label: 'Todos', value: 'all' },
                  { label: 'Imágenes', value: 'image' },
                  { label: 'Documentos', value: 'pdf' },
                ]"
              />
            </div>
          </div>
        </q-card-section>
      </q-card>

      <!-- Grilla de Archivos Multimedia -->
      <div class="row q-col-gutter-md">
        <div v-for="item in filteredMedia" :key="item.id" class="col-12 col-sm-6 col-md-4 col-lg-3">
          <q-card flat bordered class="media-card rounded-borders overflow-hidden">
            <!-- Vista Previa de Imagen o Ícono de Documento -->
            <div v-if="item.mime_type.startsWith('image')" class="relative-position bg-grey-2">
              <q-img :src="item.filepath" :alt="item.alt_text" style="height: 160px" fit="cover" />
              <q-badge color="dark" class="absolute-top-right q-ma-xs" style="opacity: 0.85">
                {{ item.dimensions }}
              </q-badge>
            </div>
            <div v-else class="column flex-center bg-grey-2" style="height: 160px">
              <q-icon name="picture_as_pdf" size="64px" color="negative" />
              <span class="text-caption text-weight-bold text-grey-8 q-mt-xs">PDF Documento</span>
            </div>

            <q-card-section class="q-pa-sm">
              <div class="text-weight-bold text-subtitle2 text-dark ellipsis" :title="item.title">
                {{ item.title }}
              </div>
              <div class="row items-center justify-between text-caption text-grey-6 q-mt-xs">
                <span>{{ item.file_size }}</span>
                <span class="font-mono text-grey-5">{{ item.filename }}</span>
              </div>
            </q-card-section>

            <q-separator />

            <q-card-actions align="between" class="q-px-sm q-py-xs bg-grey-1">
              <q-btn
                flat
                size="sm"
                color="primary"
                icon="content_copy"
                label="Copiar URL"
                no-caps
                @click="copyUrl(item.filepath)"
              />
              <q-btn
                flat
                round
                dense
                size="sm"
                icon="delete"
                color="negative"
                @click="deleteMedia(item)"
              >
                <q-tooltip>Eliminar archivo</q-tooltip>
              </q-btn>
            </q-card-actions>
          </q-card>
        </div>
      </div>
    </q-page>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { useNotification } from '@/composables/useNotification'
import { useMediaApi } from '@/composables/useMediaApi'
import { useAuthStore } from '@/stores/auth'
import { API_BASE_URL } from '@/services/api'

const notify = useNotification()
const authStore = useAuthStore()
const { getMediaListRequest, deleteMediaRequest } = useMediaApi()

const uploadUrl = `${API_BASE_URL}/admin/media/upload`
const uploadHeaders = computed(() => [
  { name: 'Accept', value: 'application/json' },
  ...(authStore.token ? [{ name: 'Authorization', value: `Bearer ${authStore.token}` }] : []),
])
const uploaderRef = ref(null)
const showUploader = ref(false)
const search = ref('')
const filterType = ref('all')

const mediaList = ref([
  {
    id: 1,
    title: 'Banner Principal Slide 1',
    filename: 'slide1.jpg',
    filepath: '/img/banner/slide1.jpg',
    mime_type: 'image/jpeg',
    file_size: '340 KB',
    dimensions: '1920x800',
    alt_text: 'Estudiantes del IESTP DEPERU MASTER en aula tecnológica',
  },
  {
    id: 2,
    title: 'Banner Principal Slide 2',
    filename: 'slide2.jpg',
    filepath: '/img/banner/slide2.jpg',
    mime_type: 'image/jpeg',
    file_size: '410 KB',
    dimensions: '1920x800',
    alt_text: 'Docente guiando a postulantes',
  },
  {
    id: 3,
    title: 'Imagen Institucional Nosotros',
    filename: 'about-01.jpg',
    filepath: '/img/content/about-01.jpg',
    mime_type: 'image/jpeg',
    file_size: '220 KB',
    dimensions: '800x600',
    alt_text: 'Campus institucional en Lircay',
  },
  {
    id: 4,
    title: 'Video Institucional Portada',
    filename: 'why-choose-img.jpg',
    filepath: '/img/content/why-choose-img.jpg',
    mime_type: 'image/jpeg',
    file_size: '280 KB',
    dimensions: '1000x650',
    alt_text: 'Laboratorio de redes y sistemas',
  },
  {
    id: 5,
    title: 'Logo Principal Institucional',
    filename: 'logo-inner.png',
    filepath: '/img/logos/logo-inner.png',
    mime_type: 'image/png',
    file_size: '65 KB',
    dimensions: '350x90',
    alt_text: 'Logotipo oficial DEPERU MASTER',
  },
  {
    id: 6,
    title: 'Prospecto de Admisión 2026-I',
    filename: 'prospecto_admision_2026.pdf',
    filepath: '/docs/prospecto_admision_2026.pdf',
    mime_type: 'application/pdf',
    file_size: '2.4 MB',
    dimensions: 'PDF',
    alt_text: 'Bases y reglamento de admisión',
  },
])

function formatFileSize(size) {
  if (typeof size === 'string') return size
  const num = Number(size) || 0
  if (num >= 1048576) return `${(num / 1048576).toFixed(1)} MB`
  return `${Math.max(1, Math.round(num / 1024))} KB`
}

function normalizeMedia(raw) {
  let dims = 'Imagen'
  if (typeof raw.dimensions === 'string') {
    dims = raw.dimensions
  } else if (raw.dimensions?.label) {
    dims = raw.dimensions.label
  } else if (raw.dimensions?.width && raw.dimensions?.height) {
    dims = `${raw.dimensions.width}x${raw.dimensions.height}`
  }

  return {
    id: raw.id,
    title: raw.title || raw.filename,
    filename: raw.filename,
    filepath: raw.url || raw.filepath,
    mime_type: raw.mime_type || 'image/jpeg',
    file_size: formatFileSize(raw.file_size),
    dimensions: dims,
    alt_text: raw.alt_text || raw.title || '',
  }
}

async function fetchMedia() {
  try {
    await authStore.ensureDevSession()
    const list = await getMediaListRequest()
    if (Array.isArray(list) && list.length > 0) {
      mediaList.value = list.map(normalizeMedia)
    }
  } catch {
    // Mantiene respaldo local
  }
}

onMounted(() => {
  fetchMedia()
})

const filteredMedia = computed(() => {
  const q = (search.value || '').toLowerCase()
  return mediaList.value.filter((item) => {
    const matchSearch =
      item.title.toLowerCase().includes(q) || item.filename.toLowerCase().includes(q)
    const matchType =
      filterType.value === 'all' ||
      (filterType.value === 'image' && item.mime_type.startsWith('image')) ||
      (filterType.value === 'pdf' && item.mime_type.includes('pdf'))
    return matchSearch && matchType
  })
})

function copyUrl(url) {
  navigator.clipboard.writeText(url)
  notify.success(`URL copiada al portapapeles: ${url}`)
}

function onUploaded(info) {
  notify.success(`Archivo subido: ${info.files?.[0]?.name || 'Archivo'}`)
  fetchMedia()
}

function onFinishUpload() {
  notify.success('Carga completada. El cargador está listo para nuevos archivos.')
  // Limpiar los archivos cargados para dejar el componente listo para nuevas subidas
  if (uploaderRef.value) {
    uploaderRef.value.reset()
  }
  fetchMedia()
}

async function deleteMedia(item) {
  const ok = await notify.confirm({
    title: '¿Eliminar archivo?',
    message: `¿Estás seguro de eliminar permanentemente "${item.filename}"?`,
    ok: 'Eliminar',
    color: 'negative',
  })
  if (ok) {
    mediaList.value = mediaList.value.filter((m) => m.id !== item.id)
    try {
      await deleteMediaRequest(item.id)
    } catch {
      // Mantiene borrado optimista
    }
    notify.success('Archivo eliminado')
  }
}
</script>

<style scoped>
.rounded-borders {
  border-radius: 12px;
}
.media-card {
  transition: all 0.2s ease;
}
.media-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
}
.font-mono {
  font-family: monospace;
}
</style>
