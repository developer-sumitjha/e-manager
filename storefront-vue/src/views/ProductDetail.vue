<template>
  <div class="product-detail-view">
    <div class="container">
      <Loading v-if="loading" message="Loading product..." />
      
      <div v-else-if="product" class="product-detail">
        <div class="product-gallery">
          <img :src="product.image || '/placeholder.jpg'" :alt="product.name" />
        </div>
        
        <div class="product-info">
          <h1>{{ product.name }}</h1>
          <div class="product-meta">
            <p class="product-price">{{ formatPrice(product.price) }}</p>
            <div v-if="product.rating" class="product-rating">
              <i v-for="n in 5" :key="n" :class="['fas fa-star', n <= product.rating ? 'active' : '']"></i>
            </div>
          </div>
          
          <p class="product-description">{{ product.description }}</p>
          
          <div v-if="product.category" class="product-category">
            <router-link :to="`/category/${product.category.slug}`">
              {{ product.category.name }}
            </router-link>
          </div>
          
          <div class="product-actions">
            <button @click="addToCart" class="btn btn-primary btn-lg add-to-cart">
              <i class="fas fa-shopping-cart"></i>
              Add to Cart
            </button>
          </div>
        </div>
      </div>
      
      <div v-if="relatedProducts.length > 0" class="related-products">
        <h2>Related Products</h2>
        <ProductGrid :products="relatedProducts" card-style="card" />
      </div>
    </div>
  </div>
</template>

<script>
import { computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useProductsStore } from '@/store/products'
import { useCartStore } from '@/store/cart'
import { useSettingsStore } from '@/store/settings'
import { useToast } from 'vue-toastification'
import ProductGrid from '@/components/products/ProductGrid.vue'
import Loading from '@/components/shared/Loading.vue'

export default {
  name: 'ProductDetail',
  components: {
    ProductGrid,
    Loading
  },
  setup() {
    const route = useRoute()
    const productsStore = useProductsStore()
    const cartStore = useCartStore()
    const settingsStore = useSettingsStore()
    const toast = useToast()
    
    const product = computed(() => productsStore.currentProduct)
    const relatedProducts = computed(() => productsStore.relatedProducts)
    const loading = computed(() => productsStore.loading)
    
    const formatPrice = (price) => settingsStore.formatPrice(price)
    
    const getSubdomain = () => {
      const hostname = window.location.hostname
      if (hostname === 'localhost' || hostname === '127.0.0.1') {
        const urlParams = new URLSearchParams(window.location.search)
        return urlParams.get('store') || 'demo'
      }
      const parts = hostname.split('.')
      return parts.length > 2 ? parts[0] : 'demo'
    }
    
    const addToCart = () => {
      if (product.value) {
        cartStore.addItem(product.value)
        toast.success(`${product.value.name} added to cart!`)
      }
    }
    
    onMounted(async () => {
      const subdomain = getSubdomain()
      const slug = route.params.slug
      await productsStore.fetchProduct(subdomain, slug)
    })
    
    return {
      product,
      relatedProducts,
      loading,
      formatPrice,
      addToCart
    }
  }
}
</script>

<style scoped>
.product-detail-view {
  padding: 2rem 0;
}

.product-detail {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
  margin-bottom: 4rem;
}

.product-gallery img {
  width: 100%;
  height: auto;
  border-radius: 12px;
}

.product-info h1 {
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 1rem;
  color: var(--text-color);
}

.product-meta {
  display: flex;
  align-items: center;
  gap: 2rem;
  margin-bottom: 1.5rem;
}

.product-price {
  font-size: 2rem;
  font-weight: 700;
  color: var(--primary-color);
}

.product-rating {
  display: flex;
  gap: 0.25rem;
}

.product-rating i {
  color: var(--gray-300);
}

.product-rating i.active {
  color: #fbbf24;
}

.product-description {
  font-size: 1.125rem;
  line-height: 1.8;
  color: var(--gray-700);
  margin-bottom: 1.5rem;
}

.product-category {
  margin-bottom: 2rem;
}

.product-category a {
  color: var(--primary-color);
  font-weight: 600;
}

.add-to-cart {
  width: 100%;
  max-width: 400px;
}

.related-products {
  margin-top: 4rem;
}

.related-products h2 {
  font-size: 2rem;
  font-weight: 800;
  margin-bottom: 2rem;
  color: var(--text-color);
}

@media (max-width: 768px) {
  .product-detail {
    grid-template-columns: 1fr;
    gap: 2rem;
  }
}
</style>






