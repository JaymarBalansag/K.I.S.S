<template>


    <main class="content-overlay">
        <div class="container py-5 mt-5">

            <!-- For title -->
            <div class="row justify-content-center text-center mb-5 mt-4">
                <div class="col-lg-9 col-xl-8">
                    <span
                        class="badge bg-primary bg-opacity-75 rounded-pill px-4 py-2 mb-3 shadow-sm text-uppercase fw-bold animate__animated animate__fadeInDown">
                        Marriage License Applications
                    </span>
                    <h2 class="text-white fw-bold text-shadow-heavy">Application Registry</h2>
                </div>
            </div>

            <!-- For filters and search bar -->
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

                <div class="col-md-2 text-md-end">
                    <button class="btn btn-action-glass w-100 text-white" @click="resetFilters">
                        <i class="bi bi-x-circle me-1"></i> Clear
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
                                        <div class="d-flex justify-content-center gap-2">
                                            <button @click="openViewApplicants(app)"
                                                class="btn btn-action-glass text-white">
                                                <i class="bi bi-eye-fill me-1"></i> View
                                            </button>

                                            <button @click="handleEditClick(app)" class="btn btn-action-glass text-warning"
                                                :class="{ 'opacity-75': !canEditApplication(app) }">
                                                <i class="bi bi-pencil-square me-1"></i> Edit
                                            </button>

                                            <button v-if="app.status === 'pending'"
                                                @click="validateApproval(app, 'approved')"
                                                class="btn btn-action-glass text-success">
                                                <i class="bi bi-check-circle-fill me-1"></i> Approve
                                            </button>
                                            <button v-if="app.status === 'pending'"
                                                @click="validateApproval(app, 'rejected')"
                                                class="btn btn-action-glass text-danger">
                                                <i class="bi bi-x-circle-fill me-1"></i> Reject
                                            </button>

                                            <button v-if="app.status === 'approved'"
                                                @click="validateApproval(app, 'issued')"
                                                class="btn btn-action-glass text-info">
                                                <i class="bi bi-patch-check-fill me-1"></i> Issue
                                            </button>

                                            <button v-if="app.status === 'issued'" @click="openPrintModal(app)"
                                                class="btn btn-action-glass text-warning">
                                                <i class="bi bi-printer-fill me-1"></i> Print 8.5x13
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

                            <div class="d-flex gap-2">
                                <button @click="openViewApplicants(app)"
                                    class="btn btn-action-glass text-info flex-grow-1">
                                    <i class="bi bi-eye-fill me-1"></i> View
                                </button>

                                <button @click="handleEditClick(app)" class="btn btn-action-glass text-warning"
                                    :class="{ 'opacity-75': !canEditApplication(app) }">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <template v-if="app.status === 'pending'">
                                    <button @click="validateApproval(app, 'approved')"
                                        class="btn btn-action-glass text-success">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </button>
                                    <button @click="validateApproval(app, 'rejected')"
                                        class="btn btn-action-glass text-danger">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                </template>

                                <button v-if="app.status === 'approved'" @click="validateApproval(app, 'issued')"
                                    class="btn btn-action-glass text-warning flex-grow-1">
                                    <i class="bi bi-patch-check-fill me-1"></i> Issue
                                </button>
                                <button v-if="app.status === 'issued'" @click="openPrintModal(app)"
                                    class="btn btn-action-glass text-warning">
                                    <i class="bi bi-printer-fill"></i> 8.5x13
                                </button>


                            </div>
                        </div>
                    </div>

                    <tr v-for="app in applications" :key="app.id" class="glass-row transition">
                    </tr>

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

                            <div
                                class="p-3 rounded-4 bg-secondary bg-opacity-5 border border-white border-opacity-5 mb-3">
                                <h6 class="x-small text-white opacity-30 text-uppercase fw-bold mb-3 ls-1">Parental
                                    Information</h6>
                                <div class="mb-3">
                                    <label class="x-small text-white opacity-40 d-block">Father</label>
                                    <span class="small d-block fw-semibold">{{ person.father_first_name }} {{
                                        person.father_middle_name }} {{ person.father_last_name }}</span>
                                    <span class="x-small opacity-50">{{ person.father_citizenship }} — {{
                                        person.father_residence }}</span>
                                </div>
                                <div class="mb-0">
                                    <label class="x-small text-white opacity-40 d-block">Mother</label>
                                    <span class="small d-block fw-semibold">{{ person.mother_first_name }} {{
                                        person.mother_middle_name }} {{ person.mother_last_name }}</span>
                                    <span class="x-small opacity-50">{{ person.mother_citizenship }} — {{
                                        person.mother_residence }}</span>
                                </div>
                            </div>

                            <div v-if="person.parental_requirement && person.parental_requirement !== 'no-need'"
                                class="p-3 rounded-4 bg-secondary bg-opacity-5 border border-white border-opacity-5 mb-3">
                                <h6 class="x-small text-white opacity-30 text-uppercase fw-bold mb-3 ls-1">
                                    Consent/Advice
                                    Source</h6>
                                <div class="mb-2">
                                    <label class="x-small text-white opacity-40 d-block">Requirement</label>
                                    <span class="small fw-semibold text-capitalize">
                                        {{ person.parental_requirement === 'parental-consent' ? 'Parental Consent' :
                                        'Parental Advice' }}
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <label class="x-small text-white opacity-40 d-block">Name</label>
                                    <span class="small d-block fw-semibold">
                                        {{ person.source_first_name || 'N/A' }} {{ person.source_middle_name || '' }} {{
                                        person.source_last_name || '' }}
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <label class="x-small text-white opacity-40 d-block">Citizenship /
                                        Relationship</label>
                                    <span class="small d-block">
                                        {{ person.source_citizenship || 'N/A' }} | {{ person.source_relationship ||
                                        'N/A' }}
                                    </span>
                                </div>
                                <div class="mb-0">
                                    <label class="x-small text-white opacity-40 d-block">Residence</label>
                                    <span class="x-small opacity-75">{{ person.source_residence || 'N/A' }}</span>
                                </div>
                            </div>

                            <div v-if="person.civil_status !== 'Single'"
                                class="p-2 rounded-3 border border-warning border-opacity-20 bg-secondary bg-opacity-5 mt-2">
                                <label class="x-small text-white opacity-75 d-block text-uppercase fw-bold">Previous
                                    Marriage Details</label>
                                <div class="x-small text-white">{{ person.dissolution_details !== "N/A" ?
                                    person.dissolution_details : "Not applicable" }} — {{ person.dissolution_place }}
                                </div>
                                <div class="x-small text-white">{{ person.dissolution_day !== "N/A " ?
                                    person.dissolution_day : "Not applicable" }}/{{ person.dissolution_month }}/{{
                                    person.dissolution_year }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-black bg-opacity-20 d-flex justify-content-between align-items-center mt-auto">
                <div>
                    <p class="x-small text-secondary opacity-40 mb-0">Record Created: {{ applicant[0].created_at }}</p>
                    <p class="x-small text-secondary opacity-40 mb-0">Data Submitted: {{ applicant[0].submitted_at }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-action-glass text-warning px-4" @click="handleEditClick(selectedApp, 'groom')"
                        :disabled="!selectedApp">
                        <i class="bi bi-person me-1"></i> Edit Groom
                    </button>
                    <button class="btn btn-action-glass text-warning px-4" @click="handleEditClick(selectedApp, 'bride')"
                        :disabled="!selectedApp">
                        <i class="bi bi-person-heart me-1"></i> Edit Bride
                    </button>
                    <button class="btn btn-action-glass text-info px-4" @click="openDocumentModal">
                        <i class="bi bi-archive-fill me-1"></i> Check Documents
                    </button>
                    <button class="btn btn-action-glass text-secondary" @click="closeViewApplicants">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div v-if="showApplicantDocuments" class="modal-overlay-custom animate__animated animate__fadeIn">
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
                    @click="closeDocumentModal"></button>
            </div>

            <div class="p-4">
                <div class="nav-glass-tabs d-flex p-1 mb-4 rounded-3 bg-white bg-opacity-5"
                    style="max-width: 400px; margin: 0 auto;">
                    <button class="flex-fill btn btn-sm py-2 rounded-2 transition-all"
                        :class="activeTab === 'groom' ? 'btn-info text-white shadow-sm' : 'text-secondary border-0'"
                        @click="activeTab = 'groom'">
                        <i class="bi bi-gender-male me-2"></i>Groom's Files
                    </button>
                    <button class="flex-fill btn btn-sm py-2 rounded-2 transition-all"
                        :class="activeTab === 'bride' ? 'btn-info text-white shadow-sm' : 'text-secondary border-0'"
                        @click="activeTab = 'bride'">
                        <i class="bi bi-gender-female me-2"></i>Bride's Files
                    </button>
                </div>

                <div class="document-grid animate__animated animate__fadeIn" v-if="activeTab === 'groom'">
                    <h6 class="text-white-50 x-small text-uppercase ls-1 mb-3">Required Credentials</h6>
                    <h4 class="fw-bold mb-0 text-white mb-3">{{ activeTab === "groom" ? applicant[0].first_name + " " +
                        applicant[0].last_name : applicant[1].first_name + " " + applicant[1].last_name }}</h4>
                    <div v-for="doc in groomDocuments" :key="doc.id"
                        class="doc-item-glass d-flex align-items-center p-3 mb-2 rounded-4 border border-white border-opacity-10">
                        <div class="doc-icon me-3">
                            <i class="bi bi-file-earmark-pdf fs-4 text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-white small fw-bold">{{ doc.doc_type }}</div>
                            <div class="x-small text-secondary fw-semibold">Uploaded: {{ doc.created_at }}</div>
                        </div>
                        <div class="d-flex gap-2">
                            <button @click="openCurrentDocument(doc.document_url)"
                                class="btn btn-sm btn-outline-info rounded-pill px-3">View</button>
                            <button
                                v-if="isCohabitationDoc(doc)"
                                @click="printCohabitationAffidavit(doc)"
                                class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                Print
                            </button>
                        </div>
                    </div>
                </div>
                <div class="document-grid animate__animated animate__fadeIn" v-else-if="activeTab === 'bride'">
                    <h6 class="text-white-50 x-small text-uppercase ls-1 mb-3">Required Credentials</h6>
                    <h4 class="fw-bold mb-0 text-white mb-3">{{ activeTab === "groom" ? applicant[0].first_name + " " +
                        applicant[0].last_name : applicant[1].first_name + " " + applicant[1].last_name }}</h4>
                    <div v-for="doc in brideDocuments" :key="doc.id"
                        class="doc-item-glass d-flex align-items-center p-3 mb-2 rounded-4 border border-white border-opacity-10">
                        <div class="doc-icon me-3">
                            <i class="bi bi-file-earmark-pdf fs-4 text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-white small fw-bold">{{ doc.doc_type }}</div>
                            <div class="x-small text-secondary fw-semibold">Uploaded: {{ doc.created_at }}</div>
                        </div>
                        <div class="d-flex gap-2">
                            <button @click="openCurrentDocument(doc.document_url)"
                                class="btn btn-sm btn-outline-info rounded-pill px-3">View</button>
                            <button
                                v-if="isCohabitationDoc(doc)"
                                @click="printCohabitationAffidavit(doc)"
                                class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                Print
                            </button>
                        </div>
                    </div>
                </div>

            </div>



            <div class="p-4 bg-black bg-opacity-20 d-flex justify-content-between align-items-center mt-auto">
                <div>
                    <p class="x-small text-secondary opacity-40 mb-0">Record Created: {{ applicant[0].created_at }}</p>
                    <p class="x-small text-secondary opacity-40 mb-0">Data Submitted: {{ applicant[0].submitted_at }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-action-glass text-secondary" @click="closeDocumentModal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div v-if="showDocument" class="modal-overlay-docviewer animate__animated animate__fadeIn">
        <div class="docviewer-panel rounded-4 shadow-2xl p-0">

            <div
                class="modal-glass-header p-3 d-flex justify-content-between align-items-center border-bottom border-white border-opacity-10">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-info bg-opacity-10 p-2 rounded-3 me-3">
                        <i class="bi bi-file-earmark-text text-info fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-white">Document View</h5>
                        <p class="x-small text-info mb-0 opacity-75">Verification for: {{ control_number }}</p>
                    </div>
                </div>
                <button class="btn-close btn-close-white" @click="closeCurrentDocument"></button>
            </div>

            <div
                class="document-viewer-content docviewer-surface p-2 d-flex justify-content-center align-items-center">

                <iframe v-if="currentFileIsPDF" :src="pdfIframeSrc" class="docviewer-frame w-100 h-100 rounded-3 border-0"
                    allowfullscreen></iframe>

                <div v-else
                    class="image-zoom-container w-100 h-100 d-flex justify-content-center align-items-center overflow-auto">
                    <img :src="currentFilePath" class="img-fluid rounded-2 shadow"
                        style="max-height: 100%; object-fit: contain;" alt="Document Preview">
                </div>

            </div>

            <div
                class="p-3 docviewer-footer border-top border-white border-opacity-10 d-flex justify-content-between align-items-center">
                <div class="d-flex gap-4">
                    <div class="timestamp-group">
                        <span class="x-small text-secondary text-uppercase d-block opacity-50">Reference</span>
                        <span class="small text-white fw-bold">{{ activeTab.toUpperCase() }}'S RECORD</span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-action-glass text-white border-white border-opacity-25 px-4"
                        @click="closeCurrentDocument">
                        <i class="bi bi-arrow-left me-2"></i>Back to List
                    </button>
                    <a :href="currentFileIsPDF ? pdfIframeSrc : currentFilePath" target="_blank"
                        class="btn btn-outline-light text-white border-white border-opacity-25 px-4">
                        <i class="bi bi-box-arrow-up-right me-2"></i>Open
                    </a>
                    <a :href="currentFilePath" target="_blank" class="btn btn-info text-white px-4">
                        <i class="bi bi-download me-2"></i>Download
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div v-if="showEditModal" class="modal-overlay-custom animate__animated animate__fadeIn">
        <div class="modal-body-custom rounded-5 shadow-2xl p-0 border border-white border-opacity-20">
            <div class="modal-glass-header p-4 d-flex justify-content-between align-items-center">
                <div>
                    <span
                        class="badge bg-info bg-opacity-10 text-info text-uppercase mb-2 x-small ls-1 px-3 border border-info border-opacity-20">
                        Staff Update
                    </span>
                    <h4 class="fw-bold mb-0 text-white">Edit Application</h4>
                    <p class="text-info small mb-0 opacity-75 fw-bold mt-1">
                        <i class="bi bi-pencil-square me-1"></i> {{ editForm.control_number || 'Application Record' }}
                    </p>
                </div>
                <button class="btn-close btn-close-white opacity-50 hover-opacity-100" @click="closeEditModal"></button>
            </div>

            <form class="p-4" @submit.prevent="saveApplicationEdit">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-white">Phone Number</label>
                        <input v-model="editForm.phone_number" type="text" class="form-control glass-input"
                            placeholder="09XXXXXXXXX">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white">Foreigner Type</label>
                        <select v-model="editForm.foreigner_type" class="form-select glass-input">
                            <option value="">None</option>
                            <option value="filipino">Filipino</option>
                            <option value="groom">Groom</option>
                            <option value="bride">Bride</option>
                            <option value="both">Both</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="alert alert-info bg-info bg-opacity-10 border border-info border-opacity-25 text-white mb-0">
                            Editable fields: all application details except status and uploaded documents/images.
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="edit-person-switch rounded-4 p-2 d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-sm px-4"
                                :class="editActivePerson === 'groom' ? 'btn-info text-dark fw-bold' : 'btn-outline-light'"
                                @click="setEditActivePerson('groom')">
                                <i class="bi bi-person me-1"></i> Groom
                            </button>
                            <button type="button" class="btn btn-sm px-4"
                                :class="editActivePerson === 'bride' ? 'btn-info text-dark fw-bold' : 'btn-outline-light'"
                                @click="setEditActivePerson('bride')">
                                <i class="bi bi-person-heart me-1"></i> Bride
                            </button>
                        </div>
                    </div>

                    <div class="col-12" v-show="editActivePerson === 'groom'">
                        <div class="section-card" ref="editGroomSection">
                            <h6 class="text-info fw-bold mb-3">Groom Details</h6>
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label text-white x-small">First Name</label>
                                    <input v-model="editForm.groom.first_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-white x-small">Middle Name</label>
                                    <input v-model="editForm.groom.middle_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-white x-small">Last Name</label>
                                    <input v-model="editForm.groom.last_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-white x-small">Suffix</label>
                                    <input v-model="editForm.groom.suffix" type="text" class="form-control glass-input" placeholder="Jr, Sr, III">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label text-white x-small">Birth Day</label>
                                    <input v-model="editForm.groom.day" type="text" class="form-control glass-input" placeholder="DD">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label text-white x-small">Birth Month</label>
                                    <input v-model="editForm.groom.month" type="text" class="form-control glass-input" placeholder="MM">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label text-white x-small">Birth Year</label>
                                    <input v-model="editForm.groom.year" type="text" class="form-control glass-input" placeholder="YYYY">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label text-white x-small">Age</label>
                                    <input v-model.number="editForm.groom.age" type="number" class="form-control glass-input" min="0" max="150">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label text-white x-small">Sex</label>
                                    <input v-model="editForm.groom.sex" type="text" class="form-control glass-input" placeholder="Male/Female">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label text-white x-small">Civil Status</label>
                                    <input v-model="editForm.groom.civil_status" type="text" class="form-control glass-input">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Birth City</label>
                                    <input v-model="editForm.groom.birth_city" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Birth Province</label>
                                    <input v-model="editForm.groom.birth_province" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Birth Country</label>
                                    <input v-model="editForm.groom.birth_country" type="text" class="form-control glass-input">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-white x-small">Citizenship</label>
                                    <input v-model="editForm.groom.citizenship" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white x-small">Religion</label>
                                    <input v-model="editForm.groom.religion" type="text" class="form-control glass-input">
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white x-small">Residence Address</label>
                                    <textarea v-model="editForm.groom.residence_address" class="form-control glass-input" rows="2"></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-white x-small">Relationship Degree</label>
                                    <input v-model="editForm.groom.relationship_degree" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white x-small">Parental Requirement</label>
                                    <input v-model="editForm.groom.parental_requirement" type="text" class="form-control glass-input">
                                </div>

                                <div class="col-12 mt-2">
                                    <h6 class="text-white opacity-75 mb-2">Previous Marriage / Dissolution (if applicable)</h6>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-white x-small">Dissolution Details</label>
                                    <textarea v-model="editForm.groom.dissolution_details" class="form-control glass-input" rows="2"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Dissolution Place</label>
                                    <input v-model="editForm.groom.dissolution_place" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label text-white x-small">Day</label>
                                    <input v-model="editForm.groom.dissolution_day" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-white x-small">Month</label>
                                    <input v-model="editForm.groom.dissolution_month" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-white x-small">Year</label>
                                    <input v-model="editForm.groom.dissolution_year" type="text" class="form-control glass-input">
                                </div>

                                <div class="col-12 mt-2">
                                    <h6 class="text-white opacity-75 mb-2">Father</h6>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">First Name</label>
                                    <input v-model="editForm.groom.father_first_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Middle Name</label>
                                    <input v-model="editForm.groom.father_middle_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Last Name</label>
                                    <input v-model="editForm.groom.father_last_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white x-small">Citizenship</label>
                                    <input v-model="editForm.groom.father_citizenship" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-white x-small">Residence</label>
                                    <textarea v-model="editForm.groom.father_residence" class="form-control glass-input" rows="2"></textarea>
                                </div>

                                <div class="col-12 mt-2">
                                    <h6 class="text-white opacity-75 mb-2">Mother</h6>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">First Name</label>
                                    <input v-model="editForm.groom.mother_first_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Middle Name</label>
                                    <input v-model="editForm.groom.mother_middle_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Last Name</label>
                                    <input v-model="editForm.groom.mother_last_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white x-small">Citizenship</label>
                                    <input v-model="editForm.groom.mother_citizenship" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-white x-small">Residence</label>
                                    <textarea v-model="editForm.groom.mother_residence" class="form-control glass-input" rows="2"></textarea>
                                </div>

                                <div class="col-12 mt-2">
                                    <h6 class="text-white opacity-75 mb-2">Consent Source (if applicable)</h6>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Source First Name</label>
                                    <input v-model="editForm.groom.source_first_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Source Middle Name</label>
                                    <input v-model="editForm.groom.source_middle_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Source Last Name</label>
                                    <input v-model="editForm.groom.source_last_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white x-small">Source Citizenship</label>
                                    <input v-model="editForm.groom.source_citizenship" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white x-small">Source Relationship</label>
                                    <input v-model="editForm.groom.source_relationship" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-white x-small">Source Residence</label>
                                    <textarea v-model="editForm.groom.source_residence" class="form-control glass-input" rows="2"></textarea>
                                </div>

                                <div class="col-12 mt-2">
                                    <h6 class="text-white opacity-75 mb-2">Government ID</h6>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">ID Type</label>
                                    <input v-model="editForm.groom.government_id_type" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">ID Number</label>
                                    <input v-model="editForm.groom.government_id_number" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Issued At</label>
                                    <input v-model="editForm.groom.government_id_issued_at" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Issued On</label>
                                    <input v-model="editForm.groom.government_id_issued_on" type="date" class="form-control glass-input">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12" v-show="editActivePerson === 'bride'">
                        <div class="section-card" ref="editBrideSection">
                            <h6 class="text-info fw-bold mb-3">Bride Details</h6>
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label text-white x-small">First Name</label>
                                    <input v-model="editForm.bride.first_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-white x-small">Middle Name</label>
                                    <input v-model="editForm.bride.middle_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-white x-small">Last Name</label>
                                    <input v-model="editForm.bride.last_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-white x-small">Suffix</label>
                                    <input v-model="editForm.bride.suffix" type="text" class="form-control glass-input" placeholder="Jr, Sr, III">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label text-white x-small">Birth Day</label>
                                    <input v-model="editForm.bride.day" type="text" class="form-control glass-input" placeholder="DD">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label text-white x-small">Birth Month</label>
                                    <input v-model="editForm.bride.month" type="text" class="form-control glass-input" placeholder="MM">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label text-white x-small">Birth Year</label>
                                    <input v-model="editForm.bride.year" type="text" class="form-control glass-input" placeholder="YYYY">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label text-white x-small">Age</label>
                                    <input v-model.number="editForm.bride.age" type="number" class="form-control glass-input" min="0" max="150">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label text-white x-small">Sex</label>
                                    <input v-model="editForm.bride.sex" type="text" class="form-control glass-input" placeholder="Male/Female">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label text-white x-small">Civil Status</label>
                                    <input v-model="editForm.bride.civil_status" type="text" class="form-control glass-input">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Birth City</label>
                                    <input v-model="editForm.bride.birth_city" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Birth Province</label>
                                    <input v-model="editForm.bride.birth_province" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Birth Country</label>
                                    <input v-model="editForm.bride.birth_country" type="text" class="form-control glass-input">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-white x-small">Citizenship</label>
                                    <input v-model="editForm.bride.citizenship" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white x-small">Religion</label>
                                    <input v-model="editForm.bride.religion" type="text" class="form-control glass-input">
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white x-small">Residence Address</label>
                                    <textarea v-model="editForm.bride.residence_address" class="form-control glass-input" rows="2"></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-white x-small">Relationship Degree</label>
                                    <input v-model="editForm.bride.relationship_degree" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white x-small">Parental Requirement</label>
                                    <input v-model="editForm.bride.parental_requirement" type="text" class="form-control glass-input">
                                </div>

                                <div class="col-12 mt-2">
                                    <h6 class="text-white opacity-75 mb-2">Previous Marriage / Dissolution (if applicable)</h6>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-white x-small">Dissolution Details</label>
                                    <textarea v-model="editForm.bride.dissolution_details" class="form-control glass-input" rows="2"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Dissolution Place</label>
                                    <input v-model="editForm.bride.dissolution_place" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label text-white x-small">Day</label>
                                    <input v-model="editForm.bride.dissolution_day" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-white x-small">Month</label>
                                    <input v-model="editForm.bride.dissolution_month" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-white x-small">Year</label>
                                    <input v-model="editForm.bride.dissolution_year" type="text" class="form-control glass-input">
                                </div>

                                <div class="col-12 mt-2">
                                    <h6 class="text-white opacity-75 mb-2">Father</h6>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">First Name</label>
                                    <input v-model="editForm.bride.father_first_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Middle Name</label>
                                    <input v-model="editForm.bride.father_middle_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Last Name</label>
                                    <input v-model="editForm.bride.father_last_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white x-small">Citizenship</label>
                                    <input v-model="editForm.bride.father_citizenship" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-white x-small">Residence</label>
                                    <textarea v-model="editForm.bride.father_residence" class="form-control glass-input" rows="2"></textarea>
                                </div>

                                <div class="col-12 mt-2">
                                    <h6 class="text-white opacity-75 mb-2">Mother</h6>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">First Name</label>
                                    <input v-model="editForm.bride.mother_first_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Middle Name</label>
                                    <input v-model="editForm.bride.mother_middle_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Last Name</label>
                                    <input v-model="editForm.bride.mother_last_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white x-small">Citizenship</label>
                                    <input v-model="editForm.bride.mother_citizenship" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-white x-small">Residence</label>
                                    <textarea v-model="editForm.bride.mother_residence" class="form-control glass-input" rows="2"></textarea>
                                </div>

                                <div class="col-12 mt-2">
                                    <h6 class="text-white opacity-75 mb-2">Consent Source (if applicable)</h6>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Source First Name</label>
                                    <input v-model="editForm.bride.source_first_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Source Middle Name</label>
                                    <input v-model="editForm.bride.source_middle_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Source Last Name</label>
                                    <input v-model="editForm.bride.source_last_name" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white x-small">Source Citizenship</label>
                                    <input v-model="editForm.bride.source_citizenship" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white x-small">Source Relationship</label>
                                    <input v-model="editForm.bride.source_relationship" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-white x-small">Source Residence</label>
                                    <textarea v-model="editForm.bride.source_residence" class="form-control glass-input" rows="2"></textarea>
                                </div>

                                <div class="col-12 mt-2">
                                    <h6 class="text-white opacity-75 mb-2">Government ID</h6>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">ID Type</label>
                                    <input v-model="editForm.bride.government_id_type" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">ID Number</label>
                                    <input v-model="editForm.bride.government_id_number" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Issued At</label>
                                    <input v-model="editForm.bride.government_id_issued_at" type="text" class="form-control glass-input">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white x-small">Issued On</label>
                                    <input v-model="editForm.bride.government_id_issued_on" type="date" class="form-control glass-input">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-outline-light px-4" @click="closeEditModal"
                        :disabled="isSavingEdit">Cancel</button>
                    <button type="submit" class="btn btn-info px-4 text-dark fw-bold" :disabled="isSavingEdit">
                        <span v-if="isSavingEdit">Saving...</span>
                        <span v-else>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div v-if="showPrintModal" class="modal-overlay">
        <div class="print-modal-content">
            <div class="px-3 pb-2">
                <iframe
                    ref="printPreviewFrame"
                    :src="printPreviewSrc"
                    class="print-pdf-frame"
                    @load="handlePrintPreviewLoaded"
                ></iframe>
                <div class="print-modal-actions">
                    <button class="btn btn-warning fw-bold px-4" @click="printIframe" :disabled="isPrinting || isPrintPreviewLoading">
                        <span v-if="isPrinting">
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Loading...
                        </span>
                        <span v-else>
                            <i class="bi bi-printer-fill me-1"></i> Print
                        </span>
                    </button>
                    <button class="btn btn-outline-light fw-bold px-4" @click="closePrintModal" :disabled="isPrinting">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2';
import api from '../../controller/api';
import { ApplicationAction, getApplicants, viewApplicants } from '../../controller/MarriageLicense';

export default {
    data() {
        return {
            // Filter
            applications: [],
            status: "all",
            search: "",
            page: 1, // This is current_page
            totalPages: 0,
            totalResults: 0,
            order: "desc",
            isLoading: false,
            // Filter end
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
            editFocusSection: null,
            editActivePerson: 'groom',
            editForm: {
                id: null,
                control_number: '',
                phone_number: '',
                foreigner_type: '',
                groom: {
                    first_name: '',
                    middle_name: '',
                    last_name: '',
                    suffix: '',
                    day: '',
                    month: '',
                    year: '',
                    birth_city: '',
                    birth_province: '',
                    birth_country: '',
                    age: null,
                    sex: '',
                    citizenship: '',
                    religion: '',
                    civil_status: '',
                    residence_address: '',
                    dissolution_details: '',
                    dissolution_place: '',
                    dissolution_day: '',
                    dissolution_month: '',
                    dissolution_year: '',
                    relationship_degree: '',
                    father_first_name: '',
                    father_middle_name: '',
                    father_last_name: '',
                    father_citizenship: '',
                    father_residence: '',
                    mother_first_name: '',
                    mother_middle_name: '',
                    mother_last_name: '',
                    mother_citizenship: '',
                    mother_residence: '',
                    parental_requirement: '',
                    source_first_name: '',
                    source_middle_name: '',
                    source_last_name: '',
                    source_citizenship: '',
                    source_relationship: '',
                    source_residence: '',
                    government_id_type: '',
                    government_id_number: '',
                    government_id_issued_at: '',
                    government_id_issued_on: '',
                },
                bride: {
                    first_name: '',
                    middle_name: '',
                    last_name: '',
                    suffix: '',
                    day: '',
                    month: '',
                    year: '',
                    birth_city: '',
                    birth_province: '',
                    birth_country: '',
                    age: null,
                    sex: '',
                    citizenship: '',
                    religion: '',
                    civil_status: '',
                    residence_address: '',
                    dissolution_details: '',
                    dissolution_place: '',
                    dissolution_day: '',
                    dissolution_month: '',
                    dissolution_year: '',
                    relationship_degree: '',
                    father_first_name: '',
                    father_middle_name: '',
                    father_last_name: '',
                    father_citizenship: '',
                    father_residence: '',
                    mother_first_name: '',
                    mother_middle_name: '',
                    mother_last_name: '',
                    mother_citizenship: '',
                    mother_residence: '',
                    parental_requirement: '',
                    source_first_name: '',
                    source_middle_name: '',
                    source_last_name: '',
                    source_citizenship: '',
                    source_relationship: '',
                    source_residence: '',
                    government_id_type: '',
                    government_id_number: '',
                    government_id_issued_at: '',
                    government_id_issued_on: '',
                },
            },
            showPrintModal: false,
            isPrinting: false,
            isPrintPreviewLoading: false,
            selectedPaperSize: '8x13',
            preview8x13PdfUrl: '/api/pdf/8x13-preview-pdf',
            printPreviewSrc: '',
        };
    },
    computed: {
        allDocuments() {
            if (!this.selectedApp) return [];
            return [...this.selectedApp.groomDocuments, ...this.selectedApp.brideDocuments];
        },
        pdfIframeSrc() {
            if (!this.currentFileIsPDF || !this.currentFilePath) return this.currentFilePath;

            const [base, hash = ''] = this.currentFilePath.split('#', 2);
            const params = new URLSearchParams(hash);

            params.set('toolbar', '1');
            params.set('navpanes', '0');
            params.set('scrollbar', '1');
            params.set('zoom', 'page-width');
            params.set('view', 'FitH');

            const fragment = params.toString();
            return fragment ? `${base}#${fragment}` : base;
        },
    },
    methods: {
        canEditApplication(app) {
            const status = (app?.status || '').toLowerCase();
            return status === 'pending' || status === 'under_review';
        },
        async handleEditClick(app, focusSection = null) {
            if (!app) return;
            if (!this.canEditApplication(app)) {
                const status = (app?.status || '').toLowerCase();
                await Swal.fire({
                    title: 'Editing Locked',
                    text: `This application cannot be edited because its status is "${status}". Only pending/under_review applications can be edited.`,
                    icon: 'info',
                    background: '#0f172a',
                    color: '#fff',
                    confirmButtonText: 'OK'
                });
                return;
            }
            this.editFocusSection = focusSection;
            await this.openEditModal(app);
        },
        setEditActivePerson(person) {
            const normalized = (person || '').toLowerCase();
            if (normalized !== 'groom' && normalized !== 'bride') return;
            this.editActivePerson = normalized;
            this.$nextTick(() => {
                this.scrollToEditFocus();
            });
        },
        async openViewApplicants(app) {

            const response = await viewApplicants(app.id, app.control_number)

            // console.log(response.applicants[0])
            this.applicant = response.data.applicants;
            this.control_number = response.data.applicants[0].control_number;
            this.groomDocuments = response.data.groomDocuments;
            this.brideDocuments = response.data.brideDocuments;
            this.selectedApp = app;

            // console.log(response.data.applicants[0])


            this.showApplicantsModal = true;
        },
        closeViewApplicants() {
            this.showApplicantsModal = false;
            this.selectedApp = null;
        },

        openDocumentModal() {
            this.showApplicantsModal = false;
            this.showApplicantDocuments = true;
        },
        closeDocumentModal() {
            this.showApplicantDocuments = false;
            this.showApplicantsModal = true;
        },

        openCurrentDocument(path) {
            this.currentFilePath = path;
            // Simple check to see if it's a PDF
            const normalized = (path || '').split('#')[0].split('?')[0].toLowerCase();
            this.currentFileIsPDF = normalized.endsWith('.pdf');
            this.showDocument = true
        },
        closeCurrentDocument() {
            this.showDocument = false;
            this.currentFilePath = '';
        },

        async openEditModal(app) {
            try {
                // Prevent stacked modals (Edit on top of View/Documents/Print)
                this.showApplicantsModal = false;
                this.showApplicantDocuments = false;
                this.showDocument = false;
                this.showPrintModal = false;

                const response = await viewApplicants(app.id, app.control_number);
                const applicants = Array.isArray(response?.data?.applicants) ? response.data.applicants : [];
                const groom = applicants.find((person) => person.applicant_type === 'groom') || {};
                const bride = applicants.find((person) => person.applicant_type === 'bride') || {};

                this.editForm = {
                    id: app.id,
                    control_number: app.control_number,
                    phone_number: app.phone_number || '',
                    foreigner_type: app.foreigner_type || '',
                    groom: {
                        first_name: groom.first_name || '',
                        middle_name: groom.middle_name || '',
                        last_name: groom.last_name || '',
                        suffix: groom.suffix || '',
                        day: groom.day || '',
                        month: groom.month || '',
                        year: groom.year || '',
                        birth_city: groom.birth_city || '',
                        birth_province: groom.birth_province || '',
                        birth_country: groom.birth_country || '',
                        age: groom.age ?? null,
                        sex: groom.sex || '',
                        citizenship: groom.citizenship || '',
                        religion: groom.religion || '',
                        civil_status: groom.civil_status || '',
                        residence_address: groom.residence_address || '',
                        dissolution_details: groom.dissolution_details || '',
                        dissolution_place: groom.dissolution_place || '',
                        dissolution_day: groom.dissolution_day || '',
                        dissolution_month: groom.dissolution_month || '',
                        dissolution_year: groom.dissolution_year || '',
                        relationship_degree: groom.relationship_degree || '',
                        father_first_name: groom.father_first_name || '',
                        father_middle_name: groom.father_middle_name || '',
                        father_last_name: groom.father_last_name || '',
                        father_citizenship: groom.father_citizenship || '',
                        father_residence: groom.father_residence || '',
                        mother_first_name: groom.mother_first_name || '',
                        mother_middle_name: groom.mother_middle_name || '',
                        mother_last_name: groom.mother_last_name || '',
                        mother_citizenship: groom.mother_citizenship || '',
                        mother_residence: groom.mother_residence || '',
                        parental_requirement: groom.parental_requirement || '',
                        source_first_name: groom.source_first_name || '',
                        source_middle_name: groom.source_middle_name || '',
                        source_last_name: groom.source_last_name || '',
                        source_citizenship: groom.source_citizenship || '',
                        source_relationship: groom.source_relationship || '',
                        source_residence: groom.source_residence || '',
                        government_id_type: groom.government_id_type || '',
                        government_id_number: groom.government_id_number || '',
                        government_id_issued_at: groom.government_id_issued_at || '',
                        government_id_issued_on: groom.government_id_issued_on || '',
                    },
                    bride: {
                        first_name: bride.first_name || '',
                        middle_name: bride.middle_name || '',
                        last_name: bride.last_name || '',
                        suffix: bride.suffix || '',
                        day: bride.day || '',
                        month: bride.month || '',
                        year: bride.year || '',
                        birth_city: bride.birth_city || '',
                        birth_province: bride.birth_province || '',
                        birth_country: bride.birth_country || '',
                        age: bride.age ?? null,
                        sex: bride.sex || '',
                        citizenship: bride.citizenship || '',
                        religion: bride.religion || '',
                        civil_status: bride.civil_status || '',
                        residence_address: bride.residence_address || '',
                        dissolution_details: bride.dissolution_details || '',
                        dissolution_place: bride.dissolution_place || '',
                        dissolution_day: bride.dissolution_day || '',
                        dissolution_month: bride.dissolution_month || '',
                        dissolution_year: bride.dissolution_year || '',
                        relationship_degree: bride.relationship_degree || '',
                        father_first_name: bride.father_first_name || '',
                        father_middle_name: bride.father_middle_name || '',
                        father_last_name: bride.father_last_name || '',
                        father_citizenship: bride.father_citizenship || '',
                        father_residence: bride.father_residence || '',
                        mother_first_name: bride.mother_first_name || '',
                        mother_middle_name: bride.mother_middle_name || '',
                        mother_last_name: bride.mother_last_name || '',
                        mother_citizenship: bride.mother_citizenship || '',
                        mother_residence: bride.mother_residence || '',
                        parental_requirement: bride.parental_requirement || '',
                        source_first_name: bride.source_first_name || '',
                        source_middle_name: bride.source_middle_name || '',
                        source_last_name: bride.source_last_name || '',
                        source_citizenship: bride.source_citizenship || '',
                        source_relationship: bride.source_relationship || '',
                        source_residence: bride.source_residence || '',
                        government_id_type: bride.government_id_type || '',
                        government_id_number: bride.government_id_number || '',
                        government_id_issued_at: bride.government_id_issued_at || '',
                        government_id_issued_on: bride.government_id_issued_on || '',
                    },
                };

                this.showEditModal = true;
                this.editActivePerson = (this.editFocusSection || 'groom').toLowerCase() === 'bride' ? 'bride' : 'groom';
                this.$nextTick(() => {
                    this.scrollToEditFocus();
                });
            } catch (error) {
                Swal.fire('Error', 'Unable to load application for editing.', 'error');
            }
        },
        scrollToEditFocus() {
            const section = (this.editFocusSection || this.editActivePerson || '').toLowerCase();
            if (section === 'groom' && this.$refs.editGroomSection) {
                this.$refs.editGroomSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else if (section === 'bride' && this.$refs.editBrideSection) {
                this.$refs.editBrideSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        },
        closeEditModal() {
            if (this.isSavingEdit) return;
            this.showEditModal = false;
            this.editFocusSection = null;
            this.editActivePerson = 'groom';
        },
        async saveApplicationEdit() {
            this.isSavingEdit = true;

            try {
                const payload = {
                    phone_number: this.editForm.phone_number,
                    foreigner_type: this.editForm.foreigner_type || null,
                    groom: this.editForm.groom,
                    bride: this.editForm.bride,
                };

                const response = await api.patch(`/applications/${this.editForm.id}/staff-update`, payload);

                // Close the edit modal immediately to avoid "stacked" overlays
                this.showEditModal = false;
                this.editFocusSection = null;
                this.editActivePerson = 'groom';

                await Swal.fire({
                    title: 'Updated',
                    text: response?.data?.message || 'Application updated successfully.',
                    icon: 'success',
                    background: '#1e293b',
                    color: '#fff'
                });

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

        isCohabitationDoc(doc) {
            const type = (doc?.doc_type || '').toLowerCase();
            return type.includes('cohabitation') || type.includes('joint affidavit');
        },
        getApplicantByType(type) {
            return (this.applicant || []).find(person =>
                (person?.applicant_type || '').toLowerCase() === type
            ) || null;
        },
        buildPersonFullName(person) {
            if (!person) return '';
            return [person.first_name, person.middle_name, person.last_name]
                .filter(Boolean)
                .join(' ')
                .replace(/\s+/g, ' ')
                .trim();
        },
        getMonthName(monthNumber) {
            const month = Number(monthNumber);
            if (!Number.isFinite(month) || month < 1 || month > 12) return '';
            const monthNames = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            return monthNames[month - 1];
        },
        getYearsLivingTogether(groom, bride) {
            const person = groom || bride;
            if (!person?.living_together_since_year) return '';
            const currentYear = new Date().getFullYear();
            const sinceYear = Number(person.living_together_since_year);
            if (!Number.isFinite(sinceYear) || sinceYear <= 0 || sinceYear > currentYear) return '';
            return String(currentYear - sinceYear);
        },
        async toDataUrl(url) {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error('Could not load cohabitation image.');
            }
            const blob = await response.blob();
            return await new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onloadend = () => resolve(reader.result);
                reader.onerror = reject;
                reader.readAsDataURL(blob);
            });
        },
        buildCohabitationAffidavitHtml({ groom, bride, imageDataUrl }) {
            const groomName = this.buildPersonFullName(groom) || 'GROOM FULL NAME';
            const brideName = this.buildPersonFullName(bride) || 'BRIDE FULL NAME';
            const city = groom?.residence_city || bride?.residence_city || 'Abuyog';
            const province = groom?.residence_province || bride?.residence_province || 'Leyte';
            const monthName = this.getMonthName(groom?.living_together_since_month || bride?.living_together_since_month);
            const sinceYear = groom?.living_together_since_year || bride?.living_together_since_year || '';
            const yearsTogether = this.getYearsLivingTogether(groom, bride);
            const today = new Date();
            const issuedDay = today.getDate();
            const issuedMonth = today.toLocaleString('en-US', { month: 'long' });
            const issuedYear = today.getFullYear();

            return `<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Joint Affidavit of Cohabitation</title>
  <style>
    @page { size: 8.5in 13in; margin: 0.6in 0.8in; }
    body { font-family: "Times New Roman", serif; color: #000; font-size: 18px; line-height: 1.25; }
    .header { font-size: 16px; margin-bottom: 28px; }
    .title { text-align: center; font-weight: 700; font-size: 38px; margin: 16px 0 28px; }
    .content p { text-align: justify; margin: 0 0 16px; }
    ol { margin: 10px 0 22px 28px; }
    li { margin-bottom: 14px; text-align: justify; }
    .signatures { display: flex; justify-content: space-between; gap: 40px; margin-top: 26px; }
    .sig-col { width: 48%; }
    .sig-name { font-weight: 700; text-transform: uppercase; border-bottom: 1px solid #000; display: inline-block; min-width: 260px; }
    .image-wrap { margin-top: 18px; text-align: center; page-break-inside: avoid; }
    .image-wrap img { max-width: 100%; max-height: 3.8in; object-fit: contain; border: 1px solid #ccc; }
    .small { font-size: 16px; }
  </style>
</head>
<body>
  <div class="header">
    Republic of the Philippines<br>
    Province of ${province}<br>
    Municipality of ${city}
  </div>
  <div class="title">JOINT AFFIDAVIT OF COHABITATION</div>
  <div class="content">
    <p>We, <strong>${groomName}</strong> and <strong>${brideName}</strong>, of legal ages, Filipino Citizens, both single (living together) and both residents of <strong>${city}</strong> having been duly sworn in accordance with law, hereby depose and say:</p>
    <ol>
      <li>That we have been living together as husband and wife under the same roof, continuously and without any interruption, since ${monthName || '____________'} ${sinceYear || '____________'} or a period of more than ${yearsTogether || '___'} years;</li>
      <li>That during our cohabitation and even until present, we remain both of single status and hence, there exists no legal impediment for us to marry each other; and</li>
      <li>As such, we are executing this Affidavit to attest to the foregoing facts and for purposes of contracting marriage without need of securing marriage license pursuant to the provisions of Article 34 of the Family Code of the Philippines for all legal intents and purposes this may serve.</li>
    </ol>
    <p>IN WITNESS WHEREOF, we have hereunto set our hands this ${issuedDay}${this.ordinalSuffix(issuedDay)} day of ${issuedMonth} ${issuedYear} at ${city}, ${province}, Philippines.</p>
    <div class="signatures">
      <div class="sig-col small">
        <div class="sig-name">${groomName.toUpperCase()}</div><br>
        Affiant
      </div>
      <div class="sig-col small">
        <div class="sig-name">${brideName.toUpperCase()}</div><br>
        Affiant
      </div>
    </div>
    <p class="small" style="margin-top: 30px;">SUBSCRIBED AND SWORN TO before me this ____ day of ${issuedMonth}, ${issuedYear} at ${city}, ${province}, Philippines.</p>
    <div class="image-wrap">
      <div class="small" style="margin-bottom: 8px;"><strong>Attached Cohabitation Proof</strong></div>
      <img src="${imageDataUrl}" alt="Cohabitation proof">
    </div>
  </div>
</body>
</html>`;
        },
        ordinalSuffix(day) {
            const n = Number(day);
            if (![1, 2, 3].includes(n % 10) || [11, 12, 13].includes(n % 100)) return 'th';
            if (n % 10 === 1) return 'st';
            if (n % 10 === 2) return 'nd';
            return 'rd';
        },
        async printCohabitationAffidavit(doc) {
            try {
                const groom = this.getApplicantByType('groom');
                const bride = this.getApplicantByType('bride');
                const imageDataUrl = await this.toDataUrl(doc.document_url);
                const html = this.buildCohabitationAffidavitHtml({ groom, bride, imageDataUrl });
                const printWindow = window.open('', '_blank');

                if (!printWindow) {
                    throw new Error('Unable to open print window. Please allow pop-ups.');
                }

                printWindow.document.open();
                printWindow.document.write(html);
                printWindow.document.close();
                printWindow.focus();
                printWindow.onload = () => {
                    printWindow.print();
                };
            } catch (error) {
                Swal.fire({
                    title: 'Print Failed',
                    text: error?.message || 'Unable to prepare cohabitation affidavit PDF.',
                    icon: 'error',
                    background: '#1e293b',
                    color: '#fff'
                });
            }
        },

        async fetchApplications() {
            this.isLoading = true;
            try {
                // Ensure 'this.order' is passed here
                const response = await getApplicants(this.status, this.search, this.page, this.order);

                // Map data directly to applications
                this.applications = response.data.data.data.map(app => ({
                    id: app.id,
                    control_number: app.control_number,
                    status: app.status,
                    dateApplied: app.created_at,
                    coupleNames: app.applicant_names,
                    phone_number: app.phone_number || '',
                    foreigner_type: app.foreigner_type || ''
                }));

                // Update pagination metadata from Laravel
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
            if (s === 'issued') return base + "status-issued"; // Add a blue/cyan color
            if (s === 'rejected') return base + "status-rejected"; // Add a red color
            return base + "status-pending";
        },

        validateApproval(app, action) {

            let userAction;

            switch (action) {
                case "approved":
                    userAction = "Approve"
                    break;

                case "rejected":
                    userAction = "Reject"
                    break;

                case "issued":
                    userAction = "Issue"
                    break;

                default:
                    break;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to ${userAction} this marriage application.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0dcaf0', // Matching your info/cyan theme
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${userAction} it!`,
                cancelButtonText: 'No, cancel',
                background: '#1e293b', // Matching your dark glass theme
                color: '#fff',
                backdrop: `rgba(15, 23, 42, 0.5) blur(5px)` // Matching your modal backdrop
            }).then((result) => {
                if (result.isConfirmed) {
                    // "If yes, do something"
                    this.validateAction(app, action);
                }
                // "If no, just close" (Swal handles this automatically by closing the modal)
            });
        },

        async validateAction(app, action) {
            try {
                const response = await ApplicationAction(app.control_number, app.id, action)

                // 4. Success Alert
                await Swal.fire({
                    title: 'Success!',
                    text: response.data.message,
                    icon: 'success',
                    background: '#1e293b',
                    color: '#fff'
                });

                this.fetchApplications();

            } catch (error) {
                Swal.fire({
                    title: 'Request Failed',
                    text: error.response?.data?.message || "Something went wrong on the server.",
                    icon: 'error',
                    background: '#1e293b',
                    color: '#fff'
                })
            }
        },

        build8x13PdfUrl() {
            return `${this.preview8x13PdfUrl}${this.preview8x13PdfUrl.includes('?') ? '&' : '?'}paper_size=8x13`;
        },
        openPrintModal(app) {
            if (app?.id && app?.control_number) {
                this.preview8x13PdfUrl = `/api/pdf/8x13-preview-pdf?application_id=${app.id}&control_number=${encodeURIComponent(app.control_number)}`;
            } else {
                this.preview8x13PdfUrl = '/api/pdf/8x13-preview-pdf';
            }
            this.selectedPaperSize = '8x13';
            this.showPrintModal = true;
            this.loadPrintPreview();
        },
        closePrintModal() {
            this.showPrintModal = false;
            this.isPrinting = false;
            this.isPrintPreviewLoading = false;
            this.printPreviewSrc = '';
        },
        loadPrintPreview() {
            this.isPrintPreviewLoading = true;
            const baseUrl = this.build8x13PdfUrl(this.selectedPaperSize);
            this.printPreviewSrc = `${baseUrl}${baseUrl.includes('?') ? '&' : '?'}_preview_ts=${Date.now()}#toolbar=0&navpanes=0&view=FitH`;
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
        }
    },
    mounted() {
        this.fetchApplications();
    },
    watch: {
        // Auto-fetch when filters change
        status() {
            this.page = 1; // Reset to page 1
            this.fetchApplications();
        },
        order() {
            this.page = 1;
            this.fetchApplications();
        },
        search() {
            // Basic debounce logic to avoid too many requests
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.page = 1;
                this.fetchApplications();
            }, 500);
        },
        selectedPaperSize() {
            if (this.showPrintModal) {
                this.loadPrintPreview();
            }
        }
    },
};
</script>

<style scoped>
/* Added Pagination Styles */
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
    /* background: rgba(15, 23, 42, 0.4); */
}

/* GLASS TABLE CORE */
.glass-table {
    --bs-table-bg: transparent !important;
    border-collapse: separate !important;
    border-spacing: 0 15px !important;
    /* Ensure the table itself doesn't trap the menu */
    position: relative;
    z-index: 1;
}

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

.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.7);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2100;
}

.print-modal-content {
    width: min(96vw, 1100px);
    max-height: 94vh;
    display: flex;
    flex-direction: column;
    background: rgba(30, 41, 59, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 14px;
    padding: 10px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.45);
}

.print-pdf-frame {
    width: 100%;
    height: min(66vh, 760px);
    border: none;
    border-radius: 10px;
    background: #fff;
}

.print-modal-actions {
    margin-top: 8px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 8px 4px 4px;
    border-top: 1px solid rgba(255, 255, 255, 0.12);
    position: sticky;
    bottom: 0;
    background: rgba(30, 41, 59, 0.98);
    z-index: 2;
}

.glass-row {
    background: rgba(255, 255, 255, 0.07) !important;
    backdrop-filter: blur(20px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    /* Remove position: relative if it's causing the dropdown to go under the next row */
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
    /* Official Blue */
    border: 1px solid rgba(13, 110, 253, 0.3);
}

.status-rejected {
    color: #ff4d4d;
    /* Sharp Red */
    border: 1px solid rgba(255, 77, 77, 0.3);
}

/* TEXT HELPERS */
.text-shadow-heavy {
    text-shadow: 0 4px 15px rgba(0, 0, 0, 0.7);
}

.text-shadow-medium {
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
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

/* Deep Blur Overlay */
.modal-overlay-custom {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.7);
    /* Darker, more professional overlay */
    backdrop-filter: blur(12px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
}

/* The Main Container */
.modal-body-custom {
    background: rgba(255, 255, 255, 0.05);
    /* Very faint white */
    border: 1px solid rgba(255, 255, 255, 0.2);
    /* Thin 'ice' edge */
    width: 95%;
    max-width: 900px;
    max-height: 90vh;
    overflow-y: auto;
    color: white;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

/* Document Viewer (PDF/Image) */
.modal-overlay-docviewer {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.45);
    backdrop-filter: blur(12px) saturate(160%);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    padding: 14px;
}

.docviewer-panel {
    width: min(98vw, 1280px);
    height: min(94vh, 980px);
    max-height: 94vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    color: #fff;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0.06));
    border: 1px solid rgba(255, 255, 255, 0.18);
    box-shadow: 0 28px 60px rgba(0, 0, 0, 0.35);
}

.docviewer-surface {
    flex: 1;
    min-height: 0;
    background: rgba(255, 255, 255, 0.08);
}

.docviewer-frame {
    background: #fff;
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.docviewer-footer {
    background: rgba(255, 255, 255, 0.06);
}

.section-card {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 18px;
    padding: 1rem;
}

.edit-person-switch {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(10px) saturate(160%);
}

/* Muted Groom & Bride Cards */
.applicant-glass-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: transform 0.2s ease;
}

/* Muted Blue for Groom */
.groom-accent {
    border-left: 4px solid #60a5fa !important;
    /* Soft Sky Blue */
}

.groom-accent .type-pill {
    background: rgba(96, 165, 250, 0.15);
    color: #93c5fd;
}

/* Muted Rose for Bride */
.bride-accent {
    border-left: 4px solid #f472b6 !important;
    /* Soft Rose */
}

.bride-accent .type-pill {
    background: rgba(244, 114, 182, 0.15);
    color: #fbcfe8;
}

/* Document Tags */
.doc-tag {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #e2e8f0;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 11px;
    letter-spacing: 0.5px;
}

/* Custom Scrollbar for the Modal */
.modal-body-custom::-webkit-scrollbar {
    width: 6px;
}

.modal-body-custom::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
}

/* Glass Dropdown Styling */
.glass-dropdown {
    background: rgba(30, 41, 59, 0.95) !important;
    /* Slightly more opaque for focus */
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    z-index: 10000 !important;
    /* Extremely high to beat any row layering */
}

.glass-dropdown .dropdown-item {
    border-radius: 8px;
    padding: 8px 15px;
    transition: 0.2s;
}

.glass-dropdown .dropdown-item:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(5px);
}

.table-responsive {
    overflow: visible !important;
    padding-bottom: 60px;
    /* Buffer for the bottom-most row dropdown */
}

.glass-table {
    --bs-table-bg: transparent !important;
    border-collapse: separate !important;
    border-spacing: 0 15px !important;
    /* Ensure the table itself doesn't trap the menu */
    position: relative;
    z-index: 1;
}
</style>
