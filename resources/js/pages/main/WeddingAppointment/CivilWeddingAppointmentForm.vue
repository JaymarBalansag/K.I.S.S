<template>
    <div class="main-container min-vh-100 d-flex flex-column">
        <main class="flex-grow-1 d-flex align-items-center py-5">
            <div class="container py-5 mt-5">
                <router-link to="/civil-wedding-appointment" class="text-white text-decoration-none fw-bold">
                    <span class="me-2">&larr;</span> Back
                </router-link>
                <div class="row justify-content-center text-center mb-5 mt-4">
                    <div class="col-lg-8">
                        <h1 class="display-4 fw-bold text-white text-shadow-heavy mb-3">
                            {{ config.servicesHeading }}
                        </h1>
                        <p class="lead text-white text-shadow-medium opacity-90">
                            {{ config.servicesSubtext }}
                        </p>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-12 col-xl-10">
                        <div class="glass-card p-4 p-md-5 shadow-lg border-0">

                            <form @submit.prevent="handleSubmit">
                                <div class="form-section mb-5">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="section-badge me-3">1</div>
                                        <h4 class="mb-0 text-dark fw-bold">Applicant's Information</h4>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">First
                                                Name</label>
                                            <input type="text" v-model="form.firstName"
                                                class="form-control custom-input" placeholder="John" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">Middle
                                                Name</label>
                                            <input type="text" v-model="form.middleName"
                                                class="form-control custom-input" placeholder="Quency">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">Last
                                                Name</label>
                                            <input type="text" v-model="form.lastName" class="form-control custom-input"
                                                placeholder="Doe" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label
                                                class="form-label small fw-bold text-uppercase tracking-wider">Extension</label>
                                            <select v-model="form.extension" class="form-select custom-input">
                                                <option value="">None</option>
                                                <option value="Jr">Jr.</option>
                                                <option value="Sr">Sr.</option>
                                                <option value="III">III</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mt-4">
                                            <label
                                                class="form-label small fw-bold text-uppercase tracking-wider">Cellphone
                                                Number</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0">+63</span>
                                                <input type="tel" v-model="form.phone"
                                                    class="form-control custom-input border-start-0"
                                                    placeholder="912 345 6789" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-top pt-4 mt-5 d-flex justify-content-between align-items-center">
                                    <p class="text-muted small mb-0">Please ensure all details match your legal IDs.</p>
                                    <button type="submit"
                                        class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow-sm transition-hover">
                                        Next
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>
</template>

<script>
export default {
    name: 'CivilWeddingAppointmentForm',
    data() {
        return {
            config: {
                servicesHeading: "Civil Wedding Appointment",
                servicesSubtext: "Appointment for Civil Wedding."
            },
            form: {
                firstName: '',
                middleName: '',
                lastName: '',
                extension: '',
                phone: ''
            }
        };
    },
    methods: {
        handleSubmit() {
            console.log("Form Submitted:", this.form);
            alert("Proceeding to next step...");
        }
    }
};
</script>

<style scoped>
/* BACKGROUND & GLOBAL */
.main-container {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6)),
        url('/public/background.jpg') no-repeat center center fixed;
    background-size: cover;
}

.text-shadow-heavy {
    text-shadow: 0 4px 15px rgba(0, 0, 0, 0.6);
}

.text-shadow-medium {
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
}

/* GLASSMORPHISM CARD */
.glass-card {
    background: rgba(255, 255, 255, 0.82) !important;
    backdrop-filter: blur(15px) saturate(180%);
    -webkit-backdrop-filter: blur(15px) saturate(180%);
    border-radius: 28px;
    border: 1px solid rgba(255, 255, 255, 0.4) !important;
}

/* FORM ELEMENTS */
.custom-input {
    background: rgba(255, 255, 255, 0.6);
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    padding: 12px 16px;
    transition: all 0.3s ease;
}

.custom-input:focus {
    background: #ffffff;
    box-shadow: 0 0 0 4px rgba(30, 60, 114, 0.1);
    border-color: #1e3c72;
    outline: none;
}

.input-group-text {
    border-radius: 12px 0 0 12px;
    color: #555;
    font-weight: 600;
}

.section-badge {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: bold;
    box-shadow: 0 4px 10px rgba(30, 60, 114, 0.3);
}

.tracking-wider {
    letter-spacing: 0.05em;
    font-size: 0.75rem;
    color: #555;
}

/* BUTTONS */
.btn-primary {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    border: none;
    transition: all 0.3s ease;
}

.transition-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(30, 60, 114, 0.3) !important;
    filter: brightness(1.1);
}

/* MOBILE FIXES */
@media (max-width: 768px) {
    .glass-card {
        padding: 1.5rem !important;
        border-radius: 20px;
    }
}
</style>