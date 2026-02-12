<template>
    <main class="content-overlay">
        <div class="container py-5 mt-5">
            <div class="row justify-content-center text-center mb-4 mt-4">
                <div class="col-lg-9 col-xl-8">
                    <span
                        class="badge bg-primary bg-opacity-75 rounded-pill px-4 py-2 mb-3 shadow-sm text-uppercase fw-bold animate__animated animate__fadeInDown">
                        Appointments Management
                    </span>
                    <h2 class="text-white fw-bold text-shadow-heavy">Schedule Overview</h2>
                </div>
            </div>

            <div class="row justify-content-center mb-4 g-3">
                <div class="col-md-5">
                    <div class="search-box">
                        <i class="bi bi-search search-icon"></i>
                        <input v-model="searchQuery" type="text" class="form-control glass-input"
                            placeholder="Search Name or Control No...">
                    </div>
                </div>
                <div class="col-md-5 d-flex justify-content-md-end justify-content-center gap-2">
                    <button v-for="status in ['all', 'pending', 'approved']" :key="status"
                        @click="filterStatus = status" class="btn btn-action-glass text-capitalize px-4"
                        :class="{ 'active-filter': filterStatus === status }">
                        {{ status }}
                    </button>
                </div>
            </div>

            <div class="staff-content animate__animated animate__fadeInUp">
                <div v-if="loading" class="text-center text-white py-5">
                    <div class="spinner-border text-info" role="status"></div>
                    <p class="mt-2 opacity-75">Connecting to server...</p>
                </div>

                <div v-else-if="filteredAppointments && filteredAppointments.length > 0">
                    <div class="table-responsive d-none d-md-block">
                        <table class="table glass-table align-middle">
                            <thead>
                                <tr class="text-uppercase small opacity-75 ls-1">
                                    <th class="px-4 py-3 text-white border-0">Control No.</th>
                                    <th class="py-3 text-white border-0">Client Name</th>
                                    <th class="py-3 text-white border-0">Type</th>
                                    <th class="py-3 text-white border-0">Requested Date</th>
                                    <th class="py-3 text-white border-0">Status</th>
                                    <th class="py-3 text-center text-white border-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="apt in filteredAppointments" :key="apt.id" class="glass-row transition">
                                    <td class="px-4 fw-bold text-info border-0 rounded-start-4">
                                        {{ apt.control_number }}
                                    </td>
                                    <td class="text-white border-0">
                                        <div class="fw-bold">{{ formatFullName(apt) }}</div>
                                        <small class="opacity-50">{{ apt.phone_number }}</small>
                                    </td>
                                    <td class="text-white border-0">
                                        <span class="small opacity-75">{{ apt.appointment_type }}</span>
                                    </td>
                                    <td class="text-white opacity-75 border-0">
                                        {{ formatDate(apt.requested_date) }}
                                    </td>
                                    <td class="border-0">
                                        <span :class="getStatusClass(apt.status)">
                                            {{ apt.status === 'confirmed' ? 'Approved' : (apt.status || 'pending')
                                            }}
                                        </span>
                                    </td>
                                    <td class="text-center border-0 rounded-end-4 px-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button @click="viewDetails(apt)"
                                                class="btn btn-action-glass text-info">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>
                                            <button v-if="apt.status === 'pending'"
                                                @click="approveAppointment(apt.id)"
                                                class="btn btn-action-glass text-success">
                                                <i class="bi bi-check-circle-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-md-none px-2">
                        <div v-for="apt in filteredAppointments" :key="'mob-' + apt.id"
                            class="mobile-staff-card glass-row rounded-4 p-4 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <h6 class="text-white fw-bold mb-0">{{ formatFullName(apt) }}</h6>
                                <span :class="getStatusClass(apt.status)">{{ apt.status === 'confirmed' ? 'Approved'
                                    : (apt.status || 'pending') }}</span>
                            </div>
                            <p class="small text-info mt-1 mb-3">{{ apt.control_number }}</p>
                            <div class="d-flex gap-2">
                                <button @click="viewDetails(apt)"
                                    class="btn btn-action-glass text-info flex-grow-1">View</button>
                                <button v-if="apt.status === 'pending'" @click="approveAppointment(apt.id)"
                                    class="btn btn-action-glass text-success flex-grow-1">Approve</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center text-white opacity-50 py-5">
                    <i class="bi bi-clipboard-x display-4"></i>
                    <p class="mt-3">No records found matching your criteria.</p>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import Swal from 'sweetalert2';
import api from '../../controller/api';

export default {
    name: 'AppointmentsManagement',
    data() {
        return {
            appointments: [],
            loading: true,
            filterStatus: 'all',
            searchQuery: ''
        };
    },
    computed: {
        filteredAppointments() {
            if (!Array.isArray(this.appointments)) return [];

            return this.appointments.filter(apt => {
                const statusValue = apt.status || 'pending';
                const matchesStatus = this.filterStatus === 'all' ||
                    (this.filterStatus === 'approved' && statusValue === 'confirmed') ||
                    (this.filterStatus === 'pending' && statusValue === 'pending');

                const fullName = `${apt.first_name || ''} ${apt.last_name || ''}`.toLowerCase();
                const controlNo = (apt.control_number || '').toLowerCase();
                const query = this.searchQuery.toLowerCase();
                const matchesSearch = fullName.includes(query) || controlNo.includes(query);

                return matchesStatus && matchesSearch;
            });
        }
    },
    mounted() {
        this.fetchAppointments();
    },
    methods: {
        async fetchAppointments() {
            this.loading = true;
            try {
                // Using 'Appointments' to match api.php apiResource name
                const response = await api.get('Appointments');

                // // Validate that response.data is JSON and not HTML string
                // if (typeof response.data === 'string' && response.data.includes('<!DOCTYPE html>')) {
                //     throw new Error("Received HTML instead of JSON. Please check login session.");
                // }

                if (Array.isArray(response.data)) {
                    this.appointments = response.data;
                } else if (response.data && Array.isArray(response.data.data)) {
                    this.appointments = response.data.data;
                } else {
                    this.appointments = [];
                }
            } catch (error) {
                console.error("Fetch Error:", error);
                this.appointments = [];
                if (error.response?.status === 401 || error.response?.status === 403) {
                    Swal.fire('Access Denied', 'Please log in as Staff to view records.', 'warning');
                } else {
                    Swal.fire('Error', 'Could not load data from server.', 'error');
                }
            } finally {
                this.loading = false;
            }
        },
        formatFullName(apt) {
            return `${apt.first_name || ''} ${apt.last_name || ''}`;
        },
        formatDate(dateStr) {
            if (!dateStr) return 'N/A';
            return new Date(dateStr).toLocaleDateString('en-US', {
                month: 'short', day: 'numeric', year: 'numeric'
            });
        },
        getStatusClass(status) {
            const base = "badge glass-pill px-3 py-2 ";
            return status === 'confirmed' ? base + "status-approved" : base + "status-pending";
        },
        async approveAppointment(id) {
            const result = await Swal.fire({
                title: 'Confirm Approval',
                text: "Set this appointment to Approved?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                background: '#0f172a',
                color: '#fff',
                customClass: { popup: 'glass-row' }
            });

            if (result.isConfirmed) {
                try {
                    await api.patch(`Appointments/${id}`, { status: 'confirmed' });

                    const index = this.appointments.findIndex(a => a.id === id);
                    if (index !== -1) {
                        this.appointments[index].status = 'confirmed';
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Approved!',
                        showConfirmButton: false,
                        timer: 1500,
                        background: '#0f172a',
                        color: '#fff'
                    });
                } catch (e) {
                    Swal.fire('Error', 'Failed to update record.', 'error');
                }
            }
        },
        viewDetails(apt) {
            Swal.fire({
                title: '<span class="text-white">Appointment Details</span>',
                html: `
                    <div class="text-start text-white p-2" style="font-size: 0.9rem;">
                        <div class="mb-2 opacity-50">Control Number</div>
                        <div class="mb-3 fw-bold text-info fs-5">${apt.control_number}</div>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="opacity-50">Client</div>
                                <strong>${this.formatFullName(apt)}</strong>
                            </div>
                            <div class="col-6">
                                <div class="opacity-50">Type</div>
                                <strong>${apt.appointment_type}</strong>
                            </div>
                            <div class="col-6">
                                <div class="opacity-50">Phone</div>
                                <strong>${apt.phone_number}</strong>
                            </div>
                            <div class="col-6">
                                <div class="opacity-50">Date</div>
                                <strong>${this.formatDate(apt.requested_date)}</strong>
                            </div>
                        </div>
                    </div>`,
                background: '#0f172a',
                confirmButtonColor: '#0dcaf0',
                customClass: { popup: 'glass-row rounded-4 border-white border-opacity-10' }
            });
        }
    }
};
</script>

<style scoped>
/* Search Box */
.search-box {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 15px;
    color: rgba(255, 255, 255, 0.5);
    z-index: 5;
}

.glass-input {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 12px;
    padding-left: 45px;
    color: white;
}

.glass-input::placeholder {
    color: white;
}

.glass-input:focus {
    border-color: #0dcaf0 !important;
    box-shadow: 0 0 15px rgba(13, 202, 240, 0.2);
    outline: none;
}

.active-filter {
    background: rgba(13, 202, 240, 0.2) !important;
    border-color: #0dcaf0 !important;
    color: #0dcaf0 !important;
}

.management-page {
    min-height: 100vh;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

.content-overlay {
    min-height: 100vh;
}

.glass-table {
    --bs-table-bg: transparent !important;
    border-collapse: separate !important;
    border-spacing: 0 12px !important;
}

.glass-row {
    background: rgba(255, 255, 255, 0.07) !important;
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
}

.btn-action-glass {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: white;
    border-radius: 10px;
    transition: 0.3s;
}

.status-pending {
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.status-approved {
    color: #0dfaf0;
    border: 1px solid rgba(13, 250, 240, 0.3);
}

.text-info {
    color: #0dcaf0 !important;
}
</style>
