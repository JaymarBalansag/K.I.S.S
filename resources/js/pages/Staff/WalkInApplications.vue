<template>
    <main class="content-overlay">
        <div class="container py-5 mt-5">
            <div class="row justify-content-center text-center mb-5 mt-4">
                <div class="col-lg-10 col-xl-9">
                    <span
                        class="badge bg-primary bg-opacity-75 rounded-pill px-4 py-2 mb-3 shadow-sm text-uppercase fw-bold animate__animated animate__fadeInDown">
                        Staff Walk-In Applications
                    </span>
                    <h2 class="text-white fw-bold text-shadow-heavy">Manual Marriage License Encoding</h2>
                    <p class="text-white opacity-75 mb-0">
                        Encode applications for walk-in clients, save the record, and print the license form right away.
                    </p>
                </div>
            </div>

            <form class="glass-panel rounded-5 p-4 p-lg-5 mb-4" @submit.prevent="submitForm">
                <div class="walkthrough-grid">
                    <aside class="guide-panel">
                        <div class="guide-card">
                            <p class="guide-eyebrow">How This Works</p>
                            <h4 class="text-white fw-bold mb-3">Encode one person at a time</h4>
                            <div class="guide-step">
                                <span>1</span>
                                <p>Start with the contact number and the groom details.</p>
                            </div>
                            <div class="guide-step">
                                <span>2</span>
                                <p>Switch to the bride tab after the first section is complete.</p>
                            </div>
                            <div class="guide-step">
                                <span>3</span>
                                <p>Save once both sides are complete, then print right away.</p>
                            </div>
                        </div>

                        <div class="guide-card compact">
                            <p class="guide-eyebrow">Progress</p>
                            <button
                                v-for="personKey in personOrder"
                                :key="`switch-${personKey}`"
                                type="button"
                                class="person-switch-btn"
                                :class="{ active: activePerson === personKey }"
                                @click="activePerson = personKey"
                            >
                                <div>
                                    <strong>{{ personLabels[personKey] }}</strong>
                                    <small>{{ completionLabel(personKey) }}</small>
                                </div>
                                <i class="bi" :class="personCompletion(personKey) ? 'bi-check-circle-fill text-success' : 'bi-chevron-right'"></i>
                            </button>
                        </div>
                    </aside>

                    <div class="form-panel">
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="contact-card rounded-4 p-3 p-md-4">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                                        <div>
                                            <p class="guide-eyebrow mb-2">Shared Details</p>
                                            <h5 class="text-white fw-bold mb-1">Contact Number</h5>
                                            <p class="text-white-50 mb-0">Optional, but helpful when the couple wants updates later.</p>
                                        </div>
                                        <div class="contact-input-wrap">
                                            <input v-model="form.phone_number" type="text" class="form-control glass-input"
                                                placeholder="09XXXXXXXXX">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="person-tabs">
                                    <button
                                        v-for="personKey in personOrder"
                                        :key="`tab-${personKey}`"
                                        type="button"
                                        class="person-tab"
                                        :class="{ active: activePerson === personKey }"
                                        @click="activePerson = personKey"
                                    >
                                        <span>{{ personLabels[personKey] }}</span>
                                        <small>{{ personCompletion(activePerson === personKey ? activePerson : personKey) ? 'Ready to save' : 'Needs details' }}</small>
                                    </button>
                                </div>
                            </div>

                            <div class="col-12">
                                <section class="person-card rounded-5 p-4">
                                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                                        <div>
                                            <span class="person-pill">{{ personLabels[activePerson] }}</span>
                                            <h4 class="text-white fw-bold mt-3 mb-1">{{ personLabels[activePerson] }} Information</h4>
                                            <p class="text-white-50 mb-0">Required fields come first. Optional printable items are tucked at the bottom.</p>
                                        </div>
                                        <div class="completion-pill" :class="{ ready: personCompletion(activePerson) }">
                                            {{ completionLabel(activePerson) }}
                                        </div>
                                    </div>

                                    <div class="section-card">
                                        <div class="section-header">
                                            <h6>Identity</h6>
                                            <p>Name, birthday, age, and civil profile.</p>
                                        </div>
                                        <div class="row g-3">
                                            <div v-for="field in primaryFields" :key="`${activePerson}-${field.key}`" :class="field.col">
                                                <label class="form-label text-white fw-semibold">
                                                    {{ field.label }}<span v-if="field.required" class="text-danger ms-1">*</span>
                                                </label>
                                                <input v-if="field.type !== 'select'" v-model="form[activePerson][field.key]"
                                                    :type="field.type" class="form-control glass-input"
                                                    :placeholder="field.placeholder || field.label"
                                                    @input="field.key === 'birth_date' ? syncAge(activePerson) : null">
                                                <select v-else v-model="form[activePerson][field.key]" class="form-select glass-input">
                                                    <option value="" disabled>Select {{ field.label }}</option>
                                                    <option v-for="option in field.options" :key="option" :value="option">{{ option }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-card">
                                        <div class="section-header">
                                            <h6>Birthplace & Residence</h6>
                                            <p>Address details used in the printable form.</p>
                                        </div>
                                        <div class="row g-3">
                                            <div v-for="field in locationFields" :key="`${activePerson}-${field.key}`" :class="field.col">
                                                <label class="form-label text-white fw-semibold">
                                                    {{ field.label }}<span v-if="field.required" class="text-danger ms-1">*</span>
                                                </label>
                                                <textarea v-if="field.type === 'textarea'" v-model="form[activePerson][field.key]"
                                                    class="form-control glass-input" rows="3"
                                                    :placeholder="field.placeholder || field.label"></textarea>
                                                <input v-else v-model="form[activePerson][field.key]" type="text" class="form-control glass-input"
                                                    :placeholder="field.placeholder || field.label">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-card">
                                        <div class="section-header">
                                            <h6>Parents</h6>
                                            <p>Required for the printed marriage license application.</p>
                                        </div>
                                        <div class="row g-3">
                                            <div v-for="field in parentFields" :key="`${activePerson}-${field.key}`" :class="field.col">
                                                <label class="form-label text-white fw-semibold">
                                                    {{ field.label }}<span v-if="field.required" class="text-danger ms-1">*</span>
                                                </label>
                                                <textarea v-if="field.type === 'textarea'" v-model="form[activePerson][field.key]"
                                                    class="form-control glass-input" rows="3"
                                                    :placeholder="field.placeholder || field.label"></textarea>
                                                <input v-else v-model="form[activePerson][field.key]" type="text" class="form-control glass-input"
                                                    :placeholder="field.placeholder || field.label">
                                            </div>
                                        </div>
                                    </div>

                                    <details class="optional-section">
                                        <summary>
                                            <div class="optional-summary-text">
                                                <span class="optional-kicker">Click to expand</span>
                                                <strong>Optional Printable Details</strong>
                                                <small>Open this section for previous marriage details, ID details, and extra notes.</small>
                                            </div>
                                            <i class="bi bi-chevron-down optional-chevron"></i>
                                        </summary>
                                        <div class="row g-3">
                                            <div v-for="field in optionalFields" :key="`${activePerson}-${field.key}`" :class="field.col">
                                                <label class="form-label text-white fw-semibold">{{ field.label }}</label>
                                                <textarea v-if="field.type === 'textarea'" v-model="form[activePerson][field.key]"
                                                    class="form-control glass-input" rows="3"
                                                    :placeholder="field.placeholder || field.label"></textarea>
                                                <input v-else v-model="form[activePerson][field.key]" :type="field.type"
                                                    class="form-control glass-input" :placeholder="field.placeholder || field.label">
                                            </div>
                                        </div>

                                        <div class="optional-subtitle">Person Giving Consent</div>
                                        <div class="row g-3">
                                            <div v-for="field in consentFields" :key="`${activePerson}-${field.key}`" :class="field.col">
                                                <label class="form-label text-white fw-semibold">{{ field.label }}</label>
                                                <textarea v-if="field.type === 'textarea'" v-model="form[activePerson][field.key]"
                                                    class="form-control glass-input" rows="3"
                                                    :placeholder="field.placeholder || field.label"></textarea>
                                                <input v-else v-model="form[activePerson][field.key]" :type="field.type"
                                                    class="form-control glass-input" :placeholder="field.placeholder || field.label">
                                            </div>
                                        </div>
                                    </details>

                                    <div class="form-footer">
                                        <button
                                            v-if="activePerson === 'groom'"
                                            type="button"
                                            class="btn btn-outline-light px-4 py-2 rounded-pill"
                                            @click="activePerson = 'bride'"
                                        >
                                            Continue to Bride
                                        </button>
                                        <div class="footer-actions">
                                            <button type="button" class="btn btn-outline-light px-4 py-2 rounded-pill" @click="resetForm" :disabled="isSaving">
                                                Reset
                                            </button>
                                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold" :disabled="isSaving">
                                                <span v-if="isSaving" class="spinner-border spinner-border-sm me-2"></span>
                                                Save Manual Application
                                            </button>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <section class="glass-panel rounded-5 p-4 p-lg-5">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <div>
                        <h4 class="text-white fw-bold mb-1">Recent Walk-In Records</h4>
                        <p class="text-white-50 mb-0">Quick reprint access for recently encoded applications.</p>
                    </div>
                    <div class="input-group search-group">
                        <span class="input-group-text glass-addon border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input v-model="search" type="text" class="form-control glass-input border-start-0 ps-0"
                            placeholder="Search control number or name...">
                    </div>
                </div>

                <div v-if="records.length" class="table-responsive">
                    <table class="table glass-table align-middle mb-0">
                        <thead>
                            <tr class="text-uppercase small opacity-75 ls-1">
                                <th class="text-white border-0">Control Number</th>
                                <th class="text-white border-0">Couple</th>
                                <th class="text-white border-0">Created</th>
                                <th class="text-white border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="record in records" :key="record.id" class="glass-row">
                                <td class="text-white fw-bold border-0">{{ record.control_number }}</td>
                                <td class="text-white border-0">{{ record.couple_names }}</td>
                                <td class="text-white-50 border-0">{{ formatDate(record.created_at) }}</td>
                                <td class="border-0 text-center">
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <button class="btn btn-action-glass text-info" @click="viewRecord(record.id)">
                                            <i class="bi bi-eye-fill me-1"></i> View
                                        </button>
                                        <button class="btn btn-action-glass text-warning" @click="openPrintModal(record)">
                                            <i class="bi bi-printer-fill me-1"></i> Print
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="text-center py-5">
                    <i class="bi bi-folder2-open display-1 text-white opacity-25"></i>
                    <h5 class="text-white opacity-75 mt-3">No walk-in applications yet</h5>
                    <p class="text-white-50 mb-0">Saved records will appear here after the first manual encoding.</p>
                </div>
            </section>
        </div>
    </main>

    <div v-if="showDetailsModal && selectedRecord" class="modal-overlay-custom">
        <div class="modal-body-custom rounded-5 shadow-2xl p-0 border border-white border-opacity-20">
            <div class="modal-glass-header p-4 d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-info bg-opacity-10 text-info text-uppercase mb-2 x-small ls-1 px-3 border border-info border-opacity-20">
                        Walk-In Record
                    </span>
                    <h4 class="fw-bold mb-0 text-white">{{ selectedRecord.control_number }}</h4>
                </div>
                <button class="btn-close btn-close-white opacity-50" @click="closeDetailsModal"></button>
            </div>

            <div class="p-4">
                <div class="row g-4">
                    <div v-for="personKey in personOrder" :key="`details-${personKey}`" class="col-md-6">
                        <div class="detail-card rounded-4 p-4 h-100">
                            <h5 class="text-white fw-bold mb-3">{{ personLabels[personKey] }}</h5>
                            <div class="detail-line"><span>Name</span>{{ fullName(selectedRecord[personKey]) }}</div>
                            <div class="detail-line"><span>Birth Date</span>{{ selectedRecord[personKey].birth_date || 'N/A' }}</div>
                            <div class="detail-line"><span>Age</span>{{ selectedRecord[personKey].age }}</div>
                            <div class="detail-line"><span>Citizenship</span>{{ selectedRecord[personKey].citizenship }}</div>
                            <div class="detail-line"><span>Civil Status</span>{{ selectedRecord[personKey].civil_status }}</div>
                            <div class="detail-line"><span>Residence</span>{{ selectedRecord[personKey].residence_address }}</div>
                            <div class="detail-line"><span>Consent By</span>{{ selectedRecord[personKey].consent_name || 'NOT APPLICABLE' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="showPrintModal" class="modal-overlay">
        <div class="print-modal-content">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-white fw-bold mb-0">Print Walk-In Application</h5>
                <button class="btn-close btn-close-white" @click="closePrintModal" :disabled="isPrinting"></button>
            </div>
            <div class="print-preview-shell">
                <iframe ref="printPreviewFrame" :src="printPreviewSrc" class="print-pdf-frame"
                    @load="handlePrintPreviewLoaded"></iframe>
            </div>
            <div class="print-modal-actions">
                <button class="btn btn-warning fw-bold px-4" @click="printIframe" :disabled="isPrinting || isPrintPreviewLoading">
                    <span v-if="isPrinting" class="spinner-border spinner-border-sm me-2"></span>
                    <i v-else class="bi bi-printer-fill me-1"></i>
                    Print
                </button>
                <button class="btn btn-outline-light fw-bold px-4" @click="closePrintModal" :disabled="isPrinting">Close</button>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2';
import {
    getManualMarriageLicenseApplications,
    storeManualMarriageLicenseApplication,
    viewManualMarriageLicenseApplication
} from '../../controller/ManualMarriageLicenseApplications';

const createPerson = (sex = '') => ({
    first_name: '',
    middle_name: '',
    last_name: '',
    birth_date: '',
    age: '',
    birth_city: '',
    birth_province: '',
    birth_country: 'Philippines',
    sex,
    citizenship: 'Filipino',
    religion: '',
    civil_status: 'Single',
    residence_address: '',
    dissolution_details: '',
    dissolution_place: '',
    dissolution_date: '',
    relationship_degree: '',
    father_first_name: '',
    father_middle_name: '',
    father_last_name: '',
    father_citizenship: 'Filipino',
    father_residence: '',
    mother_first_name: '',
    mother_middle_name: '',
    mother_last_name: '',
    mother_citizenship: 'Filipino',
    mother_residence: '',
    consent_name: '',
    consent_relationship: '',
    consent_citizenship: '',
    consent_residence: '',
    id_type: '',
    id_number: '',
});

export default {
    name: 'StaffWalkInApplications',
    data() {
        return {
            form: {
                phone_number: '',
                groom: createPerson('Male'),
                bride: createPerson('Female'),
            },
            activePerson: 'groom',
            personOrder: ['groom', 'bride'],
            personLabels: { groom: 'Groom', bride: 'Bride' },
            primaryFields: [
                { key: 'first_name', label: 'First Name', type: 'text', col: 'col-md-4', required: true },
                { key: 'middle_name', label: 'Middle Name', type: 'text', col: 'col-md-4' },
                { key: 'last_name', label: 'Last Name', type: 'text', col: 'col-md-4', required: true },
                { key: 'birth_date', label: 'Birth Date', type: 'date', col: 'col-md-4', required: true },
                { key: 'age', label: 'Age', type: 'number', col: 'col-md-2', required: true },
                { key: 'sex', label: 'Sex', type: 'select', col: 'col-md-3', required: true, options: ['Male', 'Female'] },
                { key: 'citizenship', label: 'Citizenship', type: 'text', col: 'col-md-3', required: true },
                { key: 'religion', label: 'Religion', type: 'text', col: 'col-md-4', required: true },
                { key: 'civil_status', label: 'Civil Status', type: 'select', col: 'col-md-4', required: true, options: ['Single', 'Widowed', 'Divorced', 'Annulled'] },
            ],
            locationFields: [
                { key: 'birth_city', label: 'Birth City/Municipality', col: 'col-md-4', required: true },
                { key: 'birth_province', label: 'Birth Province', col: 'col-md-4', required: true },
                { key: 'birth_country', label: 'Birth Country', col: 'col-md-4', required: true },
                { key: 'residence_address', label: 'Residence Address', type: 'textarea', col: 'col-12', required: true },
            ],
            parentFields: [
                { key: 'father_first_name', label: "Father's First Name", col: 'col-md-4', required: true },
                { key: 'father_middle_name', label: "Father's Middle Name", col: 'col-md-4' },
                { key: 'father_last_name', label: "Father's Last Name", col: 'col-md-4', required: true },
                { key: 'father_citizenship', label: "Father's Citizenship", col: 'col-md-6', required: true },
                { key: 'father_residence', label: "Father's Residence", type: 'textarea', col: 'col-md-6', required: true },
                { key: 'mother_first_name', label: "Mother's First Name", col: 'col-md-4', required: true },
                { key: 'mother_middle_name', label: "Mother's Middle Name", col: 'col-md-4' },
                { key: 'mother_last_name', label: "Mother's Last Name", col: 'col-md-4', required: true },
                { key: 'mother_citizenship', label: "Mother's Citizenship", col: 'col-md-6', required: true },
                { key: 'mother_residence', label: "Mother's Residence", type: 'textarea', col: 'col-md-6', required: true },
            ],
            optionalFields: [
                { key: 'dissolution_details', label: 'If Previously Married', type: 'textarea', col: 'col-md-6' },
                { key: 'dissolution_place', label: 'Place Dissolved', type: 'text', col: 'col-md-3' },
                { key: 'dissolution_date', label: 'Date Dissolved', type: 'date', col: 'col-md-3' },
                { key: 'relationship_degree', label: 'Relationship Degree', type: 'text', col: 'col-md-4', placeholder: 'Leave blank if none' },
                { key: 'id_type', label: 'ID Type', type: 'text', col: 'col-md-4', placeholder: 'e.g. PhilSys ID' },
                { key: 'id_number', label: 'ID Number', type: 'text', col: 'col-md-4' },
            ],
            consentFields: [
                { key: 'consent_name', label: 'Person Giving Consent', type: 'text', col: 'col-md-6', placeholder: 'Full name of parent or guardian' },
                { key: 'consent_relationship', label: 'Relationship', type: 'text', col: 'col-md-3', placeholder: 'e.g. Mother' },
                { key: 'consent_citizenship', label: 'Citizenship', type: 'text', col: 'col-md-3' },
                { key: 'consent_residence', label: 'Residence Address', type: 'textarea', col: 'col-12' },
            ],
            records: [],
            selectedRecord: null,
            showDetailsModal: false,
            showPrintModal: false,
            isPrintPreviewLoading: false,
            isPrinting: false,
            printPreviewSrc: '',
            isSaving: false,
            search: '',
            searchTimeout: null,
        };
    },
    watch: {
        search() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => this.fetchRecords(), 300);
        }
    },
    methods: {
        async fetchRecords() {
            const response = await getManualMarriageLicenseApplications(this.search);
            this.records = response.data?.data || [];
        },
        syncAge(personKey) {
            const birthDate = this.form[personKey].birth_date;
            if (!birthDate) return;

            const today = new Date();
            const birth = new Date(birthDate);
            let age = today.getFullYear() - birth.getFullYear();
            const monthDiff = today.getMonth() - birth.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                age -= 1;
            }

            this.form[personKey].age = age > 0 ? age : '';
        },
        async submitForm() {
            const confirmation = await Swal.fire({
                title: 'Save manual application?',
                text: 'This will store the walk-in record and prepare it for printing.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, save it',
                cancelButtonText: 'Cancel',
                background: '#0f172a',
                color: '#fff',
            });

            if (!confirmation.isConfirmed) return;

            this.isSaving = true;

            try {
                const response = await storeManualMarriageLicenseApplication(this.form);
                const savedRecord = response.data?.data;

                await this.fetchRecords();
                this.resetForm();

                const printPrompt = await Swal.fire({
                    title: 'Application saved',
                    text: `${savedRecord.control_number} was stored successfully. Do you want to print it now?`,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Print now',
                    cancelButtonText: 'Later',
                    background: '#0f172a',
                    color: '#fff',
                });

                if (printPrompt.isConfirmed && savedRecord) {
                    this.openPrintModal(savedRecord);
                }
            } catch (error) {
                const message = error.response?.data?.message || 'Unable to save the manual application.';
                const validationErrors = error.response?.data?.errors;
                const validationText = validationErrors ? Object.values(validationErrors).flat().join('\n') : message;

                Swal.fire({
                    title: 'Save failed',
                    text: validationText,
                    icon: 'error',
                    background: '#0f172a',
                    color: '#fff',
                });
            } finally {
                this.isSaving = false;
            }
        },
        resetForm() {
            this.form = {
                phone_number: '',
                groom: createPerson('Male'),
                bride: createPerson('Female'),
            };
            this.activePerson = 'groom';
        },
        async viewRecord(id) {
            try {
                const response = await viewManualMarriageLicenseApplication(id);
                this.selectedRecord = response.data?.data || null;
                this.showDetailsModal = true;
            } catch {
                Swal.fire({
                    title: 'Unable to load record',
                    text: 'Please try again.',
                    icon: 'error',
                    background: '#0f172a',
                    color: '#fff',
                });
            }
        },
        closeDetailsModal() {
            this.showDetailsModal = false;
            this.selectedRecord = null;
        },
        openPrintModal(record) {
            this.printPreviewSrc = `${record.preview_pdf_url}${record.preview_pdf_url.includes('?') ? '&' : '?'}_preview_ts=${Date.now()}#toolbar=0&navpanes=0&view=FitH`;
            this.showPrintModal = true;
            this.isPrintPreviewLoading = true;
        },
        closePrintModal() {
            this.showPrintModal = false;
            this.printPreviewSrc = '';
            this.isPrintPreviewLoading = false;
            this.isPrinting = false;
        },
        handlePrintPreviewLoaded() {
            this.isPrintPreviewLoading = false;
        },
        printIframe() {
            if (this.isPrinting || this.isPrintPreviewLoading) return;

            this.isPrinting = true;
            const frameWindow = this.$refs.printPreviewFrame?.contentWindow;

            if (!frameWindow) {
                this.isPrinting = false;
                return;
            }

            let done = false;
            const cleanup = () => {
                if (done) return;
                done = true;
                this.isPrinting = false;
            };

            frameWindow.onafterprint = cleanup;
            setTimeout(cleanup, 8000);
            frameWindow.focus();
            frameWindow.print();
        },
        fullName(person) {
            return [person.first_name, person.middle_name, person.last_name].filter(Boolean).join(' ');
        },
        formatDate(value) {
            if (!value) return 'N/A';
            return new Date(value).toLocaleString();
        },
        personCompletion(personKey) {
            const person = this.form[personKey];
            const requiredKeys = [
                'first_name',
                'last_name',
                'birth_date',
                'age',
                'sex',
                'citizenship',
                'religion',
                'civil_status',
                'birth_city',
                'birth_province',
                'birth_country',
                'residence_address',
                'father_first_name',
                'father_last_name',
                'father_citizenship',
                'father_residence',
                'mother_first_name',
                'mother_last_name',
                'mother_citizenship',
                'mother_residence',
                'consent_name',
                'consent_relationship',
                'consent_citizenship',
                'consent_residence',
            ];

            return requiredKeys.every((key) => {
                const value = person[key];
                return value !== null && value !== undefined && String(value).trim() !== '';
            });
        },
        completionLabel(personKey) {
            return this.personCompletion(personKey) ? 'Required fields complete' : 'Required fields missing';
        }
    },
    mounted() {
        this.fetchRecords();
    }
};
</script>

<style scoped>
.walkthrough-grid {
    display: grid;
    grid-template-columns: minmax(240px, 290px) minmax(0, 1fr);
    gap: 1.5rem;
}

.guide-panel {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.guide-card,
.contact-card,
.section-card,
.optional-section {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 1.25rem;
}

.guide-card {
    padding: 1.25rem;
}

.guide-card.compact {
    padding: 1rem;
}

.guide-eyebrow {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: rgba(191, 219, 254, 0.85);
    margin-bottom: 0.75rem;
}

.guide-step {
    display: grid;
    grid-template-columns: 2rem 1fr;
    gap: 0.75rem;
    align-items: start;
    margin-top: 0.9rem;
    color: rgba(255, 255, 255, 0.85);
}

.guide-step span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    border-radius: 999px;
    background: rgba(59, 130, 246, 0.18);
    color: #bfdbfe;
    font-weight: 700;
}

.guide-step p {
    margin: 0;
    line-height: 1.45;
}

.person-switch-btn,
.person-tab {
    width: 100%;
    border: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(255, 255, 255, 0.04);
    color: #fff;
    transition: 0.2s ease;
}

.person-switch-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.95rem 1rem;
    border-radius: 1rem;
    margin-top: 0.75rem;
}

.person-switch-btn strong,
.person-tab span {
    display: block;
    font-weight: 700;
}

.person-switch-btn small,
.person-tab small {
    color: rgba(255, 255, 255, 0.55);
}

.person-switch-btn.active,
.person-tab.active {
    background: rgba(59, 130, 246, 0.16);
    border-color: rgba(96, 165, 250, 0.45);
    box-shadow: 0 0 0 1px rgba(96, 165, 250, 0.2) inset;
}

.contact-card {
    padding: 1rem;
}

.contact-input-wrap {
    width: min(100%, 360px);
}

.person-tabs {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.75rem;
}

.person-tab {
    padding: 1rem 1.1rem;
    border-radius: 1rem;
    text-align: left;
}

.glass-panel {
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(16px);
    box-shadow: 0 24px 80px rgba(15, 23, 42, 0.25);
}

.person-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.78), rgba(15, 23, 42, 0.9));
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.completion-pill {
    display: inline-flex;
    align-items: center;
    padding: 0.6rem 0.9rem;
    border-radius: 999px;
    background: rgba(250, 204, 21, 0.14);
    color: #fde68a;
    font-size: 0.82rem;
    font-weight: 700;
}

.completion-pill.ready {
    background: rgba(34, 197, 94, 0.14);
    color: #86efac;
}

.person-pill {
    display: inline-flex;
    padding: 0.45rem 0.95rem;
    border-radius: 999px;
    background: rgba(59, 130, 246, 0.18);
    color: #bfdbfe;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}

.section-card {
    padding: 1.1rem;
    margin-bottom: 1rem;
}

.section-header {
    margin-bottom: 1rem;
}

.section-header h6 {
    margin: 0 0 0.3rem;
    color: #fff;
    font-weight: 700;
}

.section-header p {
    margin: 0;
    color: rgba(255, 255, 255, 0.55);
    font-size: 0.92rem;
}

.optional-section {
    padding: 1rem 1.1rem;
    margin-top: 1rem;
    border-style: dashed;
    border-color: rgba(96, 165, 250, 0.35);
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.85), rgba(15, 23, 42, 0.92));
}

.optional-section summary {
    cursor: pointer;
    color: #fff;
    list-style: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.2rem 0;
}

.optional-section summary::-webkit-details-marker {
    display: none;
}

.optional-section[open] summary {
    margin-bottom: 1rem;
}

.optional-summary-text {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

.optional-summary-text strong {
    font-size: 1rem;
}

.optional-summary-text small {
    color: rgba(255, 255, 255, 0.58);
}

.optional-kicker {
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: #93c5fd;
    font-weight: 700;
}

.optional-chevron {
    color: #93c5fd;
    transition: transform 0.2s ease;
}

.optional-section[open] .optional-chevron {
    transform: rotate(180deg);
}

.optional-subtitle {
    margin: 1.1rem 0 0.85rem;
    color: rgba(191, 219, 254, 0.85);
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

.form-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
    margin-top: 1.5rem;
}

.footer-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.glass-input,
.glass-input:focus,
.glass-addon {
    background: rgba(255, 255, 255, 0.08) !important;
    color: #fff !important;
    border-color: rgba(255, 255, 255, 0.12) !important;
    box-shadow: none !important;
}

.glass-input::placeholder {
    color: rgba(255, 255, 255, 0.45);
}

.glass-input option {
    color: #fff;
    background: #0f172a;
}

.search-group {
    width: min(100%, 360px);
}

.glass-table {
    --bs-table-bg: transparent;
    --bs-table-color: #fff;
}

.glass-row {
    background: rgba(255, 255, 255, 0.04);
}

.glass-row td {
    background: transparent !important;
}

.btn-action-glass {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(12px);
}

.modal-overlay-custom,
.modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 1200;
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.modal-body-custom {
    width: min(980px, 100%);
    max-height: 90vh;
    overflow: auto;
    background: rgba(15, 23, 42, 0.92);
}

.modal-glass-header {
    background: rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.detail-card {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.detail-line {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
    color: #fff;
    margin-bottom: 1rem;
}

.detail-line span {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: rgba(255, 255, 255, 0.5);
}

.print-modal-content {
    width: min(1100px, 100%);
    max-height: 92vh;
    background: rgba(15, 23, 42, 0.94);
    border-radius: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.12);
    padding: 1.25rem;
}

.print-preview-shell {
    min-height: 70vh;
    border-radius: 1rem;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.04);
}

.print-pdf-frame {
    width: 100%;
    height: 70vh;
    border: 0;
    background: #fff;
}

.print-modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    margin-top: 1rem;
}

@media (max-width: 991.98px) {
    .walkthrough-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 767.98px) {
    .person-tabs {
        grid-template-columns: 1fr;
    }

    .form-footer {
        flex-direction: column;
        align-items: stretch;
    }

    .footer-actions {
        width: 100%;
    }

    .footer-actions .btn,
    .form-footer > .btn {
        width: 100%;
    }
}
</style>
