<template>
  <section
    :id="sectionSettings?.custom_id || undefined"
    class="section-padding bg-very-light-gray position-relative"
    :class="sectionSettings?.custom_class"
    :style="sectionStyle"
  >
    <div class="container-custom">
      <div class="row q-col-gutter-xl">
        <div v-for="(stat, index) in counters" :key="index" class="col-12 col-sm-6 col-lg-3">
          <div class="counter-box">
            <div class="counter-icon">
              <i :class="stat.icon || 'ti-medall'"></i>
            </div>
            <div class="counter-text">
              <div class="counter-number">
                {{ stat.value }}{{ stat.suffix !== undefined ? stat.suffix : '+' }}
              </div>
              <div class="counter-label">
                {{ stat.label }}
              </div>
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

const DEFAULT_COUNTERS = [
  { icon: 'ti-user', value: 1800, suffix: '+', label: 'Egresados Titulados' },
  { icon: 'ti-book', value: 45, suffix: '+', label: 'Cursos y Carreras' },
  { icon: 'ti-medall', value: 85, suffix: '+', label: 'Docentes Expertos' },
  { icon: 'ti-cup', value: 30, suffix: '+', label: 'Premios y Reconocimientos' },
]

const counters = computed(() =>
  Array.isArray(props.sectionData?.counters) && props.sectionData.counters.length > 0
    ? props.sectionData.counters
    : DEFAULT_COUNTERS,
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
