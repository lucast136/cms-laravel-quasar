<template>
  <section class="contact-section-block" :style="sectionStyle">
    <div :class="sectionSettings.full_width ? 'container-fluid q-px-xl' : 'container-custom'">
      <!-- Tarjetas de Canales de Contacto -->
      <div v-if="cardsList.length > 0" class="row q-col-gutter-lg q-mb-xl">
        <div v-for="(card, idx) in cardsList" :key="idx" class="col-12 col-md-4">
          <div class="contact-info-card text-center q-pa-xl">
            <div class="contact-icon q-mb-md">
              <i :class="card.icon || 'ti-email'"></i>
            </div>
            <h4 class="text-subtitle1 font-weight-700 q-mb-sm">{{ card.title }}</h4>
            <p class="text-grey-8 q-mb-xs font-weight-bold">
              {{ card.value }}
            </p>
            <p v-if="card.subtitle" class="text-grey-6 q-mb-none text-caption">
              {{ card.subtitle }}
            </p>
          </div>
        </div>
      </div>

      <!-- Bloque Visual de Formulario e Imagen Institucional -->
      <div class="row items-center q-col-gutter-xl">
        <!-- Columna Imagen -->
        <div class="col-12 col-lg-5 gt-md">
          <div class="rounded-borders overflow-hidden shadow-3">
            <img
              :src="blockData.image || '/img/content/contact-img-01.jpg'"
              :alt="blockData.title || 'Contacto IESTP DEPERU MASTER'"
              class="w-100 full-width"
            />
          </div>
        </div>

        <!-- Columna Formulario Visual (Bloque sin eventos de envío de correo por ahora) -->
        <div class="col-12 col-lg-7">
          <div class="contact-form-box bg-white q-pa-xl rounded-borders shadow-2">
            <div class="section-heading text-start q-mb-md">
              <span class="sub-title">{{ blockData.subtitle }}</span>
              <h2 class="text-primary font-weight-800">{{ blockData.title }}</h2>
              <p v-if="blockData.description" class="text-grey-7 q-mt-xs q-mb-none">
                {{ blockData.description }}
              </p>
            </div>

            <form @submit.prevent>
              <div class="row q-col-gutter-md">
                <div class="col-12 col-sm-6">
                  <q-input
                    v-model="form.name"
                    label="Nombres y Apellidos *"
                    outlined
                    dense
                  />
                </div>
                <div class="col-12 col-sm-6">
                  <q-input
                    v-model="form.email"
                    type="email"
                    label="Correo Electrónico *"
                    outlined
                    dense
                  />
                </div>
                <div class="col-12 col-sm-6">
                  <q-input
                    v-model="form.phone"
                    label="Número de Teléfono / Celular *"
                    outlined
                    dense
                  />
                </div>
                <div class="col-12 col-sm-6">
                  <q-select
                    v-model="form.program"
                    :options="programOptions"
                    label="Carrera o Área de Interés"
                    outlined
                    dense
                  />
                </div>
                <div class="col-12">
                  <q-input
                    v-model="form.message"
                    type="textarea"
                    label="Escribe tu consulta o mensaje..."
                    outlined
                    rows="4"
                  />
                </div>
                <div class="col-12 q-mt-md">
                  <button type="button" class="butn">
                    <i class="fas fa-paper-plane icon-arrow before"></i>
                    <span class="label">{{ blockData.button_text || 'Enviar Solicitud' }}</span>
                    <i class="fas fa-paper-plane icon-arrow after"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { reactive, computed } from 'vue'

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
    icon: 'ti-email',
    title: 'Correo Institucional',
    value: 'informes@deperumaster.edu.pe',
    subtitle: 'Mesa de Partes y Admisión',
  },
  {
    icon: 'ti-map-alt',
    title: 'Sede Académica',
    value: 'Av. Principal s/n, Lircay, Angaraes',
    subtitle: 'Huancavelica - Perú',
  },
  {
    icon: 'ti-mobile',
    title: 'Central Telefónica',
    value: '(01) 456-7890',
    subtitle: 'WhatsApp: +51 987 654 321',
  },
]

const blockData = computed(() => ({
  subtitle: props.sectionData?.subtitle || 'Envíanos un Mensaje',
  title: props.sectionData?.title || 'Solicita Información o Admisión',
  description:
    props.sectionData?.description ||
    'Completa tus datos y nuestro equipo de admisión se comunicará contigo a la brevedad.',
  image: props.sectionData?.image || '/img/content/contact-img-01.jpg',
  button_text: props.sectionData?.button_text || 'Enviar Solicitud',
}))

const cardsList = computed(() => {
  if (Array.isArray(props.sectionData?.cards) && props.sectionData.cards.length > 0) {
    return props.sectionData.cards
  }
  return DEFAULT_CARDS
})

const programOptions = [
  'Desarrollo de Sistemas de Información',
  'Administración de Redes y Comunicaciones',
  'Diseño y Animación Digital Multimedia',
  'Marketing Digital y Comercio Electrónico',
  'Contabilidad y Finanzas',
  'Ciberseguridad y Auditoría TI',
]

const form = reactive({
  name: '',
  email: '',
  phone: '',
  program: programOptions[0],
  message: '',
})

const sectionStyle = computed(() => ({
  paddingTop:
    props.sectionSettings?.padding_top !== undefined
      ? `${props.sectionSettings.padding_top}px`
      : '80px',
  paddingBottom:
    props.sectionSettings?.padding_bottom !== undefined
      ? `${props.sectionSettings.padding_bottom}px`
      : '80px',
  backgroundColor: props.sectionSettings?.background_color || '#f8fafc',
}))
</script>

<style scoped lang="scss">
@import '@/css/template/variables';

.contact-info-card {
  background: #ffffff;
  border-radius: 8px;
  box-shadow: 0 5px 25px rgba(0, 0, 0, 0.06);
  border: 1px solid $theme-border;
  height: 100%;
  transition: transform 0.3s ease;

  &:hover {
    transform: translateY(-5px);
    border-color: $theme-primary;
  }

  .contact-icon {
    width: 65px;
    height: 65px;
    border-radius: 50%;
    background-color: rgba(47, 191, 167, 0.12);
    color: $theme-primary;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
  }
}
</style>

