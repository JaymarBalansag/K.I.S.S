<template>
  <div class="main-container min-vh-100 d-flex flex-column">

    <!-- For the navbar -->
    <Navbar></Navbar>

    <!-- For the main Content -->
    <main class="flex-grow-1 d-flex align-items-center py-5">
      <div class="container py-5 mt-5"> 
        <div class="row justify-content-center text-center mb-5 mt-4">
          <div class="col-lg-9 col-xl-8">
            <span class="badge bg-primary bg-opacity-75 rounded-pill px-4 py-2 mb-3 shadow-sm text-uppercase fw-bold" style="letter-spacing: 1px;">
              Civil Registry Portal
            </span>
            <h1 class="display-2 fw-bolder text-white mb-3 text-shadow-heavy">
              {{ config.servicesHeading }}
            </h1>
            <p class="lead text-white fw-medium mb-4 text-shadow-medium opacity-90 mx-auto" style="max-width: 700px;">
              {{ config.servicesSubtext }}
            </p>
          </div>
        </div>

        <div class="row g-4 justify-content-center">
          <div v-for="service in services" :key="service.title" class="col-md-6 col-lg-5">
            <div class="card h-100 border-0 shadow-lg glass-card p-4">
              <div class="card-body text-start">
                <div class="service-icon-box rounded-4 mb-4 d-flex align-items-center justify-content-center text-white shadow" 
                     :style="{ background: service.gradient }">
                  <span class="fs-2">{{ service.emoji }}</span>
                </div>
                <h3 class="card-title h4 fw-bold text-dark mb-3">{{ service.title }}</h3>
                <p class="card-text text-muted mb-4 lh-lg">{{ service.description }}</p>
                <RouterLink :to="service.link" class="btn btn-outline-primary rounded-pill fw-bold px-4 transition-all">
                  Explore Service <span class="ms-1">→</span>
                </RouterLink>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- For the footer -->
    <Footer></Footer>
  </div>
</template>

<script>
import Navbar from '../../components/navbar.vue';
import Footer from '../../components/footer.vue';
export default {
  name: 'GovPortal',
  components: {
    Navbar,
    Footer  
  },
  data() {
    return {
      config: {
        servicesHeading: "Marriage & Civil Wedding",
        servicesSubtext: "Apply for marriage license documents and schedule appointments with ease through our secure digital gateway."
      },
      
      services: [
        { title: "Marriage License", emoji: "🆔", description: "Apply for certified true copies of marriage license online.", gradient: "linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)", link: '/marriage-license' },
        { title: "Wedding Appointment", emoji: "📅", description: "Book an available slot for your civil ceremony and coordinate with our officials.", gradient: "linear-gradient(135deg, #ff6a00 0%, #ee0979 100%)", link: '/wedding-appointment' }
      ]
    };
  }
};
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

/* BACKGROUND & TYPOGRAPHY */
.main-container {
  font-family: 'Inter', sans-serif;
  background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), 
              url('../../../../public/background.jpg'); 
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
}

.text-shadow-heavy {
  text-shadow: 0 4px 15px rgba(0, 0, 0, 0.9);
}

.text-shadow-medium {
  text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
}

.glass-card {
  background: rgba(255, 255, 255, 0.96) !important;
  backdrop-filter: blur(12px);
  border-radius: 20px;
}

.service-icon-box {
  width: 65px;
  height: 65px;
}
</style>