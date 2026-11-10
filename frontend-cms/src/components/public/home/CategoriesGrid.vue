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

      <div class="row q-col-gutter-lg">
        <div v-for="(cat, index) in visibleCategories" :key="index" class="col-12 col-sm-6 col-lg-3">
          <router-link :to="cat.link === '/courses' ? '/carreras' : cat.link || '/carreras'" class="category-item-01">
            <div class="category-icon">
              <img :src="cat.icon" :alt="cat.name" />
            </div>
            <div class="category-info">
              <h4>{{ cat.name }}</h4>
              <span>{{ cat.coursesCount }} Programas</span>
            </div>
          </router-link>
        </div>
      </div>

      <div v-if="categories.length > visibleLimit" class="text-center q-mt-xl">
        <button type="button" class="butn" @click="visibleLimit += 5">
          <i class="fas fa-plus-circle icon-arrow before"></i>
          <span class="label">Ver Más Especialidades ({{ categories.length - visibleLimit }})</span>
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

const DEFAULT_CATEGORIES = [
  { name: 'Desarrollo de Software', coursesCount: '12+', icon: '/img/icons/icon-01.png', link: '/courses' },
  { name: 'Diseño y Creatividad', coursesCount: '8+', icon: '/img/icons/icon-02.png', link: '/courses' },
  { name: 'Redes y Servidores', coursesCount: '10+', icon: '/img/icons/icon-03.png', link: '/courses' },
  { name: 'Marketing Digital', coursesCount: '6+', icon: '/img/icons/icon-04.png', link: '/courses' },
  { name: 'Gestión y Finanzas', coursesCount: '9+', icon: '/img/icons/icon-05.png', link: '/courses' },
  { name: 'Ciberseguridad', coursesCount: '5+', icon: '/img/icons/icon-06.png', link: '/courses' },
  { name: 'Inteligencia Artificial', coursesCount: '4+', icon: '/img/icons/icon-07.png', link: '/courses' },
  { name: 'Gestión de Proyectos', coursesCount: '7+', icon: '/img/icons/icon-08.png', link: '/courses' },
]

const subtitle = computed(() => props.sectionData?.subtitle || 'Áreas de Especialidad')
const title = computed(() => props.sectionData?.title || 'Categorías en Tendencia')

const visibleLimit = ref(5)

const categories = computed(() =>
  Array.isArray(props.sectionData?.categories) && props.sectionData.categories.length > 0
    ? props.sectionData.categories
    : DEFAULT_CATEGORIES,
)

const visibleCategories = computed(() => categories.value.slice(0, visibleLimit.value))

const sectionStyle = computed(() => {
  const s = props.sectionSettings || {}
  return {
    paddingTop: s.padding_top !== undefined ? `${s.padding_top}px` : undefined,
    paddingBottom: s.padding_bottom !== undefined ? `${s.padding_bottom}px` : undefined,
    backgroundColor: s.background_color || undefined,
  }
})
</script>
