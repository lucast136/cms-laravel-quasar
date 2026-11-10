<template>
  <section
    :id="sectionSettings?.custom_id || undefined"
    class="partners-section q-py-xl position-relative"
    :class="sectionSettings?.custom_class"
    :style="sectionStyle"
  >
    <div class="container-custom">
      <div class="text-center q-mb-lg">
        <span class="text-caption text-uppercase text-grey-6 font-weight-700 letter-spacing-1">
          {{ title }}
        </span>
      </div>

      <div class="row items-center justify-center q-col-gutter-lg partners-grid">
        <div
          v-for="(partner, idx) in partners"
          :key="idx"
          class="col-4 col-sm-3 col-md-2 text-center"
        >
          <div class="partner-item">
            <img
              :src="partner.logo"
              :alt="partner.name || `Partner ${idx + 1}`"
              class="partner-logo"
            />
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

const DEFAULT_PARTNERS = [
  { name: 'Aliado Estratégico 1', logo: '/img/partners/partner-01.jpg', url: '#' },
  { name: 'Aliado Estratégico 2', logo: '/img/partners/partner-02.jpg', url: '#' },
  { name: 'Aliado Estratégico 3', logo: '/img/partners/partner-03.jpg', url: '#' },
  { name: 'Aliado Estratégico 4', logo: '/img/partners/partner-04.jpg', url: '#' },
  { name: 'Aliado Estratégico 5', logo: '/img/partners/partner-05.jpg', url: '#' },
  { name: 'Aliado Estratégico 6', logo: '/img/partners/partner-06.jpg', url: '#' },
]

const title = computed(
  () =>
    props.sectionData?.title || 'Empresas e Instituciones con Convenio de Empleabilidad',
)

const partners = computed(() =>
  Array.isArray(props.sectionData?.partners) && props.sectionData.partners.length > 0
    ? props.sectionData.partners
    : DEFAULT_PARTNERS,
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
.partners-section {
  border-top: 1px solid #ededed;
  background-color: #ffffff;
}

.partner-item {
  padding: 10px;
  display: flex;
  align-items: center;
  justify-content: center;

  .partner-logo {
    max-height: 48px;
    width: auto;
    filter: grayscale(100%);
    opacity: 0.6;
    transition: all 0.3s ease;

    &:hover {
      filter: grayscale(0%);
      opacity: 1;
      transform: scale(1.08);
    }
  }
}
</style>
