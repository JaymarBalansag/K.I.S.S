<template>
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top nav-glass shadow-sm" style="z-index: 2000;">
    <div class="container">
      
      <a class="navbar-brand d-flex align-items-center" href="#">
        <div class="bg-primary rounded-2 me-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px;">
            <img src="../../../../public/favicon.ico" alt="logo" style="width: 24px;">
        </div>
        <div class="brand-text text-start">
          <span class="fw-bold d-block lh-1 text-white fs-5">{{ config.siteName }}</span>
          <small class="text-white-50 d-none d-sm-block" style="font-size: 0.75rem; letter-spacing: 0.5px;">Marriage Services</small>
        </div>
      </a>

      <button 
        class="navbar-toggler border-0 shadow-none" 
        type="button" 
        data-bs-toggle="collapse" 
        data-bs-target="#mainNav" 
        aria-controls="mainNav" 
        aria-expanded="false" 
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
          <li v-for="link in navLinks" :key="link.text" class="nav-item">
            <RouterLink class="nav-link fw-bold px-3 text-uppercase small text-center" :to="link.url">
              {{ link.text }}
            </RouterLink> 
          </li>
        </ul>
      </div>

    </div>
  </nav>
</template>

<script>
export default {
  data() {
    return {
      config: {
        siteName: "LCRO Abuyog"
      },
      navLinks: [
        { text: 'Services', url: '/home' },
        { text: 'About Us', url: '/about' },
        { text: 'Contact', url: '/contact' },
        { text: 'FAQ', url: '/faq' }
      ],
    };
  }
}
</script>

<style scoped>
/* NAVBAR STYLING */
.nav-glass {
  background-color: rgba(33, 37, 41, 0.92) !important;
  backdrop-filter: blur(15px);
  -webkit-backdrop-filter: blur(15px);
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.nav-link {
  color: #ffffff !important;
  transition: opacity 0.2s ease;
  position: relative;
}

.nav-link:hover {
  opacity: 0.7;
}

/* MOBILE FIXES */
@media (max-width: 991px) {
  .navbar-collapse {
    background: #212529 !important;
    padding: 1.5rem;
    border-radius: 12px;
    margin-top: 10px;
  }
  
  .nav-link {
    padding: 12px 0 !important;
    border-bottom: 1px solid rgba(255,255,255,0.05);
  }
}
</style>