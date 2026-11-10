<template>
  <PublicLayout>
    <template v-for="sec in activeSections" :key="sec.id">
      <component
        :is="resolveSectionComponent(sec)"
        v-if="resolveSectionComponent(sec)"
        :section-data="sec.data"
        :section-settings="sec.settings"
      />
    </template>
  </PublicLayout>
</template>

<script setup>
import { computed } from 'vue'
import { useMeta } from 'quasar'
import PublicLayout from '@/layouts/PublicLayout.vue'
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
import { usePublicCmsStore } from '@/stores/public-cms'

defineOptions({
  async preFetch({ store }) {
    const publicCmsStore = usePublicCmsStore(store)
    await Promise.all([
      publicCmsStore.bootstrapPublicSite(),
      publicCmsStore.fetchPage('inicio'),
    ])
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

const DEFAULT_SECTIONS = [
  { id: 'def-1', code: 'hero_slider', sort_order: 1, is_active: true },
  { id: 'def-2', code: 'feature_cards', sort_order: 2, is_active: true },
  { id: 'def-3', code: 'about_section', sort_order: 3, is_active: true },
  { id: 'def-4', code: 'courses_section', sort_order: 4, is_active: true },
  { id: 'def-5', code: 'categories_grid', sort_order: 5, is_active: true },
  { id: 'def-6', code: 'instructors_grid', sort_order: 6, is_active: true },
  { id: 'def-7', code: 'why_choose_us', sort_order: 7, is_active: true },
  { id: 'def-8', code: 'stats_counter', sort_order: 8, is_active: true },
  { id: 'def-9', code: 'testimonials_slider', sort_order: 9, is_active: true },
  { id: 'def-10', code: 'learning_process', sort_order: 10, is_active: true },
  { id: 'def-11', code: 'upcoming_events', sort_order: 11, is_active: true },
  { id: 'def-12', code: 'latest_blog', sort_order: 12, is_active: true },
  { id: 'def-13', code: 'partners_slider', sort_order: 13, is_active: true },
]

const activeSections = computed(() => {
  const remotePage = publicCmsStore.pagesBySlug.inicio
  if (
    remotePage?.sections &&
    Array.isArray(remotePage.sections) &&
    remotePage.sections.length > 0
  ) {
    return remotePage.sections
      .filter((s) => s.is_active !== false)
      .sort((a, b) => (a.sort_order ?? 0) - (b.sort_order ?? 0))
  }
  return DEFAULT_SECTIONS
})

function resolveSectionComponent(sec) {
  const key = sec.code || sec.component_code || sec.component_type?.code || sec.component_name
  return COMPONENT_MAP[key] || null
}

// Inyección SSR de Meta Etiquetas SEO en Español para Google
useMeta(() => {
  const page = publicCmsStore.pagesBySlug.inicio
  const metas = page?.metas || []
  const metaTitle =
    metas.find((m) => m.meta_key === 'meta_title')?.meta_value ||
    page?.title ||
    'IESTP DEPERU MASTER - Educación Técnica Superior Licenciada'
  const metaDesc =
    metas.find((m) => m.meta_key === 'meta_description')?.meta_value ||
    page?.excerpt ||
    'Sitio web oficial del IESTP DEPERU MASTER. Formación superior tecnológica licenciada en carreras profesionales de alta demanda.'
  const metaKeywords =
    metas.find((m) => m.meta_key === 'meta_keywords')?.meta_value ||
    'instituto de educacion superior tecnologico, carreras tecnicas, admision 2026, lircay huancavelica'

  return {
    title: metaTitle,
    meta: {
      description: { name: 'description', content: metaDesc },
      keywords: { name: 'keywords', content: metaKeywords },
      ogTitle: { property: 'og:title', content: metaTitle },
      ogDescription: { property: 'og:description', content: metaDesc },
      ogImage: { property: 'og:image', content: page?.featured_image || '/img/banner/slide1.jpg' },
    },
  }
})
</script>
