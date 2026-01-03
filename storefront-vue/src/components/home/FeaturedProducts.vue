<template>
  <section v-if="products.length > 0" class="section">
    <div class="section-header">
      <h2>{{ title }}</h2>
      <router-link to="/products" class="view-all">View All <i class="fas fa-arrow-right"></i></router-link>
    </div>
    <ProductGrid :products="products" card-style="card" />
  </section>
</template>

<script>
import ProductGrid from '@/components/products/ProductGrid.vue'

export default {
  name: 'FeaturedProducts',
  components: { ProductGrid },
  props: {
    products: {
      type: Array,
      required: true
    },
    title: {
      type: String,
      default: 'Featured Products'
    }
  }
}
</script>

<style scoped>
.section {
  margin: 3rem 0;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.section-header h2 {
  font-size: 2rem;
  font-weight: 800;
  color: var(--text-color);
}

.view-all {
  color: var(--primary-color);
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: var(--transition);
}

.view-all:hover {
  gap: 0.75rem;
}
</style>






