<template>
  <div class="section-list column full-height bg-white">
    <!-- Header del Árbol de Secciones -->
    <div class="q-pa-md border-bottom row items-center justify-between">
      <div>
        <div class="text-subtitle1 text-weight-bold text-dark">Estructura de Secciones</div>
        <div class="text-caption text-grey-6">{{ sections.length }} bloques en esta página</div>
      </div>

      <q-btn
        unelevated
        color="primary"
        icon="add"
        label="Agregar Bloque"
        size="sm"
        no-caps
        @click="$emit('open-palette')"
      />
    </div>

    <!-- Lista de Secciones -->
    <div class="col overflow-auto q-py-xs">
      <q-list separator class="q-px-sm">
        <div
          v-for="(section, index) in sections"
          :key="section.id"
          :class="[
            'section-item q-my-xs rounded-borders cursor-pointer',
            { 'section-item-active': selectedId === section.id },
            { 'section-item-inactive': !section.is_active },
          ]"
          @click="$emit('select-section', section.id)"
        >
          <q-item dense class="q-py-sm">
            <!-- Posición / Índice correlativo -->
            <q-item-section avatar class="min-avatar q-pr-xs">
              <q-badge
                :color="selectedId === section.id ? 'primary' : 'grey-5'"
                :label="index + 1"
                rounded
              />
            </q-item-section>

            <!-- Ícono y Nombre de Componente -->
            <q-item-section>
              <q-item-label class="text-weight-bold text-body2 text-dark ellipsis">
                {{ getComponentName(section.component_code) }}
              </q-item-label>
              <q-item-label caption class="text-grey-6 font-mono ellipsis">
                {{ section.component_code }}
              </q-item-label>
            </q-item-section>

            <!-- Acciones Rápidas (Subir, Bajar, Visibilidad, Opciones) -->
            <q-item-section side class="no-wrap row items-center q-gutter-x-none">
              <!-- Switch de Visibilidad -->
              <q-btn
                flat
                round
                dense
                size="sm"
                :icon="section.is_active ? 'visibility' : 'visibility_off'"
                :color="section.is_active ? 'primary' : 'grey-5'"
                @click.stop="$emit('toggle-active', section.id)"
              >
                <q-tooltip>{{ section.is_active ? 'Visible en web' : 'Oculto' }}</q-tooltip>
              </q-btn>

              <!-- Mover Arriba -->
              <q-btn
                flat
                round
                dense
                size="sm"
                icon="keyboard_arrow_up"
                :disable="index === 0"
                color="grey-7"
                @click.stop="$emit('move-section', index, 'up')"
              >
                <q-tooltip>Mover arriba</q-tooltip>
              </q-btn>

              <!-- Mover Abajo -->
              <q-btn
                flat
                round
                dense
                size="sm"
                icon="keyboard_arrow_down"
                :disable="index === sections.length - 1"
                color="grey-7"
                @click.stop="$emit('move-section', index, 'down')"
              >
                <q-tooltip>Mover abajo</q-tooltip>
              </q-btn>

              <!-- Menú Extra (Duplicar / Eliminar) -->
              <q-btn flat round dense size="sm" icon="more_vert" color="grey-7" @click.stop>
                <q-menu anchor="bottom right" self="top right">
                  <q-list dense style="min-width: 140px">
                    <q-item clickable v-close-popup @click="$emit('duplicate-section', section.id)">
                      <q-item-section avatar
                        ><q-icon name="content_copy" size="xs"
                      /></q-item-section>
                      <q-item-section>Duplicar</q-item-section>
                    </q-item>
                    <q-separator />
                    <q-item
                      clickable
                      v-close-popup
                      class="text-negative"
                      @click="$emit('remove-section', section.id)"
                    >
                      <q-item-section avatar
                        ><q-icon name="delete" color="negative" size="xs"
                      /></q-item-section>
                      <q-item-section>Eliminar</q-item-section>
                    </q-item>
                  </q-list>
                </q-menu>
              </q-btn>
            </q-item-section>
          </q-item>
        </div>

        <div v-if="sections.length === 0" class="text-center q-pa-lg text-grey-6">
          <q-icon name="view_in_ar" size="36px" />
          <div class="text-caption q-mt-xs">No hay bloques agregados</div>
          <q-btn
            flat
            color="primary"
            label="+ Agregar el primer bloque"
            size="sm"
            no-caps
            class="q-mt-sm"
            @click="$emit('open-palette')"
          />
        </div>
      </q-list>
    </div>
  </div>
</template>

<script setup>
import { COMPONENT_TYPES } from '@/constants/componentTypes'

defineProps({
  sections: { type: Array, required: true },
  selectedId: { type: [Number, String], default: null },
})

defineEmits([
  'select-section',
  'open-palette',
  'move-section',
  'toggle-active',
  'duplicate-section',
  'remove-section',
])

function getComponentName(code) {
  const found = COMPONENT_TYPES.find((c) => c.code === code)
  return found ? found.name : code
}
</script>

<style scoped>
.border-bottom {
  border-bottom: 1px solid #e2e8f0;
}
.section-item {
  border: 1px solid #e2e8f0;
  background-color: #ffffff;
  transition: all 0.2s ease;
}
.section-item:hover {
  border-color: #003278;
  background-color: #f8fafc;
}
.section-item-active {
  border: 2px solid #003278 !important;
  background-color: rgba(0, 50, 120, 0.05) !important;
}
.section-item-inactive {
  opacity: 0.55;
  background-color: #f1f5f9;
}
.rounded-borders {
  border-radius: 8px;
}
.font-mono {
  font-family: monospace;
  font-size: 11px;
}
.min-avatar {
  min-width: 32px;
}
</style>
