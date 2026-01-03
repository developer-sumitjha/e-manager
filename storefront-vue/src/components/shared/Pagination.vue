<template>
  <div v-if="totalPages > 1" class="pagination">
    <button 
      @click="$emit('change', currentPage - 1)" 
      :disabled="currentPage === 1"
      class="page-btn"
    >
      <i class="fas fa-chevron-left"></i>
    </button>
    
    <button 
      v-for="page in visiblePages" 
      :key="page"
      @click="$emit('change', page)"
      :class="['page-btn', { active: page === currentPage }]"
    >
      {{ page }}
    </button>
    
    <button 
      @click="$emit('change', currentPage + 1)" 
      :disabled="currentPage === totalPages"
      class="page-btn"
    >
      <i class="fas fa-chevron-right"></i>
    </button>
  </div>
</template>

<script>
import { computed } from 'vue'

export default {
  name: 'Pagination',
  props: {
    currentPage: { type: Number, required: true },
    totalPages: { type: Number, required: true }
  },
  emits: ['change'],
  setup(props) {
    const visiblePages = computed(() => {
      const pages = []
      const maxVisible = 5
      let start = Math.max(1, props.currentPage - Math.floor(maxVisible / 2))
      let end = Math.min(props.totalPages, start + maxVisible - 1)
      
      if (end - start < maxVisible - 1) {
        start = Math.max(1, end - maxVisible + 1)
      }
      
      for (let i = start; i <= end; i++) {
        pages.push(i)
      }
      return pages
    })
    
    return { visiblePages }
  }
}
</script>

<style scoped>
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;
  margin: 2rem 0;
}

.page-btn {
  min-width: 40px;
  height: 40px;
  padding: 0.5rem 1rem;
  border: 2px solid var(--gray-200);
  border-radius: 8px;
  background: white;
  color: var(--text-color);
  font-weight: 600;
  transition: var(--transition);
}

.page-btn:hover:not(:disabled) {
  border-color: var(--primary-color);
  color: var(--primary-color);
}

.page-btn.active {
  background: var(--primary-color);
  border-color: var(--primary-color);
  color: white;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>






