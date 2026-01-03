<template>
  <div class="search-view">
    <div class="container">
      <h1 class="page-title">Search Results for "{{ searchQuery }}"</h1>
      
      <Loading v-if="loading" message="Searching..." />
      
      <div v-else-if="products.length > 0">
        <p class="results-count">Found {{ pagination.total }} results</p>
        <ProductGrid :products="products" :card-style="cardStyle" />
        <Pagination 
          :current-page="pagination.current_page"
          :total-pages="pagination.last_page"
          @change="changePage"
        />
      </div>
      
      <div v-else class="empty-state">
        <i class="fas fa-search"></i>
        <p>No products found for "{{ searchQuery }}"</p>
        <router-link to="/products" class="btn btn-primary">Browse All Products</router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { computed, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useProductsStore } from '@/store/products'
import { useSettingsStore } from '@/store/settings'
import ProductGrid from '@/components/products/ProductGrid.vue'
import Pagination from '@/components/shared/Pagination.vue'
import Loading from '@/components/shared/Loading.vue'

export default {
  name: 'Search',
  components: {
    ProductGrid,
    Pagination,
    Loading
  },
  setup() {
    const route = useRoute()
    const productsStore = useProductsStore()
    const settingsStore = useSettingsStore()
    
    const searchQuery = computed(() => route.query.q || '')
    const products = computed(() => productsStore.products)
    const pagination = computed(() => productsStore.pagination)
    const loading = computed(() => productsStore.loading)
    const cardStyle = computed(() => settingsStore.settings?.product_card_style || 'card')
    
    const getSubdomain = () => {
      const hostname = window.location.hostname
      if (hostname === 'localhost' || hostname === '127.0.0.1') {
        const urlParams = new URLSearchParams(window.location.search)
        return urlParams.get('store') || 'demo'
      }
      const parts = hostname.split('.')
      return parts.length > 2 ? parts[0] : 'demo'
    }
    
    const fetchProducts = async (page = 1) => {
      if (searchQuery.value) {
        const subdomain = getSubdomain()
        productsStore.setFilter('search', searchQuery.value)
        await productsStore.fetchProducts(subdomain, { page })
      }
    }
    
    const changePage = (page) => {
      fetchProducts(page)
      window.scrollTo({ top: 0, behavior: 'smooth' })
    }
    
    watch(searchQuery, () => {
      fetchProducts()
    })
    
    onMounted(() => {
      fetchProducts()
    })
    
    return {
      searchQuery,
      products,
      pagination,
      loading,
      cardStyle,
      changePage
    }
  }
}
</script>

<style scoped>
.search-view {
  padding: 2rem 0;
}

.page-title {
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 1rem;
  color: var(--text-color);
}

.results-count {
  color: var(--gray-600);
  margin-bottom: 2rem;
}

.empty-state {
  text-align: center;
  padding: 4rem 2rem;
  color: var(--gray-500);
}

.empty-state i {
  font-size: 4rem;
  margin-bottom: 1rem;
}

.empty-state p {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 2rem;
}
</style>






