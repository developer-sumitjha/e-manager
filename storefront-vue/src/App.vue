<template>
  <div id="app" :class="{ 'loading': isLoading }">
    <!-- Loading Overlay -->
    <div v-if="isInitializing" class="app-loader">
      <div class="loader-content">
        <div class="spinner"></div>
        <p>Loading Store...</p>
      </div>
    </div>

    <!-- Main App -->
    <template v-else-if="!maintenanceMode">
      <Header />
      <main class="main-content">
        <router-view v-slot="{ Component }">
          <transition name="fade" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </main>
      <Footer />
      
      <!-- Cookie Notice -->
      <CookieNotice v-if="showCookieNotice" @accept="acceptCookies" />
      
      <!-- Promo Popup -->
      <PromoPopup v-if="showPromoPopup" @close="closePromoPopup" />
    </template>

    <!-- Maintenance Mode -->
    <div v-else class="maintenance-mode">
      <div class="maintenance-content">
        <i class="fas fa-tools"></i>
        <h1>Under Maintenance</h1>
        <p>{{ maintenanceMessage }}</p>
        <p class="maintenance-hint">We'll be back soon!</p>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import { useSettingsStore } from '@/store/settings'
import { useCartStore } from '@/store/cart'
import Header from '@/components/layout/Header.vue'
import Footer from '@/components/layout/Footer.vue'
import CookieNotice from '@/components/shared/CookieNotice.vue'
import PromoPopup from '@/components/shared/PromoPopup.vue'

export default {
  name: 'App',
  components: {
    Header,
    Footer,
    CookieNotice,
    PromoPopup,
  },
  setup() {
    const settingsStore = useSettingsStore()
    const cartStore = useCartStore()
    
    const isInitializing = ref(true)
    const isLoading = ref(false)
    const showCookieNotice = ref(false)
    const showPromoPopup = ref(false)
    
    const maintenanceMode = computed(() => settingsStore.settings?.maintenance_mode || false)
    const maintenanceMessage = computed(() => 
      settingsStore.settings?.maintenance_message || 'We are currently updating our store. Please check back soon!'
    )
    
    // Get subdomain from URL or use default
    const getSubdomain = () => {
      const hostname = window.location.hostname
      const parts = hostname.split('.')
      
      // For localhost testing, use query parameter or default
      if (hostname === 'localhost' || hostname === '127.0.0.1') {
        const urlParams = new URLSearchParams(window.location.search)
        return urlParams.get('store') || 'demo'
      }
      
      // For production, extract subdomain
      return parts.length > 2 ? parts[0] : 'demo'
    }
    
    const subdomain = getSubdomain()
    
    const initializeApp = async () => {
      try {
        // Load settings and apply theme
        await settingsStore.fetchSettings(subdomain)
        applyTheme()
        
        // Load cart from localStorage
        cartStore.loadCart()
        
        // Check cookie consent
        const cookieConsent = localStorage.getItem('cookie_consent')
        if (!cookieConsent && settingsStore.settings.show_cookie_notice) {
          setTimeout(() => {
            showCookieNotice.value = true
          }, 2000)
        }
        
        // Check promo popup
        const promoShown = sessionStorage.getItem('promo_shown')
        if (!promoShown && settingsStore.settings.show_promo_popup) {
          setTimeout(() => {
            showPromoPopup.value = true
          }, 5000)
        }
        
      } catch (error) {
        console.error('Failed to initialize app:', error)
      } finally {
        isInitializing.value = false
      }
    }
    
    const applyTheme = () => {
      const settings = settingsStore.settings
      if (!settings) return
      
      // Apply colors
      document.documentElement.style.setProperty('--primary-color', settings.primary_color)
      document.documentElement.style.setProperty('--secondary-color', settings.secondary_color)
      document.documentElement.style.setProperty('--accent-color', settings.accent_color)
      document.documentElement.style.setProperty('--text-color', settings.text_color)
      document.documentElement.style.setProperty('--background-color', settings.background_color)
      document.documentElement.style.setProperty('--header-bg-color', settings.header_bg_color)
      document.documentElement.style.setProperty('--footer-bg-color', settings.footer_bg_color)
      
      // Apply typography
      document.documentElement.style.setProperty('--font-family', settings.font_family)
      document.documentElement.style.setProperty('--font-size', `${settings.font_size}px`)
      document.documentElement.style.setProperty('--heading-font', settings.heading_font)
      
      // Apply custom CSS
      if (settings.custom_css) {
        const style = document.createElement('style')
        style.textContent = settings.custom_css
        document.head.appendChild(style)
      }
      
      // Apply custom JS
      if (settings.custom_js) {
        const script = document.createElement('script')
        script.textContent = settings.custom_js
        document.body.appendChild(script)
      }
      
      // Update meta tags
      if (settings.meta_title) {
        document.title = settings.meta_title
      } else if (settings.site_name) {
        document.title = settings.site_name
      }
      
      if (settings.meta_description) {
        let metaDesc = document.querySelector('meta[name="description"]')
        if (!metaDesc) {
          metaDesc = document.createElement('meta')
          metaDesc.name = 'description'
          document.head.appendChild(metaDesc)
        }
        metaDesc.content = settings.meta_description
      }
      
      // Update favicon
      if (settings.favicon) {
        let favicon = document.querySelector('link[rel="icon"]')
        if (!favicon) {
          favicon = document.createElement('link')
          favicon.rel = 'icon'
          document.head.appendChild(favicon)
        }
        favicon.href = settings.favicon
      }
    }
    
    const acceptCookies = () => {
      localStorage.setItem('cookie_consent', 'true')
      showCookieNotice.value = false
    }
    
    const closePromoPopup = () => {
      sessionStorage.setItem('promo_shown', 'true')
      showPromoPopup.value = false
    }
    
    onMounted(() => {
      initializeApp()
    })
    
    return {
      isInitializing,
      isLoading,
      maintenanceMode,
      maintenanceMessage,
      showCookieNotice,
      showPromoPopup,
      acceptCookies,
      closePromoPopup,
    }
  },
}
</script>

<style scoped>
.app-loader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

.loader-content {
  text-align: center;
  color: white;
}

.spinner {
  width: 60px;
  height: 60px;
  border: 4px solid rgba(255, 255, 255, 0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 1rem;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.main-content {
  min-height: calc(100vh - 400px);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.maintenance-mode {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  text-align: center;
  padding: 2rem;
}

.maintenance-content {
  max-width: 600px;
}

.maintenance-content i {
  font-size: 80px;
  margin-bottom: 2rem;
  opacity: 0.9;
}

.maintenance-content h1 {
  font-size: 3rem;
  font-weight: 800;
  margin-bottom: 1rem;
}

.maintenance-content p {
  font-size: 1.25rem;
  margin-bottom: 1rem;
  opacity: 0.95;
}

.maintenance-hint {
  font-size: 1rem;
  opacity: 0.8;
}
</style>






