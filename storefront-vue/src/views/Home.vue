<template>
  <div class="home-view">
    <HeroBanner />
    <div class="container">
      <FeaturedProducts 
        v-if="settings.show_featured_products && featuredProducts.length > 0" 
        :products="featuredProducts" 
        title="Featured Products" 
      />
      <NewArrivals 
        v-if="settings.show_new_arrivals && newArrivals.length > 0" 
        :products="newArrivals" 
        title="New Arrivals" 
      />
      <CategoriesGrid 
        v-if="settings.show_categories && categories.length > 0" 
        :categories="categories" 
      />
    </div>
  </div>
</template>

<script>
import { computed, onMounted } from 'vue'
import { useSettingsStore } from '@/store/settings'
import { useProductsStore } from '@/store/products'
import HeroBanner from '@/components/home/HeroBanner.vue'
import FeaturedProducts from '@/components/home/FeaturedProducts.vue'
import NewArrivals from '@/components/home/NewArrivals.vue'
import CategoriesGrid from '@/components/home/CategoriesGrid.vue'

export default {
  name: 'Home',
  components: {
    HeroBanner,
    FeaturedProducts,
    NewArrivals,
    CategoriesGrid
  },
  setup() {
    const settingsStore = useSettingsStore()
    const productsStore = useProductsStore()
    
    const settings = computed(() => settingsStore.settings || {})
    const featuredProducts = computed(() => productsStore.featuredProducts)
    const newArrivals = computed(() => productsStore.newArrivals)
    const categories = computed(() => productsStore.categories)
    
    const getSubdomain = () => {
      const hostname = window.location.hostname
      if (hostname === 'localhost' || hostname === '127.0.0.1') {
        const urlParams = new URLSearchParams(window.location.search)
        return urlParams.get('store') || 'demo'
      }
      const parts = hostname.split('.')
      return parts.length > 2 ? parts[0] : 'demo'
    }
    
    onMounted(async () => {
      const subdomain = getSubdomain()
      
      if (settings.value.show_featured_products) {
        await productsStore.fetchFeaturedProducts(subdomain)
      }
      if (settings.value.show_new_arrivals) {
        await productsStore.fetchNewArrivals(subdomain)
      }
      if (settings.value.show_categories) {
        await productsStore.fetchCategories(subdomain)
      }
    })
    
    return {
      settings,
      featuredProducts,
      newArrivals,
      categories
    }
  }
}
</script>

<style scoped>
.home-view {
  animation: fadeIn 0.6s ease;
}
</style>






