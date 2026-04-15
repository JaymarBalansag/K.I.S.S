<template>
    <div class="staff-container min-vh-100 d-flex overflow-hidden" :class="{ 'mobile-open': mobileShow }">

        <div v-if="mobileShow" class="sidebar-backdrop d-lg-none position-fixed top-0 start-0 w-100 h-100"
            style="background: rgba(0,0,0,0.5); z-index: 1040; backdrop-filter: blur(4px);" @click="mobileShow = false">
        </div>

        <aside class="sidebar glass-effect vh-100 position-fixed top-0 d-flex flex-column shadow-lg transition-all"
            :class="[
                mobileShow ? 'd-flex translate-middle-x-0' : 'd-none d-lg-flex',
                isCollapsed ? 'sidebar-narrow' : 'sidebar-wide'
            ]" style="z-index: 1050; left: 0;">

            <div
                class="sidebar-header p-4 border-bottom border-white border-opacity-10 d-flex justify-content-between align-items-center">
                <h5 v-if="!isCollapsed || mobileShow"
                    class="text-white fw-bold mb-0 text-nowrap animate__animated animate__fadeIn">Staff Dashboard</h5>
                <button @click="isCollapsed = !isCollapsed"
                    class="btn btn-sm btn-link text-white opacity-75 d-none d-lg-block p-0">
                    <i class="bi fs-4" :class="isCollapsed ? 'bi-text-indent-left' : 'bi-text-indent-right'"></i>
                </button>
                <button @click="mobileShow = false" class="btn btn-sm btn-link text-white d-lg-none">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div v-if="isAdmin && (!isCollapsed || mobileShow)" class="px-3 pt-3">
                <div class="mode-switch glass-toggle rounded-pill p-1 d-flex position-relative">
                    <div class="mode-switch-indicator rounded-pill" :class="{ 'staff-active': !isAdminView }"></div>
                    <button
                        class="btn btn-sm flex-fill rounded-pill fw-semibold mode-switch-btn"
                        :class="{ active: isAdminView }"
                        @click="switchToAdminView"
                    >
                        Admin View
                    </button>
                    <button
                        class="btn btn-sm flex-fill rounded-pill fw-semibold mode-switch-btn"
                        :class="{ active: !isAdminView }"
                        @click="switchToStaffView"
                    >
                        Staff View
                    </button>
                </div>
            </div>

            <nav class="nav flex-column p-3 gap-2 flex-grow-1">
                <router-link to="/Staff/Dashboard" @click="closeMobile"
                    class="nav-link glass-nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center transition">
                    <i class="bi bi-grid-1x2-fill fs-5"></i>
                    <span v-if="!isCollapsed || mobileShow"
                        class="ms-3 animate__animated animate__fadeIn text-nowrap">Dashboard</span>
                </router-link>

                <router-link to="/Staff/Appointments" @click="closeMobile"
                    class="nav-link glass-nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center transition">
                    <i class="bi bi-calendar-check fs-5"></i>
                    <span v-if="!isCollapsed || mobileShow"
                        class="ms-3 animate__animated animate__fadeIn text-nowrap">Appointments</span>
                </router-link>

                <router-link to="/Staff/Applications" @click="closeMobile"
                    class="nav-link glass-nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center transition">
                    <i class="bi bi-file-earmark-text fs-5"></i>
                    <span v-if="!isCollapsed || mobileShow"
                        class="ms-3 animate__animated animate__fadeIn text-nowrap">Applications</span>
                </router-link>

                <router-link to="/Staff/WalkInApplications" @click="closeMobile"
                    class="nav-link glass-nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center transition">
                    <i class="bi bi-pencil-square fs-5"></i>
                    <span v-if="!isCollapsed || mobileShow"
                        class="ms-3 animate__animated animate__fadeIn text-nowrap">Walk-In Applications</span>
                </router-link>
            </nav>
        </aside>

        <div class="main-wrapper flex-grow-1 d-flex flex-column min-vh-100 transition-all"
            :style="{ marginLeft: isMobile ? '0' : (isCollapsed ? '80px' : '260px') }">

            <nav class="navbar glass-effect border-bottom border-white border-opacity-10 sticky-top py-2">
                <div class="container-fluid d-flex align-items-center justify-content-between px-2 px-md-4">

                    <div class="d-flex align-items-center flex-shrink-1 overflow-hidden">
                        <button class="btn btn-link text-white d-lg-none p-1 me-2" @click="mobileShow = true">
                            <i class="bi bi-list fs-2"></i>
                        </button>
                        <span class="navbar-text fw-bold text-white opacity-75 small text-uppercase ls-1 text-truncate">
                            {{ $route.name ? $route.name.replace(/([A-Z])/g, ' $1').trim() : 'Staff Portal' }}
                        </span>
                    </div>

                    <div class="dropdown">
                        <button
                            class="btn d-flex align-items-center text-white glass-pill rounded-pill py-1 px-2 px-sm-3 border-0"
                            type="button" data-bs-toggle="dropdown">
                            <div class="user-avatar bg-primary text-white rounded-circle shadow-sm d-flex justify-content-center align-items-center me-sm-2"
                                style="width: 30px; height: 30px; min-width: 30px; font-size: 0.8rem;">
                                S
                            </div>
                            <span class="small fw-bold d-none d-sm-inline">Staff</span>
                        </button>

                        <ul
                            class="dropdown-menu dropdown-menu-end glass-dropdown shadow-lg border-white border-opacity-10 mt-2 p-2 animate__animated animate__fadeInUp animate__faster">
                            <li>
                                <router-link class="dropdown-item rounded-2 text-white py-2" to="/home">
                                    <i class="bi bi-person me-2"></i> Home
                                </router-link>
                            </li>
                            
                            <li>
                                <hr class="dropdown-divider border-white border-opacity-10">
                            </li>
                            <li><button class="dropdown-item rounded-2 text-danger py-2 fw-bold"
                                    @click="handleLogout"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="content-area flex-grow-1 p-3 p-md-4">
                <router-view />
            </main>
        </div>
    </div>
</template>

<script>
import { logout } from '../../controller/Authentication';
export default {
    name: 'StaffLayout',
    data() {
        return {
            isCollapsed: false,
            mobileShow: false,
            isMobile: false,
            role: 'guest'
        };
    },
    computed: {
        isAdmin() {
            return this.role === 'admin';
        },
        isAdminView() {
            return this.$route.path.startsWith('/Admin');
        }
    },
    mounted() {
        this.checkScreen();
        window.addEventListener('resize', this.checkScreen);
        const userInfoString = localStorage.getItem("userInfo");
        const user = userInfoString ? JSON.parse(userInfoString) : null;
        this.role = user?.role || 'guest';
    },
    beforeUnmount() {
        window.removeEventListener('resize', this.checkScreen);
    },
    methods: {
        checkScreen() {
            this.isMobile = window.innerWidth < 992;
            if (!this.isMobile) this.mobileShow = false;
        },
        closeMobile() {
            if (this.isMobile) this.mobileShow = false;
        },
        switchToAdminView() {
            this.$router.push('/Admin/Dashboard');
        },
        switchToStaffView() {
            if (this.$route.path.startsWith('/Staff')) return;
            this.$router.push('/Staff/Dashboard');
        },
        async handleLogout() {
            const result = await Swal.fire({
                title: 'Logout from LCRO Staff?',
                text: "You will need to login again to access your dashboard.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout!'
            });

            if (result.isConfirmed) {
                try {
                const res = await logout();
                
                // Optional: Show a quick success message before redirecting
                await Swal.fire({
                    title: 'Logged Out!',
                    text: 'You have been successfully logged out.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });

                this.$router.push('/staff-portal');
                } catch (error) {
                Swal.fire('Error', 'Something went wrong during logout.', 'error');
                }
            }
        },
    }
};
</script>

<style scoped>
/* BACKGROUND - MATCHES ADMIN */
.staff-container {
    background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.9)),
        url('/background.jpg') no-repeat center center fixed;
    background-size: cover;
    overflow-x: hidden;
    overflow-y: visible;
}

/* GLASS EFFECT */
.glass-effect {
    background: rgba(255, 255, 255, 0.05) !important;
    backdrop-filter: blur(15px) saturate(160%);
    -webkit-backdrop-filter: blur(15px) saturate(160%);
}

.glass-dropdown {
    background: rgba(15, 23, 42, 0.98) !important;
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.dropdown-item:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white !important;
    transform: translateX(4px);
}

/* SIDEBAR DIMENSIONS */
.sidebar-wide {
    width: 260px;
}

.sidebar-narrow {
    width: 80px;
}

.glass-nav-link {
    opacity: 0.7;
    transition: all 0.3s ease;
}

.glass-nav-link:hover,
.router-link-active {
    opacity: 1;
    background: rgba(13, 110, 253, 0.2) !important;
    border: 1px solid rgba(13, 110, 253, 0.3);
}

.glass-toggle {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
    overflow: hidden;
}

.mode-switch {
    min-height: 44px;
}

.mode-switch-indicator {
    position: absolute;
    top: 4px;
    left: 4px;
    width: calc(50% - 4px);
    height: calc(100% - 8px);
    background: linear-gradient(135deg, #67e8f9 0%, #22d3ee 100%);
    box-shadow: 0 8px 18px rgba(34, 211, 238, 0.28);
    transition: transform 0.25s ease;
}

.mode-switch-indicator.staff-active {
    transform: translateX(100%);
}

.mode-switch-btn {
    position: relative;
    z-index: 1;
    border: 0 !important;
    background: transparent !important;
    color: rgba(255, 255, 255, 0.72) !important;
    transition: color 0.2s ease;
}

.mode-switch-btn.active {
    color: #062630 !important;
}

.mode-switch-btn:not(.active):hover {
    color: #ffffff !important;
}

.glass-pill {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.ls-1 {
    letter-spacing: 1px;
}

.transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* MOBILE OVERRIDES */
@media (max-width: 991.98px) {
    .sidebar {
        width: 260px !important;
        transform: translateX(-100%);
        display: flex !important;
    }

    .mobile-open .sidebar {
        transform: translateX(0);
    }
}
</style>
