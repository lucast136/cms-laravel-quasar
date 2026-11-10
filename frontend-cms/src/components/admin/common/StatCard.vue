<template>
  <q-card flat bordered class="stat-card">
    <q-card-section class="q-pa-md">
      <div class="row items-center justify-between no-wrap">
        <div>
          <div class="text-caption text-uppercase text-grey-7 text-weight-medium">
            {{ title }}
          </div>
          <div class="text-h4 text-weight-bold q-my-xs text-dark">
            {{ value }}
          </div>
          <div class="text-caption text-grey-6 row items-center">
            <q-icon
              v-if="trendIcon"
              :name="trendIcon"
              :color="trendColor"
              size="16px"
              class="q-mr-xs"
            />
            <span :class="`text-${trendColor} text-weight-bold q-mr-xs`">
              {{ trendText }}
            </span>
            <span>{{ subtitle }}</span>
          </div>
        </div>

        <q-avatar :icon="icon" :color="color" text-color="white" size="52px" class="shadow-2" />
      </div>
    </q-card-section>
  </q-card>
</template>

<script setup>
defineProps({
  title: { type: String, required: true },
  value: { type: [String, Number], required: true },
  subtitle: { type: String, default: '' },
  icon: { type: String, required: true },
  color: { type: String, default: 'primary' },
  trendText: { type: String, default: '' },
  trendIcon: { type: String, default: '' },
  trendColor: { type: String, default: 'positive' },
})
</script>

<style scoped>
.stat-card {
  border-radius: 12px;
  transition:
    transform 0.2s ease,
    box-shadow 0.2s ease;
}
.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
}
</style>
