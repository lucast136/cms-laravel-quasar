<template>
  <section
    :id="sectionSettings?.custom_id || undefined"
    class="section-padding position-relative overflow-hidden"
    :class="sectionSettings?.custom_class"
    :style="sectionStyle"
  >
    <div class="container-custom">
      <div class="section-heading">
        <span class="sub-title">{{ subtitle }}</span>
        <h2>{{ title }}</h2>
      </div>

      <div class="testimonial-container position-relative">
        <q-carousel
          v-model="activeTestimonial"
          animated
          infinite
          :autoplay="6000"
          transition-prev="slide-right"
          transition-next="slide-left"
          navigation
          height="auto"
          class="testimonial-carousel bg-transparent"
        >
          <q-carousel-slide
            v-for="(item, index) in testimonials"
            :key="index"
            :name="index"
            class="q-pa-md text-center"
          >
            <div class="testimonial-card">
              <div class="testimonial-avatar q-mb-md">
                <img :src="item.avatar" :alt="item.name" />
              </div>
              <div class="text-amber-9 q-mb-sm">
                <q-icon
                  v-for="s in Number(item.rating) || 5"
                  :key="s"
                  name="star"
                  size="18px"
                />
              </div>
              <p class="testimonial-text text-body1 text-grey-8 q-mb-lg">“{{ item.quote }}”</p>
              <h4 class="text-subtitle1 font-weight-700 q-mb-none">{{ item.name }}</h4>
              <span class="text-caption text-secondary font-weight-600">{{ item.career }}</span>
            </div>
          </q-carousel-slide>
        </q-carousel>
      </div>
    </div>
  </section>
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

const activeTestimonial = ref(0)

const DEFAULT_TESTIMONIALS = [
  {
    name: 'Andrea Morales',
    career: 'Egresada de Desarrollo de Software',
    avatar: '/img/avatar/avatar-01.jpg',
    rating: 5,
    quote:
      'La formación en DEPERU MASTER me permitió ingresar a trabajar a una empresa de tecnología antes de terminar mi último ciclo. Los docentes son profesionales con experiencia real.',
  },
  {
    name: 'Jorge Quispe',
    career: 'Estudiante de Redes y Telecomunicaciones',
    avatar: '/img/avatar/avatar-02.jpg',
    rating: 5,
    quote:
      'Los laboratorios y la metodología orientada a certificaciones marcaron la diferencia en mi aprendizaje. Recomiendo el instituto al 100%.',
  },
  {
    name: 'Valeria Benítez',
    career: 'Egresada de Diseño Multimedia',
    avatar: '/img/avatar/avatar-03.jpg',
    rating: 5,
    quote:
      'Aprendí a usar herramientas y software de nivel internacional. Los proyectos que desarrollamos enriquecieron mi portafolio para conseguir clientes de inmediato.',
  },
]

const subtitle = computed(() => props.sectionData?.subtitle || 'Opiniones Reales')
const title = computed(() => props.sectionData?.title || 'Lo Que Dicen Nuestros Estudiantes')

const testimonials = computed(() =>
  Array.isArray(props.sectionData?.testimonials) && props.sectionData.testimonials.length > 0
    ? props.sectionData.testimonials
    : DEFAULT_TESTIMONIALS,
)

const sectionStyle = computed(() => {
  const s = props.sectionSettings || {}
  return {
    paddingTop: s.padding_top !== undefined ? `${s.padding_top}px` : undefined,
    paddingBottom: s.padding_bottom !== undefined ? `${s.padding_bottom}px` : undefined,
    backgroundColor: s.background_color || undefined,
  }
})
</script>

<style scoped lang="scss">
@import '@/css/template/variables';

.testimonial-container {
  max-width: 800px;
  margin: 0 auto;
}

.testimonial-card {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.testimonial-avatar {
  width: 90px;
  height: 90px;
  border-radius: 50%;
  border: 4px solid $theme-primary;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(47, 191, 167, 0.25);

  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}

.testimonial-text {
  font-size: 18px;
  line-height: 1.8;
  font-style: italic;
  max-width: 700px;
}

:deep(.q-carousel__navigation .q-btn) {
  color: $theme-border;
  &.q-carousel__navigation-button--active {
    color: $theme-secondary !important;
  }
}
</style>
