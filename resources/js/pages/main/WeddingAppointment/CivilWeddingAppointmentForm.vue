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

                <div class="row justify-content-center mb-4">
                    <div class="col-12 col-xl-10">
                        <div class="quick-note d-flex flex-column flex-md-row justify-content-between gap-2">
                            <span><strong>Selected Date:</strong> {{ formattedRequestedDate }}</span>
                            <span><strong>Type:</strong> Civil Wedding</span>
                            <span><strong>Checklist:</strong> Complete all required uploads</span>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-12 col-xl-10">
                        <div class="glass-card form-panel p-4 p-md-5 shadow-lg border-0">

                            <form @submit.prevent="handleSubmit">
                                <div class="form-section form-section-card mb-4 mb-md-5">
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
                                            <input type="text" v-model="form.extension"
                                                class="form-control custom-input" placeholder="Jr., Sr., III, etc.">
                                        </div>

                                        <div class="col-md-6 mt-4">
                                            <label
                                                class="form-label small fw-bold text-uppercase tracking-wider">Cellphone
                                                Number</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0">+63</span>
                                                <input type="tel" v-model="form.phone"
                                                    class="form-control custom-input border-start-0"
                                                    :class="{ 'border-danger': errors.phone }" placeholder="9XXXXXXXXX"
                                                    required>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <small v-if="errors.phone" class="text-danger d-block mt-1">{{
                                                    errors.phone }}</small>
                                                <small v-else class="text-muted mt-1">10 digits starting with 9</small>
                                                <small class="text-muted mt-1">{{ form.phone.length }}/10</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="errors.name"
                                        class="alert alert-light border-0 py-2 px-3 mt-4 small text-danger shadow-sm">
                                        <i class="bi bi-exclamation-circle me-2"></i> {{ errors.name }}
                                    </div>
                                </div>

                                <div class="form-section form-section-card mb-4 mb-md-5">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="section-badge me-3">2</div>
                                        <h4 class="mb-0 text-dark fw-bold">Eligibility Requirements</h4>
                                    </div>

                                    <label class="form-label small fw-bold text-uppercase tracking-wider">Are you and
                                        your partner cohabiting for at least 5 years prior to your application?</label>

                                    <div class="cohabiting-options mt-2">
                                        <div class="choice-col">
                                            <div class="choice-box h-100" :class="{ active: form.cohabiting === 'yes' }"
                                                @click="handleCohabitingSelect('yes')">
                                                <div class="choice-title">Yes</div>
                                                <small class="text-muted">Upload required supporting documents</small>
                                            </div>
                                        </div>

                                        <div class="choice-col">
                                            <div class="choice-box h-100" :class="{ active: form.cohabiting === 'no' }"
                                                @click="handleCohabitingSelect('no')">
                                                <div class="choice-title">No</div>
                                                <small class="text-muted">Marriage License is required</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="errors.cohabiting"
                                        class="alert alert-light border-0 py-2 px-3 mt-3 small text-danger shadow-sm">
                                        <i class="bi bi-exclamation-circle me-2"></i> {{ errors.cohabiting }}
                                    </div>

                                    <div v-if="form.cohabiting === 'no'" class="mt-4">
                                        <div class="requirement-card">
                                            <div class="fw-bold text-dark mb-2">Requirement: Marriage License</div>
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">Upload
                                                Marriage License</label>
                                            <input type="file" accept="image/*" class="form-control custom-input"
                                                @change="handleFileChange('marriageLicense', $event)">
                                        </div>
                                    </div>

                                    <div v-if="form.cohabiting === 'yes'" class="mt-4">
                                        <div class="requirement-card">
                                            <div class="fw-bold text-dark mb-2">Requirements (Cohabiting 5+ Years)</div>
                                            <div class="small text-muted mb-3">Please upload image files for all
                                                required documents:</div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">CENOMAR (Issued at least 3
                                                    months before application)</label>
                                                <input type="file" accept="image/*" class="form-control custom-input"
                                                    @change="handleFileChange('cenomar', $event)">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">PSA or LCRO copy of Certificate
                                                    of Live Birth</label>
                                                <input type="file" accept="image/*" class="form-control custom-input"
                                                    @change="handleFileChange('birthCertificate', $event)">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">National ID or any
                                                    government-issued ID</label>
                                                <input type="file" accept="image/*" class="form-control custom-input"
                                                    @change="handleFileChange('nationalId', $event)">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">PMOC Certificate</label>
                                                <input type="file" accept="image/*" class="form-control custom-input"
                                                    @change="handleFileChange('pmocCert', $event)">
                                                <a href="/pre-marriage-counseling"
                                                    class="d-inline-block mt-2 small text-decoration-underline">
                                                    No PMOC Certificate Yet? Click here for counseling options.
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-top pt-4 mt-5 d-flex justify-content-between align-items-center">
                                    <p class="text-muted small mb-0">Please ensure all details match your legal IDs.</p>
                                    <button type="submit" :disabled="loading"
                                        class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow-sm transition-hover">
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
import api from '../../../controller/api';
import Swal from 'sweetalert2';

export default {
    name: 'CivilWeddingAppointmentForm',
    data() {
        return {
            loading: false,
            config: {
                servicesHeading: "Civil Wedding Appointment",
                servicesSubtext: "Appointment for Civil Wedding."
            },
            form: {
                firstName: '',
                middleName: '',
                lastName: '',
                extension: '',
                phone: '',
                email: '',
                cohabiting: '',
                application_id: null,
                requested_date: ''
            },
            errors: {
                name: '',
                phone: '',
                cohabiting: ''
            },
            uploads: {
                cenomar: null,
                birthCertificate: null,
                nationalId: null,
                pmocCert: null,
                complianceCert: null,
                marriageLicense: null
            }
        };
    },
    mounted() {
        this.form.requested_date = this.getRequestedDate();
    },
    computed: {
        formattedRequestedDate() {
            if (!this.form.requested_date) return 'Not selected';
            const parsed = new Date(this.form.requested_date);
            if (Number.isNaN(parsed.getTime())) return this.form.requested_date;
            return new Intl.DateTimeFormat('en-US', { dateStyle: 'full' }).format(parsed);
        }
    },
    watch: {
        'form.firstName'(val) {
            const clean = val.replace(/[^a-zA-Z\s]/g, '');
            if (val !== clean) {
                this.errors.name = "Names should only contain letters.";
                this.form.firstName = clean;
            } else { this.errors.name = ""; }
        },
        'form.middleName'(val) {
            const clean = val.replace(/[^a-zA-Z\s]/g, '');
            if (val !== clean) {
                this.errors.name = "Names should only contain letters.";
                this.form.middleName = clean;
            } else { this.errors.name = ""; }
        },
        'form.lastName'(val) {
            const clean = val.replace(/[^a-zA-Z\s]/g, '');
            if (val !== clean) {
                this.errors.name = "Names should only contain letters.";
                this.form.lastName = clean;
            } else { this.errors.name = ""; }
        },
        'form.phone'(val) {
            let clean = val.replace(/\D/g, '');

            if (clean.length > 0 && clean[0] !== '9') {
                this.errors.phone = "Number must start with 9.";
                this.form.phone = '';
                return;
            }

            if (clean.length > 10) {
                clean = clean.slice(0, 10);
            }

            this.errors.phone = "";
            this.form.phone = clean;
        }
    },
    methods: {
        getRequestedDate() {
            const rawDate = String(this.$route?.query?.date || '');

            if (/^\d{4}-\d{2}-\d{2}$/.test(rawDate)) {
                return rawDate;
            }

            if (rawDate) {
                const parsed = new Date(rawDate);
                if (!Number.isNaN(parsed.getTime())) {
                    const year = parsed.getFullYear();
                    const month = String(parsed.getMonth() + 1).padStart(2, '0');
                    const day = String(parsed.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                }
            }

            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },
        handleCohabitingSelect(value) {
            this.form.cohabiting = value;
            this.errors.cohabiting = '';
        },
        handleFileChange(field, event) {
            const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;
            this.uploads[field] = file;
        },
        async handleSubmit() {
            if (this.form.phone.length !== 10) {
                this.errors.phone = "Phone number must be exactly 10 digits.";
                return;
            }

            if (!this.form.cohabiting) {
                this.errors.cohabiting = "Please select Yes or No.";
                return;
            }

            if (this.form.cohabiting === 'yes') {
                const yesRequirements = [
                    this.uploads.cenomar,
                    this.uploads.birthCertificate,
                    this.uploads.nationalId,
                    this.uploads.pmocCert,
                ];

                if (yesRequirements.some((item) => !item)) {
                    alert("Please upload all required documents for cohabiting applicants.");
                    return;
                }
            }

            if (this.form.cohabiting === 'no' && !this.uploads.marriageLicense) {
                alert("Please upload your Marriage License.");
                return;
            }

            if (this.errors.name || this.errors.phone) {
                alert("Please correct the invalid entries.");
                return;
            }

            this.loading = true;
            try {
                const payload = {
                    application_id: this.form.application_id,
                    appointment_type: 'Civil Wedding',
                    first_name: this.form.firstName,
                    middle_name: this.form.middleName,
                    last_name: this.form.lastName,
                    extension: this.form.extension,
                    phone_number: this.form.phone,
                    requested_date: this.form.requested_date
                };

                const response = await api.post('/Appointments', payload);
                const result = response?.data || {};

                if (!result.success) {
                    throw new Error('Unable to save civil wedding appointment.');
                }

                await this.showSuccessPopup(result.controlNumber);
            } catch (error) {
                const message = error?.response?.data?.message || 'Failed to submit appointment.';
                Swal.fire('Error', message, 'error');
            } finally {
                this.loading = false;
            }
        },
        async showSuccessPopup(code) {
            await Swal.fire({
                html: `
                    <div class="text-center px-1">
                        <div style="display:flex;justify-content:center;margin-bottom:12px;">
                            <div style="width:58px;height:58px;border:3px solid #cdeac0;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                <span style="color:#64b65b;font-size:30px;line-height:1;">&#10003;</span>
                            </div>
                        </div>

                        <h2 style="color:#444;font-weight:700;font-size:1.85rem;margin-bottom:14px;">Application Submitted!</h2>

                        <p style="color:#666;margin-bottom:4px;font-size:0.98rem;">Your Control Number is:</p>
                        <h1 style="color:#1d6fe7;font-weight:800;font-size:2rem;letter-spacing:1px;margin-bottom:16px;">${code}</h1>

                        <div style="background-color:#f3e7c1;border:1px solid #e7d79d;border-radius:8px;padding:10px 12px;margin-bottom:18px;text-align:left;">
                            <p style="color:#6c5b2c;font-weight:800;font-size:0.86rem;margin-bottom:4px;text-align:center;">ACTION REQUIRED:</p>
                            <p style="color:#6c5b2c;font-size:0.95rem;line-height:1.25;margin:0;">
                                Please <strong>SCREENSHOT</strong> this screen or write down the code now.
                            </p>
                        </div>

                        <p style="color:#777;font-size:0.92rem;line-height:1.35;margin-bottom:0;">
                            Present this number at the <strong>Abuyog LCRO Office</strong> to verify your application.
                        </p>
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: 'Done',
                confirmButtonColor: '#1f8e4d',
                width: '90%',
                maxWidth: '430px',
                padding: '1.2rem',
                allowOutsideClick: false,
                allowEscapeKey: false
            });

            this.$router.push('/');
        }
    }
};
</script>

<style scoped>
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

.glass-card {
    background: rgba(255, 255, 255, 0.82) !important;
    backdrop-filter: blur(15px) saturate(180%);
    -webkit-backdrop-filter: blur(15px) saturate(180%);
    border-radius: 28px;
    border: 1px solid rgba(255, 255, 255, 0.4) !important;
}

.form-panel {
    box-shadow: 0 14px 34px rgba(0, 0, 0, 0.22) !important;
}

.quick-note {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.35);
    border-radius: 14px;
    padding: 10px 14px;
    backdrop-filter: blur(8px);
}

.form-section-card {
    background: rgba(255, 255, 255, 0.72);
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 16px;
    padding: 18px;
}

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

.border-danger {
    border-color: #dc3545 !important;
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

.cohabiting-options {
    display: flex;
    gap: 12px;
}

.choice-col {
    flex: 1 1 0;
    min-width: 0;
}

.choice-box {
    background: rgba(255, 255, 255, 0.6);
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    padding: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.choice-box:hover {
    border-color: #1e3c72;
}

.choice-box.active {
    border-color: #1e3c72;
    box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.12);
    background: #fff;
}

.choice-title {
    font-weight: 700;
    color: #1e3c72;
}

.requirement-card {
    background: rgba(255, 255, 255, 0.72);
    border: 1px solid rgba(30, 60, 114, 0.18);
    border-radius: 12px;
    padding: 16px;
}

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

@media (max-width: 768px) {
    .glass-card {
        padding: 1.5rem !important;
        border-radius: 20px;
    }

    .form-section-card {
        padding: 14px;
    }

    .cohabiting-options {
        gap: 10px;
    }
}
</style>
