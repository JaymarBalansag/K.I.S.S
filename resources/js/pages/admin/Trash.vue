<template>
    <div class="trash-page animate__animated animate__fadeIn">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div class="text-white">
                <span class="badge glass-pill text-warning rounded-pill px-4 py-2 mb-3 fw-bold">TRASH BIN</span>
                <h2 class="fw-bold mb-1">Admin Trash</h2>
                <p class="opacity-75 mb-0">Restore or permanently delete applications and appointments.</p>
            </div>
            <button class="btn btn-outline-light rounded-pill px-4" @click="refreshAll" :disabled="loading">
                <i class="bi bi-arrow-clockwise me-2"></i> Refresh
            </button>
        </div>

        <div class="glass-card p-3 p-md-4 mb-4">
            <div class="d-flex flex-wrap gap-2">
                <button class="btn rounded-pill px-4 fw-bold"
                    :class="activeTab === 'applications' ? 'btn-info text-dark' : 'btn-outline-light text-white'"
                    @click="activeTab = 'applications'">
                    <i class="bi bi-file-earmark-text me-2"></i> Applications
                </button>
                <button class="btn rounded-pill px-4 fw-bold"
                    :class="activeTab === 'appointments' ? 'btn-info text-dark' : 'btn-outline-light text-white'"
                    @click="activeTab = 'appointments'">
                    <i class="bi bi-calendar-check me-2"></i> Appointments
                </button>
            </div>
        </div>

        <div class="glass-card p-3 p-md-4">
            <div v-if="loading" class="text-center py-5 text-white">
                <div class="spinner-border text-info" role="status"></div>
                <p class="mt-3 mb-0 opacity-75">Loading trash...</p>
            </div>

            <template v-else>
                <div v-if="activeTab === 'applications'">
                    <div v-if="applications.length" class="table-responsive">
                        <table class="table align-middle trash-table mb-0">
                            <thead>
                                <tr class="text-uppercase small opacity-75 ls-1">
                                    <th class="text-white border-0">Control No.</th>
                                    <th class="text-white border-0">Couple Name</th>
                                    <th class="text-white border-0">Deleted</th>
                                    <th class="text-white border-0 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="app in applications" :key="app.id" class="glass-row">
                                    <td class="text-white fw-semibold border-0">{{ app.control_number }}</td>
                                    <td class="text-white border-0">{{ app.coupleNames }}</td>
                                    <td class="text-white opacity-75 border-0">{{ formatDate(app.deleted_at) }}</td>
                                    <td class="border-0 text-center">
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <button class="btn btn-action-glass text-success" @click="restoreApplication(app)">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                                            </button>
                                            <button class="btn btn-action-glass text-danger" @click="eraseApplication(app)">
                                                <i class="bi bi-trash3 me-1"></i> Erase
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="text-center text-white opacity-75 py-5">
                        <i class="bi bi-trash display-4 d-block mb-2"></i>
                        <p class="mb-0">No trashed applications.</p>
                    </div>
                </div>

                <div v-else>
                    <div v-if="appointments.length" class="table-responsive">
                        <table class="table align-middle trash-table mb-0">
                            <thead>
                                <tr class="text-uppercase small opacity-75 ls-1">
                                    <th class="text-white border-0">Control No.</th>
                                    <th class="text-white border-0">Client</th>
                                    <th class="text-white border-0">Type</th>
                                    <th class="text-white border-0">Deleted</th>
                                    <th class="text-white border-0 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="apt in appointments" :key="apt.id" class="glass-row">
                                    <td class="text-white fw-semibold border-0">{{ apt.control_number }}</td>
                                    <td class="text-white border-0">{{ formatFullName(apt) }}</td>
                                    <td class="text-white border-0">{{ apt.appointment_type }}</td>
                                    <td class="text-white opacity-75 border-0">{{ formatDate(apt.deleted_at) }}</td>
                                    <td class="border-0 text-center">
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <button class="btn btn-action-glass text-success" @click="restoreAppointment(apt)">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                                            </button>
                                            <button class="btn btn-action-glass text-danger" @click="eraseAppointment(apt)">
                                                <i class="bi bi-trash3 me-1"></i> Erase
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="text-center text-white opacity-75 py-5">
                        <i class="bi bi-trash display-4 d-block mb-2"></i>
                        <p class="mb-0">No trashed appointments.</p>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2';
import api from '../../controller/api';

export default {
    name: 'AdminTrash',
    data() {
        return {
            activeTab: 'applications',
            applications: [],
            appointments: [],
            loading: false,
        };
    },
    mounted() {
        this.refreshAll();
    },
    methods: {
        async refreshAll() {
            this.loading = true;
            try {
                const [appRes, aptRes] = await Promise.all([
                    api.get('/applications/trash'),
                    api.get('/Appointments/trash')
                ]);

                const appPayload = appRes?.data?.data?.data || [];
                this.applications = Array.isArray(appPayload)
                    ? appPayload.map((app) => ({
                        id: app.id,
                        control_number: app.control_number,
                        coupleNames: app.applicant_names,
                        deleted_at: app.deleted_at,
                    }))
                    : [];

                const aptPayload = Array.isArray(aptRes?.data) ? aptRes.data : (aptRes?.data?.data || []);
                this.appointments = Array.isArray(aptPayload) ? aptPayload : [];
            } catch (error) {
                this.applications = [];
                this.appointments = [];
                Swal.fire('Error', 'Unable to load trash items.', 'error');
            } finally {
                this.loading = false;
            }
        },
        formatDate(value) {
            if (!value) return 'N/A';
            return new Date(value).toLocaleString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
            });
        },
        formatFullName(apt) {
            return `${apt.first_name || ''} ${apt.last_name || ''}`.trim();
        },
        async restoreApplication(app) {
            await this.confirmAndRun({
                title: 'Restore application?',
                text: `Restore ${app.control_number} back to active list?`,
                confirmColor: '#16a34a',
                action: async () => {
                    await api.patch(`/applications/${app.id}/restore`);
                    this.applications = this.applications.filter(item => item.id !== app.id);
                }
            });
        },
        async eraseApplication(app) {
            await this.confirmAndRun({
                title: 'Erase application?',
                text: `Permanently delete ${app.control_number}?`,
                confirmColor: '#ef4444',
                action: async () => {
                    await api.delete(`/applications/${app.id}/force`);
                    this.applications = this.applications.filter(item => item.id !== app.id);
                }
            });
        },
        async restoreAppointment(apt) {
            await this.confirmAndRun({
                title: 'Restore appointment?',
                text: `Restore ${apt.control_number} back to active list?`,
                confirmColor: '#16a34a',
                action: async () => {
                    await api.patch(`/Appointments/${apt.id}/restore`);
                    this.appointments = this.appointments.filter(item => item.id !== apt.id);
                }
            });
        },
        async eraseAppointment(apt) {
            await this.confirmAndRun({
                title: 'Erase appointment?',
                text: `Permanently delete ${apt.control_number}?`,
                confirmColor: '#ef4444',
                action: async () => {
                    await api.delete(`/Appointments/${apt.id}/force`);
                    this.appointments = this.appointments.filter(item => item.id !== apt.id);
                }
            });
        },
        async confirmAndRun({ title, text, confirmColor, action }) {
            const result = await Swal.fire({
                title,
                text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#64748b',
                background: '#0f172a',
                color: '#fff',
            });

            if (!result.isConfirmed) return;

            try {
                await action();
                Swal.fire({
                    title: 'Done',
                    text: 'Action completed successfully.',
                    icon: 'success',
                    background: '#0f172a',
                    color: '#fff'
                });
            } catch (error) {
                Swal.fire('Error', 'Action failed.', 'error');
            }
        }
    }
};
</script>

<style scoped>
.glass-card {
    background: rgba(255, 255, 255, 0.05) !important;
    backdrop-filter: blur(15px) saturate(160%);
    -webkit-backdrop-filter: blur(15px) saturate(160%);
    border: 1px solid rgba(255, 255, 255, 0.12);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
}

.glass-pill {
    background: rgba(245, 158, 11, 0.15);
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.trash-table {
    --bs-table-bg: transparent !important;
    border-collapse: separate;
    border-spacing: 0 12px;
}

.glass-row {
    background: rgba(255, 255, 255, 0.06) !important;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.btn-action-glass {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 12px;
    padding: 8px 14px;
    transition: all 0.2s ease;
}

.btn-action-glass:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-1px);
}

.ls-1 {
    letter-spacing: 1px;
}
</style>
