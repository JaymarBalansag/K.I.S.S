<template>
  <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom sticky-top">
    <div class="container">
      <RouterLink class="navbar-brand fw-bold" to="/sample">
        <span class="text-primary">KISS</span> App
      </RouterLink>

      <button 
        class="navbar-toggler" 
        type="button" 
        data-bs-toggle="collapse" 
        data-bs-target="#mainNavbar" 
        aria-controls="mainNavbar" 
        aria-expanded="false" 
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <RouterLink class="nav-link" active-class="active" to="/">Home</RouterLink>
          </li>
          <li class="nav-item">
            <RouterLink class="nav-link" active-class="active" to="/">About</RouterLink>
          </li>
          
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Services
            </a>
            <ul class="dropdown-menu shadow-sm">
              <li><RouterLink class="dropdown-item" to="/">Action</RouterLink></li>
              <li><RouterLink class="dropdown-item" to="/">Another action</RouterLink></li>
              <li><hr class="dropdown-divider"></li>
              <li><RouterLink class="dropdown-item" to="/">Something else</RouterLink></li>
            </ul>
          </li>
        </ul>
    </div>
  </nav>
</template>

<script setup>
// You can add logic here later, like search functions
const handleSearch = () => {
  console.log('Searching...');
};
</script>

<style scoped>
/* Optional: Highlight the active link smoothly */
.nav-link.active {
  color: var(--bs-primary) !important;
  font-weight: 600;
}
</style>