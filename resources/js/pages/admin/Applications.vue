<template>
    <main class="content-overlay">
        <div class="container py-5 mt-5">
            <div class="row justify-content-center text-center mb-5 mt-4">
                <div class="col-lg-9 col-xl-8">
                    <span
                        class="badge bg-primary bg-opacity-75 rounded-pill px-4 py-2 mb-3 shadow-sm text-uppercase fw-bold animate__animated animate__fadeInDown">
                        Marriage License Applications
                    </span>
                    <h2 class="text-white fw-bold text-shadow-heavy">Admin Application Registry</h2>
                    <p class="text-white opacity-75 mb-0">Manage application records with admin CRUD tools.</p>
                </div>
            </div>

            <div class="row g-3 mb-4 animate__animated animate__fadeIn">
                <div class="col-md-5">
                    <div class="input-group glass-input-group">
                        <span class="input-group-text glass-addon border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" v-model="search" class="form-control glass-input border-start-0 ps-0"
                            placeholder="Search by Groom, Bride, or Control Number...">
                    </div>
                </div>

                <div class="col-md-3">
                    <select v-model="status" class="form-select glass-input">
                        <option value="all">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="issued">Issued</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select v-model="order" class="form-select glass-input">
                        <option value="desc">Newest First</option>
                        <option value="asc">Oldest First</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-action-glass w-100 text-white" @click="resetFilters">
                        <i class="bi bi-x-circle me-1"></i> Clear
                    </button>
                    <button class="btn btn-outline-light w-100" @click="goToTrash">
                        <i class="bi bi-trash3 me-1"></i> Trash
                    </button>
                </div>
            </div>

            <div class="staff-content animate__animated animate__fadeInUp">
                <div v-if="applications.length > 0">
                    <div class="table-responsive d-none d-md-block">
                        <table class="table glass-table align-middle">
                            <thead>
                                <tr class="text-uppercase small opacity-75 ls-1">
                                    <th class="px-4 py-3 text-white border-0">Control Number</th>
                                    <th class="py-3 text-white border-0">Couple Name</th>
                                    <th class="py-3 text-white border-0">Application Date</th>
                                    <th class="py-3 text-white border-0">Status</th>
                                    <th class="py-3 text-center text-white border-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="app in applications" :key="app.id" class="glass-row transition">
                                    <td class="px-4 fw-bold text-white border-0 rounded-start-4">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-file-earmark-check text-danger me-2 small"></i>
                                            {{ app.control_number }}
                                        </div>
                                    </td>
                                    <td class="px-4 fw-bold text-white border-0 ">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-heart-fill text-danger me-2 small"></i>
                                            {{ app.coupleNames }}
                                        </div>
                                    </td>
                                    <td class="text-white opacity-75 border-0">{{ app.dateApplied }}</td>
                                    <td class="border-0">
                                        <span :class="getStatusClass(app.status)">{{ app.status }}</span>
                                    </td>
                                    <td class="text-center border-0 rounded-end-4 px-4">
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <button @click="openViewApplicants(app)"
                                                class="btn btn-action-glass text-white">
                                                <i class="bi bi-eye-fill me-1"></i> View
                                            </button>

                                            <button @click="openEditModal(app)"
                                                class="btn btn-action-glass text-info">
                                                <i class="bi bi-pencil-square me-1"></i> Edit
                                            </button>

                                            <button @click="moveToTrash(app)" class="btn btn-action-glass text-danger">
                                                <i class="bi bi-trash3 me-1"></i> Trash
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-md-none px-2">
                        <div v-for="app in applications" :key="'mob-' + app.id"
                            class="mobile-staff-card glass-row rounded-4 p-4 mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="text-white fw-bold mb-0 pe-2">{{ app.coupleNames }}</h6>
                                <span :class="getStatusClass(app.status)">{{ app.status }}</span>
                            </div>
                            <p class="small text-white opacity-50 mb-1">Applied: {{ app.dateApplied }}</p>
                            <p class="small text-white opacity-50 mb-4">Ref: {{ app.control_number }}</p>

                            <div class="d-flex gap-2 flex-wrap">
                                <button @click="openViewApplicants(app)"
                                    class="btn btn-action-glass text-info flex-grow-1">
                                    <i class="bi bi-eye-fill me-1"></i> View
                                </button>

                                <button @click="openEditModal(app)"
                                    class="btn btn-action-glass text-info flex-grow-1">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </button>

                                <button @click="moveToTrash(app)"
                                    class="btn btn-action-glass text-danger flex-grow-1">
                                    <i class="bi bi-trash3 me-1"></i> Trash
                                </button>
                            </div>
                        </div>
                    </div>

                    <nav v-if="totalPages > 1" class="d-flex flex-column align-items-center mt-4">
                        <p class="text-white-50 x-small mb-2">
                            Showing Page {{ page }} of {{ totalPages }} (Total {{ totalResults }} records)
                        </p>
                        <ul class="pagination glass-pagination">
                            <li class="page-item" :class="{ disabled: page === 1 }">
                                <button class="page-link" @click="changePage(page - 1)">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                            </li>

                            <li v-for="p in totalPages" :key="p"
                                v-show="p === 1 || p === totalPages || Math.abs(p - page) <= 1" class="page-item"
                                :class="{ active: page === p }">
                                <button class="page-link" @click="changePage(p)">{{ p }}</button>
                            </li>

                            <li class="page-item" :class="{ disabled: page === totalPages }">
                                <button class="page-link" @click="changePage(page + 1)">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </li>
                        </ul>
                    </nav>
                </div>

                <div v-else class="text-center py-5 animate__animated animate__fadeIn">
                    <div class="empty-state-icon mb-4">
                        <i class="bi bi-folder2-open display-1 text-white opacity-25"></i>
                    </div>
                    <h4 class="text-white opacity-75">No Applications Found</h4>
                    <p class="text-white opacity-50">There are currently no marriage license applications to display.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <div v-if="showApplicantsModal" class="modal-overlay-custom animate__animated animate__fadeIn">
        <div class="modal-body-custom rounded-5 shadow-2xl p-0 border border-white border-opacity-20">
            <div class="modal-glass-header p-4 d-flex justify-content-between align-items-center">
                <div>
                    <span
                        class="badge bg-info bg-opacity-10 text-info text-uppercase mb-2 x-small ls-1 px-3 border border-info border-opacity-20">Official
                        Registry Record</span>
                    <h4 class="fw-bold mb-0 text-white">Marriage License Application</h4>
                    <p class="text-info small mb-0 opacity-75 fw-bold mt-1">
                        <i class="bi bi-qr-code-scan me-1"></i> {{ control_number }}
                    </p>
                </div>
                <button class="btn-close btn-close-white opacity-50 hover-opacity-100"
                    @click="closeViewApplicants"></button>
            </div>

            <div class="p-4 pt-2">
                <div class="row g-4" v-if="applicant">
                    <div v-for="person in applicant" :key="person.id" class="col-md-6">
                        <div class="applicant-glass-card h-100 p-4 rounded-4"
                            :class="person.applicant_type === 'groom' ? 'groom-accent' : 'bride-accent'">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="type-pill px-3 py-1 rounded-pill x-small fw-bold text-uppercase">
                                    {{ person.applicant_type }}
                                </span>
                                <span class="x-small text-white opacity-50 fw-bold text-uppercase">
                                    {{ person.civil_status }}
                                </span>
                            </div>
                            <div class="mb-4">
                                <label class="x-small text-white opacity-40 text-uppercase ls-1 d-block mb-1">Legal Full
                                    Name</label>
                                <h5 class="fw-bold mb-0">{{ person.first_name }} {{ person.middle_name }} {{
                                    person.last_name }}</h5>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <label class="x-small text-white opacity-40 d-block">Birth Date</label>
                                    <span class="small">{{ person.month }}/{{ person.day }}/{{ person.year }} (Age: {{
                                        person.age }})</span>
                                </div>
                                <div class="col-6">
                                    <label class="x-small text-white opacity-40 d-block">Citizenship/Religion</label>
                                    <span class="small">{{ person.citizenship }} | {{ person.religion }}</span>
                                </div>
                                <div class="col-12">
                                    <label class="x-small text-white opacity-40 d-block">Birthplace</label>
                                    <span class="small">{{ person.birth_city }}, {{ person.birth_province }}, {{
                                        person.birth_country }}</span>
                                </div>
                                <div class="col-12">
                                    <label class="x-small text-white opacity-40 d-block">Current Residence</label>
                                    <span class="small opacity-80">{{ person.residence_address }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="showEditModal" class="modal-overlay-custom animate__animated animate__fadeIn">
        <div class="modal-body-custom rounded-5 shadow-2xl p-0 border border-white border-opacity-20">
            <div class="modal-glass-header p-4 d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-info bg-opacity-10 text-info text-uppercase mb-2 x-small ls-1 px-3 border border-info border-opacity-20">
                        Admin Update
                    </span>
                    <h4 class="fw-bold mb-0 text-white">Edit Application</h4>
                    <p class="text-info small mb-0 opacity-75 fw-bold mt-1">
                        <i class="bi bi-pencil-square me-1"></i> {{ editForm.control_number || 'Application Record' }}
                    </p>
                </div>
                <button class="btn-close btn-close-white opacity-50 hover-opacity-100"
                    @click="closeEditModal"></button>
            </div>

            <form class="p-4" @submit.prevent="saveApplicationEdit">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-white">Status</label>
                        <select v-model="editForm.status" class="form-select glass-input">
                            <option value="pending">Pending</option>
                            <option value="under_review">Under Review</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="issued">Issued</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white">Phone Number</label>
                        <input v-model="editForm.phone_number" type="text" class="form-control glass-input" placeholder="09XXXXXXXXX">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-white">Foreigner Type</label>
                        <select v-model="editForm.foreigner_type" class="form-select glass-input">
                            <option value="">None</option>
                            <option value="filipino">Filipino</option>
                            <option value="groom">Groom</option>
                            <option value="bride">Bride</option>
                            <option value="both">Both</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <div class="section-card h-100">
                            <h6 class="text-info fw-bold mb-3">Groom</h6>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input v-model="editForm.groom.first_name" type="text" class="form-control glass-input" placeholder="First name">
                                </div>
                                <div class="col-4">
                                    <input v-model="editForm.groom.middle_name" type="text" class="form-control glass-input" placeholder="Middle name">
                                </div>
                                <div class="col-4">
                                    <input v-model="editForm.groom.last_name" type="text" class="form-control glass-input" placeholder="Last name">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="section-card h-100">
                            <h6 class="text-info fw-bold mb-3">Bride</h6>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input v-model="editForm.bride.first_name" type="text" class="form-control glass-input" placeholder="First name">
                                </div>
                                <div class="col-4">
                                    <input v-model="editForm.bride.middle_name" type="text" class="form-control glass-input" placeholder="Middle name">
                                </div>
                                <div class="col-4">
                                    <input v-model="editForm.bride.last_name" type="text" class="form-control glass-input" placeholder="Last name">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-outline-light px-4" @click="closeEditModal">Cancel</button>
                    <button type="submit" class="btn btn-info px-4 text-dark fw-bold" :disabled="isSavingEdit">
                        <span v-if="isSavingEdit">Saving...</span>
                        <span v-else>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2';
import api from '../../controller/api';
import { getApplicants, viewApplicants } from '../../controller/MarriageLicense';

export default {
    data() {
        return {
            applications: [],
            status: "all",
            search: "",
            page: 1,
            totalPages: 0,
            totalResults: 0,
            order: "desc",
            isLoading: false,
            showApplicantsModal: false,
            control_number: "",
            applicant: [],
            groomDocuments: [],
            brideDocuments: [],
            selectedApp: null,
            showApplicantDocuments: false,
            activeTab: "groom",
            showDocument: false,
            currentFilePath: '',
            currentFileIsPDF: false,
            showEditModal: false,
            isSavingEdit: false,
            editForm: {
                id: null,
                control_number: '',
                status: 'pending',
                phone_number: '',
                foreigner_type: '',
                groom: {
                    first_name: '',
                    middle_name: '',
                    last_name: '',
                },
                bride: {
                    first_name: '',
                    middle_name: '',
                    last_name: '',
                }
            }
        };
    },
    computed: {
        allDocuments() {
            if (!this.selectedApp) return [];
            return [...this.selectedApp.groomDocuments, ...this.selectedApp.brideDocuments];
        },
    },
    methods: {
        goToTrash() {
            this.$router.push('/Admin/Trash');
        },
        async openViewApplicants(app) {
            const response = await viewApplicants(app.id, app.control_number)
            this.applicant = response.data.applicants;
            this.control_number = response.data.applicants[0].control_number;
            this.groomDocuments = response.data.groomDocuments;
            this.brideDocuments = response.data.brideDocuments;
            this.showApplicantsModal = true;
        },
        closeViewApplicants() {
            this.showApplicantsModal = false;
        },
        async openEditModal(app) {
            try {
                const response = await viewApplicants(app.id, app.control_number);
                const applicants = Array.isArray(response?.data?.applicants) ? response.data.applicants : [];
                const groom = applicants.find((person) => person.applicant_type === 'groom') || {};
                const bride = applicants.find((person) => person.applicant_type === 'bride') || {};

                this.editForm = {
                    id: app.id,
                    control_number: app.control_number,
                    status: app.status || 'pending',
                    phone_number: app.phone_number || '',
                    foreigner_type: app.foreigner_type || '',
                    groom: {
                        first_name: groom.first_name || '',
                        middle_name: groom.middle_name || '',
                        last_name: groom.last_name || '',
                    },
                    bride: {
                        first_name: bride.first_name || '',
                        middle_name: bride.middle_name || '',
                        last_name: bride.last_name || '',
                    }
                };

                this.showEditModal = true;
            } catch (error) {
                Swal.fire('Error', 'Unable to load application for editing.', 'error');
            }
        },
        closeEditModal() {
            if (this.isSavingEdit) return;
            this.showEditModal = false;
        },
        async saveApplicationEdit() {
            this.isSavingEdit = true;

            try {
                const payload = {
                    status: this.editForm.status,
                    phone_number: this.editForm.phone_number,
                    foreigner_type: this.editForm.foreigner_type || null,
                    groom: this.editForm.groom,
                    bride: this.editForm.bride,
                };

                const response = await api.patch(`/applications/${this.editForm.id}`, payload);

                await Swal.fire({
                    title: 'Updated',
                    text: response?.data?.message || 'Application updated successfully.',
                    icon: 'success',
                    background: '#1e293b',
                    color: '#fff'
                });

                this.showEditModal = false;
                await this.fetchApplications();
            } catch (error) {
                Swal.fire({
                    title: 'Update Failed',
                    text: error.response?.data?.message || 'Unable to update application.',
                    icon: 'error',
                    background: '#1e293b',
                    color: '#fff'
                });
            } finally {
                this.isSavingEdit = false;
            }
        },
        async fetchApplications() {
            this.isLoading = true;
            try {
                const response = await getApplicants(this.status, this.search, this.page, this.order);
                this.applications = response.data.data.data.map(app => ({
                    id: app.id,
                    control_number: app.control_number,
                    status: app.status,
                    dateApplied: app.created_at,
                    coupleNames: app.applicant_names,
                    phone_number: app.phone_number || '',
                    foreigner_type: app.foreigner_type || ''
                }));
                this.totalPages = response.data.data.last_page;
                this.totalResults = response.data.data.total;
            } catch (error) {
                console.error("Fetch error:", error);
            } finally {
                this.isLoading = false;
            }
        },
        resetFilters() {
            this.search = "";
            this.status = "all";
            this.page = 1;
            this.fetchApplications();
        },
        changePage(newPage) {
            if (newPage >= 1 && newPage <= this.totalPages) {
                this.page = newPage;
                this.fetchApplications();
            }
        },
        getStatusClass(status) {
            const base = "badge glass-pill px-3 py-2 ";
            const s = status ? status.toLowerCase() : '';
            if (s === 'approved') return base + "status-approved";
            if (s === 'issued') return base + "status-issued";
            if (s === 'rejected') return base + "status-rejected";
            return base + "status-pending";
        },
        async moveToTrash(app) {
            const result = await Swal.fire({
                title: 'Move to trash?',
                text: `This will move ${app.control_number} to Trash.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                background: '#0f172a',
                color: '#fff',
            });

            if (!result.isConfirmed) {
                return;
            }

            try {
                await api.delete(`/applications/${app.id}`);
                this.applications = this.applications.filter(item => item.id !== app.id);
                Swal.fire({
                    title: 'Moved to Trash',
                    text: 'Application moved to trash.',
                    icon: 'success',
                    background: '#0f172a',
                    color: '#fff'
                });
            } catch (error) {
                Swal.fire('Error', 'Failed to move application to trash.', 'error');
            }
        }
    },
    mounted() {
        this.fetchApplications();
    },
    watch: {
        status() {
            this.page = 1;
            this.fetchApplications();
        },
        order() {
            this.page = 1;
            this.fetchApplications();
        },
        search() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.page = 1;
                this.fetchApplications();
            }, 500);
        }
    },
};
</script>

<style scoped>
.glass-input-group {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 12px;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}

.glass-addon {
    background: transparent !important;
    border: none !important;
    color: rgba(255, 255, 255, 0.6) !important;
}

.glass-input {
    background: transparent !important;
    color: #fff !important;
    border: 1px solid rgba(255, 255, 255, 0.18) !important;
    border-radius: 12px;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}

.glass-input:focus {
    border-color: rgba(13, 202, 240, 0.6) !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 202, 240, 0.2);
}

.glass-input::placeholder {
    color: rgba(255, 255, 255, 0.55);
}

.form-select.glass-input option {
    background: #0f172a;
    color: #fff;
}

.glass-pagination .page-link {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: white;
    margin: 0 5px;
    border-radius: 8px;
    transition: 0.3s;
}

.glass-pagination .page-item.active .page-link {
    background: #0dcaf0;
    color: #000;
    border-color: #0dcaf0;
    font-weight: bold;
}

.glass-pagination .page-item.disabled .page-link {
    background: rgba(255, 255, 255, 0.05);
    color: rgba(255, 255, 255, 0.3);
}

.glass-pagination .page-link:hover:not(.active) {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.content-overlay {
    min-height: 100vh;
}

.glass-table {
    --bs-table-bg: transparent !important;
    border-collapse: separate !important;
    border-spacing: 0 15px !important;
    position: relative;
    z-index: 1;
}

.glass-row {
    background: rgba(255, 255, 255, 0.07) !important;
    backdrop-filter: blur(20px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.glass-row:hover {
    background: rgba(255, 255, 255, 0.12) !important;
    transform: translateY(-3px);
}

.btn-action-glass {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: white;
    border-radius: 12px;
    padding: 8px 18px;
    transition: 0.3s;
}

.btn-action-glass:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
    transform: scale(1.05);
}

.glass-pill {
    background: rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(4px);
    font-weight: 600;
}

.status-pending {
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.status-approved {
    color: #0dfaf0;
    border: 1px solid rgba(13, 250, 240, 0.3);
}

.status-issued {
    color: #0d6efd;
    border: 1px solid rgba(13, 110, 253, 0.3);
}

.status-rejected {
    color: #ff4d4d;
    border: 1px solid rgba(255, 77, 77, 0.3);
}

.text-shadow-heavy {
    text-shadow: 0 4px 15px rgba(0, 0, 0, 0.7);
}

.x-small {
    font-size: 0.75rem;
}

.ls-1 {
    letter-spacing: 1px;
}

.transition {
    transition: all 0.3s ease;
}

.modal-overlay-custom {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.7);
    backdrop-filter: blur(12px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
}

.modal-body-custom {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.2);
    width: 95%;
    max-width: 900px;
    max-height: 90vh;
    overflow-y: auto;
    color: white;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

.applicant-glass-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: transform 0.2s ease;
}

.section-card {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 18px;
    padding: 1rem;
}

.groom-accent {
    border-left: 4px solid #60a5fa !important;
}

.groom-accent .type-pill {
    background: rgba(96, 165, 250, 0.15);
    color: #93c5fd;
}

.bride-accent {
    border-left: 4px solid #f472b6 !important;
}

.bride-accent .type-pill {
    background: rgba(244, 114, 182, 0.15);
    color: #fbcfe8;
}
</style>
