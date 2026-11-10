<template>
  <section
    :id="sectionSettings?.custom_id || undefined"
    class="section-padding bg-very-light-gray position-relative"
    :class="sectionSettings?.custom_class"
    :style="sectionStyle"
  >
    <div class="container-custom">
      <div class="section-heading">
        <span class="sub-title">{{ subtitle }}</span>
        <h2>{{ title }}</h2>
      </div>

      <div class="row q-col-gutter-xl">
        <div
          v-for="(instructor, idx) in visibleInstructors"
          :key="instructor.id || idx"
          class="col-12 col-sm-6 col-lg-3"
        >
          <div class="team-style1">
            <div class="team-img">
              <img :src="instructor.image" :alt="instructor.name" />
            </div>

            <!-- Información visible por defecto -->
            <div class="team-info">
              <h4>{{ instructor.name }}</h4>
              <span>{{ instructor.specialty }}</span>
            </div>

            <!-- Overlay animado al pasar el mouse -->
            <div class="team-overlay">
              <h4 class="text-white text-h6 font-weight-700 q-mb-xs">{{ instructor.name }}</h4>
              <p class="text-caption text-grey-2 q-mb-md">{{ instructor.bio }}</p>
              <div class="social-links row items-center justify-center">
                <a :href="instructor.facebook || '#'" aria-label="Facebook"
                  ><i class="fab fa-facebook-f"></i
                ></a>
                <a :href="instructor.linkedin || '#'" aria-label="LinkedIn"
                  ><i class="fab fa-linkedin-in"></i
                ></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="instructors.length > visibleLimit" class="text-center q-mt-xl">
        <button type="button" class="butn" @click="visibleLimit += 5">
          <i class="fas fa-plus-circle icon-arrow before"></i>
          <span class="label">Ver Más Docentes ({{ instructors.length - visibleLimit }})</span>
          <i class="fas fa-plus-circle icon-arrow after"></i>
        </button>
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

const DEFAULT_INSTRUCTORS = [
  {
    id: 1,
    name: 'Ing. Carlos Mendoza',
    specialty: 'Arquitectura de Software y Cloud',
    bio: 'Más de 10 años de experiencia desarrollando plataformas de gran escala.',
    image: '/img/team/team-01.jpg',
    linkedin: '#',
    facebook: '#',
  },
  {
    id: 2,
    name: 'Lic. Elena Ramos',
    specialty: 'Seguridad y Redes Cisco',
    bio: 'Certificada CCNA y consultora en infraestructura y ciberseguridad.',
    image: '/img/team/team-02.jpg',
    linkedin: '#',
    facebook: '#',
  },
  {
    id: 3,
    name: 'Mg. Roberto Silva',
    specialty: 'Diseño UX/UI y Multimedia',
    bio: 'Especialista en experiencia de usuario y diseño de interfaces interactivas.',
    image: '/img/team/team-03.jpg',
    linkedin: '#',
    facebook: '#',
  },
  {
    id: 4,
    name: 'Lic. Patricia Vega',
    specialty: 'Estrategia Digital y Growth',
    bio: 'Líder en marketing orientado a resultados y comercio digital.',
    image: '/img/team/team-04.jpg',
    linkedin: '#',
    facebook: '#',
  },
]

const subtitle = computed(() => props.sectionData?.subtitle || 'Plana Docente de Primer Nivel')
const title = computed(() => props.sectionData?.title || 'Nuestros Instructores Expertos')

const visibleLimit = ref(5)

const instructors = computed(() =>
  Array.isArray(props.sectionData?.instructors) && props.sectionData.instructors.length > 0
    ? props.sectionData.instructors
    : DEFAULT_INSTRUCTORS,
)

const visibleInstructors = computed(() => instructors.value.slice(0, visibleLimit.value))

const sectionStyle = computed(() => {
  const s = props.sectionSettings || {}
  return {
    paddingTop: s.padding_top !== undefined ? `${s.padding_top}px` : undefined,
    paddingBottom: s.padding_bottom !== undefined ? `${s.padding_bottom}px` : undefined,
    backgroundColor: s.background_color || undefined,
  }
})
</script>
