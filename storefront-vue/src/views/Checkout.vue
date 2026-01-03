<template>
  <div class="checkout-view">
    <div class="container">
      <h1 class="page-title">Checkout</h1>
      
      <div v-if="hasItems" class="checkout-content">
        <div class="checkout-form">
          <div class="form-section">
            <h2><i class="fas fa-shipping-fast"></i> Shipping Information</h2>
            <form @submit.prevent="proceedToPayment">
              <div class="form-row">
                <div class="form-group">
                  <label>Full Name *</label>
                  <input v-model="shippingInfo.name" type="text" required />
                </div>
                <div class="form-group">
                  <label>Email *</label>
                  <input v-model="shippingInfo.email" type="email" required />
                </div>
              </div>
              
              <div class="form-row">
                <div class="form-group">
                  <label>Phone *</label>
                  <input v-model="shippingInfo.phone" type="tel" required />
                </div>
                <div class="form-group">
                  <label>City *</label>
                  <input v-model="shippingInfo.city" type="text" required />
                </div>
              </div>
              
              <div class="form-group">
                <label>Address *</label>
                <textarea v-model="shippingInfo.address" rows="3" required></textarea>
              </div>
              
              <div class="form-group">
                <label>Order Notes (Optional)</label>
                <textarea v-model="shippingInfo.notes" rows="2" placeholder="Any special instructions..."></textarea>
              </div>
              
              <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-lock"></i>
                Place Order
              </button>
            </form>
          </div>
        </div>
        
        <div class="checkout-sidebar">
          <div class="order-summary">
            <h3>Order Summary</h3>
            <div class="summary-items">
              <div v-for="item in items" :key="item.id" class="summary-item">
                <span>{{ item.name }} × {{ item.quantity }}</span>
                <span>{{ formatPrice(item.price * item.quantity) }}</span>
              </div>
            </div>
            <div class="summary-totals">
              <div class="summary-line">
                <span>Subtotal:</span>
                <span>{{ formatPrice(subtotal) }}</span>
              </div>
              <div class="summary-line">
                <span>Shipping:</span>
                <span>{{ qualifiesForFreeShipping ? 'FREE' : formatPrice(shipping) }}</span>
              </div>
              <div class="summary-line total">
                <span>Total:</span>
                <span>{{ formatPrice(total) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div v-else class="empty-state">
        <i class="fas fa-shopping-cart"></i>
        <h2>Your cart is empty</h2>
        <router-link to="/products" class="btn btn-primary">Browse Products</router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { computed, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '@/store/cart'
import { useSettingsStore } from '@/store/settings'
import { useToast } from 'vue-toastification'

export default {
  name: 'Checkout',
  setup() {
    const router = useRouter()
    const cartStore = useCartStore()
    const settingsStore = useSettingsStore()
    const toast = useToast()
    
    const items = computed(() => cartStore.items)
    const hasItems = computed(() => cartStore.hasItems)
    const subtotal = computed(() => cartStore.subtotal)
    const shipping = computed(() => cartStore.shipping)
    const total = computed(() => cartStore.total)
    const qualifiesForFreeShipping = computed(() => cartStore.qualifiesForFreeShipping)
    
    const formatPrice = (price) => settingsStore.formatPrice(price)
    
    const shippingInfo = reactive({
      name: '',
      email: '',
      phone: '',
      city: '',
      address: '',
      notes: ''
    })
    
    const proceedToPayment = () => {
      cartStore.setShippingInfo(shippingInfo)
      toast.success('Order placed successfully!')
      // Here you would integrate with payment gateway
      setTimeout(() => {
        cartStore.clearCart()
        router.push('/')
      }, 2000)
    }
    
    return {
      items,
      hasItems,
      subtotal,
      shipping,
      total,
      qualifiesForFreeShipping,
      shippingInfo,
      formatPrice,
      proceedToPayment
    }
  }
}
</script>

<style scoped>
.checkout-view {
  padding: 2rem 0;
  min-height: calc(100vh - 400px);
}

.page-title {
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 2rem;
  color: var(--text-color);
}

.checkout-content {
  display: grid;
  grid-template-columns: 1fr 400px;
  gap: 2rem;
}

.form-section {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: var(--shadow);
}

.form-section h2 {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  color: var(--text-color);
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: var(--text-color);
}

.order-summary {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: var(--shadow);
  position: sticky;
  top: 100px;
}

.order-summary h3 {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
}

.summary-items {
  margin-bottom: 1.5rem;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  padding: 0.75rem 0;
  border-bottom: 1px solid var(--gray-200);
  font-size: 0.875rem;
}

.summary-totals {
  border-top: 2px solid var(--gray-300);
  padding-top: 1rem;
}

.summary-line {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0;
  font-weight: 600;
}

.summary-line.total {
  font-size: 1.25rem;
  color: var(--primary-color);
  margin-top: 0.5rem;
}

.empty-state {
  text-align: center;
  padding: 4rem 2rem;
}

.empty-state i {
  font-size: 5rem;
  color: var(--gray-300);
  margin-bottom: 1rem;
}

.empty-state h2 {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 2rem;
}

@media (max-width: 1024px) {
  .checkout-content {
    grid-template-columns: 1fr;
  }
  
  .form-row {
    grid-template-columns: 1fr;
  }
}
</style>






