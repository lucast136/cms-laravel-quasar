<template>
  <div class="media-picker-input">
    <!-- Tarjeta de Vista Previa Destacada (Ideal para Logotipos, Favicons y Portadas) -->
    <div
      v-if="showLargePreview"
      class="large-preview-box q-pa-md q-mb-sm rounded-borders border-light row items-center justify-between no-wrap"
      :class="darkPreviewBg ? 'bg-dark text-white' : 'bg-grey-1 text-dark'"
    >
      <div class="row items-center no-wrap q-gutter-x-md">
        <div
          class="large-preview-frame rounded-borders flex flex-center overflow-hidden"
          :class="darkPreviewBg ? 'bg-blue-grey-10' : 'bg-white border-light'"
        >
          <img
            v-if="modelValue && !imgBroken"
            :src="resolvedPreviewUrl"
            alt="Vista previa actual"
            class="preview-img"
            @error="imgBroken = true"
          />
          <q-icon v-else name="broken_image" size="32px" color="grey-5" />
        </div>
        <div>
          <div class="text-caption text-weight-bold">
            {{ label }}
          </div>
          <div
            class="text-caption font-mono ellipsis"
            :class="darkPreviewBg ? 'text-grey-4' : 'text-grey-7'"
            style="max-width: 260px"
          >
            {{ modelValue || 'Sin archivo seleccionado' }}
          </div>
        </div>
      </div>

      <div class="row items-center q-gutter-x-xs">
        <q-btn
          flat
          dense
          round
          size="sm"
          :icon="darkPreviewBg ? 'light_mode' : 'dark_mode'"
          :color="darkPreviewBg ? 'amber' : 'grey-7'"
          @click="darkPreviewBg = !darkPreviewBg"
        >
          <q-tooltip>Alternar fondo claro/oscuro para previsualizar contraste</q-tooltip>
        </q-btn>
        <q-btn
          unelevated
          dense
          no-caps
          size="sm"
          color="primary"
          icon="cloud_upload"
          label="Subir Archivo"
          class="q-px-sm"
          :loading="uploading"
          @click="triggerDirectUpload"
        />
        <q-btn
          outline
          dense
          no-caps
          size="sm"
          :color="darkPreviewBg ? 'white' : 'primary'"
          icon="photo_library"
          label="Selector"
          class="q-px-sm"
          @click="openLibrary"
        />
      </div>
    </div>

    <!-- Input de Ruta + Miniatura Compacta + Acciones -->
    <div class="row items-center no-wrap q-gutter-x-sm">
      <!-- Miniatura de Previsualización (cuando no está activo showLargePreview) -->
      <div
        v-if="!showLargePreview"
        class="media-thumb rounded-borders overflow-hidden bg-grey-3 flex flex-center cursor-pointer border-light"
        @click="openLibrary"
      >
        <img
          v-if="modelValue && !imgBroken"
          :src="resolvedPreviewUrl"
          alt="Preview"
          class="full-width full-height"
          style="object-fit: contain"
          @error="imgBroken = true"
        />
        <q-icon v-else name="image" size="22px" color="grey-6" />
        <q-tooltip>Clic para elegir o subir desde la Biblioteca de Medios</q-tooltip>
      </div>

      <!-- Input oculto para subida directa al backend -->
      <input
        ref="hiddenFileInput"
        type="file"
        accept="image/*,.ico,.svg"
        style="display: none"
        @change="onDirectFileSelected"
      />

      <!-- Campo de ruta / URL -->
      <q-input
        :model-value="modelValue"
        :label="label"
        outlined
        dense
        bg-color="white"
        class="col"
        @update:model-value="onManualInput"
      >
        <template #append>
          <q-btn
            flat
            round
            dense
            icon="cloud_upload"
            color="secondary"
            size="sm"
            :loading="uploading"
            @click="triggerDirectUpload"
          >
            <q-tooltip>Subir archivo desde mi equipo al servidor</q-tooltip>
          </q-btn>
          <q-btn
            flat
            round
            dense
            icon="photo_library"
            color="primary"
            size="sm"
            @click="openLibrary"
          >
            <q-tooltip>Abrir selector de imágenes e íconos</q-tooltip>
          </q-btn>
        </template>
      </q-input>
    </div>

    <!-- Modal Selector de Medios -->
    <q-dialog v-model="dialogOpen">
      <q-card style="width: 780px; max-width: 95vw">
        <q-card-section class="row items-center justify-between bg-primary text-white q-py-sm">
          <div class="row items-center q-gutter-x-sm">
            <q-icon name="perm_media" size="22px" />
            <div class="text-subtitle1 text-weight-bold">
              Selector de Biblioteca de Medios & Subida al Servidor
            </div>
          </div>
          <q-btn v-close-popup flat round dense icon="close" color="white" />
        </q-card-section>

        <q-card-section class="q-pa-md">
          <!-- Barra superior: Subir archivo o filtrar -->
          <div class="row items-center justify-between q-col-gutter-sm q-mb-md">
            <div class="col-12 col-sm-7">
              <q-input
                v-model="searchQuery"
                outlined
                dense
                placeholder="Buscar logotipo, ícono o imagen por nombre..."
                clearable
              >
                <template #prepend>
                  <q-icon name="search" />
                </template>
              </q-input>
            </div>
            <div class="col-12 col-sm-5 text-right">
              <q-file
                v-model="uploadFile"
                outlined
                dense
                accept="image/*,.ico,.svg"
                label="Subir nuevo archivo al backend..."
                :loading="uploading"
                @update:model-value="handleUploadFile"
              >
                <template #prepend>
                  <q-icon name="cloud_upload" color="primary" />
                </template>
              </q-file>
            </div>
          </div>

          <!-- Cuadrícula de Imágenes Disponibles -->
          <div style="max-height: 380px; overflow-y: auto" class="q-pa-xs">
            <div class="row q-col-gutter-sm">
              <div
                v-for="(item, idx) in filteredMedia"
                :key="item.id || idx"
                class="col-6 col-sm-4 col-md-3"
              >
                <q-card
                  flat
                  bordered
                  class="media-card cursor-pointer overflow-hidden"
                  :class="{ 'media-card--active': modelValue === item.url }"
                  @click="selectImage(item.url)"
                >
                  <div style="height: 100px" class="bg-grey-2 flex flex-center overflow-hidden q-pa-xs">
                    <img
                      :src="resolveUrl(item.url)"
                      :alt="item.name"
                      style="max-width: 100%; max-height: 100%; object-fit: contain"
                    />
                  </div>
                  <div class="q-pa-xs text-caption ellipsis text-center text-grey-8">
                    {{ item.name }}
                  </div>
                </q-card>
              </div>
            </div>
          </div>
        </q-card-section>

        <q-separator />

        <q-card-actions align="right" class="q-pa-sm bg-grey-1">
          <q-btn v-close-popup flat label="Cerrar" no-caps color="grey-7" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { useMediaApi } from '@/composables/useMediaApi'
import { useAuthStore } from '@/stores/auth'
import { useNotification } from '@/composables/useNotification'

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
  label: {
    type: String,
    default: 'Imagen (URL o Ruta)',
  },
  showLargePreview: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['update:modelValue'])

const { getMediaListRequest, uploadMediaFileRequest } = useMediaApi()
const authStore = useAuthStore()
const notify = useNotification()

const dialogOpen = ref(false)
const searchQuery = ref('')
const uploadFile = ref(null)
const hiddenFileInput = ref(null)
const uploading = ref(false)
const remoteMedia = ref([])
const imgBroken = ref(false)
const darkPreviewBg = ref(false)

watch(
  () => props.modelValue,
  () => {
    imgBroken.value = false
  },
)

const BACKEND_ORIGIN = 'http://localhost:8000'

function resolveUrl(url) {
  if (!url) return ''
  if (url.startsWith('/storage/')) {
    return `${BACKEND_ORIGIN}${url}`
  }
  return url
}

const resolvedPreviewUrl = computed(() => resolveUrl(props.modelValue))

// Catálogo base de logotipos, favicons e imágenes institucionales disponibles en /public/img
const PRESET_IMAGES = [
  { name: 'Logotipo Principal (Color)', url: '/img/logos/logo-inner.png' },
  { name: 'Logotipo Claro (Footer)', url: '/img/logos/footer-light-logo.png' },
  { name: 'Favicon Institucional (.ico)', url: '/favicon.ico' },
  { name: 'Favicon PNG', url: '/img/logos/favicon.png' },
  { name: 'Banner Slide 1', url: '/img/banner/slide1.jpg' },
  { name: 'Banner Slide 2', url: '/img/banner/slide2.jpg' },
  { name: 'Banner Slide 3', url: '/img/banner/slide3.jpg' },
  { name: 'Nosotros Principal', url: '/img/content/about-03.jpg' },
  { name: 'Nosotros Secundaria', url: '/img/content/about-02.jpg' },
  { name: 'Nosotros Campus', url: '/img/content/about-01.jpg' },
  { name: 'Por Qué Elegirnos', url: '/img/content/why-choose-img.jpg' },
  { name: 'Contacto Sede', url: '/img/content/contact-img-01.jpg' },
  { name: 'Carrera Software', url: '/img/content/courses-01.jpg' },
  { name: 'Carrera Redes', url: '/img/content/courses-02.jpg' },
  { name: 'Carrera Diseño', url: '/img/content/courses-03.jpg' },
  { name: 'Carrera Marketing', url: '/img/content/courses-04.jpg' },
  { name: 'Carrera Contabilidad', url: '/img/content/courses-05.jpg' },
  { name: 'Carrera Ciberseguridad', url: '/img/content/courses-06.jpg' },
  { name: 'Docente 1', url: '/img/team/team-01.jpg' },
  { name: 'Docente 2', url: '/img/team/team-02.jpg' },
  { name: 'Docente 3', url: '/img/team/team-03.jpg' },
  { name: 'Docente 4', url: '/img/team/team-04.jpg' },
  { name: 'Avatar 1', url: '/img/avatar/avatar-01.jpg' },
  { name: 'Avatar 2', url: '/img/avatar/avatar-02.jpg' },
  { name: 'Avatar 3', url: '/img/avatar/avatar-03.jpg' },
  { name: 'Avatar 4', url: '/img/avatar/avatar-04.jpg' },
  { name: 'Evento 1', url: '/img/content/event-01.jpg' },
  { name: 'Evento 2', url: '/img/content/event-02.jpg' },
  { name: 'Blog 1', url: '/img/blog/blog-01.jpg' },
  { name: 'Blog 2', url: '/img/blog/blog-02.jpg' },
  { name: 'Blog 3', url: '/img/blog/blog-03.jpg' },
  { name: 'Partner 1', url: '/img/partners/partner-01.jpg' },
  { name: 'Partner 2', url: '/img/partners/partner-02.jpg' },
  { name: 'Ícono Especialidad 1', url: '/img/icons/icon-01.png' },
  { name: 'Ícono Especialidad 2', url: '/img/icons/icon-02.png' },
  { name: 'Ícono Especialidad 3', url: '/img/icons/icon-03.png' },
  { name: 'Ícono Especialidad 4', url: '/img/icons/icon-04.png' },
]

const allMedia = computed(() => {
  const combined = [...remoteMedia.value, ...PRESET_IMAGES]
  const seen = new Set()
  return combined.filter((item) => {
    if (!item.url || seen.has(item.url)) return false
    seen.add(item.url)
    return true
  })
})

const filteredMedia = computed(() => {
  if (!searchQuery.value) return allMedia.value
  const q = searchQuery.value.toLowerCase()
  return allMedia.value.filter(
    (m) => m.name?.toLowerCase().includes(q) || m.url?.toLowerCase().includes(q),
  )
})

function onManualInput(val) {
  imgBroken.value = false
  emit('update:modelValue', val)
}

function triggerDirectUpload() {
  hiddenFileInput.value?.click()
}

async function onDirectFileSelected(event) {
  const file = event.target?.files?.[0]
  if (file) {
    await handleUploadFile(file)
  }
  if (event.target) {
    event.target.value = ''
  }
}

async function openLibrary() {
  dialogOpen.value = true
  try {
    await authStore.ensureDevSession()
    const list = await getMediaListRequest()
    if (Array.isArray(list)) {
      remoteMedia.value = list.map((m) => ({
        id: m.id,
        name: m.title || m.original_name || m.filename || m.alt_text || 'Archivo Subido',
        url: m.url || resolveUrl(m.filepath || m.file_path),
      }))
    }
  } catch {
    // Usa el catálogo preset si falla la red
  }
}

async function handleUploadFile(file) {
  if (!file) return
  uploading.value = true
  try {
    await authStore.ensureDevSession()
    const uploaded = await uploadMediaFileRequest(file, file.name)
    const rawUrl = uploaded?.url || uploaded?.filepath || uploaded?.file_path
    const finalUrl = resolveUrl(rawUrl)
    if (finalUrl) {
      remoteMedia.value.unshift({
        id: uploaded.id || Date.now(),
        name: uploaded.title || uploaded.filename || file.name,
        url: finalUrl,
      })
      selectImage(finalUrl)
      notify.success(`Archivo "${file.name}" subido al servidor exitosamente`)
    }
  } catch (err) {
    notify.error(err?.response?.data?.message || 'No se pudo subir el archivo al servidor')
  } finally {
    uploading.value = false
    uploadFile.value = null
  }
}

function selectImage(url) {
  imgBroken.value = false
  emit('update:modelValue', url)
  dialogOpen.value = false
}
</script>

<style scoped>
.media-thumb {
  width: 44px;
  height: 40px;
  flex-shrink: 0;
  padding: 2px;
}
.large-preview-frame {
  width: 120px;
  height: 64px;
  padding: 6px;
  flex-shrink: 0;
}
.preview-img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}
.border-light {
  border: 1px solid #cbd5e1;
}
.font-mono {
  font-family: monospace;
  font-size: 11px;
}
.media-card {
  transition: all 0.2s ease;
}
.media-card:hover {
  border-color: #003278;
  transform: translateY(-2px);
}
.media-card--active {
  border: 2px solid #003278;
}
</style>
