<template>
    <div class="sms-page animate__animated animate__fadeIn">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div class="text-white">
                <span class="badge glass-pill text-info rounded-pill px-4 py-2 mb-3 fw-bold">SMS QUEUE</span>
                <h2 class="fw-bold mb-1">SMS Request Management</h2>
                <p class="opacity-75 mb-0">Create, update, review, and remove queued SMS messages for the admin dashboard.</p>
            </div>

            <button class="btn btn-info rounded-pill px-4 fw-bold shadow-sm" @click="openCreateModal">
                <i class="bi bi-plus-lg me-2"></i>New SMS Request
            </button>
        </div>

        <div class="glass-card p-3 p-md-4 mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-lg-5">
                    <label class="form-label text-white opacity-75 small text-uppercase">Search</label>
                    <input
                        v-model.trim="filters.search"
                        type="text"
                        class="form-control glass-input"
                        placeholder="Search phone number or message"
                    >
                </div>

                <div class="col-lg-3">
                    <label class="form-label text-white opacity-75 small text-uppercase">Status</label>
                    <select v-model="filters.status" class="form-select glass-input">
                        <option value="">All statuses</option>
                        <option value="pending">Pending</option>
                        <option value="sent">Sent</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>

                <div class="col-lg-4">
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary rounded-pill px-4 fw-bold flex-grow-1" @click="fetchSmsRequests" :disabled="loading">
                            <i class="bi bi-search me-2"></i>{{ loading ? 'Loading...' : 'Apply Filters' }}
                        </button>
                        <button class="btn btn-outline-light rounded-pill px-4" @click="resetFilters" :disabled="loading">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4" v-for="card in summaryCards" :key="card.label">
                <div class="glass-card p-4 h-100 text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-uppercase opacity-50 fw-bold">{{ card.label }}</div>
                            <div class="display-6 fw-bold">{{ card.value }}</div>
                        </div>
                        <div :class="`summary-icon bg-${card.color} bg-opacity-25 text-${card.color}`">
                            <i :class="`bi ${card.icon}`"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card p-3 p-md-4">
            <div v-if="loading" class="text-center py-5 text-white">
                <div class="spinner-border text-info" role="status"></div>
                <p class="mt-3 mb-0 opacity-75">Loading SMS requests...</p>
            </div>

            <template v-else-if="smsRequests.length">
                <div class="table-responsive d-none d-lg-block">
                    <table class="table align-middle sms-table mb-0">
                        <thead>
                            <tr class="text-uppercase small opacity-75 ls-1">
                                <th class="text-white border-0">Phone Number</th>
                                <th class="text-white border-0">Message</th>
                                <th class="text-white border-0">Status</th>
                                <th class="text-white border-0">Created</th>
                                <th class="text-white border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="request in smsRequests" :key="request.id" class="glass-row">
                                <td class="text-white fw-semibold border-0">{{ request.phone_number }}</td>
                                <td class="text-white border-0">
                                    <div class="message-preview">{{ request.message }}</div>
                                </td>
                                <td class="border-0">
                                    <span :class="statusClass(request.status)">{{ request.status }}</span>
                                </td>
                                <td class="text-white opacity-75 border-0">{{ formatDate(request.created_at) }}</td>
                                <td class="border-0 text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-action-glass text-info" @click="openEditModal(request)">
                                            <i class="bi bi-pencil-square me-1"></i>Edit
                                        </button>
                                        <button class="btn btn-action-glass text-danger" @click="removeSmsRequest(request)">
                                            <i class="bi bi-trash3 me-1"></i>Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-lg-none">
                    <div v-for="request in smsRequests" :key="`mobile-${request.id}`" class="glass-row rounded-4 p-3 mb-3 text-white">
                        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                            <div>
                                <div class="fw-bold">{{ request.phone_number }}</div>
                                <small class="opacity-75">{{ formatDate(request.created_at) }}</small>
                            </div>
                            <span :class="statusClass(request.status)">{{ request.status }}</span>
                        </div>
                        <p class="opacity-90 mb-3">{{ request.message }}</p>
                        <div class="d-flex gap-2">
                            <button class="btn btn-action-glass text-info flex-grow-1" @click="openEditModal(request)">
                                <i class="bi bi-pencil-square me-1"></i>Edit
                            </button>
                            <button class="btn btn-action-glass text-danger flex-grow-1" @click="removeSmsRequest(request)">
                                <i class="bi bi-trash3 me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <div v-else class="text-center py-5 text-white opacity-75">
                <i class="bi bi-chat-left-text display-4 d-block mb-3"></i>
                <h4 class="fw-bold">No SMS requests found</h4>
                <p class="mb-3">Try changing the filters or create the first queued message.</p>
                <button class="btn btn-info rounded-pill px-4 fw-bold" @click="openCreateModal">
                    Create SMS Request
                </button>
            </div>
        </div>

        <div v-if="showModal" class="modal fade show d-block modal-overlay" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content glass-modal border-0">
                    <div class="modal-header border-white border-opacity-10">
                        <div>
                            <h5 class="modal-title text-white fw-bold mb-1">{{ isEditing ? 'Edit SMS Request' : 'Create SMS Request' }}</h5>
                            <p class="text-white opacity-75 mb-0 small">Manage the SMS queue directly from the admin dashboard.</p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" @click="closeModal"></button>
                    </div>

                    <form @submit.prevent="submitForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label text-white">Phone Number</label>
                                <input
                                    v-model.trim="form.phone_number"
                                    type="text"
                                    class="form-control glass-input"
                                    placeholder="09XXXXXXXXX"
                                    maxlength="30"
                                >
                                <small v-if="errors.phone_number" class="text-danger">{{ errors.phone_number[0] }}</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white">Message</label>
                                <textarea
                                    v-model.trim="form.message"
                                    rows="5"
                                    class="form-control glass-input"
                                    placeholder="Enter the SMS message"
                                    maxlength="1000"
                                ></textarea>
                                <div class="d-flex justify-content-between mt-2">
                                    <small v-if="errors.message" class="text-danger">{{ errors.message[0] }}</small>
                                    <small class="text-white opacity-50 ms-auto">{{ form.message.length }}/1000</small>
                                </div>
                            </div>

                            <div>
                                <label class="form-label text-white">Status</label>
                                <select v-model="form.status" class="form-select glass-input">
                                    <option value="pending">Pending</option>
                                    <option value="sent">Sent</option>
                                    <option value="failed">Failed</option>
                                </select>
                                <small v-if="errors.status" class="text-danger">{{ errors.status[0] }}</small>
                            </div>
                        </div>

                        <div class="modal-footer border-white border-opacity-10">
                            <button type="button" class="btn btn-outline-light rounded-pill px-4" @click="closeModal" :disabled="saving">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-info rounded-pill px-4 fw-bold" :disabled="saving">
                                <i class="bi bi-save me-2"></i>{{ saving ? 'Saving...' : (isEditing ? 'Update Request' : 'Create Request') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2';
import {
    createSmsRequest,
    deleteSmsRequest,
    readSmsRequests,
    updateSmsRequest,
} from '../../controller/SmsRequests';

const emptyForm = () => ({
    id: null,
    phone_number: '',
    message: '',
    status: 'pending',
});

export default {
    name: 'AdminSmsRequests',
    data() {
        return {
            loading: true,
            saving: false,
            showModal: false,
            isEditing: false,
            smsRequests: [],
            filters: {
                search: '',
                status: '',
            },
            form: emptyForm(),
            errors: {},
        };
    },
    computed: {
        summaryCards() {
            const pending = this.smsRequests.filter((item) => item.status === 'pending').length;
            const sent = this.smsRequests.filter((item) => item.status === 'sent').length;
            const failed = this.smsRequests.filter((item) => item.status === 'failed').length;

            return [
                { label: 'Pending Queue', value: pending, color: 'warning', icon: 'bi-hourglass-split' },
                { label: 'Sent Messages', value: sent, color: 'success', icon: 'bi-check2-circle' },
                { label: 'Failed Messages', value: failed, color: 'danger', icon: 'bi-exclamation-triangle' },
            ];
        },
    },
    created() {
        this.fetchSmsRequests();
    },
    methods: {
        async fetchSmsRequests() {
            this.loading = true;

            try {
                const response = await readSmsRequests(this.filters);
                const payload = response?.data?.data || response?.data || [];
                this.smsRequests = Array.isArray(payload) ? payload : [];
            } catch (error) {
                this.smsRequests = [];
                Swal.fire('Error', 'Unable to load SMS requests.', 'error');
            } finally {
                this.loading = false;
            }
        },
        resetFilters() {
            this.filters.search = '';
            this.filters.status = '';
            this.fetchSmsRequests();
        },
        openCreateModal() {
            this.isEditing = false;
            this.errors = {};
            this.form = emptyForm();
            this.showModal = true;
        },
        openEditModal(request) {
            this.isEditing = true;
            this.errors = {};
            this.form = {
                id: request.id,
                phone_number: request.phone_number,
                message: request.message,
                status: request.status || 'pending',
            };
            this.showModal = true;
        },
        closeModal() {
            if (this.saving) return;
            this.showModal = false;
            this.errors = {};
            this.form = emptyForm();
        },
        async submitForm() {
            this.saving = true;
            this.errors = {};

            try {
                if (this.isEditing) {
                    await updateSmsRequest(this.form.id, {
                        phone_number: this.form.phone_number,
                        message: this.form.message,
                        status: this.form.status,
                    });
                } else {
                    await createSmsRequest({
                        phone_number: this.form.phone_number,
                        message: this.form.message,
                        status: this.form.status,
                    });
                }

                await this.fetchSmsRequests();
                this.closeModal();

                Swal.fire({
                    title: this.isEditing ? 'Updated' : 'Created',
                    text: `SMS request ${this.isEditing ? 'updated' : 'created'} successfully.`,
                    icon: 'success',
                    background: '#0f172a',
                    color: '#fff',
                });
            } catch (error) {
                this.errors = error?.response?.data?.errors || {};

                if (!Object.keys(this.errors).length) {
                    Swal.fire('Error', 'Unable to save the SMS request.', 'error');
                }
            } finally {
                this.saving = false;
            }
        },
        async removeSmsRequest(request) {
            const result = await Swal.fire({
                title: 'Delete SMS request?',
                text: `This will remove the queued message for ${request.phone_number}.`,
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
                await deleteSmsRequest(request.id);
                this.smsRequests = this.smsRequests.filter((item) => item.id !== request.id);

                Swal.fire({
                    title: 'Deleted',
                    text: 'SMS request removed from the queue.',
                    icon: 'success',
                    background: '#0f172a',
                    color: '#fff',
                });
            } catch (error) {
                Swal.fire('Error', 'Unable to delete the SMS request.', 'error');
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
        statusClass(status) {
            const base = 'badge rounded-pill px-3 py-2 text-capitalize ';

            if (status === 'sent') return base + 'bg-success-subtle text-success border border-success border-opacity-25';
            if (status === 'failed') return base + 'bg-danger-subtle text-danger border border-danger border-opacity-25';

            return base + 'bg-warning-subtle text-warning border border-warning border-opacity-25';
        },
    },
};
</script>

<style scoped>
.glass-card,
.glass-modal {
    background: rgba(255, 255, 255, 0.05) !important;
    backdrop-filter: blur(15px) saturate(160%);
    -webkit-backdrop-filter: blur(15px) saturate(160%);
    border: 1px solid rgba(255, 255, 255, 0.12);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
}

.glass-pill {
    background: rgba(13, 202, 240, 0.14);
    border: 1px solid rgba(13, 202, 240, 0.3);
}

.glass-input {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #fff;
}

.glass-input:focus {
    background: rgba(255, 255, 255, 0.12);
    color: #fff;
    border-color: rgba(13, 202, 240, 0.5);
    box-shadow: 0 0 0 0.25rem rgba(13, 202, 240, 0.15);
}

.glass-input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.form-select.glass-input option {
    background: #0f172a;
    color: #fff;
}

.sms-table {
    --bs-table-bg: transparent !important;
    border-collapse: separate;
    border-spacing: 0 12px;
}

.glass-row {
    background: rgba(255, 255, 255, 0.06) !important;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.glass-row:hover {
    background: rgba(255, 255, 255, 0.1) !important;
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

.message-preview {
    max-width: 430px;
    white-space: normal;
    word-break: break-word;
}

.summary-icon {
    width: 54px;
    height: 54px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
}

.ls-1 {
    letter-spacing: 1px;
}

.modal-overlay {
    background: rgba(15, 23, 42, 0.72);
    backdrop-filter: blur(6px);
}

@media (max-width: 991px) {
    .message-preview {
        max-width: none;
    }
}
</style>
