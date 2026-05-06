<template>
    <div class="main-container min-vh-100 d-flex flex-column">
        <main class="flex-grow-1 py-5 mt-5">
            <div class="container py-4">
                <div class="text-start mb-4">
                    <a @click.prevent="this.$router.push('/home')"
                        class="text-white text-decoration-none opacity-75 hover-opacity-100 back-link"
                        style="cursor: pointer;">
                        <span class="me-2">&larr;</span> Home
                    </a>
                </div>

                <div class="row justify-content-center">
                    <div class="col-12 col-xl-10">
                        <div class="card glass-card shadow-lg border-0 overflow-hidden">
                            <div class="card-header p-4 border-0">
                                <h2 class="text-white fw-bold mb-1">
                                    Cohabitation Form
                                </h2>
                                <p class="text-white opacity-75 mb-0 text-shadow-medium">
                                    Please complete the details below. Review entries carefully before submitting.
                                </p>
                            </div>

                            <div class="card-body p-4 p-md-5">
                                <form @submit.prevent="handleSubmit">
                                <!-- Section 1: Form -->
                                <div class="glass-panel p-4 mb-4 mb-md-5">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="section-badge me-3">1</div>
                                        <h4 class="mb-0 text-white fw-bold">Partners Information</h4>
                                    </div>

                                    <div class="row g-4">

                                        <!-- Groom Full Name -->
                                        <h4 class="mb-0 text-white fw-bold">Groom Information</h4>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">
                                                First Name
                                            </label>
                                            <input type="text" v-model.trim="form.groom.first_name"
                                                class="form-control custom-input" placeholder="Juan" required />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">Middle
                                                Name</label>
                                            <input type="text" v-model.trim="form.groom.middle_name"
                                                class="form-control custom-input" placeholder="Santos" />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">Last
                                                Name</label>
                                            <input type="text" v-model.trim="form.groom.last_name"
                                                class="form-control custom-input" placeholder="Dela Cruz" required />
                                        </div>
                                        <div class="col-md-3">
                                            <label
                                                class="form-label small fw-bold text-uppercase tracking-wider">Suffix</label>
                                            <input type="text" v-model.trim="form.groom.suffix"
                                                class="form-control custom-input" placeholder="Jr., Sr., III" />
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">ID Type</label>
                                            <select v-model="form.groom.id_type"
                                                    class="form-select custom-input form-control" required>
                                                <option disabled value="">Select…</option>
                                                <option value="Drivers Lic.">Driver's License</option>
                                                <option value="P">Passport</option>
                                                <option value="PhilSys">PhilSys</option>
                                                <option value="UMID">UMID</option>
                                                <option value="TIN">TIN ID</option>
                                                <option value="PRC">PRC License</option>
                                                <option value="Postal">Postal ID</option>
                                                <option value="Voter">Voter's ID</option>
                                                <option value="SSS">SSS ID</option>
                                                <option value="GSIS">GSIS ID</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">Valid
                                                ID Number</label>
                                            <input type="text" v-model.trim="form.groom.id_number"
                                                class="form-control custom-input" placeholder="ID Number" required/>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">Valid
                                                Issued At</label>
                                            <input type="text" v-model.trim="form.groom.issued_at"
                                                class="form-control custom-input" placeholder="Issued At" required/>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">Valid
                                                Issued On</label>
                                            <input type="date" v-model.trim="form.groom.issued_on"
                                                class="form-control custom-input" placeholder="Issued On" required />
                                        </div>

                                        <!-- Bride Full Name -->
                                        <h4 class="mb-0 text-white fw-bold">Bride Information</h4>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">
                                                First Name
                                            </label>
                                            <input type="text" v-model.trim="form.bride.first_name"
                                                class="form-control custom-input" placeholder="Juan" required />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">Middle
                                                Name</label>
                                            <input type="text" v-model.trim="form.bride.middle_name"
                                                class="form-control custom-input" placeholder="Santos" />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">Last
                                                Name</label>
                                            <input type="text" v-model.trim="form.bride.last_name"
                                                class="form-control custom-input" placeholder="Dela Cruz" required />
                                        </div>
                                        <div class="col-md-3">
                                            <label
                                                class="form-label small fw-bold text-uppercase tracking-wider">Suffix</label>
                                            <input type="text" v-model.trim="form.bride.suffix"
                                                class="form-control custom-input" placeholder="Jr., Sr., III" />
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">ID Type</label>
                                            <select v-model="form.bride.id_type"
                                                    class="form-select custom-input form-control" required>
                                                <option disabled value="">Select…</option>
                                                <option value="Drivers Lic.">Driver's License</option>
                                                <option value="P">Passport</option>
                                                <option value="PhilSys">PhilSys</option>
                                                <option value="UMID">UMID</option>
                                                <option value="TIN">TIN ID</option>
                                                <option value="PRC">PRC License</option>
                                                <option value="Postal">Postal ID</option>
                                                <option value="Voter">Voter's ID</option>
                                                <option value="SSS">SSS ID</option>
                                                <option value="GSIS">GSIS ID</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">Valid
                                                ID Number</label>
                                            <input type="text" v-model.trim="form.bride.id_number"
                                                class="form-control custom-input" placeholder="ID Number" required />
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">Valid
                                                Issued At</label>
                                            <input type="text" v-model.trim="form.bride.issued_at"
                                                class="form-control custom-input" placeholder="Issued At" />
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">Valid
                                                Issued On</label>
                                            <input type="date" v-model.trim="form.bride.issued_on"
                                                class="form-control custom-input" placeholder="Issued On" required />
                                        </div>

                                        <!-- Both Information -->
                                        <h4 class="mb-0 text-white fw-bold">Partnership Information</h4>

                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">Full Residence</label>
                                            <input type="text" v-model.trim="form.residence"
                                                class="form-control custom-input" placeholder="Full Residence" required />
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold text-uppercase tracking-wider">Start of Cohabitation</label>
                                            <input type="date" v-model.trim="form.cohabitation_start_date"
                                                class="form-control custom-input" placeholder="Start of Cohabitation" required/>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3">
                                    <button type="button" class="btn btn-outline-secondary btn-lg px-4"
                                        @click="resetForm" :disabled="submitting">
                                        Reset
                                    </button>

                                    <button type="submit"
                                        class="btn btn-primary btn-lg px-5 fw-bold shadow-sm glow-button"
                                        :disabled="submitting">
                                        <span v-if="submitting" class="spinner-border spinner-border-sm me-2"
                                            role="status" aria-hidden="true"></span>
                                        Submit Cohabitation Form
                                    </button>
                                </div>

                                
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
import Swal from 'sweetalert2';

const initialForm = () => ({
    groom: {
        first_name: '',
        middle_name: '',
        last_name: '',
        suffix: '',
        id_type: '',
        id_number: '',
        issued_at: '',
        issued_on: '',
    },
    bride: {
        first_name: '',
        middle_name: '',
        last_name: '',
        suffix: '',
        id_type: '',
        id_number: '',
        issued_at: '',
        issued_on: '',
    },
    residence: '',
    cohabitation_start_date: '',

});

export default {
    name: 'CohabitionForm',
    data() {
        return {
            form: initialForm(),
            submitting: false
        };
    },
    methods: {
        isCohabitingMessage() {
            Swal.fire({
                title: "Cohabitation",
                text: "Couples or partners who have cohabited for 5 years or more do not need a marriage license.",
                icon: "warning"
            });
        },
        resetForm() {
            this.form = initialForm();
        },
        formatErrors(errorData) {
            const errors = errorData?.errors;
            if (!errors || typeof errors !== 'object') return [];
            return Object.values(errors).flat().filter(Boolean);
        },
        async handleSubmit() {
            this.submitting = true;
            try {
                const confirm = await Swal.fire({
                    icon: 'question',
                    title: 'Submit cohabitation form?',
                    text: 'Please review your entries before continuing.',
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#1e3c72',
                    cancelButtonColor: '#6c757d',
                });

                if (!confirm.isConfirmed) return;

                const payload = {
                    residence: this.form.residence,
                    cohabitation_start_date: this.form.cohabitation_start_date,
                    form: {
                        groom: { ...this.form.groom },
                        bride: { ...this.form.bride },
                    },
                };

                const response = await window.axios.post('/api/cohabitation', payload);
                const responseBody = response?.data ?? null;
                const record = responseBody?.data ?? null;
                const controlNumber =
                    record?.control_number ??
                    record?.controlNumber ??
                    responseBody?.control_number ??
                    responseBody?.controlNumber ??
                    null;

                await Swal.fire({
                    icon: 'success',
                    title: 'Application Submitted!',
                    background: 'rgba(14, 20, 38, 0.96)',
                    color: '#ffffff',
                    html: `
                        <div style="text-align:center">
                            <p style="margin:0 0 6px;color:rgba(255,255,255,0.78);font-size:0.95rem;">
                                Your Control Number is:
                            </p>

                            <div style="font-weight:900;font-size:1.7rem;letter-spacing:1px;color:#cfe2ff;margin:0 0 12px;">
                                ${controlNumber ? String(controlNumber) : '—'}
                            </div>

                            <div style="background:rgba(255,193,7,0.12);border:1px solid rgba(255,193,7,0.35);border-radius:12px;padding:10px 12px;text-align:left;">
                                <div style="font-weight:800;font-size:0.8rem;letter-spacing:0.5px;color:#fbbf24;margin-bottom:4px;text-align:center;">
                                    ACTION REQUIRED
                                </div>
                                <div style="color:rgba(255,255,255,0.80);font-size:0.94rem;line-height:1.35;margin:0;">
                                    Please screenshot this screen or write down the control number now.
                                </div>
                            </div>
                        </div>
                    `,
                    confirmButtonText: 'Done',
                    confirmButtonColor: '#1e3c72',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                });

                this.resetForm();
            } catch (error) {
                const status = error?.response?.status;
                const messages = this.formatErrors(error?.response?.data);

                if (status === 422 && messages.length) {
                    await Swal.fire({
                        icon: 'error',
                        title: 'Please check your inputs',
                        background: 'rgba(14, 20, 38, 0.96)',
                        color: '#ffffff',
                        html: `<ul style="text-align:left;margin:0;padding-left:18px;">${messages
                            .map((msg) => `<li style="margin:4px 0;">${String(msg)}</li>`)
                            .join('')}</ul>`,
                        confirmButtonColor: '#1e3c72',
                    });
                    return;
                }

                await Swal.fire({
                    icon: 'error',
                    title: 'Submission failed',
                    text: 'Please try again.',
                    background: 'rgba(14, 20, 38, 0.96)',
                    color: '#ffffff',
                    confirmButtonColor: '#1e3c72',
                });
            } finally {
                this.submitting = false;
            }
        }
    },
    mounted() {
        const isCohabiting = sessionStorage.getItem("isCohabiting");

        if(isCohabiting){
            this.isCohabitingMessage();
            sessionStorage.removeItem("isCohabiting")
        }
    },
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

.text-shadow-medium {
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
}

.glass-card {
    background: rgba(255, 255, 255, 0.1) !important;
    backdrop-filter: blur(20px) saturate(160%);
    -webkit-backdrop-filter: blur(20px) saturate(160%);
    border-radius: 24px;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
}

.card-header {
    background: rgba(30, 60, 114, 0.5) !important;
    border-radius: 24px 24px 0 0 !important;
}

.glass-panel {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 16px;
}

.custom-input {
    background: rgba(255, 255, 255, 0.08) !important;
    border: 1px solid rgba(255, 255, 255, 0.16) !important;
    border-radius: 12px;
    padding: 12px 16px;
    transition: all 0.3s ease;
    color: rgba(255, 255, 255, 0.92);
}

.custom-input:focus {
    background: rgba(255, 255, 255, 0.12) !important;
    box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.25);
    border-color: rgba(13, 110, 253, 0.55) !important;
    outline: none;
}

.custom-input::placeholder {
    color: rgba(255, 255, 255, 0.55);
}

select.custom-input option {
    color: #111;
}

input[type='date'].custom-input {
    color-scheme: dark;
}

.form-label {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 8px;
}

.input-group-text {
    border-radius: 12px 0 0 12px;
    color: rgba(255, 255, 255, 0.85);
    font-weight: 600;
}

.input-group-glass {
    background: rgba(255, 255, 255, 0.08) !important;
    border: 1px solid rgba(255, 255, 255, 0.16) !important;
    border-right: 0 !important;
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
    color: rgba(255, 255, 255, 0.75);
}

.btn-primary {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    border: none;
    transition: all 0.3s ease;
}

.glow-button {
    box-shadow: 0 0 20px rgba(13, 110, 253, 0.35);
    transition: all 0.3s ease;
}

.glow-button:hover {
    box-shadow: 0 0 30px rgba(13, 110, 253, 0.55);
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .glass-card {
        padding: 1.5rem !important;
        border-radius: 20px;
    }
}

.back-link {
    transition: all 0.2s ease;
}

.back-link:hover {
    opacity: 1 !important;
    transform: translateX(-5px);
}
</style>
