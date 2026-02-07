<template>
    <div class="staff-form-container animate__animated animate__fadeIn px-2 px-md-3">
        <router-link to="/Admin/Staffs" class="btn btn-link text-white opacity-75 text-decoration-none mb-4 p-0">
            <i class="bi bi-arrow-left me-2"></i> Back to Staff List
        </router-link>

        <div class="card glass-card border-0 shadow-lg p-4 p-md-5" v-if="!loading">
            <div class="mb-4">
                <h3 class="text-white fw-bold mb-1">Edit Staff Profile</h3>
                <p class="text-white opacity-50 small">Updating: <strong>{{ form.first_name }} {{ form.last_name
                        }}</strong></p>
            </div>

            <form @submit.prevent="handleUpdate">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold opacity-75">First Name</label>
                        <input v-model="form.first_name" type="text" class="form-control glass-input" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold opacity-75">Last Name</label>
                        <input v-model="form.last_name" type="text" class="form-control glass-input" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold opacity-75">Position</label>
                        <select v-model="form.role" class="form-select glass-input" required>
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold opacity-75">Email Address</label>
                        <input v-model="form.email" type="email" class="form-control glass-input" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-white small fw-bold opacity-75">New Password (leave blank to keep
                            current)</label>
                        <input v-model="form.password" type="password" class="form-control glass-input">
                    </div>
                    <div class="col-12 mt-5">
                        <div class="d-flex gap-3">
                            <button type="submit" :disabled="submitting"
                                class="btn btn-info rounded-pill px-5 fw-bold shadow-sm border-0">
                                <span v-if="submitting" class="spinner-border spinner-border-sm me-2"></span>
                                Update Changes
                            </button>
                            <router-link to="/Admin/Staffs"
                                class="btn btn-action-glass text-white px-4 rounded-pill">Cancel</router-link>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div v-else class="text-center py-5">
            <div class="spinner-border text-info"></div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import { UpdateStaff, ShowStaff } from '../../controller/Staffs';

export default {
    data() {
        return {
            form: { first_name: '', last_name: '', role: '', email: '', password: '' },
            loading: true,
            submitting: false
        };
    },
    created() { 
        this.fetchStaff(); 
    },
    methods: {
        async fetchStaff() {
            try {
                const response = await ShowStaff(this.$route.params.id);
                this.form = { ...response.data, password: '' };
            } catch (e) { this.$router.push('/Admin/Staffs'); }
            finally { this.loading = false; }
        },
        async handleUpdate() {
            this.submitting = true;
            try {
                await UpdateStaff(this.$route.params.id, this.form);
                Swal.fire({ icon: 'success', title: 'Updated', background: '#1a1d21', color: '#fff' });
                this.$router.push('/Admin/Staffs');
            } catch (e) { Swal.fire('Error', 'Update failed', 'error'); }
            finally { this.submitting = false; }
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
}

.btn-action-glass {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
}
</style>