import { defineStore } from 'pinia'
import api from '@/services/api'

export const useProductsStore = defineStore('products', {
  state: () => ({
    products: [],
    featuredProducts: [],
    newArrivals: [],
    currentProduct: null,
    relatedProducts: [],
    categories: [],
    loading: false,
    error: null,
    pagination: {
      current_page: 1,
      last_page: 1,
      per_page: 12,
      total: 0,
    },
    filters: {
      search: '',
      category: null,
      min_price: null,
      max_price: null,
      sort: 'latest',
    },
  }),
  
  getters: {
    hasProducts: (state) => state.products.length > 0,
    hasFeaturedProducts: (state) => state.featuredProducts.length > 0,
    hasNewArrivals: (state) => state.newArrivals.length > 0,
  },
  
  actions: {
    async fetchProducts(subdomain, params = {}) {
      this.loading = true
      this.error = null
      
      try {
        const response = await api.getProducts(subdomain, {
          ...this.filters,
          ...params,
        })
        
        this.products = response.products.data
        this.pagination = {
          current_page: response.products.current_page,
          last_page: response.products.last_page,
          per_page: response.products.per_page,
          total: response.products.total,
        }
      } catch (error) {
        this.error = error.message
        console.error('Failed to fetch products:', error)
      } finally {
        this.loading = false
      }
    },
    
    async fetchProduct(subdomain, slug) {
      this.loading = true
      this.error = null
      
      try {
        const response = await api.getProduct(subdomain, slug)
        this.currentProduct = response.product
        this.relatedProducts = response.related_products || []
      } catch (error) {
        this.error = error.message
        console.error('Failed to fetch product:', error)
        throw error
      } finally {
        this.loading = false
      }
    },
    
    async fetchCategories(subdomain) {
      try {
        const response = await api.getCategories(subdomain)
        this.categories = response.categories
      } catch (error) {
        console.error('Failed to fetch categories:', error)
      }
    },
    
    async fetchFeaturedProducts(subdomain) {
      try {
        const response = await api.getFeaturedProducts(subdomain)
        this.featuredProducts = response.products
      } catch (error) {
        console.error('Failed to fetch featured products:', error)
      }
    },
    
    async fetchNewArrivals(subdomain) {
      try {
        const response = await api.getNewArrivals(subdomain)
        this.newArrivals = response.products
      } catch (error) {
        console.error('Failed to fetch new arrivals:', error)
      }
    },
    
    setFilter(key, value) {
      this.filters[key] = value
    },
    
    clearFilters() {
      this.filters = {
        search: '',
        category: null,
        min_price: null,
        max_price: null,
        sort: 'latest',
      }
    },
  },
})






