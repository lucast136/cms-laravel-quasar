<template>
  <PublicLayout>
    <PageHeader
      title="Términos, Condiciones y Políticas del Portal"
      subtitle="Políticas de Privacidad, Uso de Cookies, Google Analytics y Optimización Web"
      bg-image="/img/bg/bg-04.jpg"
    />

    <section class="section-padding bg-very-light-gray">
      <div class="container-custom">
        <div class="row q-col-gutter-xl">
          <!-- Columna Lateral: Índice Rápido de Navegación -->
          <div class="col-12 col-md-4 col-lg-3">
            <div class="sticky-sidebar bg-white q-pa-lg rounded-borders shadow-1">
              <h3 class="text-subtitle1 font-weight-700 text-dark q-mt-none q-mb-md">
                Índice de Políticas
              </h3>
              <q-list dense separator class="legal-nav-list">
                <q-item clickable :active="activeSection === 'terminos'" active-class="text-primary font-weight-700" @click="scrollToSection('terminos')">
                  <q-item-section avatar><q-icon name="gavel" size="xs" color="secondary" /></q-item-section>
                  <q-item-section>1. Términos y Condiciones</q-item-section>
                </q-item>
                <q-item clickable :active="activeSection === 'privacidad'" active-class="text-primary font-weight-700" @click="scrollToSection('privacidad')">
                  <q-item-section avatar><q-icon name="security" size="xs" color="secondary" /></q-item-section>
                  <q-item-section>2. Privacidad y Datos (Ley 29733)</q-item-section>
                </q-item>
                <q-item clickable :active="activeSection === 'cookies'" active-class="text-primary font-weight-700" @click="scrollToSection('cookies')">
                  <q-item-section avatar><q-icon name="cookie" size="xs" color="secondary" /></q-item-section>
                  <q-item-section>3. Política de Cookies</q-item-section>
                </q-item>
                <q-item clickable :active="activeSection === 'analytics'" active-class="text-primary font-weight-700" @click="scrollToSection('analytics')">
                  <q-item-section avatar><q-icon name="analytics" size="xs" color="secondary" /></q-item-section>
                  <q-item-section>4. Google Analytics y Métricas</q-item-section>
                </q-item>
                <q-item clickable :active="activeSection === 'seo'" active-class="text-primary font-weight-700" @click="scrollToSection('seo')">
                  <q-item-section avatar><q-icon name="travel_explore" size="xs" color="secondary" /></q-item-section>
                  <q-item-section>5. Optimización SEO y Rastreo</q-item-section>
                </q-item>
                <q-item clickable :active="activeSection === 'preferencias'" active-class="text-primary font-weight-700" @click="scrollToSection('preferencias')">
                  <q-item-section avatar><q-icon name="tune" size="xs" color="secondary" /></q-item-section>
                  <q-item-section>6. Configurar Consentimiento</q-item-section>
                </q-item>
              </q-list>

              <div class="q-mt-lg q-pt-md border-top text-caption text-grey-7">
                <div><strong>Última actualización:</strong></div>
                <div>Noviembre 2026</div>
                <div class="q-mt-xs">IESTP DEPERU MASTER</div>
              </div>
            </div>
          </div>

          <!-- Columna Principal: Contenido Detallado -->
          <div class="col-12 col-md-8 col-lg-9">
            <div class="legal-content bg-white q-pa-xl rounded-borders shadow-1">
              <!-- Introducción Institucional -->
              <div class="q-mb-xl p-intro">
                <p class="text-body1 text-grey-8 leading-relaxed">
                  Bienvenido al portal web oficial del <strong>Instituto de Educación Superior Tecnológico Privado DEPERU MASTER</strong>.
                  El presente documento establece las condiciones de uso, los estándares de privacidad conforme al marco legal de la República del Perú
                  (Ley N° 29733 y D.S. N° 003-2013-JUS), y los lineamientos de recopilación de métricas técnicas mediante <strong>Google Analytics</strong>
                  y cookies de rendimiento web.
                </p>
              </div>

              <!-- 1. Términos y Condiciones -->
              <section id="terminos" class="legal-section q-mb-xl">
                <h2 class="text-h5 font-weight-800 text-dark q-mb-md section-title">
                  <span class="number-tag">1</span> Términos y Condiciones de Uso
                </h2>
                <div class="text-body1 text-grey-8 q-gutter-y-sm">
                  <p>
                    El acceso y navegación en este sitio web atribuye la condición de <strong>USUARIO</strong> e implica la aceptación plena de las presentes estipulaciones. Si el usuario no está de acuerdo con alguno de los términos, deberá abstenerse de utilizar el portal.
                  </p>
                  <p>
                    <strong>Propiedad Intelectual:</strong> Todos los contenidos, logotipos, emblemas institucionales, fotografías, textos, temarios de carreras y material académico multimedia son propiedad exclusiva del IESTP DEPERU MASTER o de sus respectivos titulares licenciantes, encontrándose protegidos por las leyes de propiedad intelectual y derechos de autor vigentes en el Perú e internacionalmente.
                  </p>
                  <p>
                    <strong>Uso Permitido:</strong> Queda prohibida la reproducción, distribución o modificación no autorizada de los contenidos con fines comerciales ajenos a la formación educativa del usuario.
                  </p>
                </div>
              </section>

              <!-- 2. Política de Privacidad y Datos Personales -->
              <section id="privacidad" class="legal-section q-mb-xl">
                <h2 class="text-h5 font-weight-800 text-dark q-mb-md section-title">
                  <span class="number-tag">2</span> Protección de Datos Personales (Ley N° 29733)
                </h2>
                <div class="text-body1 text-grey-8 q-gutter-y-sm">
                  <p>
                    En observancia de la <em>Ley N° 29733 (Ley de Protección de Datos Personales del Perú)</em> y su Reglamento, el IESTP DEPERU MASTER garantiza que los datos personales recabados a través de formularios de admisión, contacto o consultas académicas serán tratados bajo estrictas medidas de seguridad técnica y confidencialidad.
                  </p>
                  <p>
                    <strong>Finalidad del Tratamiento:</strong> Los datos se emplearán exclusivamente para:
                  </p>
                  <ul class="styled-list">
                    <li>Brindar información sobre procesos de admisión, carreras técnicas, matrícula y certificaciones.</li>
                    <li>Atención de consultas, solicitudes y trámites en mesa de partes institucional.</li>
                    <li>Comunicaciones oficiales del instituto relativas al calendario académico y actividades formativas.</li>
                  </ul>
                  <p>
                    <strong>Derechos ARCO:</strong> Todo titular de datos puede ejercer en cualquier momento sus derechos de Acceso, Rectificación, Cancelación y Oposición remitiendo una solicitud a la dirección de correo institucional: <strong>informes@deperumaster.edu.pe</strong>.
                  </p>
                </div>
              </section>

              <!-- 3. Política de Cookies -->
              <section id="cookies" class="legal-section q-mb-xl">
                <h2 class="text-h5 font-weight-800 text-dark q-mb-md section-title">
                  <span class="number-tag">3</span> Política de Uso de Cookies
                </h2>
                <div class="text-body1 text-grey-8 q-gutter-y-sm">
                  <p>
                    Una cookie es un pequeño archivo de texto almacenado en su navegador que permite recordar sus preferencias, mejorar los tiempos de carga y brindarle una experiencia fluida. Nuestro portal clasifica las cookies en las siguientes categorías:
                  </p>

                  <div class="row q-col-gutter-md q-my-md">
                    <div class="col-12 col-sm-6">
                      <div class="cookie-card bg-light-blue q-pa-md rounded-borders full-height">
                        <div class="row items-center q-mb-xs">
                          <q-icon name="lock" color="primary" class="q-mr-xs" />
                          <strong class="text-primary">Cookies Técnicas Esenciales</strong>
                        </div>
                        <p class="text-caption text-grey-8 q-mb-none">
                          Requeridas para la navegación segura, autenticación en panel administrativo y gestión de sesión básica. No pueden ser deshabilitadas.
                        </p>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6">
                      <div class="cookie-card bg-light-green q-pa-md rounded-borders full-height">
                        <div class="row items-center q-mb-xs">
                          <q-icon name="insights" color="secondary" class="q-mr-xs" />
                          <strong class="text-dark">Cookies de Rendimiento y Analítica</strong>
                        </div>
                        <p class="text-caption text-grey-8 q-mb-none">
                          Gestionadas mediante Google Analytics para analizar el comportamiento anónimo de visitas y optimizar el rendimiento de las páginas.
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </section>

              <!-- 4. Google Analytics y Métricas de Navegación -->
              <section id="analytics" class="legal-section q-mb-xl">
                <h2 class="text-h5 font-weight-800 text-dark q-mb-md section-title">
                  <span class="number-tag">4</span> Google Analytics y Métricas Web
                </h2>
                <div class="text-body1 text-grey-8 q-gutter-y-sm">
                  <p>
                    Este sitio web utiliza <strong>Google Analytics</strong> (servicio de analítica web prestado por Google LLC / Google Ireland Limited) con anonimización de dirección IP activada. Google Analytics emplea cookies (como <code>_ga</code>, <code>_ga_*</code>) para recopilar estadísticas agregadas y anónimas que nos permiten:
                  </p>
                  <ul class="styled-list">
                    <li>Conocer qué secciones, carreras o artículos informativos son más consultados por los estudiantes y postulantes.</li>
                    <li>Monitorear la velocidad de carga y detectar posibles fallas en dispositivos móviles o navegadores.</li>
                    <li>Optimizar la estructura informativa de las páginas para facilitar la búsqueda de información académica.</li>
                  </ul>
                  <div class="q-banner bg-amber-1 text-dark rounded-borders q-pa-md q-my-md border-amber">
                    <div class="row items-center no-wrap">
                      <q-icon name="info" color="warning" size="md" class="q-mr-md" />
                      <div class="text-body2">
                        <strong>Transparencia y Privacidad:</strong> Los datos de Google Analytics se procesan de forma anonimizada y agregada. No recopilamos nombres, contraseñas ni datos bancarios mediante este sistema. Usted puede revocar o activar el consentimiento de Google Analytics en cualquier momento desde el panel inferior.
                      </div>
                    </div>
                  </div>
                </div>
              </section>

              <!-- 5. Optimización SEO y Rastreo por Motores de Búsqueda -->
              <section id="seo" class="legal-section q-mb-xl">
                <h2 class="text-h5 font-weight-800 text-dark q-mb-md section-title">
                  <span class="number-tag">5</span> Optimización SEO y Rastreo Técnico
                </h2>
                <div class="text-body1 text-grey-8 q-gutter-y-sm">
                  <p>
                    El portal público del IESTP DEPERU MASTER opera bajo arquitectura <strong>SSR (Server-Side Rendering)</strong>, lo cual permite que motores de búsqueda como Google o Bing indexen de forma precisa la oferta académica, comunicados y planes de estudio licenciados.
                  </p>
                  <p>
                    Se hace uso de metadatos abiertos (Open Graph y Schema.org) para que, al compartir un enlace institucional en redes sociales o mensajería, se muestre de manera fidedigna el título oficial, resumen y logotipo del instituto.
                  </p>
                </div>
              </section>

              <!-- 6. Panel Interactivo de Preferencias y Consentimiento -->
              <section id="preferencias" class="legal-section q-pt-md">
                <h2 class="text-h5 font-weight-800 text-dark q-mb-md section-title">
                  <span class="number-tag">6</span> Gestionar Preferencias de Consentimiento
                </h2>
                <p class="text-body1 text-grey-8 q-mb-lg">
                  A continuación puede consultar el estado de sus preferencias de cookies en este navegador y modificarlas según su decisión:
                </p>

                <div class="preference-box q-pa-lg rounded-borders bg-light-gray border-light">
                  <div class="row items-center justify-between q-py-sm border-bottom">
                    <div>
                      <div class="text-subtitle2 text-weight-bold text-dark">Cookies Esenciales del Sistema</div>
                      <div class="text-caption text-grey-7">Necesarias para el correcto funcionamiento técnico del portal.</div>
                    </div>
                    <q-badge color="grey-6" label="Siempre Activas" class="q-px-sm q-py-xs" />
                  </div>

                  <div class="row items-center justify-between q-py-md border-bottom">
                    <div>
                      <div class="text-subtitle2 text-weight-bold text-dark">Google Analytics y Métricas de Rendimiento</div>
                      <div class="text-caption text-grey-7">Ayuda a mejorar la experiencia del usuario analizando el tráfico de forma anónima.</div>
                    </div>
                    <q-toggle v-model="consentPreferences.analytics" color="primary" />
                  </div>

                  <div class="row items-center justify-between q-py-md">
                    <div>
                      <div class="text-subtitle2 text-weight-bold text-dark">Preferencias de Experiencia y SEO</div>
                      <div class="text-caption text-grey-7">Permite recordar preferencias de vista e interactividad del portal.</div>
                    </div>
                    <q-toggle v-model="consentPreferences.preferences" color="primary" />
                  </div>

                  <div class="row items-center justify-end q-gutter-sm q-mt-md">
                    <q-btn
                      label="Guardar Preferencias"
                      color="primary"
                      unelevated
                      no-caps
                      icon="save"
                      @click="savePreferences"
                    />
                    <q-btn
                      label="Aceptar Todas"
                      color="secondary"
                      unelevated
                      no-caps
                      icon="done_all"
                      @click="acceptAll"
                    />
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useMeta, useQuasar } from 'quasar'
import PublicLayout from '@/layouts/PublicLayout.vue'
import PageHeader from '@/components/public/common/PageHeader.vue'

const $q = useQuasar()
const activeSection = ref('terminos')

const consentPreferences = ref({
  essential: true,
  analytics: true,
  preferences: true,
})

const COOKIE_STORAGE_KEY = 'dpm_cookie_consent'

onMounted(() => {
  if (typeof window !== 'undefined') {
    try {
      const stored = localStorage.getItem(COOKIE_STORAGE_KEY)
      if (stored) {
        const parsed = JSON.parse(stored)
        consentPreferences.value.analytics = parsed.analytics !== false
        consentPreferences.value.preferences = parsed.preferences !== false
      }
    } catch {
      // Ignorar fallback
    }
  }
})

function savePreferences() {
  if (typeof window !== 'undefined') {
    const payload = {
      essential: true,
      analytics: consentPreferences.value.analytics,
      preferences: consentPreferences.value.preferences,
      updated_at: new Date().toISOString(),
    }
    localStorage.setItem(COOKIE_STORAGE_KEY, JSON.stringify(payload))
    window.dispatchEvent(new CustomEvent('dpm-cookie-consent-updated', { detail: payload }))
    $q.notify({
      type: 'positive',
      message: 'Preferencias de cookies y Google Analytics actualizadas correctamente.',
      position: 'top-right',
      timeout: 3000,
    })
  }
}

function acceptAll() {
  consentPreferences.value.analytics = true
  consentPreferences.value.preferences = true
  savePreferences()
}

function scrollToSection(id) {
  activeSection.value = id
  if (typeof document !== 'undefined') {
    const element = document.getElementById(id)
    if (element) {
      element.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }
  }
}

// Inyección SSR y reactiva de Metadatos SEO en Español
useMeta(() => ({
  title: 'Términos, Privacidad, Cookies y Google Analytics | IESTP DEPERU MASTER',
  meta: {
    description: {
      name: 'description',
      content:
        'Conoce los términos y condiciones de uso, política de protección de datos personales (Ley 29733), directrices de cookies y Google Analytics del IESTP DEPERU MASTER.',
    },
    keywords: {
      name: 'keywords',
      content:
        'terminos y condiciones deperu master, politica de cookies, ley 29733 peru, google analytics educacion, privacidad institucional',
    },
    ogTitle: { property: 'og:title', content: 'Términos, Condiciones y Políticas Institucionales | IESTP DEPERU MASTER' },
    ogDescription: {
      property: 'og:description',
      content: 'Políticas de privacidad, tratamiento de datos personales, uso de cookies y métricas web oficiales del IESTP DEPERU MASTER.',
    },
  },
}))
</script>

<style scoped lang="scss">
@import '@/css/template/variables';

.sticky-sidebar {
  position: sticky;
  top: 100px;
}

.legal-content {
  line-height: 1.8;
}

.section-title {
  display: flex;
  align-items: center;
  gap: 12px;
  border-bottom: 2px solid #e2e8f0;
  padding-bottom: 12px;

  .number-tag {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background-color: $theme-primary;
    color: #ffffff;
    font-size: 15px;
    font-weight: 700;
  }
}

.legal-nav-list .q-item {
  border-radius: 6px;
  margin-bottom: 4px;
}

.styled-list {
  padding-left: 20px;
  li {
    margin-bottom: 8px;
    color: #475569;
  }
}

.bg-light-blue {
  background-color: #f0f7ff;
  border: 1px solid #cce3ff;
}

.bg-light-green {
  background-color: #f3fbf7;
  border: 1px solid #d0f0e0;
}

.bg-light-gray {
  background-color: #f8fafc;
}

.border-light {
  border: 1px solid #e2e8f0;
}

.border-top {
  border-top: 1px solid #e2e8f0;
}

.border-bottom {
  border-bottom: 1px solid #e2e8f0;
}

.border-amber {
  border-left: 4px solid #f59e0b;
}

.p-intro {
  border-left: 4px solid $theme-secondary;
  padding-left: 18px;
}
</style>

