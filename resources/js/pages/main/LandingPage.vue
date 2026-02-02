<template>
    <div class="main-container">
        <nav class="navbar navbar-light bg-white shadow-sm fixed-top">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <div class="nav-logo me-2 me-md-3">
                        <svg width="22" height="22" fill="white" viewBox="0 0 16 16">
                            <path
                                d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z" />
                        </svg>
                    </div>
                    <div>
                        <div class="fw-bold fs-6 fs-md-5 lh-sm text-dark">{{ config.siteName }}</div>
                        <small class="text-muted d-none d-md-inline small-text">Digital Services</small>
                    </div>
                </a>

                <div class="d-none d-md-flex align-items-center">
                    <ul class="navbar-nav flex-row me-4">
                        <li v-for="link in navLinks" :key="link.text" class="nav-item px-2">
                            <a class="nav-link fw-semibold py-2" :href="link.url">{{ link.text }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="content-wrapper">
            <!-- Start Introduction -->
            <section class="hero-section text-white py-5 position-relative">
                <div class="container position-relative z-1">
                    <div class="row align-items-center min-vh-75 py-5">
                        <div class="col-lg-6 mb-5 mb-lg-0 text-start">
                            <div class="feature-badge mb-4">
                                <span class="badge-pulse">●</span> <span>Trusted by 10M+ Citizens</span>
                            </div>
                            <h1 class="display-3 fw-bold mb-4 text-shadow">{{ config.heroHeading }}</h1>
                            <p class="fs-5 mb-4 fw-medium text-shadow">{{ config.heroSubtext }}</p>
                            <div class="d-flex flex-wrap gap-3 mb-5">
                                <button class="btn btn-primary btn-lg px-5 py-3 fw-semibold shadow-lg">Get Started
                                    Now</button>
                                <button class="btn btn-light btn-lg px-5 py-3 fw-semibold shadow">Learn More →</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End of introduction -->

            <!-- Start of services -->
            <section id="services" class="py-5">
                <div class="container py-5">
                    <div class="text-center mb-5">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-4 py-2 rounded-pill fs-6 mb-3">Our
                            Services</span>
                        <h2 class="display-5 fw-bold mb-3 text-dark text-shadow-light">{{ config.servicesHeading }}</h2>
                        <p class="text-dark fw-medium fs-5 mx-auto max-w-600">{{ config.servicesSubtext }}</p>
                    </div>
                    <div class="row g-4 justify-content-center">
                        <div v-for="service in services" :key="service.title" class="col-lg-4 col-md-6">
                            <div class="floating-card glass-card rounded-4 p-4 shadow-sm h-100 text-start">
                                <div class="service-icon" :style="{ background: service.gradient }">
                                    {{ service.emoji }}
                                </div>
                                <h4 class="fw-bold mb-3 text-dark">{{ service.title }}</h4>
                                <p class="text-muted mb-4 fw-medium">{{ service.description }}</p>
                                <a href="#" class="text-decoration-none fw-bold gradient-text">Explore Service →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End of services -->

            <!-- Start of about section -->
            <section id="about" class="py-5 bg-light bg-opacity-75">
                <div class="container py-5">
                    <div class="row align-items-center">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div class="pe-lg-4">
                                <span
                                    class="badge bg-primary bg-opacity-10 text-primary px-4 py-2 rounded-pill fs-6 mb-3">About
                                    Us</span>
                                <h2 class="display-5 fw-bold mb-4 text-dark">Building a Seamless Digital Future</h2>
                                <p class="lead text-muted mb-4">We are committed to transforming how citizens interact
                                    with government services through innovation and accessibility.</p>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div
                                            class="p-3 bg-white rounded-3 shadow-sm border-start border-primary border-4">
                                            <h5 class="fw-bold mb-1">Secure</h5>
                                            <p class="small text-muted mb-0">Enterprise-grade data protection for your
                                                peace of mind.</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div
                                            class="p-3 bg-white rounded-3 shadow-sm border-start border-success border-4">
                                            <h5 class="fw-bold mb-1">24/7 Access</h5>
                                            <p class="small text-muted mb-0">No more queues. Access services anytime,
                                                anywhere.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="glass-card p-2 rounded-4 shadow-lg rotate-lg-3">
                                <div class="bg-primary rounded-3 d-flex align-items-center justify-content-center"
                                    style="height: 350px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <div class="text-center text-white p-4">
                                        <div class="display-1 mb-3">🏛️</div>
                                        <h3>Modern Governance</h3>
                                        <p class="opacity-75">Efficiency through Digitalization</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End of about section -->

            <!-- Start of contact section -->
            <section id="contact" class="py-5">
                <div class="container py-5">
                    <div class="glass-card rounded-4 shadow-lg overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-5 bg-primary p-5 text-white d-flex flex-column justify-content-center"
                                style="background: linear-gradient(135deg, #1e3a8a 0%, #2a52be 100%) !important;">
                                <h2 class="fw-bold mb-4">Get in Touch</h2>
                                <p class="mb-5 opacity-75">Have questions? Our support team is here to help you navigate
                                    the digital portal.</p>

                                <div class="d-flex align-items-center mb-4">
                                    <div class="icon-circle bg-white bg-opacity-20 me-3">📍</div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Location</h6>
                                        <small class="opacity-75">123 Government Plaza, Capital City</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-4">
                                    <div class="icon-circle bg-white bg-opacity-20 me-3">📞</div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Phone</h6>
                                        <small class="opacity-75">+1 (555) 000-1234</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-white bg-opacity-20 me-3">✉️</div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Email</h6>
                                        <small class="opacity-75">support@govportal.gov</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 p-5 bg-white">
                                <form @submit.prevent>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Full Name</label>
                                            <input type="text"
                                                class="form-control form-control-lg bg-light border-0 fs-6"
                                                placeholder="John Doe">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Email Address</label>
                                            <input type="email"
                                                class="form-control form-control-lg bg-light border-0 fs-6"
                                                placeholder="john@example.com">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Subject</label>
                                            <select class="form-select form-select-lg bg-light border-0 fs-6">
                                                <option selected disabled>Choose a service...</option>
                                                <option>Marriage License</option>
                                                <option>Technical Support</option>
                                                <option>Other Inquiry</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Message</label>
                                            <textarea class="form-control bg-light border-0 fs-6" rows="4"
                                                placeholder="How can we help you?"></textarea>
                                        </div>
                                        <div class="col-12 mt-4">
                                            <button type="submit"
                                                class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">Send
                                                Message</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End of contact section -->

        </main>

        <footer class="bg-dark text-white py-5">
            <div class="container">
                <div class="row g-4 text-start">
                    <div class="col-lg-4 mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="nav-logo me-3">
                                <svg width="24" height="24" fill="white" viewBox="0 0 16 16">
                                    <path
                                        d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z" />
                                </svg>
                            </div>
                            <div class="fw-bold fs-5">{{ config.siteName }}</div>
                        </div>
                        <p class="text-white-50">Empowering citizens through accessible and secure digital government
                            services.</p>
                    </div>
                    <div v-for="group in footerLinks" :key="group.title" class="col-lg-2 col-md-3 col-sm-6">
                        <h6 class="fw-bold mb-3">{{ group.title }}</h6>
                        <ul class="list-unstyled">
                            <li v-for="link in group.links" :key="link" class="mb-2">
                                <a href="#" class="text-white-50 text-decoration-none hover-white">{{ link }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <hr class="my-4 opacity-25">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <small class="text-white-50">© 2026 {{ config.siteName }}. All rights reserved.</small>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white-50 text-decoration-none small">Privacy Policy</a>
                        <a href="#" class="text-white-50 text-decoration-none small">Accessibility</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { reactive } from 'vue';

const config = reactive({
    siteName: "Gov Portal",
    heroHeading: "Access Government Services Anytime",
    heroSubtext: "Your one-stop digital platform for all public services. Fast, secure, and available round the clock.",
    servicesHeading: "Marriage & Civil Wedding",
    servicesSubtext: "Complete your marriage registration and schedule your ceremony entirely online."
});

const navLinks = [
    { text: 'Services', url: '#services' },
    { text: 'About', url: '#about' },
    { text: 'Contact', url: '#contact' }
];

const stats = [
    { label: 'Applications', value: '24', color: 'primary' },
    { label: 'Completed', value: '18', color: 'success' }
];

const activities = [
    { id: 1, title: 'Passport Application', status: 'Approved', time: '2h ago', type: 'primary', icon: 'check-circle' },
    { id: 2, title: 'Tax Return Filing', status: 'In Progress', time: '1d ago', type: 'warning', icon: 'clock' }
];

const services = [
    { title: "Marriage Certificate", emoji: "🆔", description: "Apply for Marriage Certificate online.", gradient: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)" },
    { title: "Wedding Appointment", emoji: "📅", description: "Schedule your ceremony online.", gradient: "linear-gradient(135deg, #f093fb 0%, #f5576c 100%)" },
];

const footerLinks = [
    { title: 'Services', links: ['Identity', 'Payments', 'Licensing'] },
    { title: 'Resources', links: ['Help Center', 'API Access', 'Status'] },
    { title: 'Legal', links: ['Privacy', 'Terms', 'Security'] }
];
</script>

<style scoped>
.main-container {
    font-family: 'Poppins', sans-serif;
    /* SAMPLE BACKGROUND IMAGE (Government/Public Service theme) */
    background-image: linear-gradient(rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.1)), url('../../images/background.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    min-height: 100vh;
}

.text-shadow {
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
}

.text-shadow-light {
    text-shadow: 1px 1px 4px rgba(255, 255, 255, 0.8);
}

.z-1 {
    z-index: 1;
}

.pointer {
    cursor: pointer;
}

.max-w-600 {
    max-width: 600px;
}

.x-small {
    font-size: 11px;
}

/* Glassmorphism for cards */
.glass-card {
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.4);
}

.nav-logo {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}

.hero-section {
    margin-top: 72px;
    overflow: hidden;
}

/* Animations */
@keyframes float {

    0%,
    100% {
        transform: translateY(0px);
    }

    50% {
        transform: translateY(-20px);
    }
}

.float-animation {
    animation: float 6s ease-in-out infinite;
}

@keyframes pulse {

    0%,
    100% {
        opacity: 1;
    }

    50% {
        opacity: 0.5;
    }
}

.badge-pulse {
    animation: pulse 2s infinite;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.5rem;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.icon-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.service-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 20px;
    color: white;
    transition: transform 0.3s ease;
}

.floating-card {
    transition: all 0.3s ease;
}

.floating-card:hover {
    transform: translateY(-10px);
    background: rgba(255, 255, 255, 0.9);
}

.gradient-text {
    background: linear-gradient(135deg, #2a52be, #1e3a8a);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hover-white:hover {
    color: white !important;
}
</style>