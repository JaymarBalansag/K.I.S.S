<template>
    <div class="add-staff-container animate__animated animate__fadeIn">
        <router-link to="/Admin/Staffs" class="btn btn-link text-white opacity-75 text-decoration-none mb-4 p-0">
            <i class="bi bi-arrow-left me-2"></i> Back to Staff List
        </router-link>

        <div class="card glass-card border-0 shadow-lg p-4 p-md-5">
            <div class="mb-4">
                <h3 class="text-white fw-bold mb-1">Add New Staff</h3>
                <p class="text-white opacity-50 small">Fill in the details to register a new member to the LCRO system.
                </p>
            </div>

            <form @submit.prevent="handleSubmit">
                <div class="row g-4">
                    <div class="col-md-12">
                        <label class="form-label text-white small fw-bold opacity-75">Full Name</label>
                        <input v-model="form.name" type="text" class="form-control glass-input"
                            placeholder="e.g. Maria Clara" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold opacity-75">Position</label>
                        <select v-model="form.role" class="form-select glass-input" required>
                            <option value="" disabled>Select Position</option>
                            <option value="Head Registrar">Head Registrar</option>
                            <option value="Wedding Coordinator">Wedding Coordinator</option>
                            <option value="Records Officer">Records Officer</option>
                            <option value="Clerk II">Clerk II</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold opacity-75">Email Address</label>
                        <input v-model="form.email" type="email" class="form-control glass-input"
                            placeholder="name@lcro.gov" required>
                    </div>

                    <div class="col-12 mt-5">
                        <div class="d-flex gap-3">
                            <button type="submit"
                                class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm border-0 transition-all">
                                Save Staff Member
                            </button>
                            <router-link to="/Admin/Staffs" class="btn btn-action-glass text-white px-4 rounded-pill">
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
    name: 'AddStaff',
    data() {
        return {
            form: {
                name: '',
                role: '',
                email: ''
            }
        };
    },
    methods: {
        handleSubmit() {
            console.log("New Staff Data:", this.form);
            // Logic to save data to your API/Store goes here
            alert("Staff added successfully!");
            this.$router.push('/Admin/Staffs');
        }
    }
};
</script>

<style scoped>
/* GLASS CARD CONTAINER */
.glass-card {
    background: rgba(255, 255, 255, 0.05) !important;
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    border-radius: 25px;
}

/* GLASS INPUT FIELDS */
.glass-input {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    color: white !important;
    padding: 0.8rem 1.2rem;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.glass-input:focus {
    background: rgba(255, 255, 255, 0.1) !important;
    border-color: rgba(13, 202, 240, 0.5) !important;
    box-shadow: 0 0 15px rgba(13, 202, 240, 0.2);
    outline: none;
}

.glass-input::placeholder {
    color: rgba(255, 255, 255, 0.3);
}

/* Select dropdown fix */
select.glass-input option {
    background: #1e293b;
    /* Dark background for dropdown options */
    color: white;
}

/* BUTTONS */
.btn-action-glass {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.2s ease;
}

.btn-action-glass:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.3);
}

.transition-all {
    transition: all 0.3s ease;
}
</style>