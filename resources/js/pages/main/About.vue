<template>
  <div class="main-container min-vh-100 d-flex flex-column">
    
    <Navbar></Navbar>

    <main class="flex-grow-1 py-5">
      <div class="container py-5 mt-5">
        
        <div class="row justify-content-center text-center mb-5 mt-4">
          <div class="col-lg-9 col-xl-8">
            <span class="badge bg-primary bg-opacity-75 rounded-pill px-4 py-2 mb-3 shadow-sm text-uppercase fw-bold" style="letter-spacing: 1px;">
              Our Story
            </span>
            <h1 class="display-2 fw-bolder text-white mb-3 text-shadow-heavy">
              About LCRO Abuyog
            </h1>
            <p class="lead text-white fw-medium mb-4 text-shadow-medium opacity-90 mx-auto" style="max-width: 750px;">
              Dedicated to preserving the vital milestones of our citizens through integrity, security, and digital innovation.
            </p>
          </div>
        </div>
        
        <div class="row g-4 justify-content-center">

          <!-- Vision -->
          <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-lg glass-card p-4">
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <div class="service-icon-box rounded-4 d-flex align-items-center justify-content-center text-white shadow me-3" 
                       style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); width: 50px; height: 50px;">
                    <i class="bi bi-eye-fill fs-4"></i>
                  </div>
                  <h3 class="fw-bold text-dark mb-0">Our Vision</h3>
                </div>
                <p class="text-muted lh-lg">
                  Peaceful, agro-industrialized and seld-reliant Abuyog with disaster and climate resilient, healthy and empowered citizenry living in a sustainably-managed lands, developed eco-tourist destination and environment-friendly community.
                </p>
              </div>
            </div>
          </div>

          <!-- Mission -->
          <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-lg glass-card p-4">
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <div class="service-icon-box rounded-4 d-flex align-items-center justify-content-center text-white shadow me-3" 
                       style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); width: 50px; height: 50px;">
                    <i class="bi bi-flag-fill fs-4"></i>
                  </div>
                  <h3 class="fw-bold text-dark mb-0">Our Mission</h3>
                </div>
                <p class="text-muted lh-lg">
                  To provide accurate, reliable and timely civil registration services through responsive governance and innovative digital solutions that strive to uphold the integrity of vital records while ensuring accessible, people centered, and technology-driven services that meet the evolving needs of the clients.
                </p>
              </div>
            </div>
          </div>

          <!-- Goal -->
          <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-lg glass-card p-4">
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <div class="service-icon-box rounded-4 d-flex align-items-center justify-content-center text-white shadow me-3" 
                       style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); width: 50px; height: 50px;">
                    <i class="bi bi-flag fs-4"></i>
                  </div>
                  <h3 class="fw-bold text-dark mb-0">Our Goal</h3>
                </div>
                <p class="text-muted lh-lg">
                  Deliver inclusive, accessible, and timely civil registration services through digital transformation, strengthened inter-agency collaboration, increased public awareness, and enchanced institutional capacity.
                </p>
              </div>
            </div>
          </div>

          
          <div class="col-12">
            <div class="card border-0 shadow-lg glass-card p-4">
              <div class="card-body">
                <h3 class="fw-bold text-dark mb-4 text-center">Core Responsibilities</h3>
                <div class="row g-4">
                  <div v-for="item in mandates" :key="item.title" class="col-md-4 text-center">
                    <div class="fs-1 mb-2">{{ item.icon }}</div>
                    <h5 class="fw-bold">{{ item.title }}</h5>
                    <p class="small text-muted">{{ item.text }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <Footer></Footer>
    
  </div>
</template>

<script>
import Navbar from '../../components/navbar.vue';
import Footer from '../../components/footer.vue';
export default {
  name: 'AboutPage',
  components: {
    Navbar,
    Footer
  },
  data() {
    return {
      mandates: [
        { icon: "📜", title: "Registration", text: "Recording vital events including births, marriages, and deaths." },
        { icon: "🔒", title: "Archiving", text: "Securely preserving historical records for future generations." },
        { icon: "📑", title: "Certification", text: "Issuance of certified true copies and legal civil documents." }
      ]
    };
  }
};
</script>

<style scoped>
/* Reusing your established styles */
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
</style>