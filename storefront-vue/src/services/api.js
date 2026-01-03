import axios from 'axios'

const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost/e-manager/public/api'

const apiClient = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
})

// Request interceptor
apiClient.interceptors.request.use(
  (config) => {
    // Add any auth tokens here if needed
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Response interceptor
apiClient.interceptors.response.use(
  (response) => {
    return response.data
  },
  (error) => {
    if (error.response) {
      // Server responded with error
      const message = error.response.data.message || 'An error occurred'
      console.error('API Error:', message)
      return Promise.reject(new Error(message))
    } else if (error.request) {
      // Request made but no response
      console.error('Network Error:', error.request)
      return Promise.reject(new Error('Network error. Please check your connection.'))
    } else {
      // Something else happened
      console.error('Error:', error.message)
      return Promise.reject(error)
    }
  }
)

export default {
  // Settings
  getSettings(subdomain) {
    return apiClient.get(`/storefront/${subdomain}/settings`)
  },
  
  // Products
  getProducts(subdomain, params = {}) {
    return apiClient.get(`/storefront/${subdomain}/products`, { params })
  },
  
  getProduct(subdomain, slug) {
    return apiClient.get(`/storefront/${subdomain}/products/${slug}`)
  },
  
  // Categories
  getCategories(subdomain) {
    return apiClient.get(`/storefront/${subdomain}/categories`)
  },
  
  // Featured Products
  getFeaturedProducts(subdomain) {
    return apiClient.get(`/storefront/${subdomain}/featured-products`)
  },
  
  // New Arrivals
  getNewArrivals(subdomain) {
    return apiClient.get(`/storefront/${subdomain}/new-arrivals`)
  },
}






