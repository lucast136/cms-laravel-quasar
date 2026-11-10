<template>
  <section
    :id="sectionSettings?.custom_id || undefined"
    class="section-padding position-relative overflow-hidden"
    :class="sectionSettings?.custom_class"
    :style="sectionStyle"
  >
    <div class="container-custom">
      <div class="row items-center q-col-gutter-xl">
        <!-- Columna Izquierda: Video / Imagen con Botón Play -->
        <div class="col-12 col-lg-6">
          <div class="why-choose-img position-relative rounded-borders overflow-hidden shadow-3">
            <img
              :src="image"
              :alt="title"
              class="w-100 full-width"
            />
            <!-- Botón de Video con Ondas -->
            <div
              class="video-play-btn"
              role="button"
              aria-label="Ver video institucional"
              @click="videoDialogOpen = true"
            >
              <i class="fas fa-play"></i>
            </div>
          </div>
        </div>

        <!-- Columna Derecha: Contenido y Ventajas -->
        <div class="col-12 col-lg-6">
          <div class="section-heading text-start q-mb-md">
            <span class="sub-title">{{ subtitle }}</span>
            <h2 class="q-mb-md">{{ title }}</h2>
          </div>

          <p class="text-grey-7 q-mb-lg">
            {{ description }}
          </p>

          <div class="q-gutter-y-md q-mb-xl">
            <div v-for="(item, index) in benefits" :key="index" class="row items-start no-wrap">
              <div class="benefit-check-icon q-mr-md">
                <i :class="item.icon || 'ti-check'"></i>
              </div>
              <div>
                <h4 class="text-subtitle1 font-weight-700 q-mb-xs">{{ item.title }}</h4>
                <p class="text-grey-7 text-caption q-mb-none">{{ item.description }}</p>
              </div>
            </div>
          </div>

          <router-link :to="buttonLink" class="butn">
            <i class="fas fa-plus-circle icon-arrow before"></i>
            <span class="label">{{ buttonText }}</span>
            <i class="fas fa-plus-circle icon-arrow after"></i>
          </router-link>
        </div>
      </div>
    </div>

    <!-- Modal de Video Quasar -->
    <q-dialog v-model="videoDialogOpen">
      <q-card style="width: 800px; max-width: 95vw; background: black">
        <q-card-section class="q-pa-none">
          <q-video :ratio="16 / 9" :src="videoUrl" />
        </q-card-section>
        <q-card-actions align="right" class="bg-dark text-white">
          <q-btn v-close-popup flat label="Cerrar" color="white" />
        </q-card-actions>
      </q-card>
    </q-dialog>
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

const videoDialogOpen = ref(false)

const DEFAULT_BENEFITS = [
  {
    icon: 'ti-check',
    title: 'Certificación Modular Progresiva',
    description:
      'Obtén certificados oficiales al culminar cada año académico para ingresar de inmediato al campo laboral.',
  },
  {
    icon: 'ti-check',
    title: 'Laboratorios de Alta Tecnología',
    description:
      'Equipamiento informático moderno, redes conmutadas y licencias oficiales de software profesional.',
  },
  {
    icon: 'ti-check',
    title: 'Convenios y Bolsa de Trabajo Activa',
    description:
      'Alianzas con empresas del sector tecnológico para pasantías, prácticas pre-profesionales y empleo directo.',
  },
]

const subtitle = computed(() => props.sectionData?.subtitle || '¿Por Qué Elegirnos?')
const title = computed(
  () =>
    props.sectionData?.title || 'Formación Técnica de Vanguardia con Enfoque Práctico',
)
const description = computed(
  () =>
    props.sectionData?.description ||
    'En el IESTP DEPERU MASTER preparamos a nuestros estudiantes para liderar la transformación digital del país mediante metodologías ágiles, laboratorios equipados y mentoría personalizada.',
)
const image = computed(() => props.sectionData?.image || '/img/content/why-choose-img.jpg')
const videoUrl = computed(
  () =>
    props.sectionData?.video_url || 'https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1',
)
const buttonText = computed(() => props.sectionData?.button_text || 'Conoce Nuestra Misión')
const buttonLink = computed(() => props.sectionData?.button_link || '/about')

const benefits = computed(() =>
  Array.isArray(props.sectionData?.benefits) && props.sectionData.benefits.length > 0
    ? props.sectionData.benefits
    : DEFAULT_BENEFITS,
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

.why-choose-img {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 50, 120, 0.12);

  img {
    width: 100%;
    height: auto;
    min-height: 380px;
    object-fit: cover;
    display: block;
  }
}

.video-play-btn {
  position: absolute;
  width: 75px;
  height: 75px;
  border-radius: 50%;
  background-color: $theme-secondary;
  color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  cursor: pointer;
  box-shadow: 0 0 25px rgba(237, 50, 55, 0.6);
  transition: transform 0.3s ease;

  &:hover {
    transform: scale(1.1);
  }

  &::before {
    content: '';
    position: absolute;
    inset: -10px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    opacity: 0.6;
    animation: pulse-border 1.8s infinite;
  }
}

.benefit-check-icon {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background-color: rgba(0, 50, 120, 0.1);
  color: $theme-primary;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 15px;
  font-weight: 800;
  flex-shrink: 0;
}

@keyframes pulse-border {
  0% {
    transform: scale(1);
    opacity: 0.8;
  }
  100% {
    transform: scale(1.4);
    opacity: 0;
  }
}
</style>
