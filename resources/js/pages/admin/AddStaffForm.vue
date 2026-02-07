<template>
    <div class="add-staff-container animate__animated animate__fadeIn px-2 px-md-3">
        <router-link to="/Admin/Staffs" class="btn btn-link text-white opacity-75 text-decoration-none mb-4 p-0">
            <i class="bi bi-arrow-left me-2"></i> Back to Staff List
        </router-link>

        <div class="card glass-card border-0 shadow-lg p-4 p-md-5">
            <div class="mb-4">
                <h3 class="text-white fw-bold mb-1">Add New Staff</h3>
                <p class="text-white opacity-50 small">Register a new member to the LCRO system.</p>
            </div>

            <form @submit.prevent="handleSubmit">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold opacity-75">First Name</label>
                        <input v-model="form.first_name" type="text" class="form-control glass-input"
                            placeholder="e.g. Maria" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold opacity-75">Last Name</label>
                        <input v-model="form.last_name" type="text" class="form-control glass-input"
                            placeholder="e.g. Clara" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold opacity-75">Position</label>
                        <select v-model="form.role" class="form-select glass-input" required>
                            <option value="" disabled>Select Position</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold opacity-75">Password</label>
                        <input v-model="form.password" type="password" class="form-control glass-input"
                            placeholder="Min 8 characters" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-white small fw-bold opacity-75">Email Address</label>
                        <input v-model="form.email" type="email" class="form-control glass-input"
                            placeholder="name@lcro.gov" required>
                    </div>
                    <div class="col-12 mt-5">
                        <div class="d-flex gap-3">
                            <button type="submit" :disabled="submitting"
                                class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm border-0">
                                <span v-if="submitting" class="spinner-border spinner-border-sm me-2"></span>
                                Save Staff Member
                            </button>
                            <router-link to="/Admin/Staffs"
                                class="btn btn-action-glass text-white px-4 rounded-pill">Cancel</router-link>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2';
import { CreateStaff } from '../../controller/Staffs';

export default {
    data() {
        return {
            form: { first_name: '', last_name: '', role: '', email: '', password: '' },
            submitting: false
        };
    },
    methods: {
        async handleSubmit() {
            this.submitting = true;
            try {
                await CreateStaff(this.form);
                Swal.fire({ icon: 'success', title: 'Success', text: 'Staff registered!', background: '#1a1d21', color: '#fff' });
                this.$router.push('/Admin/Staffs');
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Error', text: e.response?.data?.message || 'Check inputs', background: '#1a1d21', color: '#fff' });
            } finally { this.submitting = false; }
        }
    }
};
</script>

<style scoped>
.glass-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 20px;
}

.glass-input {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    color: white !important;
    border-radius: 10px;
    padding: 12px;
}

.glass-input:focus {
    border-color: #0d6efd !important;
    box-shadow: none;
}

.glass-input option {
    background: #212529;
    color: white;
}

.btn-action-glass {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
}
</style>
