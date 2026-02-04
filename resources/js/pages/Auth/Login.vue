<template>
    <div class="main-container min-vh-100 d-flex flex-column">
        <Navbar></Navbar>
        <main class="flex-grow-1 d-flex align-items-center py-5">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-5 col-xl-4">

                        <div class="text-center mb-4">
                            <h2 class="text-white fw-bold text-shadow-heavy">Access Portal</h2>
                            <p class="text-white-50 small">Please sign in to manage your applications</p>
                        </div>

                        <div class="glass-card shadow-lg p-4 p-md-5 border border-white border-opacity-20">

                            <form @submit.prevent="handleLogin">

                                <div class="mb-3 animate-fade">
                                    <label
                                        class="form-label small fw-bold text-white text-uppercase tracking-wider opacity-75">
                                        Username
                                    </label>
                                    <div class="input-group glass-input-group">
                                        <span
                                            class="input-group-text bg-transparent border-white border-opacity-25 text-white">
                                            <i class="bi bi-person"></i>
                                        </span>
                                        <input type="text" v-model="username"
                                            class="form-control glass-input border-start-0 ps-0"
                                            placeholder="admin_user" required>
                                    </div>
                                </div>

                                <div class="mb-4 animate-fade" style="animation-delay: 0.1s;">
                                    <label
                                        class="form-label small fw-bold text-white text-uppercase tracking-wider opacity-75">
                                        Password
                                    </label>
                                    <div class="input-group glass-input-group">
                                        <span
                                            class="input-group-text bg-transparent border-white border-opacity-25 text-white">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                        <input type="password" v-model="password"
                                            class="form-control glass-input border-start-0 ps-0" placeholder="••••••••"
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
        <Footer></Footer>
    </div>
</template>

<script>
import Navbar from '../../components/navbar.vue';
import Footer from '../../components/footer.vue';
export default {
    components: {
        Navbar,
        Footer
    },
    name: 'GovPortalLogin',
    data() {
        return {
            username: '',
            password: ''
        };
    },
    methods: {
        handleLogin() {
            // Logic for authentication
            console.log("Attempting login for:", this.username);
            // Example: axios.post('/api/login', { user: this.username, pass: this.password })
        }
    }
};
</script>

<style scoped>
/* BACKGROUND CONTAINER */
.main-container {
    font-family: 'Inter', sans-serif;
    /* Ensure background.jpg exists in your public folder */
    background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.7)),
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

/* ANIMATION */
.animate-fade {
    animation: slideIn 0.4s ease-out forwards;
    opacity: 0;
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
