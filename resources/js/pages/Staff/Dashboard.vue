<template>
    <div class="dashboard-content animate__animated animate__fadeIn">
        <div class="mb-5 text-white">
            <span class="badge glass-pill text-primary rounded-pill px-4 py-2 mb-3 fw-bold text-uppercase"
                style="letter-spacing: 1px;">
                Overview
            </span>
            <h1 class="fw-bolder mb-2 text-shadow-heavy">Staff Dashboard</h1>
            <p class="opacity-75 text-shadow-medium">Manage your daily tasks and monitor marriage service applications.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-lg glass-card h-100 transition-hover">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stats-icon bg-primary bg-opacity-20 text-primary rounded-3 p-3 me-3 d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px; border: 1px solid rgba(13, 110, 253, 0.2);">
                                <i class="bi bi-calendar-heart-fill fs-3"></i>
                            </div>
                            <div>
                                <h6 class="opacity-50 mb-0 fw-bold text-uppercase small ls-1">Wedding Appointments</h6>
                                <h2 class="fw-bold mb-0">24</h2>
                            </div>
                        </div>
                        <div class="progress bg-white bg-opacity-10" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: 70%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="opacity-50">8 scheduled for today</small>
                            <small class="text-primary fw-bold">70%</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-lg glass-card h-100 transition-hover">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stats-icon bg-warning bg-opacity-20 text-warning rounded-3 p-3 me-3 d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px; border: 1px solid rgba(255, 193, 7, 0.2);">
                                <i class="bi bi-file-earmark-text-fill fs-3"></i>
                            </div>
                            <div>
                                <h6 class="opacity-50 mb-0 fw-bold text-uppercase small ls-1">License Applications</h6>
                                <h2 class="fw-bold mb-0">142</h2>
                            </div>
                        </div>
                        <div class="progress bg-white bg-opacity-10" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: 45%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="opacity-50">12 pending review</small>
                            <small class="text-warning fw-bold">45%</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-lg glass-card h-100 transition-hover">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stats-icon bg-success bg-opacity-20 text-success rounded-3 p-3 me-3 d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px; border: 1px solid rgba(25, 135, 84, 0.2);">
                                <i class="bi bi-people-fill fs-3"></i>
                            </div>
                            <div>
                                <h6 class="opacity-50 mb-0 fw-bold text-uppercase small ls-1">Total Users Served</h6>
                                <h2 class="fw-bold mb-0">1,205</h2>
                            </div>
                        </div>
                        <div class="progress bg-white bg-opacity-10" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-success small"><i class="bi bi-arrow-up"></i> 5% increase</small>
                            <small class="text-success fw-bold">100%</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'StaffDashboard'
};
</script>

<style scoped>
/* GLASS CARD CORE */
.glass-card {
    background: rgba(255, 255, 255, 0.05) !important;
    backdrop-filter: blur(15px) saturate(150%);
    -webkit-backdrop-filter: blur(15px) saturate(150%);
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 24px;
}

/* PILL BADGE */
.glass-pill {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255, 255, 255, 0.15);
}

/* INTERACTIVE EFFECTS */
.transition-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.transition-hover:hover {
    transform: translateY(-8px);
    background: rgba(255, 255, 255, 0.1) !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3) !important;
    border-color: rgba(255, 255, 255, 0.3) !important;
}

/* TEXT STYLES */
.text-shadow-heavy {
    text-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
}

.text-shadow-medium {
    text-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
}

.ls-1 {
    letter-spacing: 1px;
}
</style>