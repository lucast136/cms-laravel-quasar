<template>
  <section
    :id="sectionSettings?.custom_id || undefined"
    class="section-padding aboutus-style-01 position-relative overflow-hidden"
    :class="sectionSettings?.custom_class"
    :style="sectionStyle"
  >
    <div class="container-custom">
      <div class="row items-center q-col-gutter-xl">
        <!-- Columna Izquierda: Imágenes y Badge animado -->
        <div class="col-12 col-lg-6">
          <div class="about-image-wrapper position-relative">
            <div class="image-hover rounded-borders overflow-hidden shadow-3">
              <img
                :src="imageMain"
                :alt="title"
                class="w-100 full-width"
              />
            </div>
            <!-- Imagen superpuesta secundaria (desktop) -->
            <img
              :src="imageSecondary"
              alt="Estudiantes"
              class="img-overlay-secondary gt-md shadow-4 rounded-borders"
            />
            <!-- Badge animado con contador -->
            <div class="about-badge">
              <div class="counter-val">
                <span>{{ yearsExperience }}</span
                >+
              </div>
              <p>{{ experienceLabel }}</p>
            </div>
          </div>
        </div>

        <!-- Columna Derecha: Contenido y Contacto -->
        <div class="col-12 col-lg-6">
          <div class="section-heading text-start q-mb-md">
            <span class="sub-title">{{ subtitle }}</span>
            <h2 class="text-primary font-weight-800 q-mb-md">
              {{ title }}
            </h2>
          </div>

          <p
            v-if="descriptionLead"
            class="text-body1 text-grey-8 font-weight-600 q-mb-md"
            style="font-style: italic; border-left: 3px solid #2fbfa7; padding-left: 14px"
          >
            {{ descriptionLead }}
          </p>

          <p v-if="descriptionBody" class="text-grey-7 q-mb-lg">
            {{ descriptionBody }}
          </p>

          <div class="dotted-seprator q-my-lg" style="border-top: 2px dotted #ededed"></div>

          <div class="row q-col-gutter-md">
            <div v-for="(contact, idx) in contacts" :key="idx" class="col-12 col-sm-6">
              <div class="row items-center no-wrap">
                <i :class="contact.icon || 'ti-mobile'" class="text-secondary" style="font-size: 30px"></i>
                <div class="q-ml-md">
                  <h4 class="text-subtitle1 font-weight-700 q-mb-none">{{ contact.title }}</h4>
                  <p class="text-caption text-grey-7 q-mb-none">{{ contact.value }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Formas decorativas de fondo -->
    <div class="shape18 gt-sm">
      <img src="/img/bg/bg-01.jpg" alt="" style="max-width: 100px; opacity: 0.15" />
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'

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

const subtitle = computed(() => props.sectionData?.subtitle || '¡Bienvenidos al Instituto!')
const title = computed(
  () =>
    props.sectionData?.title || 'Aprende en Cualquier Momento, Lugar y a tu Propio Ritmo',
)
const descriptionLead = computed(
  () =>
    props.sectionData?.description_lead ??
    'Un espacio creado para dotar a los estudiantes de los conocimientos, habilidades y valores técnicos necesarios en un mundo altamente tecnológico y competitivo.',
)
const descriptionBody = computed(
  () =>
    props.sectionData?.description_body ??
    'Nuestros planes de estudio están alineados a las exigencias del sector productivo nacional e internacional, combinando formación práctica, laboratorios especializados y tutoría constante.',
)
const imageMain = computed(() => props.sectionData?.image_main || '/img/content/about-03.jpg')
const imageSecondary = computed(
  () => props.sectionData?.image_secondary || '/img/content/about-02.jpg',
)
const yearsExperience = computed(() => props.sectionData?.years_experience ?? 9)
const experienceLabel = computed(
  () => props.sectionData?.experience_label || 'Años de Trayectoria Académica',
)

const DEFAULT_CONTACTS = [
  {
    icon: 'ti-mobile',
    title: 'Central Telefónica',
    value: '(01) 456-7890',
  },
  {
    icon: 'ti-email',
    title: 'Correo Institucional',
    value: 'informes@deperumaster.edu.pe',
  },
]

const contacts = computed(() =>
  Array.isArray(props.sectionData?.contacts) && props.sectionData.contacts.length > 0
    ? props.sectionData.contacts
    : DEFAULT_CONTACTS,
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

.about-image-wrapper {
  position: relative;
  padding-bottom: 30px;
}

.img-overlay-secondary {
  position: absolute;
  bottom: 0;
  right: -20px;
  width: 220px;
  height: 180px;
  object-fit: cover;
  border: 4px solid #ffffff;
  z-index: 5;
}
</style>
