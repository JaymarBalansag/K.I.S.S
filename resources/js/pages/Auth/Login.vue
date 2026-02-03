<template>
    <div class="main-container min-vh-100 d-flex flex-column">
        <main class="flex-grow-1 d-flex align-items-center py-5">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-5 col-xl-4">

                        <div class="text-center mb-4">
                            <h2 class="text-white fw-bold text-shadow-heavy">Access Portal</h2>
                            <p class="text-white-50 small">Please sign in to manage your applications</p>
                        </div>

                        <div class="glass-card shadow-lg p-4 p-md-5 border border-white border-opacity-20">

                            <div
                                class="d-flex justify-content-center gap-2 mb-4 bg-white bg-opacity-10 p-1 rounded-pill border border-white border-opacity-10">
                                <button @click="loginMethod = 'code'"
                                    :class="['btn btn-sm rounded-pill px-3 transition-all border-0', loginMethod === 'code' ? 'bg-white text-dark shadow' : 'text-white opacity-75']">
                                    Access Code
                                </button>
                                <button @click="loginMethod = 'credentials'"
                                    :class="['btn btn-sm rounded-pill px-3 transition-all border-0', loginMethod === 'credentials' ? 'bg-white text-dark shadow' : 'text-white opacity-75']">
                                    Credentials
                                </button>
                            </div>

                            <form @submit.prevent="handleLogin">

                                <div v-if="loginMethod === 'code'" class="animate-fade">
                                    <div class="mb-4">
                                        <label
                                            class="form-label small fw-bold text-white text-uppercase tracking-wider opacity-75">
                                            Reference Code
                                        </label>
                                        <div class="input-group glass-input-group">
                                            <span
                                                class="input-group-text bg-transparent border-white border-opacity-25 text-white">
                                                <i class="bi bi-hash"></i>
                                            </span>
                                            <input type="text" class="form-control glass-input border-start-0 ps-0"
                                                placeholder="e.g. ABC-12345" required>
                                        </div>
                                        <div class="form-text text-white-50 small mt-2">Enter the code sent to your
                                            email.</div>
                                    </div>
                                </div>

                                <div v-else class="animate-fade">
                                    <div class="mb-3">
                                        <label
                                            class="form-label small fw-bold text-white text-uppercase tracking-wider opacity-75">
                                            Username
                                        </label>
                                        <input type="text" class="form-control glass-input" placeholder="admin_user"
                                            required>
                                    </div>
                                    <div class="mb-4">
                                        <label
                                            class="form-label small fw-bold text-white text-uppercase tracking-wider opacity-75">
                                            Password
                                        </label>
                                        <input type="password" class="form-control glass-input" placeholder="••••••••"
                                            required>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="btn btn-white-glass w-100 py-3 fw-bold rounded-3 mt-2 shadow">
                                    Sign In <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </form>

                            <div class="text-center mt-4">
                                <a href="#" class="text-decoration-none small text-white-50 hover-white">
                                    Forgot details? Contact LCRO Support
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
export default {
    name: 'GovPortalLogin',
    data() {
        return {
            loginMethod: 'code', // Switch between 'code' and 'credentials'
        };
    },
    methods: {
        handleLogin() {
            // Add your authentication logic here
            console.log("Login Method:", this.loginMethod);
            // Example: this.$router.push('/Staff/Dashboard');
        }
    }
};
</script>

<style scoped>
/* BACKGROUND CONTAINER */
.main-container {
    font-family: 'Inter', sans-serif;
    /* Darker gradient overlays help the white "glass" stand out */
    background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.6)),
        url('../../../../public/background.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

/* THE GLASS CARD */
.glass-card {
    background: rgba(255, 255, 255, 0.1) !important;
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    border-radius: 28px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

/* GLASS INPUTS */
.glass-input {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    color: white !important;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.glass-input::placeholder {
    color: rgba(255, 255, 255, 0.4);
}

.glass-input:focus {
    background: rgba(255, 255, 255, 0.12) !important;
    border-color: rgba(255, 255, 255, 0.4) !important;
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.08);
    outline: none;
}

/* ICON GROUP FIX */
.glass-input-group .input-group-text {
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-right: none;
}

/* PRIMARY GLASS BUTTON */
.btn-white-glass {
    background: rgba(255, 255, 255, 0.9);
    color: #1a1a1a;
    border: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-white-glass:hover {
    background: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

/* UTILITIES */
.text-shadow-heavy {
    text-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
}

.tracking-wider {
    letter-spacing: 1.2px;
}

.hover-white:hover {
    color: white !important;
}

.transition-all {
    transition: all 0.2s ease;
}

/* ANIMATION */
.animate-fade {
    animation: slideIn 0.4s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>