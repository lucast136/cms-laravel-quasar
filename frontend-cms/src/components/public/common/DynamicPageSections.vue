<template>
  <div class="dynamic-page-sections">
    <template v-for="sec in activeSections" :key="sec.id">
      <component
        :is="resolveSectionComponent(sec)"
        v-if="resolveSectionComponent(sec)"
        :section-data="sec.data"
        :section-settings="sec.settings"
      />
    </template>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { usePublicCmsStore } from '@/stores/public-cms'

import HeroSlider from '@/components/public/home/HeroSlider.vue'
import FeatureCards from '@/components/public/home/FeatureCards.vue'
import AboutSection from '@/components/public/home/AboutSection.vue'
import CoursesSection from '@/components/public/home/CoursesSection.vue'
import CategoriesGrid from '@/components/public/home/CategoriesGrid.vue'
import InstructorsGrid from '@/components/public/home/InstructorsGrid.vue'
import WhyChooseUs from '@/components/public/home/WhyChooseUs.vue'
import StatsCounter from '@/components/public/home/StatsCounter.vue'
import TestimonialsSlider from '@/components/public/home/TestimonialsSlider.vue'
import LearningProcess from '@/components/public/home/LearningProcess.vue'
import UpcomingEvents from '@/components/public/home/UpcomingEvents.vue'
import LatestBlog from '@/components/public/home/LatestBlog.vue'
import PartnersSlider from '@/components/public/home/PartnersSlider.vue'
import DataTableBlock from '@/components/public/home/DataTableBlock.vue'
import RichTextBlock from '@/components/public/home/RichTextBlock.vue'
import ContactSection from '@/components/public/home/ContactSection.vue'

const props = defineProps({
  slug: {
    type: String,
    required: true,
  },
  defaultSections: {
    type: Array,
    default: () => [],
  },
})

const publicCmsStore = usePublicCmsStore()

const COMPONENT_MAP = {
  hero_slider: HeroSlider,
  HeroSlider,
  feature_cards: FeatureCards,
  FeatureCards,
  about_section: AboutSection,
  AboutSection,
  courses_section: CoursesSection,
  CoursesSection,
  categories_grid: CategoriesGrid,
  CategoriesGrid,
  instructors_grid: InstructorsGrid,
  InstructorsGrid,
  why_choose_us: WhyChooseUs,
  WhyChooseUs,
  stats_counter: StatsCounter,
  StatsCounter,
  testimonials_slider: TestimonialsSlider,
  TestimonialsSlider,
  learning_process: LearningProcess,
  LearningProcess,
  upcoming_events: UpcomingEvents,
  UpcomingEvents,
  latest_blog: LatestBlog,
  LatestBlog,
  partners_slider: PartnersSlider,
  PartnersSlider,
  data_table: DataTableBlock,
  DataTableBlock,
  rich_text: RichTextBlock,
  RichTextBlock,
  RichText: RichTextBlock,
  contact_section: ContactSection,
  ContactSection,
}

const currentPage = computed(() => publicCmsStore.pagesBySlug[props.slug])

const activeSections = computed(() => {
  const sections = currentPage.value?.sections
  if (Array.isArray(sections) && sections.length > 0) {
    return sections
      .filter((s) => s.is_active !== false)
      .sort((a, b) => (a.sort_order ?? 0) - (b.sort_order ?? 0))
  }
  return props.defaultSections
})

function resolveSectionComponent(sec) {
  const key = sec.code || sec.component_code || sec.component_type?.code || sec.component_name
  return COMPONENT_MAP[key] || null
}
</script>
