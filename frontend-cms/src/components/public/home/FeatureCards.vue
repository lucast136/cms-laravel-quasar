<template>
  <section
    :id="sectionSettings?.custom_id || undefined"
    class="feature-cards-section q-py-none"
    :class="sectionSettings?.custom_class"
    :style="sectionStyle"
  >
    <div class="container-custom">
      <div class="row q-col-gutter-lg feature-cards-row">
        <div v-for="(card, index) in features" :key="index" class="col-12 col-md-4">
          <div class="card-style3">
            <div class="row items-center no-wrap q-mb-md">
              <div class="card-icon">
                <i :class="card.icon || 'ti-rocket'"></i>
              </div>
              <h4 class="q-ml-md q-mb-none text-h6 font-weight-700">
                {{ card.title }}
              </h4>
            </div>
            <div>
              <p class="text-grey-7 q-mb-md">
                {{ card.description }}
              </p>
              <router-link :to="card.link_url || '/about'" class="butn-style1 secondary">
                {{ card.link_text || 'Conoce Más +' }}
              </router-link>
            </div>
          </div>
        </div>
      </div>
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

const DEFAULT_CARDS = [
  {
    icon: 'ti-rocket',
    title: 'Aprende Cualquier Área',
    description:
      'Programas de estudio orientados a la práctica con tecnologías vigentes en la industria actual.',
    link_text: 'Conoce Más +',
    link_url: '/about',
  },
  {
    icon: 'ti-world',
    title: 'Comunidad Conectada',
    description:
      'Accede a networking profesional, foros académicos, proyectos colaborativos y convenios laborales.',
    link_text: 'Conoce Más +',
    link_url: '/about',
  },
  {
    icon: 'ti-user',
    title: 'Docentes Expertos',
    description:
      'Instructores en activo que lideran proyectos reales y te guiarán paso a paso en tu crecimiento.',
    link_text: 'Conoce Más +',
    link_url: '/about',
  },
]

const features = computed(() =>
  Array.isArray(props.sectionData?.cards) && props.sectionData.cards.length > 0
    ? props.sectionData.cards
    : DEFAULT_CARDS,
)

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
.feature-cards-section {
  position: relative;
  z-index: 10;
}

.feature-cards-row {
  margin-top: -60px;

  @media (max-width: 991px) {
    margin-top: 20px;
  }
}
</style>
