import { defineStore } from 'pinia'
import { useSettingsStore } from './settings'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [],
    shippingInfo: null,
  }),
  
  getters: {
    itemCount: (state) => {
      return state.items.reduce((total, item) => total + item.quantity, 0)
    },
    
    subtotal: (state) => {
      return state.items.reduce((total, item) => {
        return total + (item.price * item.quantity)
      }, 0)
    },
    
    shipping: () => {
      const settingsStore = useSettingsStore()
      return settingsStore.shippingCost || 0
    },
    
    total: (state) => {
      const settingsStore = useSettingsStore()
      const subtotal = state.items.reduce((total, item) => {
        return total + (item.price * item.quantity)
      }, 0)
      
      // Check for free shipping
      if (settingsStore.freeShippingOver && subtotal >= settingsStore.freeShippingAmount) {
        return subtotal
      }
      
      return subtotal + (settingsStore.shippingCost || 0)
    },
    
    hasItems: (state) => state.items.length > 0,
    
    qualifiesForFreeShipping: (state) => {
      const settingsStore = useSettingsStore()
      if (!settingsStore.freeShippingOver) return false
      
      const subtotal = state.items.reduce((total, item) => {
        return total + (item.price * item.quantity)
      }, 0)
      
      return subtotal >= settingsStore.freeShippingAmount
    },
  },
  
  actions: {
    addItem(product, quantity = 1) {
      const existingItem = this.items.find(item => item.id === product.id)
      
      if (existingItem) {
        existingItem.quantity += quantity
      } else {
        this.items.push({
          id: product.id,
          name: product.name,
          slug: product.slug,
          price: product.price,
          image: product.image,
          quantity: quantity,
        })
      }
      
      this.saveCart()
    },
    
    removeItem(productId) {
      const index = this.items.findIndex(item => item.id === productId)
      if (index > -1) {
        this.items.splice(index, 1)
        this.saveCart()
      }
    },
    
    updateQuantity(productId, quantity) {
      const item = this.items.find(item => item.id === productId)
      if (item) {
        if (quantity <= 0) {
          this.removeItem(productId)
        } else {
          item.quantity = quantity
          this.saveCart()
        }
      }
    },
    
    clearCart() {
      this.items = []
      this.shippingInfo = null
      this.saveCart()
    },
    
    saveCart() {
      localStorage.setItem('cart', JSON.stringify(this.items))
    },
    
    loadCart() {
      const savedCart = localStorage.getItem('cart')
      if (savedCart) {
        try {
          this.items = JSON.parse(savedCart)
        } catch (error) {
          console.error('Failed to load cart:', error)
          this.items = []
        }
      }
    },
    
    setShippingInfo(info) {
      this.shippingInfo = info
    },
  },
})






