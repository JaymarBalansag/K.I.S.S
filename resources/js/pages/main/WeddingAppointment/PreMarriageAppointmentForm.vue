<template>
    <div class="main-container min-vh-100 d-flex flex-column">
        <main class="flex-grow-1 d-flex align-items-center py-5">
            <div class="container py-5 mt-5">
                <router-link to="/pre-marriage-counseling" class="text-white text-decoration-none fw-bold">
                    <span class="me-2">&larr;</span> Back
                </router-link>

                <div class="row justify-content-center text-center mb-5 mt-4">
                    <div class="col-lg-8">
                        <h1 class="display-4 fw-bold text-white text-shadow-heavy mb-3">Pre-Marriage Counseling</h1>
                        <p class="lead text-white opacity-90">Fill out the form to secure your appointment.</p>
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
                                            <label class="form-label small fw-bold text-uppercase">First Name</label>
                                            <input type="text" v-model="form.first_name"
                                                class="form-control custom-input" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-uppercase">Middle Name</label>
                                            <input type="text" v-model="form.middle_name"
                                                class="form-control custom-input">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-uppercase">Last Name</label>
                                            <input type="text" v-model="form.last_name"
                                                class="form-control custom-input" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-uppercase">Extension</label>
                                            <select v-model="form.extension" class="form-select custom-input">
                                                <option value="">None</option>
                                                <option value="Jr">Jr.</option>
                                                <option value="Sr">Sr.</option>
                                                <option value="III">III</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mt-4">
                                            <label class="form-label small fw-bold text-uppercase">Cellphone
                                                Number</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0">+63</span>
                                                <input type="tel" v-model="form.phone_number" @input="handlePhoneInput"
                                                    class="form-control custom-input border-start-0"
                                                    placeholder="9XXXXXXXXX" required maxlength="10">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-top pt-4 mt-5 d-flex justify-content-between align-items-center">
                                    <p class="text-muted small mb-0">Ensure all details match your legal IDs.</p>
                                    <button type="submit" :disabled="loading"
                                        class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow-sm">
                                        <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                                        {{ loading ? 'Saving...' : 'Next' }}
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
import { CreateAppointment } from '../../../controller/pmoc.js';
import Swal from 'sweetalert2';

export default {
    data() {
        return {
            loading: false,
            form: {
                first_name: '',
                middle_name: '',
                last_name: '',
                extension: '',
                phone_number: '',
                application_id: null,
                requested_date: '',
                appointment_type: 'PMOC'
            }
        };
    },
    mounted() {
        this.form.requested_date = this.getRequestedDate();
        this.form.appointment_type = this.getAppointmentType();
    },
    methods: {
        getRequestedDate() {
            const rawDate = this.$route?.query?.date;
            if (rawDate) {
                const parsed = new Date(rawDate);
                if (!Number.isNaN(parsed.getTime())) {
                    return parsed.toISOString().slice(0, 10);
                }
            }
            return new Date().toISOString().slice(0, 10);
        },
        getAppointmentType() {
            const rawType = String(this.$route?.query?.type || '').toUpperCase();
            return rawType === 'PMO' ? 'PMO' : 'PMOC';
        },
        handlePhoneInput(event) {
            let value = String(event?.target?.value || this.form.phone_number || '').replace(/\D/g, '');

            if (value.length > 0 && value.charAt(0) !== '9') {
                value = '';
            }

            this.form.phone_number = value.slice(0, 10);
            if (event?.target) {
                event.target.value = this.form.phone_number;
            }
        },
        async handleSubmit() {
            if (this.form.phone_number.length !== 10) {
                return Swal.fire('Error', 'Please enter a valid 10-digit number.', 'error');
            }

            this.loading = true;
            try {
                const response = await CreateAppointment(this.form);

                // Debugging: This will show in your F12 console
                console.log("Raw Response:", response);

                // We try to find the data in response.data (Axios) or response (Direct)
                const result = response?.data || response;

                // Robust check for success and controlNumber
                if (result && (result.success === true || result.success === 'true')) {
                    this.showSuccessPopup(result.controlNumber);
                } else {
                    console.error("Structure check failed. Result was:", result);
                    throw new Error("Response success flag not found");
                }
            } catch (error) {
                console.error("Submission error details:", error);
                const message = error?.response?.data?.message || 'Failed to save appointment. Please check the console (F12) for details.';
                Swal.fire('Error', message, 'error');
            } finally {
                this.loading = false;
            }
        },

        showSuccessPopup(code) {
            Swal.fire({
                html: `
            <div class="text-center px-1">
                <div style="display: flex; justify-content: center; margin-bottom: 12px;">
                    <div style="width: 50px; height: 50px; border: 3px solid #d4edda; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <span style="color: #28a745; font-size: 28px;">✓</span>
                    </div>
                </div>

                <h2 style="color: #444; font-weight: 700; font-size: 1.4rem; margin-bottom: 10px;">Appointment Submitted!</h2>
                
                <p style="color: #666; margin-bottom: 2px; font-size: 0.9rem;">Your Control Number:</p>
                <h1 style="color: #007bff; font-weight: 800; font-size: 1.8rem; letter-spacing: 1px; margin-bottom: 15px;">${code}</h1>

                <div style="background-color: #fff9db; border: 1px solid #ffe066; border-radius: 8px; padding: 10px; margin-bottom: 15px; text-align: center;">
                    <p style="color: #856404; font-weight: 700; font-size: 0.8rem; margin-bottom: 2px; text-transform: uppercase;">📸 Action Required:</p>
                    <p style="color: #856404; font-size: 0.85rem; line-height: 1.2; margin: 0;">
                        Please <strong>SCREENSHOT</strong> this code now.
                    </p>
                </div>

                <p style="color: #888; font-size: 0.8rem; line-height: 1.3; margin-bottom: 0;">
                    Present this at the <strong>Abuyog LCRO Office</strong>.
                </p>
            </div>
        `,
                showConfirmButton: true,
                confirmButtonText: 'I have screenshotted it',
                confirmButtonColor: '#198754',
                width: '90%', // Makes it responsive on mobile
                maxWidth: '400px', // Prevents it from getting too wide on desktop
                padding: '1.2rem',
                allowOutsideClick: false,
                allowEscapeKey: false,
                customClass: {
                    confirmButton: 'btn-sm px-4 py-2 fw-bold' // Makes button smaller
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$router.push('/');
                }
            });
        }
    }
};
</script>

<style scoped>
/* Styles remain the same as your previous version */
.main-container {
    background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/public/background.jpg') no-repeat center center fixed;
    background-size: cover;
}

.glass-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 28px;
}

.section-badge {
    background: #1e3c72;
    color: white;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.custom-input {
    border-radius: 10px;
    padding: 12px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
}

.text-shadow-heavy {
    text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.3);
}
</style>
