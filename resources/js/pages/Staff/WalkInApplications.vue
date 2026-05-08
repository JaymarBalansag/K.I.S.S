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
                <div class="walkin-form">
                    <div class="sticky-top-bar">
                        <div class="top-bar-row">
                            <div class="person-toggle">
                                <button
                                    v-for="personKey in personOrder"
                                    :key="`top-${personKey}`"
                                    type="button"
                                    class="person-tab person-tab-lg"
                                    :class="{ active: activePerson === personKey }"
                                    @click="setActivePerson(personKey)"
                                >
                                    <span>{{ personLabels[personKey] }}</span>
                                    <small>{{ personCompletion(personKey) ? 'Complete' : 'Missing required' }}</small>
                                </button>
                            </div>

                            <div class="top-progress">
                                <div
                                    v-for="personKey in personOrder"
                                    :key="`progress-${personKey}`"
                                    class="progress-chip"
                                    :class="{ ready: personCompletion(personKey) }"
                                >
                                    <i class="bi me-2"
                                        :class="personCompletion(personKey) ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'"></i>
                                    <span class="me-2 fw-semibold">{{ personLabels[personKey] }}</span>
                                    <span class="opacity-75">{{ personCompletion(personKey) ? 'Ready' : 'Needs details' }}</span>
                                </div>
                            </div>
                        </div>

                        <details class="howto-details">
                            <summary>
                                <span class="guide-eyebrow mb-0">How this works</span>
                                <i class="bi bi-chevron-down"></i>
                            </summary>
                            <div class="howto-body">
                                <div class="guide-step">
                                    <span>1</span>
                                    <p>Start with the contact number and the groom details.</p>
                                </div>
                                <div class="guide-step">
                                    <span>2</span>
                                    <p>Use the section buttons (Identity, Address, Parents, Optional) one at a time.</p>
                                </div>
                                <div class="guide-step">
                                    <span>3</span>
                                    <p>Switch to the bride when you're done, then save and print.</p>
                                </div>
                            </div>
                        </details>
                    </div>

                    <div class="walkin-stack">
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

                        <section class="person-card rounded-5 p-4">
                                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                                        <div>
                                            <span class="person-pill">{{ personLabels[activePerson] }}</span>
                                            <h4 class="text-white fw-bold mt-3 mb-1">{{ personLabels[activePerson] }} Information</h4>
                                            <p class="text-white-50 mb-0">Use the tabs below to fill one section at a time.</p>
                                        </div>
                                        <div class="completion-pill" :class="{ ready: personCompletion(activePerson) }">
                                            {{ completionLabel(activePerson) }}
                                        </div>
                                    </div>

                                    <div class="section-switch mb-3">
                                        <button type="button" class="section-switch-btn"
                                            :class="{ active: activeSection === 'identity' }"
                                            @click="activeSection = 'identity'">
                                            <div>
                                                <strong>Identity</strong>
                                                <small>Name, birth date, civil status.</small>
                                            </div>
                                            <i class="bi"
                                                :class="sectionCompletion(activePerson, 'identity') ? 'bi-check-circle-fill text-success' : 'bi-chevron-right'"></i>
                                        </button>
                                        <button type="button" class="section-switch-btn"
                                            :class="{ active: activeSection === 'address' }"
                                            @click="activeSection = 'address'">
                                            <div>
                                                <strong>Birthplace & Address</strong>
                                                <small>Birthplace and residence.</small>
                                            </div>
                                            <i class="bi"
                                                :class="sectionCompletion(activePerson, 'address') ? 'bi-check-circle-fill text-success' : 'bi-chevron-right'"></i>
                                        </button>
                                        <button type="button" class="section-switch-btn"
                                            :class="{ active: activeSection === 'parents' }"
                                            @click="activeSection = 'parents'">
                                            <div>
                                                <strong>Parents</strong>
                                                <small>Father and mother details.</small>
                                            </div>
                                            <i class="bi"
                                                :class="sectionCompletion(activePerson, 'parents') ? 'bi-check-circle-fill text-success' : 'bi-chevron-right'"></i>
                                        </button>
                                        <button type="button" class="section-switch-btn"
                                            :class="{ active: activeSection === 'optional' }"
                                            @click="activeSection = 'optional'">
                                            <div>
                                                <strong>Optional</strong>
                                                <small>Previous marriage, ID, consent.</small>
                                            </div>
                                            <i class="bi bi-chevron-right"></i>
                                        </button>
                                    </div>

                                    <div class="section-card" v-show="activeSection === 'identity'">
                                        <div class="section-header">
                                            <h6>Identity</h6>
                                            <p>Name, birthday, age, and civil profile.</p>
                                        </div>
                                         <div class="row g-3">
                                             <div v-for="field in primaryFields" :key="`${activePerson}-${field.key}`" :class="field.col">
                                                 <label class="form-label text-white fw-semibold">
                                                     {{ field.label }}<span v-if="field.required" class="text-danger ms-1">*</span>
                                                 </label>
                                                 <select v-if="field.type === 'select'" v-model="form[activePerson][field.key]" class="form-select glass-input">
                                                     <option value="" disabled>Select {{ field.label }}</option>
                                                     <option v-for="option in field.options" :key="option" :value="option">{{ option }}</option>
                                                 </select>
                                                 <input
                                                     v-else-if="field.key === 'id_type'"
                                                     v-model="form[activePerson][field.key]"
                                                     :type="field.type"
                                                     class="form-control glass-input"
                                                     :placeholder="field.placeholder || field.label"
                                                     list="id-type-suggestions"
                                                 >
                                                 <input v-else v-model="form[activePerson][field.key]"
                                                     :type="field.type" class="form-control glass-input"
                                                     :placeholder="field.placeholder || field.label"
                                                     @input="field.key === 'birth_date' ? syncAge(activePerson) : null">
                                             </div>
                                         </div>
                                     </div>

                                    <div class="section-card" v-show="activeSection === 'address'">
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

                                    <div class="section-card" v-show="activeSection === 'parents'">
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

                                    <div class="section-card" v-show="activeSection === 'optional'">
                                        <div class="section-header">
                                            <h6>Optional Printable Details</h6>
                                            <p>Fill these only if applicable. Leave blank if not needed.</p>
                                        </div>
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

                                        <div class="optional-subtitle">Person Giving Consent (Optional)</div>
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
                                    </div>

                                    <div class="sticky-bottom-actions">
                                        <div class="bottom-left">
                                            <button
                                                type="button"
                                                class="btn btn-outline-light px-4 py-2 rounded-pill"
                                                @click="setActivePerson(activePerson === 'groom' ? 'bride' : 'groom')"
                                            >
                                                <i class="bi me-2"
                                                    :class="activePerson === 'groom' ? 'bi-arrow-right-circle' : 'bi-arrow-left-circle'"></i>
                                                {{ activePerson === 'groom' ? 'Next: Bride' : 'Back: Groom' }}
                                            </button>
                                            <div
                                                v-if="!personCompletion('groom') || !personCompletion('bride')"
                                                class="save-hint"
                                            >
                                                <i class="bi bi-info-circle me-1"></i>
                                                Fill required fields (marked *) for both Groom and Bride before saving.
                                            </div>
                                        </div>

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
            </form>

            <datalist id="id-type-suggestions">
                <option v-for="option in idTypeSuggestions" :key="option" :value="option"></option>
            </datalist>

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
                                        <button class="btn btn-action-glass text-white" @click="openEdit(record.id)">
                                            <i class="bi bi-pencil-square me-1"></i> Edit
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
                <div class="detail-card rounded-4 p-4 mb-4">
                    <h6 class="text-white opacity-75 fw-bold mb-3">Shared Details</h6>
                    <div class="detail-line mb-0">
                        <span>Contact Number</span>{{ selectedRecord.phone_number || 'N/A' }}
                    </div>
                </div>

                <div class="row g-4">
                    <div v-for="personKey in personOrder" :key="`details-${personKey}`" class="col-md-6">
                        <div class="detail-card rounded-4 p-4 h-100">
                            <h5 class="text-white fw-bold mb-3">{{ personLabels[personKey] }}</h5>

                            <details class="detail-section" open>
                                <summary>Identity</summary>
                                <div class="detail-grid">
                                    <div v-for="field in primaryFields" :key="`view-${personKey}-${field.key}`" class="detail-line">
                                        <span>{{ field.label }}</span>{{ formatDetailValue(selectedRecord[personKey][field.key]) }}
                                    </div>
                                </div>
                            </details>

                            <details class="detail-section">
                                <summary>Birthplace & Address</summary>
                                <div class="detail-grid">
                                    <div v-for="field in locationFields" :key="`view-${personKey}-${field.key}`" class="detail-line">
                                        <span>{{ field.label }}</span>{{ formatDetailValue(selectedRecord[personKey][field.key]) }}
                                    </div>
                                </div>
                            </details>

                            <details class="detail-section">
                                <summary>Parents</summary>
                                <div class="detail-grid">
                                    <div v-for="field in parentFields" :key="`view-${personKey}-${field.key}`" class="detail-line">
                                        <span>{{ field.label }}</span>{{ formatDetailValue(selectedRecord[personKey][field.key]) }}
                                    </div>
                                </div>
                            </details>

                            <details class="detail-section">
                                <summary>Optional Printable Details</summary>
                                <div class="detail-grid">
                                    <div v-for="field in optionalFields" :key="`view-${personKey}-${field.key}`" class="detail-line">
                                        <span>{{ field.label }}</span>{{ formatDetailValue(selectedRecord[personKey][field.key]) }}
                                    </div>
                                </div>
                            </details>

                            <details class="detail-section">
                                <summary>Consent (Optional)</summary>
                                <div class="detail-grid">
                                    <div v-for="field in consentFields" :key="`view-${personKey}-${field.key}`" class="detail-line">
                                        <span>{{ field.label }}</span>{{ formatDetailValue(selectedRecord[personKey][field.key]) }}
                                    </div>
                                </div>
                            </details>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="showEditModal" class="modal-overlay-custom">
        <div class="modal-body-custom rounded-5 shadow-2xl p-0 border border-white border-opacity-20">
            <div class="modal-glass-header p-4 d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-primary bg-opacity-10 text-primary text-uppercase mb-2 x-small ls-1 px-3 border border-primary border-opacity-20">
                        Edit Walk-In Record
                    </span>
                    <h4 class="fw-bold mb-0 text-white">{{ editRecordControlNumber || 'Manual Application' }}</h4>
                </div>
                <button class="btn-close btn-close-white opacity-50" @click="closeEditModal" :disabled="isUpdating"></button>
            </div>

            <div class="p-4">
                <div class="detail-card rounded-4 p-4 mb-4">
                    <h6 class="text-white opacity-75 fw-bold mb-3">Shared Details</h6>
                    <label class="form-label text-white fw-semibold">Contact Number</label>
                    <input v-model="editForm.phone_number" type="text" class="form-control glass-input" placeholder="09XXXXXXXXX">
                </div>

                <div class="person-toggle mb-3">
                    <button
                        v-for="personKey in personOrder"
                        :key="`edit-person-${personKey}`"
                        type="button"
                        class="person-tab person-tab-lg"
                        :class="{ active: editActivePerson === personKey }"
                        @click="editActivePerson = personKey"
                    >
                        <span>{{ personLabels[personKey] }}</span>
                    </button>
                </div>

                <div class="section-switch mb-3">
                    <button type="button" class="section-switch-btn"
                        :class="{ active: editActiveSection === 'identity' }"
                        @click="editActiveSection = 'identity'">
                        <div>
                            <strong>Identity</strong>
                            <small>Required details.</small>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                    <button type="button" class="section-switch-btn"
                        :class="{ active: editActiveSection === 'address' }"
                        @click="editActiveSection = 'address'">
                        <div>
                            <strong>Birthplace & Address</strong>
                            <small>Birthplace and residence.</small>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                    <button type="button" class="section-switch-btn"
                        :class="{ active: editActiveSection === 'parents' }"
                        @click="editActiveSection = 'parents'">
                        <div>
                            <strong>Parents</strong>
                            <small>Father and mother details.</small>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                    <button type="button" class="section-switch-btn"
                        :class="{ active: editActiveSection === 'optional' }"
                        @click="editActiveSection = 'optional'">
                        <div>
                            <strong>Optional</strong>
                            <small>Printable optional fields.</small>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                    <button type="button" class="section-switch-btn"
                        :class="{ active: editActiveSection === 'consent' }"
                        @click="editActiveSection = 'consent'">
                        <div>
                            <strong>Consent</strong>
                            <small>Optional consent fields.</small>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                <div class="detail-card rounded-4 p-4">
                    <div class="row g-3" v-if="editActiveSection === 'identity'">
                        <div v-for="field in primaryFields" :key="`edit-${editActivePerson}-${field.key}`" :class="field.col">
                            <label class="form-label text-white fw-semibold">
                                {{ field.label }}<span v-if="field.required" class="text-danger ms-1">*</span>
                            </label>
                            <select v-if="field.type === 'select'" v-model="editForm[editActivePerson][field.key]" class="form-select glass-input">
                                <option value="" disabled>Select {{ field.label }}</option>
                                <option v-for="option in field.options" :key="option" :value="option">{{ option }}</option>
                            </select>
                            <input
                                v-else-if="field.key === 'id_type'"
                                v-model="editForm[editActivePerson][field.key]"
                                :type="field.type"
                                class="form-control glass-input"
                                :placeholder="field.placeholder || field.label"
                                list="id-type-suggestions"
                            >
                            <input v-else v-model="editForm[editActivePerson][field.key]"
                                :type="field.type" class="form-control glass-input"
                                :placeholder="field.placeholder || field.label"
                                @input="field.key === 'birth_date' ? syncEditAge(editActivePerson) : null">
                        </div>
                    </div>

                    <div class="row g-3" v-else-if="editActiveSection === 'address'">
                        <div v-for="field in locationFields" :key="`edit-${editActivePerson}-${field.key}`" :class="field.col">
                            <label class="form-label text-white fw-semibold">
                                {{ field.label }}<span v-if="field.required" class="text-danger ms-1">*</span>
                            </label>
                            <textarea v-if="field.type === 'textarea'" v-model="editForm[editActivePerson][field.key]"
                                class="form-control glass-input" rows="3"
                                :placeholder="field.placeholder || field.label"></textarea>
                            <input v-else v-model="editForm[editActivePerson][field.key]" type="text" class="form-control glass-input"
                                :placeholder="field.placeholder || field.label">
                        </div>
                    </div>

                    <div class="row g-3" v-else-if="editActiveSection === 'parents'">
                        <div v-for="field in parentFields" :key="`edit-${editActivePerson}-${field.key}`" :class="field.col">
                            <label class="form-label text-white fw-semibold">
                                {{ field.label }}<span v-if="field.required" class="text-danger ms-1">*</span>
                            </label>
                            <textarea v-if="field.type === 'textarea'" v-model="editForm[editActivePerson][field.key]"
                                class="form-control glass-input" rows="3"
                                :placeholder="field.placeholder || field.label"></textarea>
                            <input v-else v-model="editForm[editActivePerson][field.key]" type="text" class="form-control glass-input"
                                :placeholder="field.placeholder || field.label">
                        </div>
                    </div>

                    <div class="row g-3" v-else-if="editActiveSection === 'optional'">
                        <div v-for="field in optionalFields" :key="`edit-${editActivePerson}-${field.key}`" :class="field.col">
                            <label class="form-label text-white fw-semibold">{{ field.label }}</label>
                            <textarea v-if="field.type === 'textarea'" v-model="editForm[editActivePerson][field.key]"
                                class="form-control glass-input" rows="3"
                                :placeholder="field.placeholder || field.label"></textarea>
                            <input v-else v-model="editForm[editActivePerson][field.key]" :type="field.type"
                                class="form-control glass-input" :placeholder="field.placeholder || field.label">
                        </div>
                    </div>

                    <div class="row g-3" v-else>
                        <div v-for="field in consentFields" :key="`edit-${editActivePerson}-${field.key}`" :class="field.col">
                            <label class="form-label text-white fw-semibold">{{ field.label }}</label>
                            <textarea v-if="field.type === 'textarea'" v-model="editForm[editActivePerson][field.key]"
                                class="form-control glass-input" rows="3"
                                :placeholder="field.placeholder || field.label"></textarea>
                            <input v-else v-model="editForm[editActivePerson][field.key]" :type="field.type"
                                class="form-control glass-input" :placeholder="field.placeholder || field.label">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-outline-light fw-bold px-4" @click="closeEditModal" :disabled="isUpdating">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary fw-bold px-4" @click="submitEdit" :disabled="isUpdating || showEditConfirm">
                        Save Changes
                    </button>
                </div>
            </div>

            <div v-if="showEditConfirm" class="confirm-overlay" @click.self="cancelEditConfirm">
                <div class="confirm-modal rounded-4 p-4">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                        <div class="text-white">
                            <div class="fw-bold fs-5">Are you sure?</div>
                            <div class="small opacity-75">Save changes to {{ editRecordControlNumber }}.</div>
                        </div>
                        <button type="button" class="btn-close btn-close-white opacity-50" @click="cancelEditConfirm" :disabled="isUpdating"></button>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4 flex-wrap">
                        <button type="button" class="btn btn-outline-light fw-bold px-4" @click="cancelEditConfirm" :disabled="isUpdating">
                            Not sure
                        </button>
                        <button type="button" class="btn btn-primary fw-bold px-4" @click="confirmEditUpdate" :disabled="isUpdating">
                            <span v-if="isUpdating" class="spinner-border spinner-border-sm me-2"></span>
                            Yes, I'm sure
                        </button>
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
    updateManualMarriageLicenseApplication,
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
    id_issued_at: '',
    id_issued_on: '',
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
            idTypeSuggestions: [
                'PhilSys ID',
                "Driver's License",
                'Passport',
                'UMID',
                'SSS ID',
                'GSIS ID',
                'PRC ID',
                'Postal ID',
                "Voter's ID",
                'TIN ID',
                'Senior Citizen ID',
                'PWD ID',
                'School ID',
                'Company ID',
                'Barangay ID',
                'NBI Clearance',
                'Police Clearance',
            ],
            activePerson: 'groom',
            activeSection: 'identity',
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
                { key: 'id_type', label: 'ID Type', type: 'text', col: 'col-md-4', required: true, placeholder: 'e.g. PhilSys ID' },
                { key: 'id_number', label: 'ID Number', type: 'text', col: 'col-md-4', required: true },
                { key: 'id_issued_at', label: 'Issued At', type: 'text', col: 'col-md-4', required: true, placeholder: 'e.g. LCR / DFA / LTO' },
                { key: 'id_issued_on', label: 'Issued On', type: 'date', col: 'col-md-4', required: true },
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
            showEditModal: false,
            editRecordId: null,
            editRecordControlNumber: '',
            editForm: {
                phone_number: '',
                groom: createPerson('Male'),
                bride: createPerson('Female'),
            },
            editActivePerson: 'groom',
            editActiveSection: 'identity',
            isUpdating: false,
            showEditConfirm: false,
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
            this.activeSection = 'identity';
        },
        setActivePerson(personKey) {
            this.activePerson = personKey;
            this.activeSection = 'identity';
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
                ...this.primaryFields.filter((field) => field.required).map((field) => field.key),
                ...this.locationFields.filter((field) => field.required).map((field) => field.key),
                ...this.parentFields.filter((field) => field.required).map((field) => field.key),
            ];

            return requiredKeys.every((key) => this.hasValue(person[key]));
        },
        completionLabel(personKey) {
            return this.personCompletion(personKey) ? 'Required fields complete' : 'Required fields missing';
        },
        sectionCompletion(personKey, sectionKey) {
            const person = this.form[personKey];
            const requiredKeysBySection = {
                identity: this.primaryFields.filter((field) => field.required).map((field) => field.key),
                address: this.locationFields.filter((field) => field.required).map((field) => field.key),
                parents: this.parentFields.filter((field) => field.required).map((field) => field.key),
            };

            const requiredKeys = requiredKeysBySection[sectionKey] || [];
            return requiredKeys.length > 0 && requiredKeys.every((key) => this.hasValue(person[key]));
        },
        hasValue(value) {
            if (value === null || value === undefined) return false;
            if (typeof value === 'number') return Number.isFinite(value);
            return String(value).trim() !== '';
        },
        formatDetailValue(value) {
            if (!this.hasValue(value)) return 'N/A';
            return String(value);
        },
        syncEditAge(personKey) {
            const birthDate = this.editForm[personKey].birth_date;
            if (!birthDate) return;

            const today = new Date();
            const birth = new Date(birthDate);
            let age = today.getFullYear() - birth.getFullYear();
            const monthDiff = today.getMonth() - birth.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                age -= 1;
            }

            this.editForm[personKey].age = age > 0 ? age : '';
        },
        normalizeEditPerson(personKey, data = {}) {
            const base = createPerson(personKey === 'groom' ? 'Male' : 'Female');
            return {
                ...base,
                ...data,
            };
        },
        async openEdit(id) {
            try {
                const response = await viewManualMarriageLicenseApplication(id);
                const record = response.data?.data || null;
                if (!record) return;

                this.editRecordId = record.id;
                this.editRecordControlNumber = record.control_number;
                this.editForm = {
                    phone_number: record.phone_number || '',
                    groom: this.normalizeEditPerson('groom', record.groom || {}),
                    bride: this.normalizeEditPerson('bride', record.bride || {}),
                };
                this.editActivePerson = 'groom';
                this.editActiveSection = 'identity';
                this.showEditConfirm = false;
                this.showEditModal = true;
            } catch (error) {
                const message = error.response?.data?.message || 'Unable to load the manual application for editing.';
                await Swal.fire({
                    title: 'Load failed',
                    text: message,
                    icon: 'error',
                    background: '#0f172a',
                    color: '#fff',
                });
            }
        },
        closeEditModal(force = false) {
            if (this.isUpdating && !force) return;
            this.showEditModal = false;
            this.editRecordId = null;
            this.editRecordControlNumber = '';
            this.editForm = {
                phone_number: '',
                groom: createPerson('Male'),
                bride: createPerson('Female'),
            };
            this.editActivePerson = 'groom';
            this.editActiveSection = 'identity';
            this.showEditConfirm = false;
        },
        async submitEdit() {
            if (!this.editRecordId) return;
            this.showEditConfirm = true;
        },
        cancelEditConfirm() {
            if (this.isUpdating) return;
            this.showEditConfirm = false;
        },
        async confirmEditUpdate() {
            if (!this.editRecordId) return;
            const recordId = this.editRecordId;
            this.isUpdating = true;
            try {
                await updateManualMarriageLicenseApplication(recordId, this.editForm);
                this.showEditConfirm = false;
                this.closeEditModal(true);
                await Swal.fire({
                    title: 'Updated',
                    text: 'Manual application updated successfully.',
                    icon: 'success',
                    background: '#0f172a',
                    color: '#fff',
                });
                await this.fetchRecords();

                if (this.showDetailsModal && this.selectedRecord?.id === recordId) {
                    const refreshed = await viewManualMarriageLicenseApplication(recordId);
                    this.selectedRecord = refreshed.data?.data || this.selectedRecord;
                }
            } catch (error) {
                const message = error.response?.data?.message || 'Unable to update the manual application.';
                const validationErrors = error.response?.data?.errors;
                const validationText = validationErrors ? Object.values(validationErrors).flat().join('\n') : message;
                await Swal.fire({
                    title: 'Update failed',
                    text: validationText,
                    icon: 'error',
                    background: '#0f172a',
                    color: '#fff',
                });
            } finally {
                this.isUpdating = false;
                this.showEditConfirm = false;
            }
        }
    },
    mounted() {
        this.fetchRecords();
    }
};
</script>

<style scoped>
.walkin-form {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.walkin-stack {
    width: min(1200px, 100%);
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.sticky-top-bar {
    position: sticky;
    top: 0.75rem;
    z-index: 30;
    width: min(1200px, 100%);
    margin: 0 auto;
    padding: 1rem;
    border-radius: 1.25rem;
    background: rgba(15, 23, 42, 0.72);
    border: 1px solid rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(16px);
    box-shadow: 0 24px 70px rgba(15, 23, 42, 0.28);
}

.top-bar-row {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: center;
    justify-content: space-between;
}

.person-toggle {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.75rem;
    flex: 1 1 340px;
}

.person-tab-lg {
    padding: 1rem 1.1rem;
    border-radius: 1.1rem;
    min-height: 68px;
}

.top-progress {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: 0.65rem;
    flex: 0 1 auto;
}

.progress-chip {
    display: inline-flex;
    align-items: center;
    padding: 0.55rem 0.85rem;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.92);
    font-size: 0.86rem;
}

.progress-chip.ready {
    background: rgba(34, 197, 94, 0.12);
    border-color: rgba(34, 197, 94, 0.22);
}

.howto-details {
    margin-top: 0.9rem;
    padding-top: 0.85rem;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
}

.howto-details summary {
    cursor: pointer;
    list-style: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    color: rgba(255, 255, 255, 0.9);
}

.howto-details summary::-webkit-details-marker {
    display: none;
}

.howto-details i {
    color: rgba(191, 219, 254, 0.9);
    transition: transform 0.2s ease;
}

.howto-details[open] i {
    transform: rotate(180deg);
}

.howto-body {
    margin-top: 0.9rem;
}

.sticky-bottom-actions {
    position: sticky;
    bottom: 0.75rem;
    z-index: 25;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1.25rem;
    padding: 1rem;
    border-radius: 1.25rem;
    background: rgba(15, 23, 42, 0.78);
    border: 1px solid rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(16px);
}

.bottom-left {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.save-hint {
    color: rgba(255, 255, 255, 0.65);
    font-size: 0.88rem;
}

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
.optional-section,
.section-switch-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 1.25rem;
}

.section-switch {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.75rem;
}

.section-switch-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.9rem 1rem;
    color: rgba(255, 255, 255, 0.9);
    text-align: left;
    transition: 0.2s ease;
}

.section-switch-btn strong {
    display: block;
    font-size: 0.95rem;
    font-weight: 700;
}

.section-switch-btn small {
    display: block;
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 0.1rem;
}

.section-switch-btn.active {
    border-color: rgba(13, 202, 240, 0.45);
    background: rgba(13, 202, 240, 0.12);
}

.section-switch-btn:hover {
    transform: translateY(-1px);
    border-color: rgba(255, 255, 255, 0.18);
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

.detail-section {
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    padding-top: 0.75rem;
    margin-top: 0.75rem;
}

.detail-section summary {
    cursor: pointer;
    color: rgba(255, 255, 255, 0.85);
    font-weight: 700;
    list-style: none;
}

.detail-section summary::-webkit-details-marker {
    display: none;
}

.detail-grid {
    margin-top: 0.75rem;
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.75rem 1rem;
}

.detail-grid .detail-line {
    margin-bottom: 0;
}

.detail-grid .detail-line span {
    margin-bottom: 0.15rem;
}

.detail-grid .detail-line:last-child {
    margin-bottom: 0;
}

.detail-grid :deep(textarea),
.detail-grid :deep(input),
.detail-grid :deep(select) {
    width: 100%;
}

.edit-confirm {
    background: rgba(59, 130, 246, 0.12);
    border: 1px solid rgba(59, 130, 246, 0.25);
}

.confirm-overlay {
    position: fixed;
    inset: 0;
    z-index: 1300;
    background: rgba(2, 6, 23, 0.72);
    backdrop-filter: blur(6px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.confirm-modal {
    width: min(520px, 100%);
    background: rgba(15, 23, 42, 0.96);
    border: 1px solid rgba(255, 255, 255, 0.14);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.55);
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
