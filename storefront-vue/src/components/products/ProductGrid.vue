<template>
  <div :class="['product-grid', `grid-style-${cardStyle}`]">
    <ProductCard 
      v-for="product in products" 
      :key="product.id" 
      :product="product" 
    />
  </div>
</template>

<script>
import ProductCard from './ProductCard.vue'

export default {
  name: 'ProductGrid',
  components: { ProductCard },
  props: {
    products: {
      type: Array,
      required: true
    },
    cardStyle: {
      type: String,
      default: 'card'
    }
  }
}
</script>

<style scoped>
.product-grid {
  display: grid;
  gap: 1.5rem;
  margin: 2rem 0;
}

.grid-style-card {
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
}

.grid-style-grid {
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
}

.grid-style-list {
  grid-template-columns: 1fr;
}

@media (max-width: 640px) {
  .product-grid {
    grid-template-columns: 1fr;
  }
}
</style>






