<template>
    <div class="add-position-container animate__animated animate__fadeIn">
        <router-link to="/Admin/Positions" class="btn btn-link text-white opacity-75 text-decoration-none mb-4 p-0">
            <i class="bi bi-arrow-left me-2"></i> Back to Position List
        </router-link>

        <div class="card glass-card border-0 shadow-lg p-4 p-md-5">
            <div class="mb-4">
                <h3 class="text-white fw-bold mb-1">Add New Position</h3>
                <p class="text-white opacity-50 small">Define a new organizational role and its category for the LCRO
                    system.</p>
            </div>

            <form @submit.prevent="handleSubmit">
                <div class="row g-4">
                    <div class="col-md-12">
                        <label class="form-label text-white small fw-bold opacity-75">Position Title</label>
                        <input v-model="form.title" type="text" class="form-control glass-input"
                            :class="{ 'border-danger': errors.title }" placeholder="e.g. Senior Registrar" required>
                        <small v-if="errors.title" class="text-danger d-block mt-1">{{ errors.title }}</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold opacity-75">Category</label>
                        <select v-model="form.category" class="form-select glass-input" required>
                            <option value="" disabled>Select Category</option>
                            <option value="Administrative">Administrative</option>
                            <option value="Events">Events</option>
                            <option value="Documentation">Documentation</option>
                            <option value="Legal">Legal</option>
                            <option value="Technical">Technical</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold opacity-75">Position Code</label>
                        <input v-model="form.code" type="text" class="form-control glass-input"
                            :class="{ 'border-danger': errors.code }" placeholder="e.g. POS-001" required>
                        <small v-if="errors.code" class="text-danger d-block mt-1">{{ errors.code }}</small>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label text-white small fw-bold opacity-75">Description</label>
                        <textarea v-model="form.description" class="form-control glass-input" rows="3"
                            placeholder="Briefly describe the responsibilities of this role..."></textarea>
                    </div>

                    <div class="col-12 mt-5">
                        <div class="d-flex gap-3">
                            <button type="submit"
                                class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm border-0 transition-all">
                                Save Position
                            </button>
                            <router-link to="/Admin/Position" class="btn btn-action-glass text-white px-4 rounded-pill">
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
    name: 'AddPosition',
    data() {
        return {
            form: {
                title: '',
                category: '',
                code: '',
                description: ''
            },
            errors: {
                title: '',
                code: ''
            }
        };
    },
    watch: {
        // Validation: Title should be letters, spaces, and hyphens/dashes only
        'form.title'(newValue) {
            const sanitized = newValue.replace(/[^a-zA-Z\s-]/g, '');
            if (newValue !== sanitized) {
                this.errors.title = "Titles can only contain letters, spaces, and hyphens.";
                this.form.title = sanitized;
            } else {
                this.errors.title = "";
            }
        },
        // Validation: Code should be alphanumeric, no spaces
        'form.code'(newValue) {
            const sanitized = newValue.replace(/[^a-zA-Z0-9-]/g, '').toUpperCase();
            if (newValue !== sanitized) {
                this.errors.code = "Code must be alphanumeric (e.g., ADM-01).";
                this.form.code = sanitized;
            } else {
                this.errors.code = "";
            }
        }
    },
    methods: {
        handleSubmit() {
            if (this.errors.title || this.errors.code) {
                alert("Please correct the validation errors.");
                return;
            }

            console.log("New Position Saved:", this.form);
            alert("Position created successfully!");
            this.$router.push('/Admin/Positions');
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

textarea.glass-input {
    resize: none;
}

/* Select dropdown fix */
select.glass-input option {
    background: #1e293b;
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

.border-danger {
    border-color: #ff4d4d !important;
}
</style>