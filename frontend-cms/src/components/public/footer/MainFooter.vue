<template>
  <footer class="main-footer bg-dark text-white">
    <div class="container-custom q-py-xl">
      <div class="row q-col-gutter-xl">
        <!-- Columna 1: Info Institucional -->
        <div class="col-12 col-md-4">
          <div class="footer-logo q-mb-md">
            <img
              v-if="resolvedLogo"
              :src="resolvedLogo"
              :alt="identity.site_name || identity.site_title || 'IESTP DEPERU MASTER'"
              class="footer-logo-img"
            />
          </div>
          <p class="text-grey-4 text-body2 q-mb-lg">
            {{
              identity.site_slogan ||
              identity.site_description ||
              'Instituto de Educación Superior Tecnológico Privado DEPERU MASTER. Formando profesionales técnicos altamente capacitados y competitivos para el mercado global.'
            }}
          </p>
          <div class="row items-center q-gutter-x-sm">
            <a
              v-if="socialLinks.facebook"
              :href="socialLinks.facebook"
              target="_blank"
              class="footer-social-btn"
            >
              <q-icon name="fab fa-facebook-f" />
            </a>
            <a
              v-if="socialLinks.instagram"
              :href="socialLinks.instagram"
              target="_blank"
              class="footer-social-btn"
            >
              <q-icon name="fab fa-instagram" />
            </a>
            <a
              v-if="socialLinks.linkedin"
              :href="socialLinks.linkedin"
              target="_blank"
              class="footer-social-btn"
            >
              <q-icon name="fab fa-linkedin-in" />
            </a>
            <a
              v-if="socialLinks.youtube"
              :href="socialLinks.youtube"
              target="_blank"
              class="footer-social-btn"
            >
              <q-icon name="fab fa-youtube" />
            </a>
          </div>
        </div>

        <!-- Columna 2: Enlaces Rápidos (Dinámico desde /api/v1/menus/footer_quick_links) -->
        <div class="col-12 col-sm-6 col-md-2">
          <h5 class="footer-title">Enlaces Rápidos</h5>
          <ul class="footer-links list-none q-pa-none q-ma-none">
            <li v-for="item in quickLinks" :key="item.url">
              <router-link :to="item.url">{{ item.title }}</router-link>
            </li>
          </ul>
        </div>

        <!-- Columna 3: Carreras Destacadas (Dinámico desde /api/v1/menus/footer_courses) -->
        <div class="col-12 col-sm-6 col-md-3">
          <h5 class="footer-title">Carreras Populares</h5>
          <ul class="footer-links list-none q-pa-none q-ma-none">
            <li v-for="item in coursesLinks" :key="item.url">
              <router-link :to="item.url">{{ item.title }}</router-link>
            </li>
          </ul>
        </div>

        <!-- Columna 4: Contacto y Boletín -->
        <div class="col-12 col-md-3">
          <h5 class="footer-title">Contacto Directo</h5>
          <div class="contact-info q-gutter-y-sm text-grey-4 text-body2 q-mb-md">
            <div class="row items-center no-wrap">
              <q-icon name="place" color="secondary" size="sm" class="q-mr-sm" />
              <span>{{ contactInfo.address || 'Av. Principal s/n, Lircay - Huancavelica' }}</span>
            </div>
            <div class="row items-center no-wrap">
              <q-icon name="call" color="secondary" size="sm" class="q-mr-sm" />
              <span>{{ contactInfo.phone || contactInfo.contact_phone || '(01) 456-7890' }}</span>
            </div>
            <div class="row items-center no-wrap">
              <q-icon name="mail" color="secondary" size="sm" class="q-mr-sm" />
              <span>{{ contactInfo.email || contactInfo.contact_email || 'informes@deperumaster.edu.pe' }}</span>
            </div>
          </div>

          <h6 class="text-subtitle2 q-mb-sm text-white font-weight-700">Mesa de Partes</h6>
          <router-link to="/contacto" class="butn sm full-width text-center">
            <span class="label">Escríbenos Ahora</span>
          </router-link>
        </div>
      </div>
    </div>

    <!-- Copyright -->
    <CopyrightBar />
  </footer>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import CopyrightBar from './CopyrightBar.vue'
import { usePublicCmsStore } from '@/stores/public-cms'

const publicCmsStore = usePublicCmsStore()

const DEFAULT_QUICK_LINKS = [
  { title: 'Nosotros', url: '/nosotros' },
  { title: 'Carreras', url: '/carreras' },
  { title: 'Admisión 2026', url: '/admision' },
  { title: 'Docentes', url: '/docentes' },
  { title: 'Noticias', url: '/noticias' },
  { title: 'Contacto', url: '/contacto' },
]

const DEFAULT_COURSES_LINKS = [
  { title: 'Desarrollo de Software y TI', url: '/carreras' },
  { title: 'Administración de Redes', url: '/carreras' },
  { title: 'Diseño y Animación Digital', url: '/carreras' },
  { title: 'Contabilidad y Finanzas', url: '/carreras' },
]

const quickLinks = computed(() => {
  const menu = publicCmsStore.menusByLocation.footer_quick_links
  if (menu?.items && Array.isArray(menu.items) && menu.items.length > 0) {
    return menu.items.map((i) => ({ title: i.title, url: i.url || `/${i.slug || ''}` }))
  }
  return DEFAULT_QUICK_LINKS
})

const coursesLinks = computed(() => {
  const menu = publicCmsStore.menusByLocation.footer_courses
  if (menu?.items && Array.isArray(menu.items) && menu.items.length > 0) {
    return menu.items.map((i) => ({ title: i.title, url: i.url || `/${i.slug || ''}` }))
  }
  return DEFAULT_COURSES_LINKS
})

const contactInfo = computed(() => publicCmsStore.publicSettings?.contact || {})
const socialLinks = computed(() => publicCmsStore.publicSettings?.social || {})
const identity = computed(() => publicCmsStore.publicSettings?.identity || {})
const resolvedLogo = computed(() => publicCmsStore.resolvedSiteLogo)

onMounted(() => {
  publicCmsStore.fetchMenu('footer_quick_links')
  publicCmsStore.fetchMenu('footer_courses')
})
</script>

<style scoped lang="scss">
@import '@/css/template/variables';

.main-footer {
  background-color: $theme-dark;
  position: relative;
}

.footer-logo-img {
  max-height: 48px;
  filter: brightness(0) invert(1);
}

.footer-title {
  font-size: 18px;
  font-weight: 700;
  color: #ffffff;
  margin-bottom: 22px;
  position: relative;
  padding-bottom: 10px;

  &::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 35px;
    height: 2px;
    background-color: $theme-secondary;
  }
}

.footer-links {
  list-style: none;
  li {
    margin-bottom: 12px;
    a {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      transition: all 0.3s ease;
      font-size: 14px;
      &:hover {
        color: $theme-primary;
        padding-left: 6px;
      }
    }
  }
}

.footer-social-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 38px;
  height: 38px;
  border-radius: 50%;
  background-color: rgba(255, 255, 255, 0.08);
  color: #ffffff;
  text-decoration: none;
  transition: all 0.3s ease;

  &:hover {
    background-color: $theme-secondary;
    color: #ffffff;
    transform: translateY(-3px);
  }
}
</style>
