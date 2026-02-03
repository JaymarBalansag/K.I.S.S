<template>
    <div class="dashboard-content">
        <div class="mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-4 py-2 mb-3 fw-bold text-uppercase"
                style="letter-spacing: 1px;">
                Overview
            </span>
            <h1 class="fw-bolder text-dark mb-2">Staff Dashboard</h1>
            <p class="text-muted">Manage your daily tasks and monitor marriage service applications.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stats-icon bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">
                                <i class="bi bi-calendar-heart fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 fw-bold text-uppercase small">Wedding Appointments</h6>
                                <h2 class="fw-black mb-0">24</h2>
                            </div>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: 70%"></div>
                        </div>
                        <small class="text-muted d-block mt-2">8 scheduled for today</small>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stats-icon bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3">
                                <i class="bi bi-file-earmark-text fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 fw-bold text-uppercase small">License Applications</h6>
                                <h2 class="fw-black mb-0">142</h2>
                            </div>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: 45%"></div>
                        </div>
                        <small class="text-muted d-block mt-2">12 pending review</small>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stats-icon bg-success bg-opacity-10 text-success rounded-3 p-3 me-3">
                                <i class="bi bi-people fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 fw-bold text-uppercase small">Total Users Served</h6>
                                <h2 class="fw-black mb-0">1,205</h2>
                            </div>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                        <small class="text-success d-block mt-2"><i class="bi bi-arrow-up"></i> 5% increase this
                            month</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.fw-black {
    font-weight: 800;
}

.transition-hover {
    transition: transform 0.3s ease, shadow 0.3s ease;
}

.transition-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
}

.stats-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
}
</style>