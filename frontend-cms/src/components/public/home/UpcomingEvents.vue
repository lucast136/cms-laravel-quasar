<template>
  <section
    :id="sectionSettings?.custom_id || undefined"
    class="section-padding position-relative"
    :class="sectionSettings?.custom_class"
    :style="sectionStyle"
  >
    <div class="container-custom">
      <div class="section-heading">
        <span class="sub-title">{{ subtitle }}</span>
        <h2>{{ title }}</h2>
      </div>

      <div class="row q-col-gutter-xl">
        <div v-for="(event, idx) in visibleEvents" :key="event.id || idx" class="col-12 col-md-6">
          <div class="event-box">
            <div class="event-img">
              <img :src="event.image" :alt="event.title" />
            </div>
            <div class="event-content">
              <div class="event-meta">
                <i class="far fa-calendar-alt q-mr-xs"></i> {{ event.date }} | {{ event.time }}
              </div>
              <h4 class="text-subtitle1 font-weight-700">
                <router-link :to="event.link || '/eventos'" class="text-dark">
                  {{ event.title }}
                </router-link>
              </h4>
              <div class="event-location q-mb-md">
                <i class="fas fa-map-marker-alt text-primary q-mr-xs"></i> {{ event.location }}
              </div>
              <div>
                <router-link
                  :to="event.link || '/contacto'"
                  class="butn-style1 secondary"
                >
                  {{ event.button_text || 'Registrarse Gratis +' }}
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-if="events.length > visibleLimit" class="text-center q-mt-xl">
        <button type="button" class="butn" @click="visibleLimit += 5">
          <i class="fas fa-plus-circle icon-arrow before"></i>
          <span class="label">Ver Más Eventos ({{ events.length - visibleLimit }})</span>
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

const DEFAULT_EVENTS = [
  {
    id: 1,
    title: 'Taller Práctico: Creación de Aplicaciones Web con Vue y Quasar',
    date: '15 Nov, 2026',
    time: '06:00 PM - 08:00 PM',
    location: 'Auditorio Principal & Transmisión Online',
    image: '/img/content/event-01.jpg',
    button_text: 'Registrarse Gratis +',
    link: '/events/1',
  },
  {
    id: 2,
    title: 'Seminario: Fundamentos de Ciberseguridad y Protección de Datos',
    date: '22 Nov, 2026',
    time: '07:00 PM - 09:00 PM',
    location: 'Campus Virtual DEPERU MASTER',
    image: '/img/content/event-02.jpg',
    button_text: 'Registrarse Gratis +',
    link: '/events/2',
  },
]

const subtitle = computed(() => props.sectionData?.subtitle || 'Agenda Académica')
const title = computed(() => props.sectionData?.title || 'Próximos Eventos y Webinars')

const visibleLimit = ref(5)

const events = computed(() =>
  Array.isArray(props.sectionData?.events) && props.sectionData.events.length > 0
    ? props.sectionData.events
    : DEFAULT_EVENTS,
)

const visibleEvents = computed(() => events.value.slice(0, visibleLimit.value))

const sectionStyle = computed(() => {
  const s = props.sectionSettings || {}
  return {
    paddingTop: s.padding_top !== undefined ? `${s.padding_top}px` : undefined,
    paddingBottom: s.padding_bottom !== undefined ? `${s.padding_bottom}px` : undefined,
    backgroundColor: s.background_color || undefined,
  }
})
</script>
