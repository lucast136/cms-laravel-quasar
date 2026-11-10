<template>
  <AdminLayout>
    <q-page class="q-pa-lg">
      <!-- Encabezado -->
      <div class="row items-center justify-between q-mb-lg">
        <div>
          <q-breadcrumbs class="text-caption text-grey-7">
            <q-breadcrumbs-el label="Dashboard" icon="dashboard" to="/admin" />
            <q-breadcrumbs-el label="Ajustes del Sitio" icon="tune" />
          </q-breadcrumbs>
          <h1 class="text-h4 text-weight-bold text-dark q-my-xs">
            Configuración Global (site_settings)
          </h1>
          <div class="text-subtitle2 text-grey-7">
            Personaliza la identidad institucional, logotipos, datos de contacto, enlaces sociales,
            colores y SEO global.
          </div>
        </div>

        <q-btn
          color="primary"
          unelevated
          icon="save"
          label="Guardar Configuración"
          no-caps
          :loading="saving"
          @click="saveSettings"
        />
      </div>

      <!-- Tarjeta con Pestañas de Ajustes -->
      <q-card flat bordered class="rounded-borders">
        <q-tabs
          v-model="tab"
          dense
          active-color="primary"
          indicator-color="primary"
          align="left"
          narrow-indicator
          class="bg-grey-1"
        >
          <q-tab name="identity" icon="badge" label="Identidad Institucional" no-caps />
          <q-tab name="contact" icon="contact_phone" label="Contacto & Sedes" no-caps />
          <q-tab name="social" icon="share" label="Redes Sociales" no-caps />
          <q-tab name="appearance" icon="palette" label="Colores & Tema" no-caps />
          <q-tab name="seo" icon="travel_explore" label="SEO Global" no-caps />
        </q-tabs>
        <q-separator />

        <q-tab-panels v-model="tab" animated class="q-pa-md">
          <!-- PESTAÑA 1: IDENTIDAD INSTITUCIONAL -->
          <q-tab-panel name="identity" class="q-gutter-y-md">
            <div class="row items-center justify-between">
              <div>
                <div class="text-subtitle1 text-weight-bold text-dark">
                  Datos de Marca e Identidad Institucional
                </div>
                <div class="text-caption text-grey-7">
                  Sube un nuevo logotipo o ícono al servidor o elígelo desde la Biblioteca de Medios.
                </div>
              </div>
            </div>

            <div class="row q-col-gutter-lg">
              <div class="col-12 col-md-6">
                <q-input
                  v-model="settings.identity.site_name"
                  label="Nombre de la Institución"
                  outlined
                  dense
                />
              </div>
              <div class="col-12 col-md-6">
                <q-input
                  v-model="settings.identity.site_slogan"
                  label="Lema / Eslogan Institucional"
                  outlined
                  dense
                />
              </div>

              <!-- Logotipo Principal con Vista Previa Actual, Subida al Backend y Selector -->
              <div class="col-12 col-md-6">
                <q-card flat bordered class="q-pa-md rounded-borders bg-white">
                  <div class="row items-center q-gutter-x-xs q-mb-sm">
                    <q-icon name="image" color="primary" size="20px" />
                    <span class="text-subtitle2 text-weight-bold text-dark">
                      Logotipo Principal del Portal
                    </span>
                  </div>
                  <MediaPickerInput
                    v-model="settings.identity.site_logo"
                    label="Ruta o URL del Logotipo Principal"
                    show-large-preview
                  />
                </q-card>
              </div>

              <!-- Favicon / Ícono Institucional con Vista Previa Actual, Subida al Backend y Selector -->
              <div class="col-12 col-md-6">
                <q-card flat bordered class="q-pa-md rounded-borders bg-white">
                  <div class="row items-center q-gutter-x-xs q-mb-sm">
                    <q-icon name="stars" color="secondary" size="20px" />
                    <span class="text-subtitle2 text-weight-bold text-dark">
                      Favicon / Ícono de Pestaña del Navegador
                    </span>
                  </div>
                  <MediaPickerInput
                    v-model="settings.identity.site_favicon"
                    label="Ruta o URL del Favicon (.ico / .png)"
                    show-large-preview
                  />
                </q-card>
              </div>
            </div>
          </q-tab-panel>

          <!-- PESTAÑA 2: CONTACTO -->
          <q-tab-panel name="contact" class="q-gutter-y-md">
            <div class="text-subtitle1 text-weight-bold text-dark">
              Canales Oficiales de Atención
            </div>
            <div class="row q-col-gutter-md">
              <div class="col-12 col-md-4">
                <q-input
                  v-model="settings.contact.phone"
                  label="Teléfono Fijo / Central"
                  outlined
                  dense
                />
              </div>
              <div class="col-12 col-md-4">
                <q-input
                  v-model="settings.contact.whatsapp"
                  label="WhatsApp de Admisión"
                  outlined
                  dense
                />
              </div>
              <div class="col-12 col-md-4">
                <q-input
                  v-model="settings.contact.email"
                  label="Correo Electrónico de Informes"
                  outlined
                  dense
                />
              </div>
              <div class="col-12 col-md-8">
                <q-input
                  v-model="settings.contact.address"
                  label="Dirección del Campus Principal"
                  outlined
                  dense
                />
              </div>
              <div class="col-12 col-md-4">
                <q-input
                  v-model="settings.contact.hours"
                  label="Horario de Atención"
                  outlined
                  dense
                />
              </div>
            </div>
          </q-tab-panel>

          <!-- PESTAÑA 3: REDES SOCIALES -->
          <q-tab-panel name="social" class="q-gutter-y-md">
            <div class="text-subtitle1 text-weight-bold text-dark">
              Enlaces a Perfiles Oficiales
            </div>
            <div class="row q-col-gutter-md">
              <div class="col-12 col-md-6">
                <q-input
                  v-model="settings.social.facebook"
                  label="Página de Facebook"
                  outlined
                  dense
                  prefix="https://"
                />
              </div>
              <div class="col-12 col-md-6">
                <q-input
                  v-model="settings.social.instagram"
                  label="Perfil de Instagram"
                  outlined
                  dense
                  prefix="https://"
                />
              </div>
              <div class="col-12 col-md-6">
                <q-input
                  v-model="settings.social.youtube"
                  label="Canal de YouTube"
                  outlined
                  dense
                  prefix="https://"
                />
              </div>
              <div class="col-12 col-md-6">
                <q-input
                  v-model="settings.social.tiktok"
                  label="Cuenta de TikTok"
                  outlined
                  dense
                  prefix="https://"
                />
              </div>
            </div>
          </q-tab-panel>

          <!-- PESTAÑA 4: COLORES & TEMA -->
          <q-tab-panel name="appearance" class="q-gutter-y-md">
            <div class="text-subtitle1 text-weight-bold text-dark">
              Paleta Oficial de Colores Institucionales
            </div>
            <div class="row q-col-gutter-md">
              <div class="col-12 col-sm-6 col-md-3">
                <q-input
                  v-model="settings.appearance.primary"
                  label="Color Primario (Azul Institucional)"
                  outlined
                  dense
                >
                  <template #append>
                    <q-icon name="colorize" class="cursor-pointer">
                      <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                        <q-color v-model="settings.appearance.primary" />
                      </q-popup-proxy>
                    </q-icon>
                  </template>
                </q-input>
              </div>

              <div class="col-12 col-sm-6 col-md-3">
                <q-input
                  v-model="settings.appearance.secondary"
                  label="Color Secundario (Rojo / Naranja Institucional)"
                  outlined
                  dense
                >
                  <template #append>
                    <q-icon name="colorize" class="cursor-pointer">
                      <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                        <q-color v-model="settings.appearance.secondary" />
                      </q-popup-proxy>
                    </q-icon>
                  </template>
                </q-input>
              </div>

              <div class="col-12 col-sm-6 col-md-3">
                <q-input
                  v-model="settings.appearance.accent"
                  label="Color Intermedio (Azul Real)"
                  outlined
                  dense
                >
                  <template #append>
                    <q-icon name="colorize" class="cursor-pointer">
                      <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                        <q-color v-model="settings.appearance.accent" />
                      </q-popup-proxy>
                    </q-icon>
                  </template>
                </q-input>
              </div>

              <div class="col-12 col-sm-6 col-md-3">
                <q-input
                  v-model="settings.appearance.warning"
                  label="Oro Académico (Acentos)"
                  outlined
                  dense
                >
                  <template #append>
                    <q-icon name="colorize" class="cursor-pointer">
                      <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                        <q-color v-model="settings.appearance.warning" />
                      </q-popup-proxy>
                    </q-icon>
                  </template>
                </q-input>
              </div>
            </div>
          </q-tab-panel>

          <!-- PESTAÑA 5: SEO GLOBAL -->
          <q-tab-panel name="seo" class="q-gutter-y-md">
            <div class="text-subtitle1 text-weight-bold text-dark">
              Optimización para Motores de Búsqueda (SEO en Español)
            </div>
            <div class="row q-col-gutter-md">
              <div class="col-12">
                <q-input
                  v-model="settings.seo.meta_title"
                  label="Título Global por Defecto (Meta Title)"
                  outlined
                  dense
                  hint="Ej: IESTP DEPERU MASTER | Carreras Técnicas Profesionales Licenciadas"
                />
              </div>
              <div class="col-12">
                <q-input
                  v-model="settings.seo.meta_description"
                  label="Descripción Global (Meta Description)"
                  type="textarea"
                  rows="3"
                  outlined
                  dense
                  hint="Texto descriptivo en español que aparece en los resultados de Google"
                />
              </div>
              <div class="col-12">
                <q-input
                  v-model="settings.seo.google_analytics_id"
                  label="ID de Medición Google Analytics (GA4 / GTM)"
                  outlined
                  dense
                  placeholder="G-XXXXXXXXXX"
                />
              </div>
            </div>
          </q-tab-panel>
        </q-tab-panels>
      </q-card>
    </q-page>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import MediaPickerInput from '@/components/admin/builder/MediaPickerInput.vue'
import { useNotification } from '@/composables/useNotification'
import { useSettingsApi } from '@/composables/useSettingsApi'
import { useAuthStore } from '@/stores/auth'
import { usePublicCmsStore } from '@/stores/public-cms'

const notify = useNotification()
const authStore = useAuthStore()
const publicCmsStore = usePublicCmsStore()
const { getSettingsRequest, updateSettingsRequest } = useSettingsApi()

const tab = ref('identity')
const saving = ref(false)

const settings = reactive({
  identity: {
    site_name: 'IESTP DEPERU MASTER',
    site_slogan: 'Donde tu talento se convierte en profesión',
    site_logo: '/img/logos/logo-inner.png',
    site_favicon: '/favicon.ico',
  },
  contact: {
    phone: '(01) 456-7890',
    whatsapp: '+51 987 654 321',
    email: 'informes@deperumaster.edu.pe',
    address: 'Av. Universitaria s/n, Lircay, Angaraes - Huancavelica',
    hours: 'Lunes a Viernes de 08:00 a 18:00 hrs',
  },
  social: {
    facebook: 'facebook.com/deperumaster',
    instagram: 'instagram.com/deperumaster',
    youtube: 'youtube.com/@deperumaster',
    tiktok: 'tiktok.com/@deperumaster',
  },
  appearance: {
    primary: '#003278',
    secondary: '#ED3237',
    accent: '#004B87',
    warning: '#E5A93C',
  },
  seo: {
    meta_title: 'IESTP DEPERU MASTER | Carreras Técnicas Profesionales Licenciadas',
    meta_description:
      'Instituto de Educación Superior Tecnológico Privado DEPERU MASTER. Formando profesionales técnicos líderes en tecnología, gestión y comunicaciones en Huancavelica.',
    google_analytics_id: 'G-DPM2026CMS',
  },
})

function hydrateSettings(grouped) {
  if (!grouped || typeof grouped !== 'object') return
  Object.keys(settings).forEach((group) => {
    if (grouped[group] && typeof grouped[group] === 'object') {
      Object.assign(settings[group], grouped[group])
    }
  })
}

onMounted(async () => {
  try {
    await authStore.ensureDevSession()
    const grouped = await getSettingsRequest()
    hydrateSettings(grouped)
  } catch (err) {
    notify.warning(
      err?.response?.data?.message || 'No se pudieron cargar los ajustes desde el servidor.',
    )
  }
})

async function saveSettings() {
  saving.value = true
  try {
    await authStore.ensureDevSession()
    const payload = JSON.parse(JSON.stringify(settings))
    const updated = await updateSettingsRequest(payload)
    if (updated) {
      hydrateSettings(updated)
    }
    // Refrescar el store público para que el Navbar y Footer reflejen de inmediato el nuevo logo/datos
    await publicCmsStore.fetchPublicSettings({ force: true })
    notify.success('Configuración global guardada en base de datos exitosamente')
  } catch (err) {
    notify.error(
      err?.response?.data?.message ||
        'Error al guardar la configuración en el servidor. Verifica la conexión con el backend.',
    )
  } finally {
    saving.value = false
  }
}
</script>

<style scoped>
.rounded-borders {
  border-radius: 12px;
}
</style>
