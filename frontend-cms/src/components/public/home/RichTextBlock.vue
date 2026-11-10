<template>
  <section
    :id="sectionSettings?.custom_id || undefined"
    class="section-padding position-relative"
    :class="sectionSettings?.custom_class"
    :style="sectionStyle"
  >
    <div :class="sectionSettings?.full_width ? 'q-px-lg' : 'container-custom'">
      <div v-if="sectionData?.title" class="section-heading">
        <h2>{{ sectionData.title }}</h2>
      </div>
      <!-- eslint-disable-next-line vue/no-v-html -->
      <div class="rich-text-content text-body1 text-grey-9" v-html="contentHtml"></div>
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

const contentHtml = computed(
  () =>
    props.sectionData?.content_html ||
    '<h3>Misión Institucional</h3><p>Formar profesionales técnicos altamente competitivos, éticos e innovadores.</p>',
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

<style scoped>
.rich-text-content :deep(h1),
.rich-text-content :deep(h2),
.rich-text-content :deep(h3),
.rich-text-content :deep(h4) {
  color: #003278;
  font-weight: 700;
  margin-bottom: 12px;
}
.rich-text-content :deep(p) {
  line-height: 1.75;
  margin-bottom: 14px;
}
</style>

