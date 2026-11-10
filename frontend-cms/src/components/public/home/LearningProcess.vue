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

      <div class="row q-col-gutter-xl process-wrapper position-relative">
        <div v-for="(step, index) in steps" :key="index" class="col-12 col-sm-6 col-lg-3">
          <div class="process-step-card text-center">
            <div class="step-badge q-mb-md">
              <span class="step-number">{{ index + 1 }}</span>
              <div class="step-icon">
                <i :class="step.icon || 'ti-medall'"></i>
              </div>
            </div>
            <h4 class="text-subtitle1 font-weight-700 q-mb-sm">{{ step.title }}</h4>
            <p class="text-grey-7 text-caption q-mb-none">{{ step.description }}</p>
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

const DEFAULT_STEPS = [
  {
    icon: 'ti-search',
    title: '1. Elige tu Carrera',
    description:
      'Explora nuestra oferta académica y selecciona el programa alineado a tus metas profesionales.',
  },
  {
    icon: 'ti-comments',
    title: '2. Asesoría Personalizada',
    description:
      'Conversa con nuestros coordinadores para conocer mallas curriculares, horarios y beneficios.',
  },
  {
    icon: 'ti-pencil-alt',
    title: '3. Matrícula y Admisión',
    description: 'Completa tu inscripción de forma presencial o 100% online en sencillos pasos.',
  },
  {
    icon: 'ti-medall',
    title: '4. ¡Comienza a Aprender!',
    description:
      'Inicia tus clases con acceso completo a laboratorios, plataforma virtual y biblioteca digital.',
  },
]

const subtitle = computed(() => props.sectionData?.subtitle || 'Tu Ruta al Éxito')
const title = computed(() => props.sectionData?.title || 'Proceso de Admisión en 4 Pasos')

const steps = computed(() =>
  Array.isArray(props.sectionData?.steps) && props.sectionData.steps.length > 0
    ? props.sectionData.steps
    : DEFAULT_STEPS,
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

.process-step-card {
  background: #ffffff;
  padding: 35px 20px;
  border-radius: 8px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  height: 100%;
  transition: transform 0.3s ease;

  &:hover {
    transform: translateY(-5px);
  }
}

.step-badge {
  position: relative;
  display: inline-block;

  .step-number {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 26px;
    height: 26px;
    background-color: $theme-secondary;
    color: #ffffff;
    border-radius: 50%;
    font-size: 12px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #ffffff;
    z-index: 2;
  }

  .step-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background-color: rgba(47, 191, 167, 0.12);
    color: $theme-primary;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
  }
}
</style>
