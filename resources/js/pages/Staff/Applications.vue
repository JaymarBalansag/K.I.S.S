<template>


    <main class="content-overlay">
        <div class="container py-5 mt-5">

            <!-- For title -->
            <div class="row justify-content-center text-center mb-5 mt-4">
                <div class="col-lg-9 col-xl-8">
                    <span class="badge bg-primary bg-opacity-75 rounded-pill px-4 py-2 mb-3 shadow-sm text-uppercase fw-bold animate__animated animate__fadeInDown">
                        Marriage License Applications
                    </span>
                    <h2 class="text-white fw-bold text-shadow-heavy">Application Registry</h2>
                </div>
            </div>

            <!-- For filters and search bar -->
            <div class="row g-3 mb-4 animate__animated animate__fadeIn">
                <div class="col-md-5">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input 
                            type="text" 
                            v-model="search" 
                            class="form-control border-start-0 ps-0" 
                            placeholder="Search by Groom, Bride, or Control Number..."
                        >
                    </div>
                </div>

                <div class="col-md-3">
                    <select v-model="status" class="form-select shadow-sm">
                        <option value="all">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="issued">Issued</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select v-model="order" class="form-select shadow-sm">
                        <option value="desc">Newest First</option>
                        <option value="asc">Oldest First</option>
                    </select>
                </div>

                <div class="col-md-2 text-md-end">
                    <button class="btn btn-outline-secondary w-100 shadow-sm text-white" @click="resetFilters">
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
                                            {{app.control_number}}
                                        </div>
                                    </td>
                                    <td class="px-4 fw-bold text-white border-0 ">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-heart-fill text-danger me-2 small"></i>
                                            {{app.coupleNames}}
                                        </div>
                                    </td>
                                    <td class="text-white opacity-75 border-0">{{ app.dateApplied }}</td>
                                    <td class="border-0">
                                        <span :class="getStatusClass(app.status)">{{ app.status }}</span>
                                    </td>
                                    <td class="text-center border-0 rounded-end-4 px-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button @click="openViewApplicants(app)" class="btn btn-action-glass text-white">
                                                <i class="bi bi-eye-fill me-1"></i> View
                                            </button>

                                            <button v-if="app.status === 'pending'" @click="validateApproval(app, 'approved')" class="btn btn-action-glass text-success">
                                                <i class="bi bi-check-circle-fill me-1"></i> Approve
                                            </button>
                                            <button v-if="app.status === 'pending'" @click="validateApproval(app, 'rejected')" class="btn btn-action-glass text-danger">
                                                <i class="bi bi-x-circle-fill me-1"></i> Reject
                                            </button>

                                            <button v-if="app.status === 'approved'" @click="validateApproval(app, 'issued')" class="btn btn-action-glass text-info">
                                                <i class="bi bi-patch-check-fill me-1"></i> Issue
                                            </button>

                                            <button 
                                                v-if="app.status === 'issued'" 
                                                @click="downloadMarriageLicense(app)" 
                                                class="btn btn-action-glass text-warning"
                                            >
                                                <i class="bi bi-printer-fill me-1"></i> Print
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-md-none px-2">
                        <div v-for="app in applications" :key="'mob-' + app.id" class="mobile-staff-card glass-row rounded-4 p-4 mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="text-white fw-bold mb-0 pe-2">{{ app.coupleNames }}</h6>
                                <span :class="getStatusClass(app.status)">{{ app.status }}</span>
                            </div>
                            <p class="small text-white opacity-50 mb-1">Applied: {{ app.dateApplied }}</p>
                            <p class="small text-white opacity-50 mb-4">Ref: {{ app.control_number }}</p>

                            <div class="d-flex gap-2">
                                <button @click="openViewApplicants(app)" class="btn btn-action-glass text-info flex-grow-1">
                                    <i class="bi bi-eye-fill me-1"></i> View
                                </button>

                                <template v-if="app.status === 'pending'">
                                    <button @click="validateApproval(app, 'approved')" class="btn btn-action-glass text-success">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </button>
                                    <button @click="validateApproval(app, 'rejected')" class="btn btn-action-glass text-danger">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                </template>

                                <button v-if="app.status === 'approved'" @click="validateApproval(app, 'issued')" class="btn btn-action-glass text-warning flex-grow-1">
                                    <i class="bi bi-patch-check-fill me-1"></i> Issue
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
                                v-show="p === 1 || p === totalPages || Math.abs(p - page) <= 1"
                                class="page-item" :class="{ active: page === p }">
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
                    <p class="text-white opacity-50">There are currently no marriage license applications to display.</p>
                </div>
            </div>
        </div>
    </main>

    <div v-if="showApplicantsModal" class="modal-overlay-custom animate__animated animate__fadeIn">
        <div class="modal-body-custom rounded-5 shadow-2xl p-0 border border-white border-opacity-20">
            
            <div class="modal-glass-header p-4 d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-info bg-opacity-10 text-info text-uppercase mb-2 x-small ls-1 px-3 border border-info border-opacity-20">Official Registry Record</span>
                    <h4 class="fw-bold mb-0 text-white">Marriage License Application</h4>
                    <p class="text-info small mb-0 opacity-75 fw-bold mt-1">
                        <i class="bi bi-qr-code-scan me-1"></i> {{ control_number }}
                    </p>
                </div>
                <button class="btn-close btn-close-white opacity-50 hover-opacity-100" @click="closeViewApplicants"></button>
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
                                <label class="x-small text-white opacity-40 text-uppercase ls-1 d-block mb-1">Legal Full Name</label>
                                <h5 class="fw-bold mb-0">{{ person.first_name }} {{ person.middle_name }} {{ person.last_name }}</h5>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <label class="x-small text-white opacity-40 d-block">Birth Date</label>
                                    <span class="small">{{ person.month }}/{{ person.day }}/{{ person.year }} (Age: {{ person.age }})</span>
                                </div>
                                <div class="col-6">
                                    <label class="x-small text-white opacity-40 d-block">Citizenship/Religion</label>
                                    <span class="small">{{ person.citizenship }} | {{ person.religion }}</span>
                                </div>
                                <div class="col-12">
                                    <label class="x-small text-white opacity-40 d-block">Birthplace</label>
                                    <span class="small">{{ person.birth_city }}, {{ person.birth_province }}, {{ person.birth_country }}</span>
                                </div>
                                <div class="col-12">
                                    <label class="x-small text-white opacity-40 d-block">Current Residence</label>
                                    <span class="small opacity-80">{{ person.residence_address }}</span>
                                </div>
                            </div>

                            <div class="p-3 rounded-4 bg-secondary bg-opacity-5 border border-white border-opacity-5 mb-3">
                                <h6 class="x-small text-white opacity-30 text-uppercase fw-bold mb-3 ls-1">Parental Information</h6>
                                <div class="mb-3">
                                    <label class="x-small text-white opacity-40 d-block">Father</label>
                                    <span class="small d-block fw-semibold">{{ person.father_first_name }} {{ person.father_middle_name }} {{ person.father_last_name }}</span>
                                    <span class="x-small opacity-50">{{ person.father_citizenship }} — {{ person.father_residence }}</span>
                                </div>
                                <div class="mb-0">
                                    <label class="x-small text-white opacity-40 d-block">Mother</label>
                                    <span class="small d-block fw-semibold">{{ person.mother_first_name }} {{ person.mother_middle_name }} {{ person.mother_last_name }}</span>
                                    <span class="x-small opacity-50">{{ person.mother_citizenship }} — {{ person.mother_residence }}</span>
                                </div>
                            </div>

                            <div v-if="person.civil_status !== 'Single'" class="p-2 rounded-3 border border-warning border-opacity-20 bg-secondary bg-opacity-5 mt-2">
                                <label class="x-small text-white opacity-75 d-block text-uppercase fw-bold">Previous Marriage Details</label>
                                <div class="x-small text-white">{{ person.dissolution_details !== "N/A" ? person.dissolution_details : "Not applicable" }} — {{ person.dissolution_place }}</div>
                                <div class="x-small text-white">{{ person.dissolution_day !== "N/A  " ? person.dissolution_day : "Not applicable" }}/{{ person.dissolution_month }}/{{ person.dissolution_year }}</div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-black bg-opacity-20 d-flex justify-content-between align-items-center mt-auto">
                <div>
                    <p class="x-small text-secondary opacity-40 mb-0">Record Created: {{ applicant[0].created_at }}</p>
                    <p class="x-small text-secondary opacity-40 mb-0">Data Submitted: {{ applicant[0].submitted_at }}</p>
                </div>
                <div class="d-flex gap-2">
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
                    <span class="badge bg-info bg-opacity-10 text-info text-uppercase mb-2 x-small ls-1 px-3 border border-info border-opacity-20">Official Registry Record</span>
                    <h4 class="fw-bold mb-0 text-white">Marriage License Application</h4>
                    <p class="text-info small mb-0 opacity-75 fw-bold mt-1">
                        <i class="bi bi-qr-code-scan me-1"></i> {{ control_number }}
                    </p>
                </div>
                <button class="btn-close btn-close-white opacity-50 hover-opacity-100" @click="closeDocumentModal"></button>
            </div>

            <div class="p-4">
                <div class="nav-glass-tabs d-flex p-1 mb-4 rounded-3 bg-white bg-opacity-5" style="max-width: 400px; margin: 0 auto;">
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
                    <h4 class="fw-bold mb-0 text-white mb-3">{{ activeTab === "groom" ? applicant[0].first_name + " " + applicant[0].last_name : applicant[1].first_name + " " + applicant[1].last_name }}</h4>
                    <div v-for="doc in groomDocuments" :key="doc.id" class="doc-item-glass d-flex align-items-center p-3 mb-2 rounded-4 border border-white border-opacity-10">
                        <div class="doc-icon me-3">
                            <i class="bi bi-file-earmark-pdf fs-4 text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-white small fw-bold">{{ doc.doc_type }}</div>
                            <div class="x-small text-secondary fw-semibold">Uploaded: {{ doc.created_at }}</div>
                        </div>
                        <button @click="openCurrentDocument(doc.document_url)" class="btn btn-sm btn-outline-info rounded-pill px-3">View</button>
                    </div>
                </div>
                <div class="document-grid animate__animated animate__fadeIn" v-else-if="activeTab === 'bride'">
                    <h6 class="text-white-50 x-small text-uppercase ls-1 mb-3">Required Credentials</h6>
                    <h4 class="fw-bold mb-0 text-white mb-3">{{ activeTab === "groom" ? applicant[0].first_name + " " + applicant[0].last_name : applicant[1].first_name + " " + applicant[1].last_name }}</h4>
                    <div v-for="doc in brideDocuments" :key="doc.id" class="doc-item-glass d-flex align-items-center p-3 mb-2 rounded-4 border border-white border-opacity-10">
                        <div class="doc-icon me-3">
                            <i class="bi bi-file-earmark-pdf fs-4 text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-white small fw-bold">{{ doc.doc_type }}</div>
                            <div class="x-small text-secondary fw-semibold">Uploaded: {{ doc.created_at }}</div>
                        </div>
                        <button class="btn btn-sm btn-outline-info rounded-pill px-3">View</button>
                    </div>
                </div>
                
            </div>

            

            <div class="p-4 bg-black bg-opacity-20 d-flex justify-content-between align-items-center mt-auto">
                <div>
                    <p class="x-small text-secondary opacity-40 mb-0">Record Created: {{ applicant[0].created_at }}</p>
                    <p class="x-small text-secondary opacity-40 mb-0">Data Submitted: {{ applicant[0].submitted_at }}</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-action-glass text-secondary" @click="closeDocumentModal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div v-if="showDocument" class="modal-overlay-custom animate__animated animate__fadeIn">
        <div class="modal-body-custom preview-large rounded-4 shadow-2xl p-0 border border-white border-opacity-20 bg-dark">
            
            <div class="modal-glass-header p-3 d-flex justify-content-between align-items-center border-bottom border-white border-opacity-10">
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

            <div class="document-viewer-content bg-black bg-opacity-40 p-2 d-flex justify-content-center align-items-center">
                
                <iframe v-if="currentFileIsPDF" :src="currentFilePath" class="w-100 h-100 rounded-3 border-0"></iframe>

                <div v-else class="image-zoom-container w-100 h-100 d-flex justify-content-center align-items-center overflow-auto">
                    <img :src="currentFilePath" class="img-fluid rounded-2 shadow" style="max-height: 100%; object-fit: contain;" alt="Document Preview">
                </div>

            </div>

            <div class="p-3 bg-black bg-opacity-40 border-top border-white border-opacity-10 d-flex justify-content-between align-items-center">
                <div class="d-flex gap-4">
                    <div class="timestamp-group">
                        <span class="x-small text-secondary text-uppercase d-block opacity-50">Reference</span>
                        <span class="small text-white fw-bold">{{ activeTab.toUpperCase() }}'S RECORD</span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-action-glass text-white border-white border-opacity-25 px-4" @click="closeCurrentDocument">
                        <i class="bi bi-arrow-left me-2"></i>Back to List
                    </button>
                    <a :href="currentFilePath" target="_blank" class="btn btn-info text-white px-4">
                        <i class="bi bi-download me-2"></i>Download
                    </a>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import Swal from 'sweetalert2';
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
            showApplicantDocuments: false,
            activeTab: "groom",
            showDocument: false,
            currentFilePath: '',
            currentFileIsPDF: false,
        };
    },
    computed: {
        allDocuments() {
            if (!this.selectedApp) return [];
            return [...this.selectedApp.groomDocuments, ...this.selectedApp.brideDocuments];
        },
    },
    methods: {
        async openViewApplicants(app) {

            const response = await viewApplicants(app.id, app.control_number)
            
            // console.log(response.applicants[0])
            this.applicant = response.data.applicants;
            this.control_number = response.data.applicants[0].control_number;
            this.groomDocuments = response.data.groomDocuments;
            this.brideDocuments = response.data.brideDocuments;

            // console.log(response.data.applicants[0])


            this.showApplicantsModal = true;
        },
        closeViewApplicants() {
            this.showApplicantsModal = false; 
        },

        openDocumentModal(){
            this.showApplicantsModal = false;
            this.showApplicantDocuments = true;
        },
        closeDocumentModal(){
            this.showApplicantDocuments = false;
            this.showApplicantsModal = true;
        },

        openCurrentDocument(path){
            this.currentFilePath = path;
            // Simple check to see if it's a PDF
            this.currentFileIsPDF = path.toLowerCase().endsWith('.pdf');
            this.showDocument = true
        },
        closeCurrentDocument(){
            this.showDocument = false;
            this.currentFilePath = '';
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
                    coupleNames: app.applicant_names 
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
        
        validateApproval(app, action){

            let userAction ;

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

        async validateAction(app, action){
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

        // In your Vue methods
        async downloadMarriageLicense(app) {
            try {
                // Axios automatically attaches your Bearer Token if configured
                const response = await axios.get(`/api/applications/print/${app.id}/${app.control_number}`, {
                    responseType: 'blob', // Tells browser to treat response as binary file
                });

                // Create a temporary 'virtual' link to the file in browser memory
                const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const url = window.URL.createObjectURL(blob);
                
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', `Marriage_App_${app.control_number}.xlsx`);
                
                document.body.appendChild(link);
                link.click();
                
                // Clean up memory and remove the link
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);
            } catch (error) {
                console.error("Download failed", error);
                Swal.fire('Error', 'Could not generate the form. Session may have expired.', 'error');
            }
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
    color: #0d6efd; /* Official Blue */
    border: 1px solid rgba(13, 110, 253, 0.3);
}

.status-rejected {
    color: #ff4d4d; /* Sharp Red */
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
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(15, 23, 42, 0.7); /* Darker, more professional overlay */
    backdrop-filter: blur(12px);
    display: flex; align-items: center; justify-content: center;
    z-index: 2000;
}

/* The Main Container */
.modal-body-custom {
    background: rgba(255, 255, 255, 0.05); /* Very faint white */
    border: 1px solid rgba(255, 255, 255, 0.2); /* Thin 'ice' edge */
    width: 95%;
    max-width: 900px;
    max-height: 90vh;
    overflow-y: auto;
    color: white;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

/* Muted Groom & Bride Cards */
.applicant-glass-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: transform 0.2s ease;
}

/* Muted Blue for Groom */
.groom-accent {
    border-left: 4px solid #60a5fa !important; /* Soft Sky Blue */
}
.groom-accent .type-pill {
    background: rgba(96, 165, 250, 0.15);
    color: #93c5fd;
}

/* Muted Rose for Bride */
.bride-accent {
    border-left: 4px solid #f472b6 !important; /* Soft Rose */
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
    background: rgba(30, 41, 59, 0.95) !important; /* Slightly more opaque for focus */
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    z-index: 10000 !important; /* Extremely high to beat any row layering */
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
    padding-bottom: 60px; /* Buffer for the bottom-most row dropdown */
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