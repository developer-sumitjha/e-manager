<template>
  <footer class="footer" :style="{ backgroundColor: settings.footer_bg_color, color: '#ffffff' }">
    <div class="container">
      <div class="footer-content">
        <!-- About -->
        <div class="footer-section">
          <h3>{{ settings.site_name }}</h3>
          <p v-if="settings.footer_about">{{ settings.footer_about }}</p>
          <p v-else>Your trusted online store for quality products.</p>
          
          <!-- Social Links -->
          <div v-if="settings.show_social_links" class="social-links">
            <a v-if="settings.facebook_url" :href="settings.facebook_url" target="_blank" rel="noopener">
              <i class="fab fa-facebook"></i>
            </a>
            <a v-if="settings.instagram_url" :href="settings.instagram_url" target="_blank" rel="noopener">
              <i class="fab fa-instagram"></i>
            </a>
            <a v-if="settings.twitter_url" :href="settings.twitter_url" target="_blank" rel="noopener">
              <i class="fab fa-twitter"></i>
            </a>
            <a v-if="settings.youtube_url" :href="settings.youtube_url" target="_blank" rel="noopener">
              <i class="fab fa-youtube"></i>
            </a>
            <a v-if="settings.linkedin_url" :href="settings.linkedin_url" target="_blank" rel="noopener">
              <i class="fab fa-linkedin"></i>
            </a>
          </div>
        </div>
        
        <!-- Quick Links -->
        <div class="footer-section">
          <h4>Quick Links</h4>
          <ul class="footer-links">
            <li><router-link to="/">Home</router-link></li>
            <li><router-link to="/products">Products</router-link></li>
            <li><router-link to="/cart">Cart</router-link></li>
          </ul>
        </div>
        
        <!-- Contact -->
        <div class="footer-section">
          <h4>Contact Us</h4>
          <ul class="footer-contact">
            <li v-if="settings.contact_email">
              <i class="fas fa-envelope"></i>
              <a :href="`mailto:${settings.contact_email}`">{{ settings.contact_email }}</a>
            </li>
            <li v-if="settings.contact_phone">
              <i class="fas fa-phone"></i>
              <a :href="`tel:${settings.contact_phone}`">{{ settings.contact_phone }}</a>
            </li>
            <li v-if="settings.address">
              <i class="fas fa-map-marker-alt"></i>
              <span>{{ settings.address }}</span>
            </li>
          </ul>
        </div>
      </div>
      
      <!-- Copyright -->
      <div class="footer-bottom">
        <p>&copy; {{ currentYear }} {{ settings.site_name }}. All rights reserved.</p>
      </div>
    </div>
  </footer>
</template>

<script>
import { computed } from 'vue'
import { useSettingsStore } from '@/store/settings'

export default {
  name: 'Footer',
  setup() {
    const settingsStore = useSettingsStore()
    const settings = computed(() => settingsStore.settings || {})
    const currentYear = new Date().getFullYear()
    
    return { settings, currentYear }
  }
}
</script>

<style scoped>
.footer {
  margin-top: 4rem;
  padding: 3rem 0 1rem;
}

.footer-content {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
  margin-bottom: 2rem;
}

.footer-section h3,
.footer-section h4 {
  margin-bottom: 1rem;
  color: white;
}

.footer-section p {
  color: rgba(255, 255, 255, 0.8);
  line-height: 1.6;
}

.social-links {
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
}

.social-links a {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  color: white;
  font-size: 1.25rem;
  transition: var(--transition);
}

.social-links a:hover {
  background: var(--primary-color);
  transform: translateY(-3px);
}

.footer-links {
  list-style: none;
}

.footer-links li {
  margin-bottom: 0.5rem;
}

.footer-links a {
  color: rgba(255, 255, 255, 0.8);
  transition: var(--transition);
}

.footer-links a:hover {
  color: white;
  padding-left: 0.5rem;
}

.footer-contact {
  list-style: none;
}

.footer-contact li {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  margin-bottom: 0.75rem;
  color: rgba(255, 255, 255, 0.8);
}

.footer-contact i {
  margin-top: 0.25rem;
  color: var(--accent-color);
}

.footer-contact a {
  color: rgba(255, 255, 255, 0.8);
}

.footer-contact a:hover {
  color: white;
}

.footer-bottom {
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  padding-top: 1.5rem;
  text-align: center;
  color: rgba(255, 255, 255, 0.6);
}

@media (max-width: 640px) {
  .footer-content {
    grid-template-columns: 1fr;
  }
}
</style>






