<template>
    <div class="staff-form-container animate__animated animate__fadeIn">
        <router-link to="/Admin/Staffs" class="btn btn-link text-white opacity-75 text-decoration-none mb-4 p-0">
            <i class="bi bi-arrow-left me-2"></i> Back to Staff List
        </router-link>

        <div class="card glass-card border-0 shadow-lg p-4 p-md-5">
            <div class="mb-4">
                <h3 class="text-white fw-bold mb-1">Edit Staff Profile</h3>
                <p class="text-white opacity-50 small">Updating information for: <strong>{{ form.name }}</strong></p>
            </div>

            <form @submit.prevent="handleUpdate">
                <div class="row g-4">
                    <div class="col-md-12">
                        <label class="form-label text-white small fw-bold opacity-75">Full Name</label>
                        <input v-model="form.name" type="text" class="form-control glass-input" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold opacity-75">Position</label>
                        <select v-model="form.role" class="form-select glass-input" required>
                            <option value="Head Registrar">Head Registrar</option>
                            <option value="Wedding Coordinator">Wedding Coordinator</option>
                            <option value="Records Officer">Records Officer</option>
                            <option value="Clerk II">Clerk II</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold opacity-75">Email Address</label>
                        <input v-model="form.email" type="email" class="form-control glass-input" required>
                    </div>

                    <div class="col-12 mt-5">
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-info rounded-pill px-5 fw-bold shadow-sm border-0">
                                Update Changes
                            </button>
                            <router-link to="/Admin/Staffs"
                                class="btn btn-action-glass text-white px-4 rounded-pill text-decoration-none">
                                Cancel
                            </router-link>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: 'EditStaff',
    data() {
        return {
            form: { id: null, name: '', role: '', email: '' }
        };
    },
    created() {
        this.fetchStaff();
    },
    methods: {
        fetchStaff() {
            const staffId = this.$route.params.id;
            // In a real app, fetch from your database/store using staffId
            // Mock fetch:
            const mockData = { id: 1, name: "Maria Clara", role: "Head Registrar", email: "m.clara@lcro.gov" };
            this.form = { ...mockData };
        },
        handleUpdate() {
            console.log("Updating ID:", this.form.id, this.form);
            alert("Changes Saved!");
            this.$router.push('/Admin/Staffs');
        }
    }
};
</script>

<style scoped>
/* Same CSS as AddStaff.vue to keep glassmorphism consistent */
.glass-card {
    background: rgba(255, 255, 255, 0.05) !important;
    backdrop-filter: blur(25px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    border-radius: 25px;
}

.glass-input {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    color: white !important;
    border-radius: 12px;
    padding: 0.8rem 1.2rem;
}

.btn-action-glass {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
}
</style>