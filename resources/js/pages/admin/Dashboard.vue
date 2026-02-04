<template>
    <div class="dashboard-content animate__animated animate__fadeIn">
        <div class="mb-5 text-white">
            <span class="badge glass-pill text-primary rounded-pill px-4 py-2 mb-3 fw-bold">OVERVIEW</span>
            <h1 class="fw-bolder mb-2 text-shadow">Admin Dashboard</h1>
            <p class="opacity-75">Manage your daily tasks and monitor marriage service applications.</p>
        </div>

        <div class="row g-4">
            <div v-for="stat in stats" :key="stat.label" class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-lg glass-card h-100 transition-hover">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center mb-3">
                            <div :class="`stats-icon bg-${stat.color} bg-opacity-20 text-${stat.color} rounded-3 p-3 me-3 d-flex align-items-center justify-content-center`"
                                style="width: 60px; height: 60px;">
                                <i :class="`bi ${stat.icon} fs-3`"></i>
                            </div>
                            <div>
                                <h6 class="opacity-50 mb-0 fw-bold text-uppercase small">{{ stat.label }}</h6>
                                <h2 class="fw-bold mb-0">{{ stat.value }}</h2>
                            </div>
                        </div>
                        <div class="progress bg-white bg-opacity-10" style="height: 6px;">
                            <div :class="`progress-bar bg-${stat.color}`" :style="`width: ${stat.progress}%`"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="opacity-50">{{ stat.subtitle }}</small>
                            <small :class="`text-${stat.color} fw-bold`">{{ stat.progress }}%</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AdminDashboard',
    data() {
        return {
            stats: [
                {
                    label: "Pending Applications",
                    value: "42",
                    icon: "bi-file-earmark-text-fill",
                    color: "warning",
                    progress: 65,
                    subtitle: "Needs review"
                },
                {
                    label: "Scheduled Weddings",
                    value: "18",
                    icon: "bi-calendar-heart-fill",
                    color: "primary",
                    progress: 80,
                    subtitle: "This month"
                },
                {
                    label: "Issued Licenses",
                    value: "1,204",
                    icon: "bi-patch-check-fill",
                    color: "success",
                    progress: 100,
                    subtitle: "Total records"
                },
                {
                    label: "Staff on Duty",
                    value: "8",
                    icon: "bi-person-badge-fill",
                    color: "info",
                    progress: 90,
                    subtitle: "Active now"
                },
                {
                    label: "Expired Licenses",
                    value: "15",
                    icon: "bi-exclamation-triangle-fill",
                    color: "danger",
                    progress: 25,
                    subtitle: "Requires action"
                },
                {
                    label: "User Inquiries",
                    value: "256",
                    icon: "bi-chat-dots-fill",
                    color: "light",
                    progress: 40,
                    subtitle: "Support tickets"
                }
            ]
        };
    }
};
</script>

<style scoped>
.glass-card {
    background: rgba(255, 255, 255, 0.03) !important;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
}

.glass-pill {
    background: rgba(13, 110, 253, 0.1);
    border: 1px solid rgba(13, 110, 253, 0.2);
}

.transition-hover {
    transition: all 0.3s ease;
}

.transition-hover:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2) !important;
}

.text-shadow {
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}
</style>