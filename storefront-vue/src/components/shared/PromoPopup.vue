<template>
  <div class="promo-overlay" @click="$emit('close')">
    <div class="promo-popup" @click.stop>
      <button @click="$emit('close')" class="close-btn">
        <i class="fas fa-times"></i>
      </button>
      <img v-if="settings.promo_popup_image" :src="settings.promo_popup_image" alt="Promo" class="promo-image" />
      <div class="promo-content">
        <p>{{ settings.promo_popup_content || 'Special Offer! Get 10% off your first order!' }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue'
import { useSettingsStore } from '@/store/settings'

export default {
  name: 'PromoPopup',
  emits: ['close'],
  setup() {
    const settingsStore = useSettingsStore()
    const settings = computed(() => settingsStore.settings || {})
    return { settings }
  }
}
</script>

<style scoped>
.promo-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
  animation: fadeIn 0.3s ease;
}

.promo-popup {
  background: white;
  border-radius: 16px;
  max-width: 500px;
  width: 90%;
  position: relative;
  overflow: hidden;
  box-shadow: var(--shadow-xl);
  animation: slideIn 0.3s ease;
}

.close-btn {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: white;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  z-index: 1;
  box-shadow: var(--shadow-md);
}

.promo-image {
  width: 100%;
  height: auto;
}

.promo-content {
  padding: 2rem;
  text-align: center;
}

.promo-content p {
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--primary-color);
}

@keyframes slideIn {
  from { transform: scale(0.9); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}
</style>






