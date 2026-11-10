import { ref } from 'vue'

export function useCounter(targetValue, duration = 2000) {
  const count = ref(0)
  let hasAnimated = false

  const startAnimation = () => {
    if (hasAnimated || typeof window === 'undefined') {
      count.value = targetValue
      return
    }

    hasAnimated = true
    const startTime = performance.now()
    const startNum = 0

    const updateCount = (currentTime) => {
      const elapsed = currentTime - startTime
      const progress = Math.min(elapsed / duration, 1)
      // Ease out quad
      const easeProgress = 1 - (1 - progress) * (1 - progress)
      count.value = Math.floor(startNum + easeProgress * (targetValue - startNum))

      if (progress < 1) {
        requestAnimationFrame(updateCount)
      } else {
        count.value = targetValue
      }
    }

    requestAnimationFrame(updateCount)
  }

  return {
    count,
    startAnimation,
  }
}
