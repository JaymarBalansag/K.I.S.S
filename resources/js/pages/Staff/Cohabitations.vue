<template>
    <main class="content-overlay">
        <div class="container py-5 mt-5">
            <div class="row justify-content-center text-center mb-5 mt-4">
                <div class="col-lg-9 col-xl-8">
                    <span
                        class="badge bg-primary bg-opacity-75 rounded-pill px-4 py-2 mb-3 shadow-sm text-uppercase fw-bold animate__animated animate__fadeInDown">
                        Cohabitation Requests
                    </span>
                    <h2 class="text-white fw-bold text-shadow-heavy">Cohabitation Registry</h2>
                </div>
            </div>

            <div class="row g-3 mb-4 animate__animated animate__fadeIn">
                <div class="col-md-6">
                    <div class="input-group glass-input-group">
                        <span class="input-group-text glass-addon border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" v-model="search" class="form-control glass-input border-start-0 ps-0"
                            placeholder="Search by Control Number, Residence, Name, or ID...">
                    </div>
                </div>

                <div class="col-md-3">
                    <select v-model="order" class="form-select glass-input">
                        <option value="desc">Newest First</option>
                        <option value="asc">Oldest First</option>
                    </select>
                </div>

                <div class="col-md-3 text-md-end">
                    <button class="btn btn-action-glass w-100 text-white" @click="resetFilters">
                        <i class="bi bi-x-circle me-1"></i> Clear
                    </button>
                </div>
            </div>

            <div v-if="isLoading" class="text-center text-white-50 py-5">
                <div class="spinner-border text-info" role="status"></div>
                <div class="mt-2">Loading cohabitation records…</div>
            </div>

            <div v-else class="staff-content animate__animated animate__fadeInUp">
                <div v-if="records.length === 0" class="glass-empty-state text-white text-center p-5">
                    <i class="bi bi-inbox fs-1 opacity-75"></i>
                    <h5 class="mt-3 mb-1 fw-bold">No records found</h5>
                    <p class="mb-0 text-white-50">Try adjusting your search keywords.</p>
                </div>

                <div v-else class="table-responsive d-none d-md-block">
                    <table class="table glass-table align-middle">
                        <thead>
                            <tr class="text-uppercase small opacity-75 ls-1">
                                <th class="px-4 py-3 text-white border-0">Control Number</th>
                                <th class="py-3 text-white border-0">Couple</th>
                                <th class="py-3 text-white border-0">Cohab Start</th>
                                <th class="py-3 text-white border-0">Submitted</th>
                                <th class="py-3 text-center text-white border-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="row in records" :key="row.id" class="glass-row transition">
                                <td class="px-4 fw-bold text-white border-0 rounded-start-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-text text-info me-2 small"></i>
                                        {{ row.control_number }}
                                    </div>
                                </td>
                                <td class="px-4 fw-bold text-white border-0">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-people-fill text-danger me-2 small"></i>
                                        {{ row.couple }}
                                    </div>
                                </td>
                                <td class="text-white opacity-75 border-0">{{ formatDate(row.cohabitation_start_date) }}</td>
                                <td class="text-white opacity-75 border-0">{{ formatDateTime(row.created_at) }}</td>
                                <td class="text-center border-0 rounded-end-4 px-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-action-glass text-white" @click="openView(row)">
                                            <i class="bi bi-eye me-1"></i> View
                                        </button>
                                        <button class="btn btn-action-glass text-white" @click="openEdit(row)">
                                            <i class="bi bi-pencil-square me-1"></i> Edit
                                        </button>
                                        <button class="btn btn-action-glass text-white" @click="showPrintComingSoon">
                                            <i class="bi bi-printer me-1"></i> Print
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <nav v-if="totalPages > 1" class="mt-4">
                    <ul class="pagination justify-content-center glass-pagination">
                        <li class="page-item" :class="{ disabled: page <= 1 }">
                            <button class="page-link" @click="changePage(page - 1)">Prev</button>
                        </li>
                        <li class="page-item disabled">
                            <span class="page-link">Page {{ page }} of {{ totalPages }}</span>
                        </li>
                        <li class="page-item" :class="{ disabled: page >= totalPages }">
                            <button class="page-link" @click="changePage(page + 1)">Next</button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- View Modal -->
        <div v-if="showViewModal" class="modal-overlay-custom" @click.self="closeModals">
            <div class="modal-body-custom rounded-4 p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <h4 class="text-white fw-bold mb-1">Cohabitation Details</h4>
                        <div class="text-white-50">
                            {{ selected?.control_number || '—' }}
                        </div>
                    </div>
                    <button class="btn btn-action-glass text-white" @click="closeModals">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div v-if="isModalLoading" class="text-center text-white-50 py-4">
                    <div class="spinner-border text-info" role="status"></div>
                </div>

                <div v-else class="row g-4">
                    <div class="col-12">
                        <div class="glass-panel p-3 p-md-4">
                            <div class="text-white-50 small text-uppercase ls-1 mb-2">Residence</div>
                            <div class="text-white fw-semibold">{{ selected?.residence || '—' }}</div>
                            <div class="text-white-50 small mt-2">
                                Cohabitation start: {{ formatDate(selected?.cohabitation_start_date) }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="glass-panel p-3 p-md-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="text-white fw-bold">Groom</div>
                                <span class="badge glass-pill status-issued">GROOM</span>
                            </div>
                            <div class="text-white-50 small mb-2">{{ fullName(groomPartner) || '—' }}</div>
                            <div class="text-white small">ID Type: <span class="text-white-50">{{ groomPartner?.id_type || '—' }}</span></div>
                            <div class="text-white small">ID Number: <span class="text-white-50">{{ groomPartner?.id_number || '—' }}</span></div>
                            <div class="text-white small">Issued At: <span class="text-white-50">{{ groomPartner?.issued_at || '—' }}</span></div>
                            <div class="text-white small">Issued On: <span class="text-white-50">{{ formatDate(groomPartner?.issued_on) }}</span></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="glass-panel p-3 p-md-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="text-white fw-bold">Bride</div>
                                <span class="badge glass-pill status-approved">BRIDE</span>
                            </div>
                            <div class="text-white-50 small mb-2">{{ fullName(bridePartner) || '—' }}</div>
                            <div class="text-white small">ID Type: <span class="text-white-50">{{ bridePartner?.id_type || '—' }}</span></div>
                            <div class="text-white small">ID Number: <span class="text-white-50">{{ bridePartner?.id_number || '—' }}</span></div>
                            <div class="text-white small">Issued At: <span class="text-white-50">{{ bridePartner?.issued_at || '—' }}</span></div>
                            <div class="text-white small">Issued On: <span class="text-white-50">{{ formatDate(bridePartner?.issued_on) }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div v-if="showEditModal" class="modal-overlay-custom" @click.self="closeModals">
            <div class="modal-body-custom rounded-4 p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <h4 class="text-white fw-bold mb-1">Edit Cohabitation</h4>
                        <div class="text-white-50">
                            {{ selected?.control_number || '—' }}
                        </div>
                    </div>
                    <button class="btn btn-action-glass text-white" @click="closeModals" :disabled="isSaving">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div v-if="isModalLoading" class="text-center text-white-50 py-4">
                    <div class="spinner-border text-info" role="status"></div>
                </div>

                <form v-else @submit.prevent="saveUpdates">
                    <div class="glass-panel p-3 p-md-4 mb-3">
                        <label class="form-label text-white-50 small text-uppercase ls-1">Residence</label>
                        <input v-model.trim="editPayload.residence" type="text" class="form-control glass-input"
                            placeholder="Complete address" required>
                        <div class="text-white-50 small mt-2">
                            Cohabitation start date is read-only in staff management.
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="glass-panel p-3 p-md-4">
                                <div class="text-white fw-bold mb-2">Groom ID Details</div>
                                <div class="mb-2">
                                    <label class="form-label text-white-50 small">ID Type</label>
                                    <input v-model.trim="editPayload.form.groom.id_type" type="text"
                                        class="form-control glass-input" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-white-50 small">ID Number</label>
                                    <input v-model.trim="editPayload.form.groom.id_number" type="text"
                                        class="form-control glass-input" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-white-50 small">Issued At</label>
                                    <input v-model.trim="editPayload.form.groom.issued_at" type="text"
                                        class="form-control glass-input" required>
                                </div>
                                <div>
                                    <label class="form-label text-white-50 small">Issued On</label>
                                    <input v-model="editPayload.form.groom.issued_on" type="date"
                                        class="form-control glass-input" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="glass-panel p-3 p-md-4">
                                <div class="text-white fw-bold mb-2">Bride ID Details</div>
                                <div class="mb-2">
                                    <label class="form-label text-white-50 small">ID Type</label>
                                    <input v-model.trim="editPayload.form.bride.id_type" type="text"
                                        class="form-control glass-input" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-white-50 small">ID Number</label>
                                    <input v-model.trim="editPayload.form.bride.id_number" type="text"
                                        class="form-control glass-input" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-white-50 small">Issued At</label>
                                    <input v-model.trim="editPayload.form.bride.issued_at" type="text"
                                        class="form-control glass-input" required>
                                </div>
                                <div>
                                    <label class="form-label text-white-50 small">Issued On</label>
                                    <input v-model="editPayload.form.bride.issued_on" type="date"
                                        class="form-control glass-input" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-action-glass text-white" @click="closeModals"
                            :disabled="isSaving">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-info text-dark fw-bold px-4" :disabled="isSaving">
                            <span v-if="isSaving" class="spinner-border spinner-border-sm me-2" role="status"
                                aria-hidden="true"></span>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</template>

<script>
import Swal from 'sweetalert2';
import { getCohabitation, listCohabitations, updateCohabitation } from '../../controller/CohabitationManagement';

const emptyEditPayload = () => ({
    residence: '',
    form: {
        groom: { id_type: '', id_number: '', issued_at: '', issued_on: '' },
        bride: { id_type: '', id_number: '', issued_at: '', issued_on: '' },
    }
});

export default {
    name: 'StaffCohabitations',
    data() {
        return {
            isLoading: false,
            isModalLoading: false,
            isSaving: false,
            records: [],
            search: '',
            order: 'desc',
            page: 1,
            totalPages: 1,
            showViewModal: false,
            showEditModal: false,
            selected: null,
            editPayload: emptyEditPayload(),
            searchTimeout: null,
        };
    },
    computed: {
        groomPartner() {
            return this.selected?.partners?.find((p) => p.partner_type === 'groom') || null;
        },
        bridePartner() {
            return this.selected?.partners?.find((p) => p.partner_type === 'bride') || null;
        }
    },
    methods: {
        formatDate(value) {
            if (!value) return '—';
            try {
                return new Date(value).toLocaleDateString();
            } catch {
                return String(value);
            }
        },
        formatDateTime(value) {
            if (!value) return '—';
            try {
                return new Date(value).toLocaleString();
            } catch {
                return String(value);
            }
        },
        fullName(partner) {
            if (!partner) return '';
            return [partner.first_name, partner.middle_name, partner.last_name, partner.suffix].filter(Boolean).join(' ');
        },
        async fetchList() {
            this.isLoading = true;
            try {
                const res = await listCohabitations({
                    search: this.search || undefined,
                    order: this.order,
                    page: this.page,
                });
                const paginated = res?.data?.data;
                const rows = paginated?.data ?? [];
                this.records = rows.map((r) => ({
                    id: r.id,
                    control_number: r.control_number,
                    couple: `${r.groom_name || '—'} & ${r.bride_name || '—'}`,
                    cohabitation_start_date: r.cohabitation_start_date,
                    created_at: r.created_at,
                }));
                this.totalPages = paginated?.last_page ?? 1;
            } catch (e) {
                console.error(e);
                await Swal.fire({
                    title: 'Error',
                    text: 'Failed to load cohabitation records.',
                    icon: 'error',
                    background: '#1e293b',
                    color: '#fff',
                });
            } finally {
                this.isLoading = false;
            }
        },
        resetFilters() {
            this.search = '';
            this.order = 'desc';
            this.page = 1;
            this.fetchList();
        },
        changePage(newPage) {
            if (newPage < 1 || newPage > this.totalPages) return;
            this.page = newPage;
            this.fetchList();
        },
        closeModals() {
            this.showViewModal = false;
            this.showEditModal = false;
            this.selected = null;
            this.editPayload = emptyEditPayload();
        },
        async loadRecord(row) {
            this.isModalLoading = true;
            try {
                const res = await getCohabitation(row.id);
                this.selected = res?.data?.data ?? null;
            } finally {
                this.isModalLoading = false;
            }
        },
        async openView(row) {
            this.showViewModal = true;
            await this.loadRecord(row);
        },
        async openEdit(row) {
            this.showEditModal = true;
            await this.loadRecord(row);
            const groom = this.groomPartner;
            const bride = this.bridePartner;
            this.editPayload = {
                residence: this.selected?.residence || '',
                form: {
                    groom: {
                        id_type: groom?.id_type || '',
                        id_number: groom?.id_number || '',
                        issued_at: groom?.issued_at || '',
                        issued_on: groom?.issued_on || '',
                    },
                    bride: {
                        id_type: bride?.id_type || '',
                        id_number: bride?.id_number || '',
                        issued_at: bride?.issued_at || '',
                        issued_on: bride?.issued_on || '',
                    },
                },
            };
        },
        async saveUpdates() {
            if (!this.selected?.id) return;
            this.isSaving = true;
            try {
                await updateCohabitation(this.selected.id, this.editPayload);
                await Swal.fire({
                    title: 'Saved',
                    text: 'Cohabitation updated successfully.',
                    icon: 'success',
                    background: '#1e293b',
                    color: '#fff',
                });
                this.closeModals();
                await this.fetchList();
            } catch (e) {
                const message = e?.response?.data?.message || 'Update failed.';
                await Swal.fire({
                    title: 'Error',
                    text: message,
                    icon: 'error',
                    background: '#1e293b',
                    color: '#fff',
                });
            } finally {
                this.isSaving = false;
            }
        },
        async showPrintComingSoon() {
            await Swal.fire({
                title: 'Coming soon',
                text: 'Printing is not implemented yet.',
                icon: 'info',
                background: '#1e293b',
                color: '#fff',
                confirmButtonColor: '#0dcaf0',
            });
        },
    },
    mounted() {
        this.fetchList();
    },
    watch: {
        order() {
            this.page = 1;
            this.fetchList();
        },
        search() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.page = 1;
                this.fetchList();
            }, 500);
        }
    }
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
    color: #fff !important;
    border-radius: 12px;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}

.glass-input:focus {
    border-color: rgba(13, 202, 240, 0.6) !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 202, 240, 0.2);
}

.glass-input::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

select.glass-input option {
    background: #0f172a;
    color: #fff;
}

.glass-table {
    --bs-table-bg: transparent !important;
    border-collapse: separate !important;
    border-spacing: 0 15px !important;
}

.glass-row {
    background: rgba(255, 255, 255, 0.07) !important;
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

.glass-empty-state {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 16px;
}

.glass-panel {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 14px;
}

.modal-overlay-custom {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.7);
    backdrop-filter: blur(12px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    padding: 16px;
}

.modal-body-custom {
    width: min(96vw, 980px);
    max-height: 92vh;
    overflow-y: auto;
    background: rgba(30, 41, 59, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.55);
}

.glass-pill {
    background: rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(4px);
    font-weight: 600;
    border: 1px solid rgba(255, 255, 255, 0.12);
}

.status-approved {
    color: #f472b6;
    border: 1px solid rgba(244, 114, 182, 0.25);
}

.status-issued {
    color: #60a5fa;
    border: 1px solid rgba(96, 165, 250, 0.25);
}

.text-shadow-heavy {
    text-shadow: 0 4px 15px rgba(0, 0, 0, 0.7);
}

.ls-1 {
    letter-spacing: 1px;
}

.transition {
    transition: all 0.3s ease;
}

.glass-pagination .page-link {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: white;
    margin: 0 5px;
    border-radius: 8px;
    transition: 0.3s;
}

.glass-pagination .page-item.disabled .page-link {
    background: rgba(255, 255, 255, 0.05);
    color: rgba(255, 255, 255, 0.3);
}

.glass-pagination .page-link:hover {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}
</style>
