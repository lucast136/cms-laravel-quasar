<template>
  <div
    :id="sectionSettings?.custom_id || undefined"
    class="hero-slider-wrapper position-relative overflow-hidden"
    :class="sectionSettings?.custom_class"
    :style="sectionStyle"
  >
    <!-- Formas geométricas animadas originales del template -->
    <div class="triangle-shape" style="top: 15%; right: 10%; z-index: 9"></div>
    <div class="square-shape" style="top: 25%; left: 5%; z-index: 9"></div>
    <div class="shape-five" style="right: 8%; bottom: 15%; z-index: 9"></div>

    <q-carousel
      v-model="activeSlide"
      animated
      infinite
      :autoplay="autoplayInterval"
      transition-prev="fade"
      transition-next="fade"
      navigation
      arrows
      height="650px"
      class="hero-carousel text-white"
    >
      <q-carousel-slide
        v-for="(slide, index) in slides"
        :key="index"
        :name="index"
        class="slide-item q-pa-none"
        :style="{
          backgroundImage: `linear-gradient(-90deg, rgba(39, 48, 68, 0.4), rgba(39, 48, 68, 0.88) 65%), url(${slide.image})`,
          backgroundSize: 'cover',
          backgroundPosition: 'center',
        }"
      >
        <div class="container-custom h-100 row items-center">
          <div class="col-12 col-md-10 col-lg-8 q-py-xl">
            <span
              class="slide-subtitle text-secondary font-weight-700 text-uppercase q-mb-sm block"
            >
              {{ slide.subtitle }}
            </span>
            <h1 class="slide-title text-white font-weight-900 q-mb-md">
              {{ slide.title }}
            </h1>
            <p class="slide-description text-grey-3 text-body1 q-mb-xl" style="max-width: 580px">
              {{ slide.description }}
            </p>
            <div class="row items-center q-gutter-md">
              <router-link :to="slide.primary_btn_link || '/contact'" class="butn">
                <i class="fas fa-plus-circle icon-arrow before"></i>
                <span class="label">{{ slide.primary_btn_text || 'Más Información' }}</span>
                <i class="fas fa-plus-circle icon-arrow after"></i>
              </router-link>
              <router-link :to="slide.secondary_btn_link || '/courses'" class="butn white">
                <i class="fas fa-plus-circle icon-arrow before"></i>
                <span class="label">{{ slide.secondary_btn_text || 'Nuestras Carreras' }}</span>
                <i class="fas fa-plus-circle icon-arrow after"></i>
              </router-link>
            </div>
          </div>
        </div>
      </q-carousel-slide>
    </q-carousel>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  sectionData: {
    type: Object,
    default: () => ({}),
  },
  sectionSettings: {
    type: Object,
    default: () => ({}),
  },
})

const activeSlide = ref(0)

const DEFAULT_SLIDES = [
  {
    image: '/img/banner/slide1.jpg',
    subtitle: 'Formación Tecnológica de Excelencia',
    title: 'Impulsa tu Futuro con Carreras de Alta Demanda',
    description:
      'En el IESTP DEPERU MASTER te capacitamos con tecnología de vanguardia, docentes experimentados y convenios empresariales para tu inserción laboral inmediata.',
    primary_btn_text: 'Más Información',
    primary_btn_link: '/contact',
    secondary_btn_text: 'Nuestras Carreras',
    secondary_btn_link: '/courses',
  },
  {
    image: '/img/banner/slide2.jpg',
    subtitle: 'Metodología Práctica e Innovadora',
    title: 'Aprende con los Mejores Especialistas y Laboratorios',
    description:
      'Desarrolla proyectos reales desde el primer ciclo con herramientas modernas, soporte académico personalizado y certificación modular progresiva.',
    primary_btn_text: 'Conoce el Instituto',
    primary_btn_link: '/about',
    secondary_btn_text: 'Ver Especialidades',
    secondary_btn_link: '/courses',
  },
  {
    image: '/img/banner/slide3.jpg',
    subtitle: 'Admisión 2026 Abierta',
    title: 'Más de 10 Especialidades y Carreras Profesionales',
    description:
      'Elige tu camino en tecnología, diseño, negocios y gestión. Clases con horarios flexibles y plataformas virtuales interactivas.',
    primary_btn_text: 'Postular Ahora',
    primary_btn_link: '/contact',
    secondary_btn_text: 'Ver Cronograma',
    secondary_btn_link: '/admision',
  },
]

const slides = computed(() =>
  Array.isArray(props.sectionData?.slides) && props.sectionData.slides.length > 0
    ? props.sectionData.slides
    : DEFAULT_SLIDES,
)

const autoplayInterval = computed(() => {
  if (props.sectionData?.autoplay === false) return false
  return Number(props.sectionData?.interval) || 5000
})

const sectionStyle = computed(() => {
  const s = props.sectionSettings || {}
  return {
    paddingTop: s.padding_top ? `${s.padding_top}px` : undefined,
    paddingBottom: s.padding_bottom ? `${s.padding_bottom}px` : undefined,
    backgroundColor: s.background_color || undefined,
  }
})
</script>

<style scoped lang="scss">
@import '@/css/template/variables';

.hero-slider-wrapper {
  position: relative;
  background-color: $theme-dark;
}

.hero-carousel {
  background: transparent;

  :deep(.q-carousel__navigation-inner) {
    bottom: 25px;
  }

  :deep(.q-carousel__navigation .q-btn) {
    margin: 0 4px;
    opacity: 0.6;
    &.q-carousel__navigation-button--active {
      opacity: 1;
      color: $theme-secondary !important;
    }
  }

  :deep(.q-carousel__prev-arrow),
  :deep(.q-carousel__next-arrow) {
    background: transparent !important;
    border-radius: 0 !important;
    margin: 0;

    .q-btn {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background-color: rgba(0, 50, 120, 0.75) !important;
      color: #ffffff !important;
      margin: 0 15px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);

      &:hover {
        background-color: $theme-secondary !important;
        transform: scale(1.1);
      }
    }
  }
}

.slide-subtitle {
  font-size: 16px;
  letter-spacing: 1.5px;
}

.slide-title {
  font-size: 48px;
  line-height: 1.15;
  font-weight: 800;

  @media (max-width: 768px) {
    font-size: 32px;
  }
}

.slide-description {
  font-size: 16px;
  line-height: 1.6;
}
</style>
