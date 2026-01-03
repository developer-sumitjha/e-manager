<template>
  <div class="cookie-notice">
    <div class="cookie-content">
      <p>{{ settings.cookie_notice_text || 'We use cookies to enhance your experience on our site.' }}</p>
      <button @click="$emit('accept')" class="btn btn-primary">Accept</button>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue'
import { useSettingsStore } from '@/store/settings'

export default {
  name: 'CookieNotice',
  emits: ['accept'],
  setup() {
    const settingsStore = useSettingsStore()
    const settings = computed(() => settingsStore.settings || {})
    return { settings }
  }
}
</script>

<style scoped>
.cookie-notice {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(30, 41, 59, 0.95);
  backdrop-filter: blur(10px);
  padding: 1.5rem;
  z-index: 1000;
  animation: slideIn 0.3s ease;
}

.cookie-content {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 2rem;
  flex-wrap: wrap;
}

.cookie-content p {
  color: white;
  margin: 0;
}

@keyframes slideIn {
  from { transform: translateY(100%); }
  to { transform: translateY(0); }
}
</style>






