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
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-lg glass-card h-100 transition-hover">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stats-icon bg-primary bg-opacity-20 text-primary rounded-3 p-3 me-3 d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px; border: 1px solid rgba(13, 110, 253, 0.2);">
                                <i class="bi bi-calendar-heart-fill fs-3"></i>
                            </div>
                            <div>
                                <h6 class="opacity-50 mb-0 fw-bold text-uppercase small ls-1">Pre-Marriage Orientation & Counseling</h6>
                                <h2 class="fw-bold mb-0">{{ pmocCount }}</h2>
                            </div>
                        </div>
                        <div class="progress bg-white bg-opacity-10" style="height: 6px;">
                            <div class="progress-bar bg-primary" :style="{ width: pmocProgress + '%' }"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="opacity-50">{{ pendingPmocCount }} pending</small>
                            <small class="text-primary fw-bold">{{ pmocProgress }}%</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-lg glass-card h-100 transition-hover">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stats-icon bg-info bg-opacity-20 text-info rounded-3 p-3 me-3 d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px; border: 1px solid rgba(13, 202, 240, 0.2);">
                                <i class="bi bi-calendar-check-fill fs-3"></i>
                            </div>
                            <div>
                                <h6 class="opacity-50 mb-0 fw-bold text-uppercase small ls-1">Marriage Appointment</h6>
                                <h2 class="fw-bold mb-0">{{ marriageAppointmentCount }}</h2>
                            </div>
                        </div>
                        <div class="progress bg-white bg-opacity-10" style="height: 6px;">
                            <div class="progress-bar bg-info" :style="{ width: marriageAppointmentProgress + '%' }"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="opacity-50">{{ pendingMarriageAppointmentCount }} pending</small>
                            <small class="text-info fw-bold">{{ marriageAppointmentProgress }}%</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-lg glass-card h-100 transition-hover">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stats-icon bg-warning bg-opacity-20 text-warning rounded-3 p-3 me-3 d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px; border: 1px solid rgba(255, 193, 7, 0.2);">
                                <i class="bi bi-file-earmark-text-fill fs-3"></i>
                            </div>
                            <div>
                                <h6 class="opacity-50 mb-0 fw-bold text-uppercase small ls-1">Marriage License Application</h6>
                                <h2 class="fw-bold mb-0">{{ applicationCount }}</h2>
                            </div>
                        </div>
                        <div class="progress bg-white bg-opacity-10" style="height: 6px;">
                            <div class="progress-bar bg-warning" :style="{ width: applicationPendingProgress + '%' }"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="opacity-50">{{ pendingApplications }} pending review</small>
                            <small class="text-warning fw-bold">{{ applicationPendingProgress }}%</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-lg glass-card h-100 transition-hover">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stats-icon bg-success bg-opacity-20 text-success rounded-3 p-3 me-3 d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px; border: 1px solid rgba(25, 135, 84, 0.2);">
                                <i class="bi bi-people-fill fs-3"></i>
                            </div>
                            <div>
                                <h6 class="opacity-50 mb-0 fw-bold text-uppercase small ls-1">Total Users Served</h6>
                                <h2 class="fw-bold mb-0">{{ totalUsersServed }}</h2>
                            </div>
                        </div>
                        <div class="progress bg-white bg-opacity-10" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-success small"><i class="bi bi-check-circle"></i> Live from records</small>
                            <small class="text-success fw-bold">100%</small>
                        </div>
                    </div>
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
            appointments: [],
            applications: []
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
            }).length;
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
            return this.civilWeddingAppointments;
        },
        pendingMarriageAppointmentCount() {
            return this.appointments.filter((apt) => {
                const status = String(apt?.status || 'pending');
                const type = String(apt?.appointment_type || '').toLowerCase();
                const isCivil = type === 'civil wedding' || type === 'civil_wedding' || (type.includes('civil') && type.includes('wedding'));
                return isCivil && status === 'pending';
            }).length;
        },
        marriageAppointmentProgress() {
            if (!this.marriageAppointmentCount) return 0;
            return Math.min(100, Math.round((this.pendingMarriageAppointmentCount / this.marriageAppointmentCount) * 100));
        },
        applicationCount() {
            return this.applications.length;
        },
        pendingApplications() {
            return this.applications.filter((app) => String(app?.status || 'pending') === 'pending').length;
        },
        applicationPendingProgress() {
            if (!this.applicationCount) return 0;
            return Math.min(100, Math.round((this.pendingApplications / this.applicationCount) * 100));
        },
        totalUsersServed() {
            return this.pmocCount + this.marriageAppointmentCount + this.applicationCount;
        }
    },
    mounted() {
        this.fetchStats();
    },
    methods: {
        async fetchStats() {
            try {
                const [appointmentsRes, applicationsRes] = await Promise.all([
                    api.get('Appointments'),
                    api.get('applications')
                ]);

                const appointments = Array.isArray(appointmentsRes?.data)
                    ? appointmentsRes.data
                    : (Array.isArray(appointmentsRes?.data?.data) ? appointmentsRes.data.data : []);

                const appsPayload = applicationsRes?.data?.data;
                const applications = Array.isArray(appsPayload?.data)
                    ? appsPayload.data
                    : (Array.isArray(appsPayload) ? appsPayload : []);

                this.appointments = appointments;
                this.applications = applications;
            } catch (error) {
                this.appointments = [];
                this.applications = [];
            }
        }
    }
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
