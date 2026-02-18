<template>
  <div class="main-container min-vh-100 d-flex flex-column">
    <main class="flex-grow-1 d-flex align-items-center py-5">
      <div class="container py-5 mt-5">
        <div class="row justify-content-center text-center mb-5 mt-4">
          <div class="col-lg-9 col-xl-8 w-100">
            <div class="text-start mb-4">
              <a @click.prevent="handleBack" class="text-white text-decoration-none opacity-75 hover-opacity-100" style="cursor: pointer;">
                <span class="fs-5">&larr;</span> Back
              </a>
            </div>
            <span class="badge bg-primary bg-opacity-75 rounded-pill px-4 py-2 mb-3 shadow-sm text-uppercase fw-bold" style="letter-spacing: 1px;">
              Marriage License Screening
            </span>
            <h1 class="display-2 fw-bolder text-white mb-3 text-shadow-heavy">
              {{ stepHeading }}
            </h1>
            <p class="lead text-white fw-medium mb-4 text-shadow-medium opacity-90 mx-auto" style="max-width: 700px;">
              {{ stepSubtext }}
            </p>
          </div>
        </div>

        <div class="row g-4 justify-content-center">
          <div v-for="option in currentOptions" :key="option.key" class="col-md-6 col-lg-5">
            <div class="card h-100 border-0 shadow-lg glass-card p-4" @click="selectOption(option)" style="cursor: pointer;">
              <div class="card-body d-flex align-items-center justify-content-center">
                <div class="service-icon-box rounded-4 mb-4 d-flex align-items-center justify-content-center text-white shadow"
                     :style="{ background: option.gradient }">
                  <span class="fs-2 fw-bold">{{ option.icon }}</span>
                </div>
                <div class="container">
                  <h3 class="card-title h4 fw-bold text-dark mb-3">{{ option.title }}</h3>
                  <p class="card-text text-muted mb-4 lh-lg">{{ option.description }}</p>
                </div>
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
  name: 'MarriageLicenseScreening',
  data() {
    return {
      step: 1,
      currentPerson: 'groom',
      isCohabiting: null,
      isForeigner: null,
      type: null,
      groomAgeRange: null,
      brideAgeRange: null
    };
  },
  computed: {
    stepHeading() {
      if (this.step === 1) return 'Are you and your partner cohabiting?';
      if (this.step === 2) return 'Are any of you foreigners?';
      return `How old is the ${this.currentPerson}?`;
    },
    stepSubtext() {
      if (this.step === 1) return 'For at least 5 years prior to application.';
      if (this.step === 2) return 'Select if the groom or bride is a foreign national.';
      return `Select the age bracket for the ${this.currentPerson}.`;
    },
    currentOptions() {
      if (this.step === 1) {
        return [
          { key: 'cohab-yes', title: 'Yes', icon: 'Y', description: 'Yes, we have cohabited for 5 years.', gradient: 'linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)', value: true },
          { key: 'cohab-no', title: 'No', icon: 'N', description: 'No, we have not lived together for 5 years.', gradient: 'linear-gradient(135deg, #ff6a00 0%, #ee0979 100%)', value: false }
        ];
      }

      if (this.step === 2) {
        return [
          { key: 'foreigner-groom', title: 'Yes', icon: 'G', description: 'The groom is a foreigner.', gradient: 'linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)', isForeigner: true, type: 'groom' },
          { key: 'foreigner-bride', title: 'Yes', icon: 'B', description: 'The bride is a foreigner.', gradient: 'linear-gradient(135deg, #ff6a00 0%, #ee0979 100%)', isForeigner: true, type: 'bride' },
          { key: 'foreigner-none', title: 'No', icon: 'N', description: 'Neither of us is a foreigner.', gradient: 'linear-gradient(135deg, #198754 0%, #20c997 100%)', isForeigner: false, type: 'filipino' }
        ];
      }

      return [
        { key: 'age-18-21', title: '18-21 years old', icon: '18', description: 'Needs parental consent.', gradient: 'linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)', value: 'parental-consent' },
        { key: 'age-21-25', title: '21-25 years old', icon: '21', description: 'Needs parental advice.', gradient: 'linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)', value: 'parental-advise' },
        { key: 'age-25-up', title: '25 years old or over', icon: '25+', description: 'No parental requirement.', gradient: 'linear-gradient(135deg, #ff6a00 0%, #ee0979 100%)', value: 'no-need' }
      ];
    }
  },
  methods: {
    handleBack() {
      if (this.step === 3 && this.currentPerson === 'bride') {
        this.currentPerson = 'groom';
        return;
      }

      if (this.step > 1) {
        this.step -= 1;
        return;
      }

      this.$router.back();
    },
    selectOption(option) {
      if (this.step === 1) {
        this.isCohabiting = option.value;

        if (option.value === true) {
          sessionStorage.setItem('isCohabiting', true);
          this.$router.push('/home');
          return;
        }

        this.step = 2;
        window.scrollTo(0, 0);
        return;
      }

      if (this.step === 2) {
        this.isForeigner = option.isForeigner;
        this.type = option.type;
        this.step = 3;
        this.currentPerson = 'groom';
        window.scrollTo(0, 0);
        return;
      }

      this.handleAgeSelection(option.value);
    },
    handleAgeSelection(value) {
      if (this.currentPerson === 'groom') {
        this.groomAgeRange = value;
        this.currentPerson = 'bride';
        window.scrollTo(0, 0);
        return;
      }

      this.brideAgeRange = value;
      const link = `/Marriage-Form/requirements/groom/${this.groomAgeRange}/bride/${this.brideAgeRange}/isForeigner/${this.isForeigner}/${this.type}/Cohab/${this.isCohabiting}`;
      this.$router.push(link);
    }
  }
};
</script>

<style scoped>
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
