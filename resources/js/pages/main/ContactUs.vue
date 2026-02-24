<template>
  <div class="main-container min-vh-100 d-flex flex-column">
    
    <Navbar></Navbar>

    <main class="flex-grow-1 py-5">
      <div class="container py-5 mt-5">
        
        <div class="row justify-content-center text-center mb-5 mt-4">
          <div class="col-lg-9 col-xl-8">
            <span class="badge bg-primary bg-opacity-75 rounded-pill px-4 py-2 mb-3 shadow-sm text-uppercase fw-bold" style="letter-spacing: 1px;">
              Get In Touch
            </span>
            <h1 class="display-2 fw-bolder text-white mb-3 text-shadow-heavy">
              Contact Us
            </h1>
            <p class="lead text-white fw-medium mb-4 text-shadow-medium opacity-90 mx-auto" style="max-width: 700px;">
              Have questions about your marriage license or appointment? Reach out to us directly via email or visit our office.
            </p>
          </div>
        </div>

        <div class="row g-4 justify-content-center">
          <div class="col-lg-5">
            <div class="card border-0 shadow-lg glass-card p-4 h-100">
              <div class="card-body">
                <h3 class="fw-bold text-dark mb-4 border-bottom pb-2">Office Information</h3>
                
                <div class="d-flex mb-4">
                  <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">
                    <i class="bi bi-geo-alt-fill fs-4"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-1">Visit Our Office</h6>
                    <p class="text-muted small mb-0">
                      Ground Flr. Annex Bldg. LGU Compound Barangay Loyonsawang, Abuyog, Philippines, 6510
                    </p>
                  </div>
                </div>

                <div class="d-flex mb-4">
                  <div class="icon-box bg-success bg-opacity-10 text-success rounded-3 p-3 me-3">
                    <i class="bi bi-clock-fill fs-4"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-1">Operating Hours</h6>
                    <p class="text-muted small mb-0">Monday - Friday: 8:00 AM - 5:00 PM</p>
                    <p class="text-muted small mb-0">Closed on Weekends & Holidays</p>
                  </div>
                </div>

                <div class="d-flex mb-4">
                  <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">
                    <i class="bi bi-facebook fs-4"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-1">Facebook Page</h6>
                    <p class="text-muted small mb-0">
                      <a href="https://www.facebook.com/profile.php?id=61557346830959" target="_blank" class="text-decoration-none text-primary fw-bold">Visit our Facebook Page</a>
                    </p>
                  </div>
                </div>

                <div class="d-none d-md-flex mb-4">
                  <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">
                    <i class="bi bi-envelope-paper-fill fs-4"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-1">Email us through</h6>
                    <p class="text-muted small mb-0">
                      <a href="https://mail.google.com/mail/u/0/#inbox?compose=HrrBHlndnSdWHCGqZnCpfRXhxBqkQLtCQDwPdNskcfHfnZWXGzDGGFHjprmNcShPjqJpRNtmpRtVdXqShvTHgCwGwjzMClsgbGMNdkcjKjqWPFGKk" target="_blank" class="text-decoration-none text-primary fw-bold">mcrabuyog@gmail.com</a>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="d-md-none d-flex col-lg-5">
            <div class="card border-0 shadow-lg glass-card p-4 h-100 d-flex flex-column justify-content-center text-center">
              <div class="card-body py-5">
                <div class="mb-4">
                  <i class="bi bi-envelope-paper-fill display-1 text-primary"></i>
                </div>
                <h3 class="fw-bold text-dark mb-3">Email Us Directly</h3>
                <p class="text-muted mb-5">
                  For all inquiries regarding marriage registration, legal documents, and ceremony appointments, please click the button below to send us an email.
                </p>
                
                <button @click="sendEmail" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm transition-all">
                  <i class="bi bi-send-fill me-2"></i> mcrabuyog@gmail.com
                </button>
                
                <div class="mt-4 pt-3 border-top border-dark border-opacity-10">
                  <small class="text-muted">Typical response: Within 24-48 hours</small>
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
  name: 'ContactPage',
  components: {
    Navbar,
    Footer
  },
  data() {
    return {
      config: {
        siteName: "LCRO Abuyog"
      }
    };
  },
  methods: {
    sendEmail() {
      // Using window.location.assign is sometimes more reliable than an <a> tag in Vue
      window.location.assign("mailto:mcrabuyog@gmail.com?subject=Inquiry from Portal");
    }
  }
};
</script>

<style scoped>
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

.glass-card {
  background: rgba(255, 255, 255, 0.96) !important;
  backdrop-filter: blur(12px);
  border-radius: 20px;
}

.icon-box {
  width: 50px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.transition-all {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3) !important;
}
</style>