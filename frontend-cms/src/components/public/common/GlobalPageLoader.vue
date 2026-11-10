<template>
  <Transition name="global-loader-fade">
    <div
      v-if="visible"
      class="global-page-loader"
      role="status"
      aria-live="polite"
      aria-label="Cargando página"
    >
      <div class="loader-emblem-container">
        <!-- Pista circular base -->
        <span class="loader-ring-track" aria-hidden="true"></span>
        <!-- Aro giratorio principal alrededor del escudo -->
        <span class="loader-ring-primary" aria-hidden="true"></span>
        <!-- Aro giratorio secundario de acento -->
        <span class="loader-ring-accent" aria-hidden="true"></span>

        <!-- Escudo Institucional DPM en el centro -->
        <img
          :src="escudoDpmLogo"
          alt="Escudo IESTP DEPERU MASTER"
          class="loader-shield-img"
        />
      </div>
    </div>
  </Transition>
</template>

<script setup>
import escudoDpmLogo from '@/assets/logo/Escudo DPM.svg'

defineProps({
  visible: {
    type: Boolean,
    default: false,
  },
})
</script>

<style scoped lang="scss">
@import '@/css/template/variables';

.global-page-loader {
  position: fixed;
  inset: 0;
  width: 100vw;
  height: 100vh;
  background-color: #ffffff;
  z-index: 99999;
  display: flex;
  align-items: center;
  justify-content: center;
  pointer-events: all;
  user-select: none;
}

.loader-emblem-container {
  position: relative;
  width: 148px;
  height: 148px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.loader-ring-track {
  position: absolute;
  inset: 0;
  border-radius: 50%;
  border: 3.5px solid rgba(0, 50, 120, 0.09);
}

.loader-ring-primary {
  position: absolute;
  inset: 0;
  border-radius: 50%;
  border: 3.5px solid transparent;
  border-top-color: $theme-primary;
  border-right-color: $theme-primary;
  animation: dpm-loader-spin 1.05s cubic-bezier(0.55, 0.15, 0.45, 0.85) infinite;
}

.loader-ring-accent {
  position: absolute;
  inset: 9px;
  border-radius: 50%;
  border: 2.5px solid transparent;
  border-bottom-color: $theme-secondary;
  border-left-color: rgba(237, 50, 55, 0.35);
  animation: dpm-loader-spin-reverse 1.45s linear infinite;
}

.loader-shield-img {
  width: 88px;
  height: 88px;
  object-fit: contain;
  position: relative;
  z-index: 2;
  animation: dpm-shield-pulse 1.8s ease-in-out infinite;
}

@keyframes dpm-loader-spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

@keyframes dpm-loader-spin-reverse {
  0% {
    transform: rotate(360deg);
  }
  100% {
    transform: rotate(0deg);
  }
}

@keyframes dpm-shield-pulse {
  0%,
  100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.04);
  }
}

.global-loader-fade-enter-active {
  transition: opacity 0.15s ease;
}

.global-loader-fade-leave-active {
  transition: opacity 0.35s ease;
}

.global-loader-fade-enter-from,
.global-loader-fade-leave-to {
  opacity: 0;
}
</style>

