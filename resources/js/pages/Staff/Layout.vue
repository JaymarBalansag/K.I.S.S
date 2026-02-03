<template>
    <div class="staff-container" :class="{ 'sidebar-collapsed': isCollapsed, 'mobile-open': mobileShow }">

        <div v-if="mobileShow" class="sidebar-backdrop d-lg-none" @click="mobileShow = false"></div>

        <aside class="sidebar bg-dark vh-100 position-fixed d-flex flex-column shadow transition-all">
            <div
                class="sidebar-header p-4 border-bottom border-secondary d-flex justify-content-between align-items-center">
                <h5 v-show="!isCollapsed || isMobile" class="text-white fw-bold mb-0 transition">LCRO Staff</h5>
                <button @click="toggleSidebar" class="btn btn-sm btn-outline-secondary border-0 text-white">
                    <i class="bi" :class="isCollapsed ? 'bi-list' : 'bi-chevron-left'"></i>
                </button>
            </div>

            <nav class="nav flex-column p-3 gap-2 flex-grow-1">
                <router-link to="/Staff/Dashboard" @click="closeMobile"
                    class="nav-link text-white-50 rounded px-3 py-2 transition shadow-hover">
                    <i class="bi bi-grid-1x2-fill me-2"></i>
                    <span v-show="!isCollapsed || isMobile">Dashboard</span>
                </router-link>
                <router-link to="/Staff/Appointments" @click="closeMobile"
                    class="nav-link text-white-50 rounded px-3 py-2 transition shadow-hover">
                    <i class="bi bi-calendar-check me-2"></i>
                    <span v-show="!isCollapsed || isMobile">Appointments</span>
                </router-link>
                <router-link to="/Staff/Applications" @click="closeMobile"
                    class="nav-link text-white-50 rounded px-3 py-2 transition shadow-hover">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    <span v-show="!isCollapsed || isMobile">Applications</span>
                </router-link>
                <router-link to="/Staff/User-Registry" @click="closeMobile"
                    class="nav-link text-white-50 rounded px-3 py-2 transition shadow-hover">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    <span v-show="!isCollapsed || isMobile">User Registry</span>
                </router-link>
            </nav>
        </aside>

        <div class="main-wrapper d-flex flex-column min-vh-100 transition-all">
            <nav class="navbar navbar-expand bg-white border-bottom px-4 py-2 sticky-top">
                <div class="container-fluid">
                    <button class="btn btn-light d-lg-none me-2" @click="mobileShow = true">
                        <i class="bi bi-list"></i>
                    </button>

                    <span class="navbar-text fw-medium text-dark">
                        {{ $route.name ? $route.name.replace(/([A-Z])/g, ' $1').trim() : 'Dashboard' }}
                    </span>

                    <ul class="navbar-nav ms-auto align-items-center rounded-pill">
                        <li class="nav-item dropdown p-2 rounded-3">
                            <a class="nav-link dropdown-toggle d-flex align-items-center text-dark fw-bold rounded-pill px-3"
                                href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar bg-primary text-white rounded-circle me-2">A</div>
                                <span class="d-none d-md-inline">Admin</span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 ">
                                <li>
                                    <router-link class="dropdown-item py-2 rounded-2" to="/Staff/Profile">
                                        <i class="bi bi-person me-2"></i> Profile
                                    </router-link>
                                </li>
                                <li>
                                    <router-link class="dropdown-item py-2 rounded-2" to="/Staff/Settings">
                                        <i class="bi bi-gear me-2"></i> Settings
                                    </router-link>
                                </li>
                                <li>
                                    <hr class="dropdown-divider mx-2">
                                </li>
                                <li>
                                    <button class="dropdown-item py-2 rounded-2 text-danger" @click="handleLogout">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content-area flex-grow-1 p-3 p-md-4 bg-light">
                <router-view />
            </main>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isCollapsed: false,
            mobileShow: false, // Controls mobile drawer
            isMobile: false
        };
    },
    mounted() {
        this.checkScreen();
        window.addEventListener('resize', this.checkScreen);
    },
    methods: {
        toggleSidebar() {
            if (window.innerWidth < 992) {
                this.mobileShow = !this.mobileShow;
            } else {
                this.isCollapsed = !this.isCollapsed;
            }
        },
        closeMobile() {
            if (window.innerWidth < 992) this.mobileShow = false;
        },
        checkScreen() {
            this.isMobile = window.innerWidth < 992;
        },
        handleLogout() {
            this.$router.push('/login');
        }
    }
};
</script>

<style scoped>
/* Desktop Bases */
.sidebar {
    width: 260px;
    z-index: 1050;
}

.main-wrapper {
    margin-left: 260px;
}

/* Desktop Collapse */
.sidebar-collapsed .sidebar {
    width: 80px;
}

.sidebar-collapsed .main-wrapper {
    margin-left: 80px;
}

/* Responsive Logic */
@media (max-width: 991.98px) {
    .sidebar {
        transform: translateX(-100%);
        /* Hidden by default */
        width: 260px !important;
        /* Force full width on mobile drawer */
    }

    .main-wrapper {
        margin-left: 0 !important;
        /* Content takes full width */
    }

    /* When mobileShow is true */
    .mobile-open .sidebar {
        transform: translateX(0);
    }

    .sidebar-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1040;
    }
}

.transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.user-avatar {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nav-link:hover {
    background-color: #0d6efd;
    color: #ffffff !important;
}

.nav-link:active {
    background-color: #0d6efd;
    color: #ffffff !important;
}
</style>