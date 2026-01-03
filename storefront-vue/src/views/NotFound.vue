<template>
  <div class="not-found-view">
    <div class="container">
      <div class="not-found-content">
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>The page you're looking for doesn't exist or has been moved.</p>
        <router-link to="/" class="btn btn-primary btn-lg">
          <i class="fas fa-home"></i>
          Go Home
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NotFound'
}
</script>

<style scoped>
.not-found-view {
  min-height: calc(100vh - 400px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 4rem 0;
}

.not-found-content {
  text-align: center;
}

.not-found-content h1 {
  font-size: 10rem;
  font-weight: 900;
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 1rem;
}

.not-found-content h2 {
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 1rem;
  color: var(--text-color);
}

.not-found-content p {
  font-size: 1.25rem;
  color: var(--gray-600);
  margin-bottom: 2rem;
  max-width: 500px;
  margin-left: auto;
  margin-right: auto;
}
</style>






