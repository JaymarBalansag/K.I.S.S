<template>
    <div class="dashboard-content animate__animated animate__fadeIn">
        <div class="mb-5 text-white d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <span class="badge glass-pill text-primary rounded-pill px-4 py-2 mb-3 fw-bold text-uppercase" style="letter-spacing: 1px;">
                    Overview
                </span>
                <h1 class="fw-bolder mb-2 text-shadow-heavy">Staff Dashboard</h1>
                <p class="opacity-75 text-shadow-medium mb-0">Manage your daily tasks and monitor marriage service applications.</p>
            </div>
            <button class="btn btn-action-glass text-white" @click="fetchStats" :disabled="loading">
                <i class="bi bi-arrow-clockwise me-2"></i>{{ loading ? 'Refreshing...' : 'Refresh' }}
            </button>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-xl-3" v-for="card in statCards" :key="card.title">
                <div class="card border-0 shadow-lg glass-card h-100 transition-hover card-link" @click="goTo(card.link)">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center mb-3">
                            <div :class="`stats-icon bg-${card.color} bg-opacity-20 text-${card.color} rounded-3 p-3 me-3 d-flex align-items-center justify-content-center`"
                                style="width: 60px; height: 60px;">
                                <i :class="`bi ${card.icon} fs-3`"></i>
                            </div>
                            <div>
                                <h6 class="opacity-50 mb-0 fw-bold text-uppercase small ls-1">{{ card.title }}</h6>
                                <h2 class="fw-bold mb-0">{{ card.value }}</h2>
                            </div>
                        </div>
                        <div class="progress bg-white bg-opacity-10" style="height: 6px;">
                            <div :class="`progress-bar bg-${card.color}`" :style="{ width: card.progress + '%' }"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="opacity-50">{{ card.subtitle }}</small>
                            <small :class="`text-${card.color} fw-bold`">{{ card.progress }}%</small>
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
                        <button class="btn btn-sm btn-outline-light" @click="goTo('/Staff/Applications')">View All</button>
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
                        <button class="btn btn-sm btn-outline-light" @click="goTo('/Staff/Appointments')">View All</button>
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
    name: 'StaffDashboard',
    data() {
        return {
            loading: false,
            appointments: [],
            recentApplications: [],
            applicationCount: 0,
            pendingApplications: 0
        };
    },
    computed: {
        pmocAppointments() {
            return this.appointments.filter((apt) => {
                const type = String(apt?.appointment_type || '').toUpperCase();
                return type === 'PMOC' || type === 'PMO';
            });
        },
        civilWeddingAppointments() {
            return this.appointments.filter((apt) => {
                const type = String(apt?.appointment_type || '').toLowerCase();
                return type === 'civil wedding' || type === 'civil_wedding' || (type.includes('civil') && type.includes('wedding'));
            });
        },
        pmocCount() {
            return this.pmocAppointments.length;
        },
        pendingPmocCount() {
            return this.pmocAppointments.filter((apt) => String(apt?.status || 'pending') === 'pending').length;
        },
        pmocProgress() {
            if (!this.pmocCount) return 0;
            return Math.min(100, Math.round((this.pendingPmocCount / this.pmocCount) * 100));
        },
        marriageAppointmentCount() {
            return this.civilWeddingAppointments.length;
        },
        pendingMarriageAppointmentCount() {
            return this.civilWeddingAppointments.filter((apt) => String(apt?.status || 'pending') === 'pending').length;
        },
        marriageAppointmentProgress() {
            if (!this.marriageAppointmentCount) return 0;
            return Math.min(100, Math.round((this.pendingMarriageAppointmentCount / this.marriageAppointmentCount) * 100));
        },
        totalAppointments() {
            return this.appointments.length;
        },
        totalUsersServed() {
            return this.totalAppointments + this.applicationCount;
        },
        applicationPendingProgress() {
            if (!this.applicationCount) return 0;
            return Math.min(100, Math.round((this.pendingApplications / this.applicationCount) * 100));
        },
        recentAppointments() {
            return [...this.appointments]
                .sort((a, b) => new Date(b.requested_date) - new Date(a.requested_date))
                .slice(0, 5);
        },
        statCards() {
            return [
                {
                    title: 'Pre-Marriage Orientation & Counseling',
                    value: this.pmocCount,
                    icon: 'bi-calendar-heart-fill',
                    color: 'primary',
                    progress: this.pmocProgress,
                    subtitle: `${this.pendingPmocCount} pending`,
                    link: '/Staff/Appointments'
                },
                {
                    title: 'Marriage Appointment',
                    value: this.marriageAppointmentCount,
                    icon: 'bi-calendar-check-fill',
                    color: 'info',
                    progress: this.marriageAppointmentProgress,
                    subtitle: `${this.pendingMarriageAppointmentCount} pending`,
                    link: '/Staff/Appointments'
                },
                {
                    title: 'Marriage License Application',
                    value: this.applicationCount,
                    icon: 'bi-file-earmark-text-fill',
                    color: 'warning',
                    progress: this.applicationPendingProgress,
                    subtitle: `${this.pendingApplications} pending review`,
                    link: '/Staff/Applications'
                },
                {
                    title: 'Total Users Served',
                    value: this.totalUsersServed,
                    icon: 'bi-people-fill',
                    color: 'success',
                    progress: 100,
                    subtitle: 'Live from records',
                    link: '/Staff/Dashboard'
                }
            ];
        }
    },
    mounted() {
        this.fetchStats();
    },
    methods: {
        goTo(path) {
            if (path) {
                this.$router.push(path);
            }
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
        async fetchStats() {
            this.loading = true;
            try {
                const [appointmentsRes, appAllRes, appPendingRes] = await Promise.all([
                    api.get('Appointments'),
                    api.get('applications/all/desc', { params: { page: 1 } }),
                    api.get('applications/pending/desc', { params: { page: 1 } })
                ]);

                const appointments = Array.isArray(appointmentsRes?.data)
                    ? appointmentsRes.data
                    : (Array.isArray(appointmentsRes?.data?.data) ? appointmentsRes.data.data : []);

                const appAllPayload = appAllRes?.data?.data || {};
                const appPendingPayload = appPendingRes?.data?.data || {};

                this.appointments = appointments;
                this.recentApplications = Array.isArray(appAllPayload.data) ? appAllPayload.data.slice(0, 5) : [];
                this.applicationCount = Number(appAllPayload.total || 0);
                this.pendingApplications = Number(appPendingPayload.total || 0);
            } catch (error) {
                this.appointments = [];
                this.recentApplications = [];
                this.applicationCount = 0;
                this.pendingApplications = 0;
            } finally {
                this.loading = false;
            }
        }
    }
};
</script>

<style scoped>
.glass-card {
    background: rgba(255, 255, 255, 0.05) !important;
    backdrop-filter: blur(15px) saturate(150%);
    -webkit-backdrop-filter: blur(15px) saturate(150%);
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 24px;
}

.glass-pill {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.transition-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.transition-hover:hover {
    transform: translateY(-8px);
    background: rgba(255, 255, 255, 0.1) !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3) !important;
    border-color: rgba(255, 255, 255, 0.3) !important;
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
