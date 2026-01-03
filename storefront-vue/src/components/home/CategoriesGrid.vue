<template>
  <section v-if="categories.length > 0" class="section">
    <h2 class="section-title">Shop by Category</h2>
    <div class="categories-grid">
      <router-link 
        v-for="category in categories" 
        :key="category.id"
        :to="`/category/${category.slug}`"
        class="category-card"
      >
        <div class="category-icon">
          <i class="fas fa-folder"></i>
        </div>
        <h3>{{ category.name }}</h3>
        <p v-if="category.products_count">{{ category.products_count }} Products</p>
      </router-link>
    </div>
  </section>
</template>

<script>
export default {
  name: 'CategoriesGrid',
  props: {
    categories: {
      type: Array,
      required: true
    }
  }
}
</script>

<style scoped>
.section {
  margin: 3rem 0;
}

.section-title {
  font-size: 2rem;
  font-weight: 800;
  text-align: center;
  margin-bottom: 2rem;
  color: var(--text-color);
}

.categories-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1.5rem;
}

.category-card {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: var(--shadow);
  text-align: center;
  transition: var(--transition);
}

.category-card:hover {
  box-shadow: var(--shadow-lg);
  transform: translateY(-4px);
}

.category-icon {
  width: 80px;
  height: 80px;
  margin: 0 auto 1rem;
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  color: white;
}

.category-card h3 {
  font-size: 1.25rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
  color: var(--text-color);
}

.category-card p {
  color: var(--gray-600);
  font-size: 0.875rem;
}

@media (max-width: 640px) {
  .categories-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>






