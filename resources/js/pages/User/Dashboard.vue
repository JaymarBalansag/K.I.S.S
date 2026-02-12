<template>
    <div class="main-container min-vh-100 d-flex flex-column">
        <Navbar />

        <main class="flex-grow-1 d-flex align-items-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-5">

                        <div class="text-center mb-4">
                            <h2 class="text-white fw-light">Appointment Status</h2>
                            <p class="text-white-50 small">Reference No: #ABC-12345</p>
                        </div>

                        <div class="status-card shadow-sm">
                            <div class="p-4 text-center border-bottom bg-light rounded-top">
                                <span class="text-uppercase text-muted small fw-bold tracking-widest">Current
                                    Phase</span>
                                <h3 class="text-primary mt-2 mb-0">Under Review</h3>
                            </div>

                            <div class="p-4 bg-white">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Applicant:</span>
                                    <span class="fw-bold">Juan Dela Cruz</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Service:</span>
                                    <span class="fw-bold">Civil Wedding</span>
                                </div>
                                <div class="d-flex justify-content-between mb-0">
                                    <span class="text-muted">Location:</span>
                                    <span class="fw-bold">Abuyog Municipality</span>
                                </div>
                            </div>

                            <div class="p-4 bg-white rounded-bottom border-top text-center">
                                <p class="small text-muted mb-3">
                                    Your documents are currently being verified by the LCRO office.
                                </p>
                                <router-link to="/wedding-appointment" class="btn btn-primary w-100 py-2">
                                    Refresh Status
                                </router-link>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>

        <Footer />
    </div>
</template>

<script>
import Navbar from '/resources/js/components/navbar.vue';
import Footer from '/resources/js/components/footer.vue';

export default {
    name: 'StatusPortal',
    components: { Navbar, Footer }
};
</script>

<style scoped>
.main-container {
    font-family: 'Inter', -apple-system, sans-serif;
    background: #1a1a1a;
    /* Solid dark background is simpler than images */
}

.status-card {
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
}

.tracking-widest {
    letter-spacing: 2px;
}

.btn-primary {
    background-color: #0062ff;
    border: none;
    font-weight: 600;
    border-radius: 8px;
}

.text-white-50 {
    color: rgba(255, 255, 255, 0.5) !important;
}

.main-container {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)),
        url('/public/background.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

@media (max-width: 768px) {
    .container {
        padding: 0 20px;
    }
}
</style>
