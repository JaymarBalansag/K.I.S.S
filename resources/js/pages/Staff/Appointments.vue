<template>
    <div class="management-page" :style="{ backgroundImage: 'url(/background.jpg)' }">
        <main class="content-overlay">
            <div class="container py-5 mt-5">
                <div class="row justify-content-center text-center mb-5 mt-4">
                    <div class="col-lg-9 col-xl-8">
                        <span
                            class="badge bg-primary bg-opacity-75 rounded-pill px-4 py-2 mb-3 shadow-sm text-uppercase fw-bold animate__animated animate__fadeInDown"
                            style="letter-spacing: 1px;">
                            Appointments Management
                        </span>
                        <h2 class="text-white fw-bold text-shadow-heavy">Schedule Overview</h2>
                    </div>
                </div>

                <div class="staff-content animate__animated animate__fadeInUp">
                    <div class="table-responsive d-none d-md-block">
                        <table class="table glass-table align-middle">
                            <thead>
                                <tr class="text-uppercase small opacity-75 ls-1">
                                    <th class="px-4 py-3 text-white border-0">Client / Couple</th>
                                    <th class="py-3 text-white border-0">Type</th>
                                    <th class="py-3 text-white border-0">Schedule Date</th>
                                    <th class="py-3 text-white border-0">Status</th>
                                    <th class="py-3 text-center text-white border-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="apt in appointments" :key="apt.id" class="glass-row transition">
                                    <td class="px-4 fw-bold text-white border-0 rounded-start-4">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calendar2-heart text-primary me-2 small"></i>
                                            {{ apt.clientName }}
                                        </div>
                                    </td>
                                    <td class="text-white border-0">
                                        <span class="small opacity-75">{{ apt.type }}</span>
                                    </td>
                                    <td class="text-white opacity-75 border-0">
                                        {{ apt.date }} <br>
                                        <small class="text-info">{{ apt.time }}</small>
                                    </td>
                                    <td class="border-0">
                                        <span :class="getStatusClass(apt.status)">
                                            {{ apt.status }}
                                        </span>
                                    </td>
                                    <td class="text-center border-0 rounded-end-4 px-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button @click="viewDetails(apt)" class="btn btn-action-glass text-info">
                                                <i class="bi bi-eye-fill me-1"></i> View
                                            </button>
                                            <button v-if="apt.status === 'Pending'" @click="approveAppointment(apt.id)"
                                                class="btn btn-action-glass text-success">
                                                <i class="bi bi-check-circle-fill me-1"></i> Approve
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-md-none px-2">
                        <div v-for="apt in appointments" :key="'mob-' + apt.id"
                            class="mobile-staff-card glass-row rounded-4 p-4 mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="text-white fw-bold mb-0 pe-2">{{ apt.clientName }}</h6>
                                <span :class="getStatusClass(apt.status)">{{ apt.status }}</span>
                            </div>
                            <div class="mb-3">
                                <p class="small text-white opacity-75 mb-1"><i class="bi bi-tag me-1"></i> {{ apt.type
                                }}</p>
                                <p class="small text-white opacity-50 mb-0"><i class="bi bi-clock me-1"></i> {{ apt.date
                                }} | {{ apt.time }}</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button @click="viewDetails(apt)" class="btn btn-action-glass text-info flex-grow-1">
                                    <i class="bi bi-eye-fill"></i> View
                                </button>
                                <button v-if="apt.status === 'Pending'" @click="approveAppointment(apt.id)"
                                    class="btn btn-action-glass text-success flex-grow-1">
                                    <i class="bi bi-check-circle-fill"></i> Approve
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
import Swal from 'sweetalert2';

export default {
    data() {
        return {
            appointments: [
                { id: 1, clientName: "Ricardo & Alyana", type: "Wedding Ceremony", date: "2026-06-14", time: "10:00 AM", status: "Pending" },
                { id: 2, clientName: "Basilio & Sisa", type: "Pre-Cana Counseling", date: "2026-02-10", time: "02:30 PM", status: "Approved" },
                { id: 3, clientName: "Crisostomo & Maria", type: "Wedding Ceremony", date: "2026-12-25", time: "08:00 AM", status: "Pending" }
            ]
        };
    },
    methods: {
        getStatusClass(status) {
            const base = "badge glass-pill px-3 py-2 ";
            return status === 'Approved' ? base + "status-approved" : base + "status-pending";
        },

        async viewDetails(apt) {
            Swal.fire({
                title: `<span class="text-white fw-bold">Appointment Details</span>`,
                html: `
                    <div class="text-start mt-3 p-3 rounded-4" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                        <p class="text-white mb-1 small opacity-50">Client / Couple:</p>
                        <h5 class="text-info fw-bold mb-3 text-shadow-medium">${apt.clientName}</h5>
                        
                        <div class="row g-3">
                            <div class="col-6">
                                <p class="text-white mb-0 small opacity-50">Event Type</p>
                                <p class="text-white fw-bold mb-0 small">${apt.type}</p>
                            </div>
                            <div class="col-6 text-end">
                                <p class="text-white mb-0 small opacity-50">Status</p>
                                <p class="text-warning fw-bold mb-0 small">${apt.status}</p>
                            </div>
                            <div class="col-12 border-top border-white border-opacity-10 pt-2">
                                <p class="text-white mb-0 small opacity-50 text-center">Scheduled For</p>
                                <p class="text-white fw-bold mb-0 text-center">${apt.date} at ${apt.time}</p>
                            </div>
                        </div>
                    </div>
                `,
                background: '#0f172a',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-images me-2"></i> View Documents',
                cancelButtonText: 'Close',
                confirmButtonColor: '#0dcaf0',
                cancelButtonColor: 'rgba(255,255,255,0.1)',
                customClass: {
                    popup: 'glass-row rounded-4 border-white border-opacity-10',
                    confirmButton: 'btn btn-info fw-bold px-4 py-2 rounded-3 text-dark mx-2',
                    cancelButton: 'btn btn-outline-light fw-bold px-4 py-2 rounded-3 mx-2'
                },
                buttonsStyling: false,
                showClass: { popup: 'animate__animated animate__zoomIn animate__faster' }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.showSampleDocuments();
                }
            });
        },

        showSampleDocuments() {
            Swal.fire({
                title: '<span class="text-white">Submitted Documents</span>',
                html: `
                    <div class="row g-2">
                        <div class="col-6">
                            <img src="https://picsum.photos/id/101/400/300" class="img-fluid rounded-3 border border-white border-opacity-25" alt="doc1">
                            <p class="text-white small mt-1 opacity-75">Valid ID</p>
                        </div>
                        <div class="col-6">
                            <img src="https://picsum.photos/id/102/400/300" class="img-fluid rounded-3 border border-white border-opacity-25" alt="doc2">
                            <p class="text-white small mt-1 opacity-75">Birth Certificate</p>
                        </div>
                        <div class="col-12 mt-2">
                            <img src="https://picsum.photos/id/103/600/300" class="img-fluid rounded-3 border border-white border-opacity-25" alt="doc3">
                            <p class="text-white small mt-1 opacity-75">CENOMAR</p>
                        </div>
                    </div>
                `,
                background: '#0f172a',
                confirmButtonText: 'Back to Details',
                confirmButtonColor: '#0dcaf0',
                customClass: {
                    popup: 'glass-row rounded-4',
                    confirmButton: 'btn btn-info fw-bold px-5 text-dark'
                },
                buttonsStyling: false
            });
        },

        async approveAppointment(id) {
            const result = await Swal.fire({
                title: 'Confirm Schedule',
                text: "Confirm and approve this appointment date?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Approve',
                background: '#0f172a',
                color: '#fff',
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                customClass: { popup: 'glass-row rounded-4' }
            });

            if (result.isConfirmed) {
                const apt = this.appointments.find(a => a.id === id);
                if (apt) {
                    apt.status = 'Approved';
                    Swal.fire({
                        title: 'Confirmed!',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        background: '#0f172a',
                        color: '#fff'
                    });
                }
            }
        }
    }
};
</script>

<style scoped>
/* BACKGROUND SETTINGS */
.management-page {
    min-height: 100vh;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    position: relative;
}

.content-overlay {
    min-height: 100vh;
    background: rgba(15, 23, 42, 0.4);
}

/* GLASS TABLE CORE */
.glass-table {
    --bs-table-bg: transparent !important;
    border-collapse: separate !important;
    border-spacing: 0 15px !important;
}

.glass-row {
    background: rgba(255, 255, 255, 0.07) !important;
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.glass-row:hover {
    background: rgba(255, 255, 255, 0.12) !important;
    transform: translateY(-3px);
}

/* BUTTONS */
.btn-action-glass {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: white;
    border-radius: 12px;
    padding: 8px 18px;
    font-size: 0.9rem;
    font-weight: 500;
    transition: 0.3s;
}

.btn-action-glass:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
    transform: scale(1.05);
}

/* STATUS PILLS */
.glass-pill {
    background: rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(4px);
    font-weight: 600;
    letter-spacing: 0.5px;
}

.status-pending {
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.status-approved {
    color: #0dfaf0;
    border: 1px solid rgba(13, 250, 240, 0.3);
}

/* TEXT SHADOWS */
.text-shadow-heavy {
    text-shadow: 0 4px 15px rgba(0, 0, 0, 0.7);
}

.text-shadow-medium {
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
}

.ls-1 {
    letter-spacing: 1px;
}

.transition {
    transition: all 0.3s ease;
}
</style>