<template>
  <div class="cart-view">
    <div class="container">
      <h1 class="page-title">Shopping Cart</h1>
      
      <div v-if="hasItems" class="cart-content">
        <div class="cart-items">
          <CartItem 
            v-for="item in items" 
            :key="item.id"
            :item="item"
            @remove="removeItem"
          />
        </div>
        <div class="cart-sidebar">
          <CartSummary />
        </div>
      </div>
      
      <div v-else class="empty-cart">
        <i class="fas fa-shopping-cart"></i>
        <h2>Your cart is empty</h2>
        <p>Add some products to get started!</p>
        <router-link to="/products" class="btn btn-primary btn-lg">
          Browse Products
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue'
import { useCartStore } from '@/store/cart'
import CartItem from '@/components/cart/CartItem.vue'
import CartSummary from '@/components/cart/CartSummary.vue'
import { useToast } from 'vue-toastification'

export default {
  name: 'Cart',
  components: {
    CartItem,
    CartSummary
  },
  setup() {
    const cartStore = useCartStore()
    const toast = useToast()
    
    const items = computed(() => cartStore.items)
    const hasItems = computed(() => cartStore.hasItems)
    
    const removeItem = (itemId) => {
      cartStore.removeItem(itemId)
      toast.success('Item removed from cart')
    }
    
    return {
      items,
      hasItems,
      removeItem
    }
  }
}
</script>

<style scoped>
.cart-view {
  padding: 2rem 0;
  min-height: calc(100vh - 400px);
}

.page-title {
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 2rem;
  color: var(--text-color);
}

.cart-content {
  display: grid;
  grid-template-columns: 1fr 400px;
  gap: 2rem;
}

.cart-items {
  display: flex;
  flex-direction: column;
}

.empty-cart {
  text-align: center;
  padding: 4rem 2rem;
}

.empty-cart i {
  font-size: 5rem;
  color: var(--gray-300);
  margin-bottom: 1rem;
}

.empty-cart h2 {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
  color: var(--text-color);
}

.empty-cart p {
  color: var(--gray-600);
  margin-bottom: 2rem;
}

@media (max-width: 1024px) {
  .cart-content {
    grid-template-columns: 1fr;
  }
}
</style>






