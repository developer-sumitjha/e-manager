<template>
  <div class="filters">
    <div class="filter-group">
      <label>Sort By:</label>
      <select @change="$emit('sort', $event.target.value)" class="filter-select">
        <option value="latest">Latest</option>
        <option value="price_low">Price: Low to High</option>
        <option value="price_high">Price: High to Low</option>
        <option value="name">Name A-Z</option>
      </select>
    </div>
    
    <div class="filter-group">
      <label>Price Range:</label>
      <div class="price-inputs">
        <input 
          type="number" 
          placeholder="Min" 
          @change="$emit('priceFilter', { min: $event.target.value, max: maxPrice })"
          v-model="minPrice"
        />
        <span>-</span>
        <input 
          type="number" 
          placeholder="Max" 
          @change="$emit('priceFilter', { min: minPrice, max: $event.target.value })"
          v-model="maxPrice"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'

export default {
  name: 'ProductFilters',
  emits: ['sort', 'priceFilter'],
  setup() {
    const minPrice = ref(null)
    const maxPrice = ref(null)
    
    return {
      minPrice,
      maxPrice
    }
  }
}
</script>

<style scoped>
.filters {
  background: white;
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: var(--shadow);
  display: flex;
  gap: 2rem;
  flex-wrap: wrap;
  margin-bottom: 2rem;
}

.filter-group {
  flex: 1;
  min-width: 200px;
}

.filter-group label {
  display: block;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: var(--text-color);
}

.filter-select {
  width: 100%;
}

.price-inputs {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.price-inputs input {
  flex: 1;
}
</style>






