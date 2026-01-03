<template>
  <form @submit.prevent="search" class="search-bar">
    <input 
      v-model="searchQuery" 
      type="text" 
      placeholder="Search products..." 
      class="search-input"
    />
    <button type="submit" class="search-btn">
      <i class="fas fa-search"></i>
    </button>
  </form>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

export default {
  name: 'SearchBar',
  setup() {
    const router = useRouter()
    const searchQuery = ref('')
    
    const search = () => {
      if (searchQuery.value.trim()) {
        router.push({ name: 'Search', query: { q: searchQuery.value } })
      }
    }
    
    return { searchQuery, search }
  }
}
</script>

<style scoped>
.search-bar {
  display: flex;
  width: 100%;
}

.search-input {
  flex: 1;
  padding: 0.75rem 1rem;
  border: 2px solid var(--gray-200);
  border-right: none;
  border-radius: 8px 0 0 8px;
}

.search-btn {
  padding: 0 1.5rem;
  background: var(--primary-color);
  color: white;
  border-radius: 0 8px 8px 0;
  transition: var(--transition);
}

.search-btn:hover {
  background: var(--secondary-color);
}
</style>






