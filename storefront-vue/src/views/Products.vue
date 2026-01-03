<template>
  <div class="products-view">
    <div class="container">
      <h1 class="page-title">All Products</h1>
      
      <ProductFilters 
        @sort="handleSort"
        @priceFilter="handlePriceFilter"
      />
      
      <Loading v-if="loading" message="Loading products..." />
      
      <div v-else-if="products.length > 0">
        <ProductGrid :products="products" :card-style="cardStyle" />
        <Pagination 
          :current-page="pagination.current_page"
          :total-pages="pagination.last_page"
          @change="changePage"
        />
      </div>
      
      <div v-else class="empty-state">
        <i class="fas fa-box-open"></i>
        <p>No products found</p>
      </div>
    </div>
  </div>
</template>

<script>
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { useProductsStore } from '@/store/products'
import { useSettingsStore } from '@/store/settings'
import ProductGrid from '@/components/products/ProductGrid.vue'
import ProductFilters from '@/components/products/ProductFilters.vue'
import Pagination from '@/components/shared/Pagination.vue'
import Loading from '@/components/shared/Loading.vue'

export default {
  name: 'Products',
  components: {
    ProductGrid,
    ProductFilters,
    Pagination,
    Loading
  },
  setup() {
    const route = useRoute()
    const productsStore = useProductsStore()
    const settingsStore = useSettingsStore()
    
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
      const subdomain = getSubdomain()
      await productsStore.fetchProducts(subdomain, { page })
    }
    
    const handleSort = (sortValue) => {
      productsStore.setFilter('sort', sortValue)
      fetchProducts()
    }
    
    const handlePriceFilter = ({ min, max }) => {
      productsStore.setFilter('min_price', min)
      productsStore.setFilter('max_price', max)
      fetchProducts()
    }
    
    const changePage = (page) => {
      fetchProducts(page)
      window.scrollTo({ top: 0, behavior: 'smooth' })
    }
    
    onMounted(() => {
      fetchProducts()
    })
    
    return {
      products,
      pagination,
      loading,
      cardStyle,
      handleSort,
      handlePriceFilter,
      changePage
    }
  }
}
</script>

<style scoped>
.products-view {
  padding: 2rem 0;
}

.page-title {
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 2rem;
  color: var(--text-color);
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
}
</style>






