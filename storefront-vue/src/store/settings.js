import { defineStore } from 'pinia'
import api from '@/services/api'

export const useSettingsStore = defineStore('settings', {
  state: () => ({
    settings: null,
    tenant: null,
    loading: false,
    error: null,
  }),
  
  getters: {
    siteName: (state) => state.settings?.site_name || 'Store',
    logo: (state) => state.settings?.logo || null,
    primaryColor: (state) => state.settings?.primary_color || '#667eea',
    currencySymbol: (state) => state.settings?.currency_symbol || 'Rs.',
    currencyPosition: (state) => state.settings?.currency_position || 'before',
    showBanner: (state) => state.settings?.show_banner || false,
    showCategoriesMenu: (state) => state.settings?.show_categories_menu !== false,
    showSearchBar: (state) => state.settings?.show_search_bar !== false,
    showCartIcon: (state) => state.settings?.show_cart_icon !== false,
    showFeaturedProducts: (state) => state.settings?.show_featured_products !== false,
    showNewArrivals: (state) => state.settings?.show_new_arrivals !== false,
    showCategories: (state) => state.settings?.show_categories !== false,
    productsPerPage: (state) => state.settings?.products_per_page || 12,
    productCardStyle: (state) => state.settings?.product_card_style || 'card',
    shippingCost: (state) => state.settings?.shipping_cost || 0,
    freeShippingOver: (state) => state.settings?.free_shipping_over || false,
    freeShippingAmount: (state) => state.settings?.free_shipping_amount || 0,
  },
  
  actions: {
    async fetchSettings(subdomain) {
      this.loading = true
      this.error = null
      
      try {
        const response = await api.getSettings(subdomain)
        this.settings = response.settings
        this.tenant = response.tenant
      } catch (error) {
        this.error = error.message
        console.error('Failed to fetch settings:', error)
        throw error
      } finally {
        this.loading = false
      }
    },
    
    formatPrice(price) {
      const formatted = parseFloat(price).toFixed(2)
      return this.currencyPosition === 'before'
        ? `${this.currencySymbol}${formatted}`
        : `${formatted}${this.currencySymbol}`
    },
  },
})






