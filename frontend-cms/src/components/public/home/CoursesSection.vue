<template>
  <section
    :id="sectionSettings?.custom_id || undefined"
    class="section-padding bg-very-light-gray"
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
          v-for="(course, idx) in visibleCourses"
          :key="course.id || idx"
          class="col-12 col-md-6 col-lg-4"
        >
          <div class="card-style1">
            <div class="card-img">
              <img :src="course.image" :alt="course.title" />
              <span class="course-tag">{{ course.category }}</span>
              <button
                class="wishlist-btn"
                aria-label="Favorito"
                @click.prevent="toggleFavorite(idx)"
              >
                <i :class="favMap[idx] ? 'fas fa-heart text-secondary' : 'far fa-heart'"></i>
              </button>
            </div>

            <div class="card-body">
              <div class="card-author">
                <div class="avatar">
                  <img
                    :src="course.instructor_avatar || course.instructor?.avatar || '/img/avatar/avatar-01.jpg'"
                    :alt="course.instructor_name || course.instructor?.name || 'Docente'"
                  />
                </div>
                <div class="author-name">
                  <span>{{ course.instructor_name || course.instructor?.name || 'Docente Especialista' }}</span>
                </div>
              </div>

              <div class="row items-center q-mb-sm text-caption text-amber-9">
                <q-icon name="star" size="16px" />
                <span class="font-weight-700 q-ml-xs">{{ course.rating || '4.9' }}</span>
                <span class="text-grey-6 q-ml-xs">({{ course.reviews || '80' }} valoraciones)</span>
              </div>

              <h3 class="text-subtitle1 font-weight-700 q-mb-md" style="min-height: 48px">
                <router-link :to="course.link || '/carreras'" class="text-dark">
                  {{ course.title }}
                </router-link>
              </h3>

              <div class="dotted-seprator q-my-sm" style="border-top: 1px solid #ededed"></div>

              <div class="row items-center justify-between text-caption text-grey-7 q-pt-sm">
                <div><i class="far fa-clock text-secondary q-mr-xs"></i> {{ course.duration }}</div>
                <div>
                  <i class="far fa-user text-secondary q-mr-xs"></i> {{ course.students }} alumnos
                </div>
                <div class="text-subtitle2 font-weight-800 text-primary">{{ course.price }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="text-center q-mt-xl row justify-center q-gutter-md">
        <button
          v-if="courses.length > visibleLimit"
          type="button"
          class="butn secondary"
          @click="visibleLimit += 5"
        >
          <i class="fas fa-plus-circle icon-arrow before"></i>
          <span class="label">Ver Más ({{ courses.length - visibleLimit }})</span>
          <i class="fas fa-plus-circle icon-arrow after"></i>
        </button>

        <router-link :to="viewAllLink" class="butn">
          <i class="fas fa-plus-circle icon-arrow before"></i>
          <span class="label">{{ viewAllText }}</span>
          <i class="fas fa-plus-circle icon-arrow after"></i>
        </router-link>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'

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

const favMap = reactive({})

const DEFAULT_COURSES = [
  {
    id: 1,
    image: '/img/content/courses-01.jpg',
    category: 'Tecnología',
    title: 'Desarrollo de Sistemas de Información y Software',
    duration: '3 Años',
    students: '120',
    price: 'S/. 350 /mes',
    rating: '4.9',
    reviews: '85',
    link: '/courses/1',
    instructor_name: 'Ing. Carlos Mendoza',
    instructor_avatar: '/img/avatar/avatar-01.jpg',
  },
  {
    id: 2,
    image: '/img/content/courses-02.jpg',
    category: 'Infraestructura',
    title: 'Administración de Redes y Comunicaciones',
    duration: '3 Años',
    students: '98',
    price: 'S/. 320 /mes',
    rating: '4.8',
    reviews: '64',
    link: '/courses/2',
    instructor_name: 'Lic. Elena Ramos',
    instructor_avatar: '/img/avatar/avatar-02.jpg',
  },
  {
    id: 3,
    image: '/img/content/courses-03.jpg',
    category: 'Creatividad',
    title: 'Diseño y Animación Digital Multimedia',
    duration: '3 Años',
    students: '145',
    price: 'S/. 330 /mes',
    rating: '4.9',
    reviews: '110',
    link: '/courses/3',
    instructor_name: 'Mg. Roberto Silva',
    instructor_avatar: '/img/avatar/avatar-03.jpg',
  },
  {
    id: 4,
    image: '/img/content/courses-04.jpg',
    category: 'Negocios',
    title: 'Marketing Digital y Comercio Electrónico',
    duration: '1.5 Años',
    students: '210',
    price: 'S/. 280 /mes',
    rating: '4.7',
    reviews: '92',
    link: '/courses/4',
    instructor_name: 'Lic. Patricia Vega',
    instructor_avatar: '/img/avatar/avatar-04.jpg',
  },
  {
    id: 5,
    image: '/img/content/courses-05.jpg',
    category: 'Gestión',
    title: 'Contabilidad y Finanzas Empresariales',
    duration: '3 Años',
    students: '115',
    price: 'S/. 300 /mes',
    rating: '4.8',
    reviews: '73',
    link: '/courses/5',
    instructor_name: 'C.P.C. Manuel Castro',
    instructor_avatar: '/img/avatar/avatar-05.jpg',
  },
  {
    id: 6,
    image: '/img/content/courses-06.jpg',
    category: 'Ciberseguridad',
    title: 'Especialista en Seguridad de la Información',
    duration: '1 Año',
    students: '80',
    price: 'S/. 360 /mes',
    rating: '5.0',
    reviews: '52',
    link: '/courses/6',
    instructor_name: 'Ing. David Flores',
    instructor_avatar: '/img/avatar/avatar-06.jpg',
  },
]

const subtitle = computed(() => props.sectionData?.subtitle || 'Descubre tu Potencial')
const title = computed(
  () => props.sectionData?.title || 'Nuestras Carreras y Cursos Destacados',
)
const viewAllText = computed(
  () => props.sectionData?.view_all_text || 'Ver Todas las Carreras',
)
const viewAllLink = computed(() => {
  const link = props.sectionData?.view_all_link
  if (!link || link === '/courses') return '/carreras'
  return link
})

const visibleLimit = ref(5)

const courses = computed(() =>
  Array.isArray(props.sectionData?.courses) && props.sectionData.courses.length > 0
    ? props.sectionData.courses
    : DEFAULT_COURSES,
)

const visibleCourses = computed(() => courses.value.slice(0, visibleLimit.value))

const sectionStyle = computed(() => {
  const s = props.sectionSettings || {}
  return {
    paddingTop: s.padding_top !== undefined ? `${s.padding_top}px` : undefined,
    paddingBottom: s.padding_bottom !== undefined ? `${s.padding_bottom}px` : undefined,
    backgroundColor: s.background_color || undefined,
  }
})

const toggleFavorite = (idx) => {
  favMap[idx] = !favMap[idx]
}
</script>
