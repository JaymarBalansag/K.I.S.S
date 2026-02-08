<template>
  <div class="main-container min-vh-100 d-flex flex-column">
    <main class="flex-grow-1 d-flex align-items-center py-5">
      <div class="container py-5 mt-5"> 
        <div class="row justify-content-center text-center mb-5 mt-4">
          <div class="col-lg-9 col-xl-8 w-100">
            <div class="text-start mb-4">
              <a @click.prevent="handleBack" class="text-white text-decoration-none opacity-75 hover-opacity-100" style="cursor: pointer;">
                <span class="fs-5">←</span> Back
              </a>
            </div>
            <span class="badge bg-primary bg-opacity-75 rounded-pill px-4 py-2 mb-3 shadow-sm text-uppercase fw-bold">
              Step {{ currentPerson === 'groom' ? '1' : '2' }} of 2: Age Verification
            </span>
            <h1 class="display-2 fw-bolder text-white mb-3 text-shadow-heavy">
              How old is the {{ currentPerson }}?
            </h1>
            <p class="lead text-white fw-medium mb-4 opacity-90 mx-auto" style="max-width: 700px;">
              Select the age range for the <strong>{{ currentPerson }}</strong> to determine their specific document requirements.
            </p>
          </div>
        </div>

        <div class="row g-4 justify-content-center">
          <div v-for="service in services" :key="service.title" class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-lg glass-card p-4 transition-hover" @click="saveAge(service.value)" style="cursor: pointer;">
              <div class="card-body d-flex flex-column align-items-center text-center">
                <div class="service-icon-box rounded-circle mb-4 d-flex align-items-center justify-content-center text-white shadow" 
                     :style="{ background: service.gradient }">
                  <span class="fs-2">{{ service.emoji }}</span>
                </div>
                <h3 class="card-title h4 fw-bold text-dark mb-3">{{ service.title }}</h3>
                <p class="card-text text-muted mb-0">{{ service.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
export default {
  data() {
    return {
      currentPerson: 'groom', // Start with Groom
      groomAgeRange: null,
      brideAgeRange: null,
      services: [
        { title: "18-21 years old", emoji: "📝", description: "Needs Parental Consent", value: "parental-consent", gradient: "linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)" },
        { title: "21-25 years old", emoji: "🗣️", description: "Needs Parental Advice", value: "parental-advise", gradient: "linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)" },
        { title: "25 years old or over", emoji: "✨", description: "No parental requirements", value: "no-need", gradient: "linear-gradient(135deg, #ff6a00 0%, #ee0979 100%)" }
      ]
    };
  },
  methods: {
    saveAge(value) {
      if (this.currentPerson === 'groom') {
        this.groomAgeRange = value;
        this.currentPerson = 'bride'; // Switch to Bride
        window.scrollTo(0,0);
      } else {
        this.brideAgeRange = value;
        this.proceedToForm();
      }   
    },
    handleBack() {
      if (this.currentPerson === 'bride') {
        this.currentPerson = 'groom';
      } else {
        this.$router.back();
      }
    },
    proceedToForm() {
      // Pass both age requirements to the next screen
      const link = `/Marriage-Form/requirements/groom/${this.groomAgeRange}/bride/${this.brideAgeRange}/isForeigner/${this.$route.params.isForeigner}/${this.$route.params.type}/Cohab/${this.$route.params.isCohabiting}`;
      this.$router.push(link);
    }
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
              url('../../../../../public/background.jpg'); 
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