<template>
    <div class="position-form-container animate__animated animate__fadeIn">
        <router-link to="/Admin/Positions" class="btn btn-link text-white opacity-75 text-decoration-none mb-4 p-0">
            <i class="bi bi-arrow-left me-2"></i> Back to Position List
        </router-link>

        <div class="card glass-card border-0 shadow-lg p-4 p-md-5">
            <div class="mb-4">
                <h3 class="text-white fw-bold mb-1">Edit Position</h3>
                <p class="text-white opacity-50 small">Updating details for: <strong>{{ form.title }}</strong></p>
            </div>

            <form @submit.prevent="handleUpdate">
                <div class="row g-4">
                    <div class="col-md-12">
                        <label class="form-label text-white small fw-bold opacity-75">Position Title</label>
                        <input v-model="form.title" type="text" class="form-control glass-input" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold opacity-75">Category</label>
                        <select v-model="form.category" class="form-select glass-input" required>
                            <option value="Administrative">Administrative</option>
                            <option value="Events">Events</option>
                            <option value="Documentation">Documentation</option>
                            <option value="Legal">Legal</option>
                            <option value="Technical">Technical</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold opacity-75">Position Code</label>
                        <input v-model="form.code" type="text" class="form-control glass-input" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label text-white small fw-bold opacity-75">Description</label>
                        <textarea v-model="form.description" class="form-control glass-input" rows="3"></textarea>
                    </div>

                    <div class="col-12 mt-5">
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-info rounded-pill px-5 fw-bold shadow-sm border-0">
                                Save Changes
                            </button>
                            <router-link to="/Admin/Position"
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
    name: 'EditPosition',
    data() {
        return {
            form: {
                id: null,
                title: '',
                category: '',
                code: '',
                description: ''
            }
        };
    },
    created() {
        this.fetchPosition();
    },
    methods: {
        fetchPosition() {
            const positionId = this.$route.params.id;
            // Mock fetch - In production, call your API here
            const mockData = {
                id: positionId,
                title: "Head Registrar",
                category: "Administrative",
                code: "HEAD-REG",
                description: "Oversees all civil registration processes and staff management."
            };
            this.form = { ...mockData };
        },
        handleUpdate() {
            console.log("Updating Position ID:", this.form.id, this.form);
            alert("Position Updated Successfully!");
            this.$router.push('/Admin/Positions');
        }
    }
};
</script>

<style scoped>
/* Consistent Glassmorphism Styles */
.glass-card {
    background: rgba(255, 255, 255, 0.05) !important;
    backdrop-filter: blur(25px) saturate(180%);
    -webkit-backdrop-filter: blur(25px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    border-radius: 25px;
}

.glass-input {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    color: white !important;
    border-radius: 12px;
    padding: 0.8rem 1.2rem;
    transition: all 0.3s ease;
}

.glass-input:focus {
    background: rgba(255, 255, 255, 0.1) !important;
    border-color: rgba(13, 202, 240, 0.5) !important;
    box-shadow: 0 0 15px rgba(13, 202, 240, 0.2);
    outline: none;
}

/* Ensure dropdown options are readable */
select.glass-input option {
    background: #1e293b;
    color: white;
}

textarea.glass-input {
    resize: none;
}

.btn-action-glass {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-info {
    background-color: #0dcaf0 !important;
    color: #000 !important;
}

.btn-info:hover {
    filter: brightness(1.1);
    transform: translateY(-1px);
}
</style>