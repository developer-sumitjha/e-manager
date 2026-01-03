<template>
  <div class="cart-summary">
    <h3>Order Summary</h3>
    
    <div class="summary-lines">
      <div class="summary-line">
        <span>Subtotal ({{ itemCount }} items):</span>
        <span class="amount">{{ formatPrice(subtotal) }}</span>
      </div>
      
      <div class="summary-line">
        <span>Shipping:</span>
        <span class="amount">
          {{ qualifiesForFreeShipping ? 'FREE' : formatPrice(shipping) }}
        </span>
      </div>
      
      <div v-if="settings.free_shipping_over && !qualifiesForFreeShipping" class="free-shipping-notice">
        <i class="fas fa-truck"></i>
        Add {{ formatPrice(settings.free_shipping_amount - subtotal) }} more for FREE shipping!
      </div>
      
      <div class="summary-line total">
        <span>Total:</span>
        <span class="amount">{{ formatPrice(total) }}</span>
      </div>
    </div>
    
    <router-link to="/checkout" class="btn btn-primary btn-lg checkout-btn">
      <i class="fas fa-lock"></i>
      Proceed to Checkout
    </router-link>
    
    <router-link to="/products" class="continue-shopping">
      <i class="fas fa-arrow-left"></i>
      Continue Shopping
    </router-link>
  </div>
</template>

<script>
import { computed } from 'vue'
import { useCartStore } from '@/store/cart'
import { useSettingsStore } from '@/store/settings'

export default {
  name: 'CartSummary',
  setup() {
    const cartStore = useCartStore()
    const settingsStore = useSettingsStore()
    
    const settings = computed(() => settingsStore.settings || {})
    const itemCount = computed(() => cartStore.itemCount)
    const subtotal = computed(() => cartStore.subtotal)
    const shipping = computed(() => cartStore.shipping)
    const total = computed(() => cartStore.total)
    const qualifiesForFreeShipping = computed(() => cartStore.qualifiesForFreeShipping)
    
    const formatPrice = (price) => settingsStore.formatPrice(price)
    
    return {
      settings,
      itemCount,
      subtotal,
      shipping,
      total,
      qualifiesForFreeShipping,
      formatPrice
    }
  }
}
</script>

<style scoped>
.cart-summary {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: var(--shadow);
  position: sticky;
  top: 100px;
}

.cart-summary h3 {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  color: var(--text-color);
}

.summary-lines {
  margin-bottom: 1.5rem;
}

.summary-line {
  display: flex;
  justify-content: space-between;
  padding: 0.75rem 0;
  border-bottom: 1px solid var(--gray-200);
}

.summary-line span {
  color: var(--gray-600);
}

.summary-line .amount {
  font-weight: 600;
  color: var(--text-color);
}

.summary-line.total {
  border-bottom: none;
  border-top: 2px solid var(--gray-300);
  margin-top: 0.5rem;
  padding-top: 1rem;
}

.summary-line.total span {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--text-color);
}

.summary-line.total .amount {
  color: var(--primary-color);
  font-size: 1.5rem;
}

.free-shipping-notice {
  background: rgba(16, 185, 129, 0.1);
  color: var(--accent-color, #10b981);
  padding: 0.75rem 1rem;
  border-radius: 8px;
  font-size: 0.875rem;
  font-weight: 600;
  margin: 1rem 0;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.checkout-btn {
  width: 100%;
  margin-bottom: 1rem;
}

.continue-shopping {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  color: var(--primary-color);
  font-weight: 600;
  padding: 0.75rem;
  transition: var(--transition);
}

.continue-shopping:hover {
  gap: 0.75rem;
}
</style>






