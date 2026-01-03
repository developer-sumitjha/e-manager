<template>
  <header class="header" :style="{ backgroundColor: settings.header_bg_color }">
    <div class="container">
      <div class="header-content">
        <!-- Logo -->
        <router-link to="/" class="logo">
          <img v-if="settings.logo" :src="settings.logo" :alt="settings.site_name" class="logo-img" />
          <span v-else class="logo-text">{{ settings.site_name }}</span>
        </router-link>
        
        <!-- Search Bar -->
        <SearchBar v-if="settings.show_search_bar" class="header-search" />
        
        <!-- Actions -->
        <div class="header-actions">
          <!-- Cart Icon -->
          <router-link v-if="settings.show_cart_icon" to="/cart" class="cart-icon">
            <i class="fas fa-shopping-cart"></i>
            <span v-if="cartCount > 0" class="cart-badge">{{ cartCount }}</span>
          </router-link>
        </div>
      </div>
      
      <!-- Navigation -->
      <nav v-if="settings.show_categories_menu" class="nav">
        <router-link to="/" class="nav-link">Home</router-link>
        <router-link to="/products" class="nav-link">All Products</router-link>
        <router-link 
          v-for="category in categories.slice(0, 5)" 
          :key="category.id"
          :to="`/category/${category.slug}`"
          class="nav-link"
        >
          {{ category.name }}
        </router-link>
      </nav>
    </div>
  </header>
</template>

<script>
import { computed, onMounted } from 'vue'
import { useSettingsStore } from '@/store/settings'
import { useProductsStore } from '@/store/products'
import { useCartStore } from '@/store/cart'
import SearchBar from '@/components/shared/SearchBar.vue'

export default {
  name: 'Header',
  components: { SearchBar },
  setup() {
    const settingsStore = useSettingsStore()
    const productsStore = useProductsStore()
    const cartStore = useCartStore()
    
    const settings = computed(() => settingsStore.settings || {})
    const categories = computed(() => productsStore.categories)
    const cartCount = computed(() => cartStore.itemCount)
    
    const getSubdomain = () => {
      const hostname = window.location.hostname
      if (hostname === 'localhost' || hostname === '127.0.0.1') {
        const urlParams = new URLSearchParams(window.location.search)
        return urlParams.get('store') || 'demo'
      }
      const parts = hostname.split('.')
      return parts.length > 2 ? parts[0] : 'demo'
    }
    
    onMounted(() => {
      const subdomain = getSubdomain()
      productsStore.fetchCategories(subdomain)
    })
    
    return { settings, categories, cartCount }
  }
}
</script>

<style scoped>
.header {
  box-shadow: var(--shadow-md);
  position: sticky;
  top: 0;
  z-index: 100;
  background: white;
}

.header-content {
  display: flex;
  align-items: center;
  gap: 2rem;
  padding: 1rem 0;
}

.logo {
  display: flex;
  align-items: center;
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--primary-color);
}

.logo-img {
  max-height: 50px;
  width: auto;
}

.logo-text {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.header-search {
  flex: 1;
  max-width: 500px;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.cart-icon {
  position: relative;
  font-size: 1.5rem;
  color: var(--text-color);
  transition: var(--transition);
}

.cart-icon:hover {
  color: var(--primary-color);
  transform: scale(1.1);
}

.cart-badge {
  position: absolute;
  top: -8px;
  right: -8px;
  background: var(--accent-color);
  color: white;
  font-size: 0.75rem;
  font-weight: 700;
  padding: 0.125rem 0.375rem;
  border-radius: 9999px;
  min-width: 20px;
  text-align: center;
}

.nav {
  display: flex;
  gap: 2rem;
  padding: 0.75rem 0;
  border-top: 1px solid var(--gray-200);
  overflow-x: auto;
}

.nav-link {
  color: var(--text-color);
  font-weight: 600;
  white-space: nowrap;
  padding: 0.5rem 0;
  border-bottom: 3px solid transparent;
  transition: var(--transition);
}

.nav-link:hover,
.nav-link.router-link-active {
  color: var(--primary-color);
  border-bottom-color: var(--primary-color);
}

@media (max-width: 768px) {
  .header-content {
    flex-wrap: wrap;
    gap: 1rem;
  }
  
  .header-search {
    order: 3;
    flex-basis: 100%;
    max-width: none;
  }
  
  .nav {
    gap: 1rem;
  }
}
</style>






