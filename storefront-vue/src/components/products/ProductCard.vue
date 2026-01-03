<template>
  <div class="product-card">
    <router-link :to="`/products/${product.slug}`" class="product-link">
      <div class="product-image">
        <img :src="product.image || '/placeholder.jpg'" :alt="product.name" />
        <div v-if="product.is_featured" class="badge-featured">Featured</div>
      </div>
      <div class="product-info">
        <h3 class="product-name">{{ product.name }}</h3>
        <p v-if="product.description" class="product-desc line-clamp-2">{{ product.description }}</p>
        <div class="product-footer">
          <p class="product-price">{{ formatPrice(product.price) }}</p>
          <div v-if="showRatings && product.rating" class="product-rating">
            <i v-for="n in 5" :key="n" :class="['fas fa-star', n <= product.rating ? 'active' : '']"></i>
          </div>
        </div>
      </div>
    </router-link>
    <button v-if="showAddToCart" @click="addToCart" class="btn btn-primary add-cart-btn">
      <i class="fas fa-shopping-cart"></i>
      Add to Cart
    </button>
  </div>
</template>

<script>
import { computed } from 'vue'
import { useCartStore } from '@/store/cart'
import { useSettingsStore } from '@/store/settings'
import { useToast } from 'vue-toastification'

export default {
  name: 'ProductCard',
  props: {
    product: {
      type: Object,
      required: true
    }
  },
  setup(props) {
    const cartStore = useCartStore()
    const settingsStore = useSettingsStore()
    const toast = useToast()
    
    const showRatings = computed(() => settingsStore.settings?.show_product_ratings !== false)
    const showAddToCart = computed(() => settingsStore.settings?.show_add_to_cart_button !== false)
    
    const formatPrice = (price) => settingsStore.formatPrice(price)
    
    const addToCart = () => {
      cartStore.addItem(props.product)
      toast.success(`${props.product.name} added to cart!`)
    }
    
    return {
      showRatings,
      showAddToCart,
      formatPrice,
      addToCart
    }
  }
}
</script>

<style scoped>
.product-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: var(--shadow);
  transition: var(--transition);
  display: flex;
  flex-direction: column;
}

.product-card:hover {
  box-shadow: var(--shadow-lg);
  transform: translateY(-4px);
}

.product-link {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.product-image {
  position: relative;
  width: 100%;
  height: 250px;
  overflow: hidden;
  background: var(--gray-100);
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: var(--transition);
}

.product-card:hover .product-image img {
  transform: scale(1.05);
}

.badge-featured {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: var(--accent-color);
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
}

.product-info {
  flex: 1;
  padding: 1rem;
  display: flex;
  flex-direction: column;
}

.product-name {
  font-size: 1.125rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
  color: var(--text-color);
}

.product-desc {
  color: var(--gray-600);
  font-size: 0.875rem;
  margin-bottom: 1rem;
}

.product-footer {
  margin-top: auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.product-price {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--primary-color);
}

.product-rating {
  display: flex;
  gap: 0.25rem;
}

.product-rating i {
  color: var(--gray-300);
  font-size: 0.875rem;
}

.product-rating i.active {
  color: #fbbf24;
}

.add-cart-btn {
  margin: 0 1rem 1rem;
  width: calc(100% - 2rem);
}
</style>






