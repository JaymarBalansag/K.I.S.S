<template>
    <div class="dashboard-content animate__animated animate__fadeIn">
        <div class="mb-5 text-white d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <span class="badge glass-pill text-primary rounded-pill px-4 py-2 mb-3 fw-bold">OVERVIEW</span>
                <h1 class="fw-bolder mb-2 text-shadow">Admin Dashboard</h1>
                <p class="opacity-75 mb-0">Manage your daily tasks and monitor marriage service applications.</p>
            </div>
            <button class="btn btn-action-glass text-white" @click="fetchDashboard" :disabled="loading">
                <i class="bi bi-arrow-clockwise me-2"></i>{{ loading ? 'Refreshing...' : 'Refresh' }}
            </button>
        </div>

        <div class="row g-4">
            <div v-for="stat in stats" :key="stat.label" class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-lg glass-card h-100 transition-hover card-link" @click="goTo(stat.link)">
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

        <div class="row g-4 mt-2">
            <div class="col-xl-6">
                <div class="glass-card p-4 h-100 text-white">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 fw-bold">Recent Applications</h5>
                        <button class="btn btn-sm btn-outline-light" @click="goTo('/Admin/Applications')">Open</button>
                    </div>

                    <div v-if="recentApplications.length">
                        <div v-for="app in recentApplications" :key="app.id" class="record-row d-flex justify-content-between align-items-center py-2 border-bottom border-white border-opacity-10">
                            <div>
                                <div class="fw-semibold">{{ app.applicant_names || app.control_number }}</div>
                                <small class="opacity-75">{{ app.control_number }} • {{ formatDate(app.created_at) }}</small>
                            </div>
                            <span :class="getStatusClass(app.status)">{{ app.status }}</span>
                        </div>
                    </div>
                    <p v-else class="opacity-50 mb-0">No recent applications found.</p>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="glass-card p-4 h-100 text-white">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 fw-bold">Recent Appointments</h5>
                        <button class="btn btn-sm btn-outline-light" @click="goTo('/Admin/Appointments')">Open</button>
                    </div>

                    <div v-if="recentAppointments.length">
                        <div v-for="apt in recentAppointments" :key="apt.id" class="record-row d-flex justify-content-between align-items-center py-2 border-bottom border-white border-opacity-10">
                            <div>
                                <div class="fw-semibold">{{ apt.first_name }} {{ apt.last_name }}</div>
                                <small class="opacity-75">{{ apt.control_number }} • {{ apt.appointment_type }} • {{ formatDate(apt.requested_date) }}</small>
                            </div>
                            <span :class="getStatusClass(apt.status)">{{ normalizeAppointmentStatus(apt.status) }}</span>
                        </div>
                    </div>
                    <p v-else class="opacity-50 mb-0">No recent appointments found.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from '../../controller/api';

export default {
    name: 'AdminDashboard',
    data() {
        return {
            loading: false,
            appointments: [],
            recentApplications: [],
            recentAppointments: [],
            applicationCount: 0,
            pendingApplications: 0,
            issuedApplications: 0,
            staffCount: 0
        };
    },
    computed: {
        pendingAppointments() {
            return this.appointments.filter((apt) => String(apt.status || 'pending') === 'pending').length;
        },
        appointmentTotal() {
            return this.appointments.length;
        },
        appointmentPendingProgress() {
            if (!this.appointmentTotal) return 0;
            return Math.min(100, Math.round((this.pendingAppointments / this.appointmentTotal) * 100));
        },
        applicationPendingProgress() {
            if (!this.applicationCount) return 0;
            return Math.min(100, Math.round((this.pendingApplications / this.applicationCount) * 100));
        },
        issuedProgress() {
            if (!this.applicationCount) return 0;
            return Math.min(100, Math.round((this.issuedApplications / this.applicationCount) * 100));
        },
        stats() {
            return [
                {
                    label: 'Total Applications',
                    value: this.applicationCount,
                    icon: 'bi-file-earmark-text-fill',
                    color: 'warning',
                    progress: this.applicationPendingProgress,
                    subtitle: `${this.pendingApplications} pending review`,
                    link: '/Admin/Applications'
                },
                {
                    label: 'Issued Licenses',
                    value: this.issuedApplications,
                    icon: 'bi-patch-check-fill',
                    color: 'success',
                    progress: this.issuedProgress,
                    subtitle: 'Issued from total applications',
                    link: '/Admin/Applications'
                },
                {
                    label: 'Total Appointments',
                    value: this.appointmentTotal,
                    icon: 'bi-calendar-heart-fill',
                    color: 'primary',
                    progress: this.appointmentPendingProgress,
                    subtitle: `${this.pendingAppointments} pending`,
                    link: '/Admin/Appointments'
                },
                {
                    label: 'Staff Accounts',
                    value: this.staffCount,
                    icon: 'bi-person-badge-fill',
                    color: 'info',
                    progress: 100,
                    subtitle: 'Active user records',
                    link: '/Admin/Staffs'
                }
            ];
        }
    },
    mounted() {
        this.fetchDashboard();
    },
    methods: {
        goTo(path) {
            if (path) this.$router.push(path);
        },
        formatDate(dateStr) {
            if (!dateStr) return 'N/A';
            return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        },
        normalizeAppointmentStatus(status) {
            if (status === 'confirmed') return 'approved';
            if (status === 'cancelled') return 'rejected';
            return status || 'pending';
        },
        getStatusClass(status) {
            const base = 'badge glass-pill px-3 py-2 text-capitalize ';
            const normalized = String(status || 'pending').toLowerCase();
            if (normalized === 'approved' || normalized === 'issued' || normalized === 'confirmed') return base + 'status-approved';
            if (normalized === 'rejected' || normalized === 'cancelled') return base + 'status-rejected';
            return base + 'status-pending';
        },
        async fetchDashboard() {
            this.loading = true;
            try {
                const [appointmentsRes, appAllRes, appPendingRes, appIssuedRes, staffsRes] = await Promise.all([
                    api.get('Appointments'),
                    api.get('applications/all/desc', { params: { page: 1 } }),
                    api.get('applications/pending/desc', { params: { page: 1 } }),
                    api.get('applications/issued/desc', { params: { page: 1 } }),
                    api.get('Staffs')
                ]);

                const appointments = Array.isArray(appointmentsRes?.data)
                    ? appointmentsRes.data
                    : (Array.isArray(appointmentsRes?.data?.data) ? appointmentsRes.data.data : []);

                const appAllPayload = appAllRes?.data?.data || {};
                const appPendingPayload = appPendingRes?.data?.data || {};
                const appIssuedPayload = appIssuedRes?.data?.data || {};
                const staffRows = Array.isArray(staffsRes?.data?.data) ? staffsRes.data.data : [];

                this.appointments = appointments;
                this.recentAppointments = [...appointments]
                    .sort((a, b) => new Date(b.requested_date) - new Date(a.requested_date))
                    .slice(0, 5);

                this.recentApplications = Array.isArray(appAllPayload.data) ? appAllPayload.data.slice(0, 5) : [];
                this.applicationCount = Number(appAllPayload.total || 0);
                this.pendingApplications = Number(appPendingPayload.total || 0);
                this.issuedApplications = Number(appIssuedPayload.total || 0);
                this.staffCount = staffRows.filter((user) => String(user.role || '').toLowerCase() === 'staff').length;
            } catch (error) {
                this.appointments = [];
                this.recentAppointments = [];
                this.recentApplications = [];
                this.applicationCount = 0;
                this.pendingApplications = 0;
                this.issuedApplications = 0;
                this.staffCount = 0;
            } finally {
                this.loading = false;
            }
        }
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

.card-link {
    cursor: pointer;
}

.btn-action-glass {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 12px;
}

.status-pending {
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.status-approved {
    color: #0dfaf0;
    border: 1px solid rgba(13, 250, 240, 0.3);
}

.status-rejected {
    color: #ff6b6b;
    border: 1px solid rgba(255, 107, 107, 0.4);
}

.text-shadow {
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}
</style>
