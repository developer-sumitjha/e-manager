<template>
  <div class="cart-item">
    <img :src="item.image || '/placeholder.jpg'" :alt="item.name" class="item-image" />
    <div class="item-details">
      <h4>{{ item.name }}</h4>
      <p class="item-price">{{ formatPrice(item.price) }}</p>
    </div>
    <div class="item-actions">
      <div class="quantity-control">
        <button @click="decreaseQuantity" class="qty-btn">
          <i class="fas fa-minus"></i>
        </button>
        <input 
          type="number" 
          :value="item.quantity" 
          @change="updateQuantity($event.target.value)"
          min="1"
          class="qty-input"
        />
        <button @click="increaseQuantity" class="qty-btn">
          <i class="fas fa-plus"></i>
        </button>
      </div>
      <p class="item-total">{{ formatPrice(item.price * item.quantity) }}</p>
      <button @click="$emit('remove', item.id)" class="btn-remove">
        <i class="fas fa-trash"></i>
      </button>
    </div>
  </div>
</template>

<script>
import { useSettingsStore } from '@/store/settings'
import { useCartStore } from '@/store/cart'

export default {
  name: 'CartItem',
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  emits: ['remove'],
  setup(props) {
    const settingsStore = useSettingsStore()
    const cartStore = useCartStore()
    
    const formatPrice = (price) => settingsStore.formatPrice(price)
    
    const updateQuantity = (value) => {
      const qty = parseInt(value)
      if (qty > 0) {
        cartStore.updateQuantity(props.item.id, qty)
      }
    }
    
    const increaseQuantity = () => {
      cartStore.updateQuantity(props.item.id, props.item.quantity + 1)
    }
    
    const decreaseQuantity = () => {
      if (props.item.quantity > 1) {
        cartStore.updateQuantity(props.item.id, props.item.quantity - 1)
      }
    }
    
    return {
      formatPrice,
      updateQuantity,
      increaseQuantity,
      decreaseQuantity
    }
  }
}
</script>

<style scoped>
.cart-item {
  display: flex;
  gap: 1.5rem;
  padding: 1.5rem;
  background: white;
  border-radius: 12px;
  box-shadow: var(--shadow);
  margin-bottom: 1rem;
}

.item-image {
  width: 120px;
  height: 120px;
  object-fit: cover;
  border-radius: 8px;
}

.item-details {
  flex: 1;
}

.item-details h4 {
  font-size: 1.125rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
  color: var(--text-color);
}

.item-price {
  color: var(--primary-color);
  font-weight: 600;
}

.item-actions {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 1rem;
}

.quantity-control {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.qty-btn {
  width: 32px;
  height: 32px;
  border: 2px solid var(--gray-200);
  border-radius: 6px;
  background: white;
  color: var(--text-color);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: var(--transition);
}

.qty-btn:hover {
  border-color: var(--primary-color);
  color: var(--primary-color);
}

.qty-input {
  width: 60px;
  text-align: center;
  padding: 0.5rem;
  border: 2px solid var(--gray-200);
  border-radius: 6px;
}

.item-total {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--primary-color);
}

.btn-remove {
  background: transparent;
  color: var(--gray-400);
  padding: 0.5rem;
  transition: var(--transition);
}

.btn-remove:hover {
  color: var(--danger, #ef4444);
}

@media (max-width: 640px) {
  .cart-item {
    flex-direction: column;
  }
  
  .item-actions {
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
  }
}
</style>






