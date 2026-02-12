<template>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top nav-glass shadow-sm" style="z-index: 2000;">
        <div class="container">

            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="/public/favicon.ico" class="me-2" alt="logo" style="width: 3rem;">
                <div class="brand-text text-start">
                    <span class="fw-bold d-block lh-1 text-white fs-5">{{ config.siteName }}</span>
                    <small class="text-white-50 d-none d-sm-block"
                        style="font-size: 0.75rem; letter-spacing: 0.5px;">Marriage Services</small>
                </div>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li v-for="link in navLinks" :key="link.text" class="nav-item">
                        <RouterLink class="nav-link fw-bold px-3 text-uppercase small text-center" :to="link.url">
                            {{ link.text }}
                        </RouterLink>
                    </li>
                </ul>

                <div class="d-flex justify-content-center mt-3 mt-lg-0">
                    <router-link v-if="role === 'admin'" to="/Admin/Dashboard"
                        class="btn btn-primary btn-sm rounded-pill px-4 fw-bold text-uppercase tracking-wider shadow-sm">
                        Admin Dashboard
                    </router-link>
                    <router-link v-if="role === 'staff'" to="/Staff/Dashboard"
                        class="btn btn-primary btn-sm rounded-pill px-4 fw-bold text-uppercase tracking-wider shadow-sm">
                        Staff Dashboard
                    </router-link>
                    <a v-if="role === 'guest'" href="/api/download"
                        class="btn btn-primary btn-sm rounded-pill px-4 fw-bold text-uppercase tracking-wider shadow-sm">
                        Download License
                    </a>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import api from '../controller/api';

export default {
    data() {
        return {
            role: "guest",
            config: {
                siteName: "LCRO Abuyog"
            },
            navLinks: [
                { text: 'Services', url: '/home' },
                { text: 'About Us', url: '/about' },
                { text: 'Contact', url: '/contact' },
                { text: 'FAQ', url: '/faq' }
            ],
        };
    },
    mounted() {
        const userInfoString = localStorage.getItem("userInfo");
        const user = userInfoString ? JSON.parse(userInfoString) : null;

        const role = user ? user.role : "guest";
        if (role) {
            this.role = role;
        }

    },
    methods: {
        async login() {
            await api.post("/login");
            this.$router.push("/login");
        }
    }
}
</script>

<style scoped>
.nav-glass {
    background-color: rgba(33, 37, 41, 0.92) !important;
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.nav-link {
    color: #ffffff !important;
    transition: all 0.2s ease;
}

.nav-link:hover {
    color: #0d6efd !important;
    opacity: 1;
}

.tracking-wider {
    letter-spacing: 1px;
    font-size: 0.8rem;
}

/* Mobile Adjustments */
@media (max-width: 991px) {
    .navbar-collapse {
        background: #212529 !important;
        padding: 1.5rem;
        border-radius: 12px;
        margin-top: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .nav-link {
        padding: 12px 0 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
}
</style>
