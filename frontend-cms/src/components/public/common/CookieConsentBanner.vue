<template>
  <Transition name="banner-slide">
    <div
      v-if="isVisible"
      class="cookie-consent-bar"
      role="dialog"
      aria-live="polite"
      aria-label="Aviso de privacidad, cookies y Google Analytics"
    >
      <div class="container-custom">
        <div class="row items-center justify-between q-col-gutter-md">
          <!-- Información y Mensaje -->
          <div class="col-12 col-lg-8">
            <div class="row items-start no-wrap">
              <q-icon name="cookie" size="36px" color="secondary" class="q-mr-md gt-xs q-mt-xs" />
              <div>
                <h4 class="text-subtitle1 font-weight-700 text-white q-mt-none q-mb-xs">
                  Aviso de Cookies, Google Analytics y Privacidad
                </h4>
                <p class="text-body2 text-grey-4 q-mb-none leading-relaxed">
                  Utilizamos cookies técnicas necesarias para el funcionamiento del portal y <strong>Google Analytics</strong>
                  para analizar el tráfico de forma anónima, mejorar la experiencia educativa y optimizar la indexación SEO de nuestros contenidos.
                  Al continuar navegando, usted acepta su uso conforme a la
                  <router-link to="/terminos-y-politicas" class="text-secondary text-weight-bold hover-underline">
                    Ley N° 29733 de Protección de Datos Personales
                  </router-link>
                  y nuestra Política Integral.
                </p>
              </div>
            </div>
          </div>

          <!-- Botones de Acción -->
          <div class="col-12 col-lg-4 text-center text-lg-right">
            <div class="row items-center justify-center justify-lg-end q-gutter-sm">
              <!-- <q-btn
                flat
                dense
                no-caps
                color="grey-4"
                label="Configurar"
                icon="tune"
                class="q-px-sm"
                @click="openModal = true"
              />
              <q-btn
                outline
                no-caps
                color="white"
                label="Solo Necesarias"
                class="q-px-md"
                @click="acceptNecessary"
              /> -->
              <q-btn
                unelevated
                no-caps
                color="secondary"
                label="Aceptar Todas"
                class="q-px-md text-weight-bold"
                @click="acceptAll"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>

  <!-- Modal de Configuración Granular -->
  <q-dialog v-model="openModal" persistent>
    <q-card style="max-width: 580px; width: 100%" class="rounded-borders q-pa-sm">
      <q-card-section class="row items-center justify-between border-bottom q-pb-md">
        <div class="text-h6 font-weight-700 text-dark row items-center">
          <q-icon name="tune" color="primary" class="q-mr-sm" />
          Configurar Consentimiento
        </div>
        <q-btn flat round dense icon="close" v-close-popup />
      </q-card-section>

      <q-card-section class="q-pt-md text-body2 text-grey-8">
        <p class="q-mb-md">
          Seleccione qué tipos de cookies y herramientas de medición autoriza para su navegación en el portal del IESTP DEPERU MASTER:
        </p>

        <!-- Esenciales -->
        <div class="q-pa-md bg-light-gray rounded-borders q-mb-sm">
          <div class="row items-center justify-between">
            <div>
              <div class="text-weight-bold text-dark">Cookies Técnicas Esenciales</div>
              <div class="text-caption text-grey-7">Indispensables para la navegación y seguridad del sitio.</div>
            </div>
            <q-badge color="grey-7" label="Requerido" class="q-px-sm q-py-xs" />
          </div>
        </div>

        <!-- Google Analytics -->
        <div class="q-pa-md bg-light-gray rounded-borders q-mb-sm">
          <div class="row items-center justify-between">
            <div class="col-9">
              <div class="text-weight-bold text-dark">Google Analytics y Métricas</div>
              <div class="text-caption text-grey-7">
                Mide de forma anónima visitas, tiempos de carga y contenidos académicos más consultados.
              </div>
            </div>
            <q-toggle v-model="modalPreferences.analytics" color="primary" />
          </div>
        </div>

        <!-- Optimización y Preferencias -->
        <div class="q-pa-md bg-light-gray rounded-borders">
          <div class="row items-center justify-between">
            <div class="col-9">
              <div class="text-weight-bold text-dark">Preferencias y Experiencia SEO</div>
              <div class="text-caption text-grey-7">
                Guarda configuraciones visuales y facilita la indexación rápida de páginas visitadas.
              </div>
            </div>
            <q-toggle v-model="modalPreferences.preferences" color="primary" />
          </div>
        </div>

        <div class="q-mt-md text-caption text-grey-7">
          Para más detalles, revise nuestra
          <router-link to="/terminos-y-politicas" class="text-primary text-weight-bold" @click="openModal = false">
            página oficial de Términos y Políticas
          </router-link>.
        </div>
      </q-card-section>

      <q-card-actions align="right" class="q-pa-md border-top">
        <q-btn flat no-caps color="grey-8" label="Cancelar" v-close-popup />
        <q-btn unelevated no-caps color="primary" label="Guardar Elección" @click="saveModalPreferences" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const isVisible = ref(false)
const openModal = ref(false)

const COOKIE_STORAGE_KEY = 'dpm_cookie_consent'

const modalPreferences = ref({
  essential: true,
  analytics: true,
  preferences: true,
})

onMounted(() => {
  if (typeof window !== 'undefined') {
    try {
      const stored = localStorage.getItem(COOKIE_STORAGE_KEY)
      if (!stored) {
        // Primera vez que ingresa al sitio: mostrar aviso con un ligero retraso para no colisionar con la carga inicial
        window.setTimeout(() => {
          isVisible.value = true
        }, 800)
      } else {
        const parsed = JSON.parse(stored)
        modalPreferences.value.analytics = parsed.analytics !== false
        modalPreferences.value.preferences = parsed.preferences !== false
      }
    } catch {
      isVisible.value = true
    }

    // Escuchar si el usuario actualiza preferencias desde la página legal
    window.addEventListener('dpm-cookie-consent-updated', (event) => {
      if (event.detail) {
        modalPreferences.value.analytics = event.detail.analytics !== false
        modalPreferences.value.preferences = event.detail.preferences !== false
        isVisible.value = false
      }
    })
  }
})

function saveConsent(payload) {
  if (typeof window !== 'undefined') {
    localStorage.setItem(COOKIE_STORAGE_KEY, JSON.stringify(payload))
    window.dispatchEvent(new CustomEvent('dpm-cookie-consent-updated', { detail: payload }))
  }
  isVisible.value = false
  openModal.value = false
}

function acceptAll() {
  saveConsent({
    essential: true,
    analytics: true,
    preferences: true,
    updated_at: new Date().toISOString(),
  })
}

// function acceptNecessary() {
//   saveConsent({
//     essential: true,
//     analytics: false,
//     preferences: false,
//     updated_at: new Date().toISOString(),
//   })
// }

function saveModalPreferences() {
  saveConsent({
    essential: true,
    analytics: modalPreferences.value.analytics,
    preferences: modalPreferences.value.preferences,
    updated_at: new Date().toISOString(),
  })
}
</script>

<style scoped lang="scss">
@import '@/css/template/variables';

.cookie-consent-bar {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 9998;
  background-color: rgba(0, 50, 120, 0.96);
  backdrop-filter: blur(8px);
  color: #ffffff;
  padding: 16px 0;
  box-shadow: 0 -4px 25px rgba(0, 0, 0, 0.2);
  border-top: 2px solid $theme-secondary;
}

.hover-underline:hover {
  text-decoration: underline;
}

.bg-light-gray {
  background-color: #f8fafc;
  border: 1px solid #e2e8f0;
}

.border-bottom {
  border-bottom: 1px solid #e2e8f0;
}

.border-top {
  border-top: 1px solid #e2e8f0;
}

.banner-slide-enter-active,
.banner-slide-leave-active {
  transition: transform 0.4s ease, opacity 0.4s ease;
}

.banner-slide-enter-from,
.banner-slide-leave-to {
  transform: translateY(100%);
  opacity: 0;
}
</style>

