<template>
  <div v-if="settings.show_banner" class="hero-banner" :style="bannerStyle">
    <div class="hero-overlay"></div>
    <div class="container hero-container">
      <div class="hero-content">
        <h1 class="hero-title">{{ settings.banner_title || 'Welcome to Our Store' }}</h1>
        <p class="hero-subtitle">{{ settings.banner_subtitle || 'Discover amazing products at great prices' }}</p>
        <router-link 
          :to="settings.banner_button_link || '/products'" 
          class="btn btn-primary btn-lg hero-btn"
        >
          {{ settings.banner_button_text || 'Shop Now' }}
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue'
import { useSettingsStore } from '@/store/settings'

export default {
  name: 'HeroBanner',
  setup() {
    const settingsStore = useSettingsStore()
    const settings = computed(() => settingsStore.settings || {})
    
    const bannerStyle = computed(() => {
      if (settings.value.banner_image) {
        return {
          backgroundImage: `url(${settings.value.banner_image})`,
          backgroundSize: 'cover',
          backgroundPosition: 'center'
        }
      }
      return {
        background: `linear-gradient(135deg, ${settings.value.primary_color || '#667eea'}, ${settings.value.secondary_color || '#764ba2'})`
      }
    })
    
    return {
      settings,
      bannerStyle
    }
  }
}
</script>

<style scoped>
.hero-banner {
  position: relative;
  min-height: 500px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 3rem;
}

.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.4);
}

.hero-container {
  position: relative;
  z-index: 1;
}

.hero-content {
  text-align: center;
  color: white;
  max-width: 800px;
  margin: 0 auto;
  animation: fadeIn 1s ease;
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 800;
  margin-bottom: 1rem;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.hero-subtitle {
  font-size: 1.5rem;
  margin-bottom: 2rem;
  opacity: 0.95;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
}

.hero-btn {
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
}

@media (max-width: 768px) {
  .hero-banner {
    min-height: 400px;
  }
  
  .hero-title {
    font-size: 2.5rem;
  }
  
  .hero-subtitle {
    font-size: 1.125rem;
  }
}
</style>






