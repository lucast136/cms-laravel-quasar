<template>
  <section
    :id="sectionSettings?.custom_id || undefined"
    class="section-padding position-relative"
    :class="sectionSettings?.custom_class"
    :style="sectionStyle"
  >
    <div :class="sectionSettings?.full_width ? 'q-px-lg' : 'container-custom'">
      <div v-if="title || subtitle" class="section-heading">
        <span v-if="subtitle" class="sub-title">{{ subtitle }}</span>
        <h2 v-if="title">{{ title }}</h2>
      </div>

      <q-card flat bordered class="rounded-borders overflow-hidden shadow-1 bg-white">
        <q-markup-table
          :separator="tableStyle === 'bordered' ? 'cell' : 'horizontal'"
          flat
          class="full-width"
        >
          <thead class="bg-primary text-white">
            <tr>
              <th
                v-for="col in columns"
                :key="col.key"
                :class="`text-${col.align || 'left'}`"
                :style="col.width ? { width: col.width } : {}"
              >
                <span class="text-subtitle2 font-weight-700">{{ col.label }}</span>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(row, rIdx) in rows"
              :key="rIdx"
              :class="tableStyle === 'striped' && rIdx % 2 === 1 ? 'bg-grey-1' : ''"
            >
              <td
                v-for="col in columns"
                :key="col.key"
                :class="`text-${col.align || 'left'}`"
              >
                <template v-if="isBadgeCell(row[col.key])">
                  <q-badge
                    :color="row[col.key].color || 'primary'"
                    :label="row[col.key].badge"
                    class="q-px-sm q-py-xs text-weight-bold"
                  />
                </template>
                <template v-else>
                  <span class="text-body2 text-grey-9">{{ row[col.key] }}</span>
                </template>
              </td>
            </tr>
          </tbody>
        </q-markup-table>
      </q-card>

      <div v-if="footerNote" class="text-caption text-grey-7 q-mt-sm text-italic">
        {{ footerNote }}
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

const title = computed(() => props.sectionData?.title ?? 'Cronograma de Admisión 2026-I')
const subtitle = computed(
  () =>
    props.sectionData?.subtitle ??
    'Fechas oficiales del proceso de postulación ordinaria y extraordinaria',
)
const tableStyle = computed(() => props.sectionData?.style || 'striped')
const footerNote = computed(() => props.sectionData?.footer_note || '')

const columns = computed(() =>
  Array.isArray(props.sectionData?.columns) && props.sectionData.columns.length > 0
    ? props.sectionData.columns
    : [
        { key: 'fase', label: 'Etapa del Proceso', align: 'left', width: '35%' },
        { key: 'fecha', label: 'Fechas Programadas', align: 'center', width: '30%' },
        { key: 'modalidad', label: 'Modalidad', align: 'center', width: '20%' },
        { key: 'estado', label: 'Estado', align: 'center', width: '15%' },
      ],
)

const rows = computed(() =>
  Array.isArray(props.sectionData?.rows) && props.sectionData.rows.length > 0
    ? props.sectionData.rows
    : [
        {
          fase: 'Inscripción Ordinaria de Postulantes',
          fecha: '01 de Febrero al 15 de Marzo 2026',
          modalidad: 'Virtual / Presencial',
          estado: { badge: 'En Curso', color: 'positive' },
        },
      ],
)

const sectionStyle = computed(() => {
  const s = props.sectionSettings || {}
  return {
    paddingTop: s.padding_top !== undefined ? `${s.padding_top}px` : undefined,
    paddingBottom: s.padding_bottom !== undefined ? `${s.padding_bottom}px` : undefined,
    backgroundColor: s.background_color || undefined,
  }
})

function isBadgeCell(val) {
  return val && typeof val === 'object' && 'badge' in val
}
</script>

