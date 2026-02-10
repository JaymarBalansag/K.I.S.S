<template>
    <div class="management-page" :style="{ backgroundImage: 'url(/background.jpg)' }">
        <main class="content-overlay">
            <div class="container py-5 mt-5">
                <div class="row justify-content-center text-center mb-5 mt-4">
                    <div class="col-lg-9 col-xl-8">
                        <span class="badge bg-primary bg-opacity-75 rounded-pill px-4 py-2 mb-3 shadow-sm text-uppercase fw-bold animate__animated animate__fadeInDown">
                            Marriage License Applications
                        </span>
                        <h2 class="text-white fw-bold text-shadow-heavy">Application Registry</h2>
                    </div>
                </div>

                <div class="staff-content animate__animated animate__fadeInUp">
                    <div v-if="paginatedApplications.length > 0">
                        <div class="table-responsive d-none d-md-block">
                            <table class="table glass-table align-middle">
                                <thead>
                                    <tr class="text-uppercase small opacity-75 ls-1">
                                        <th class="px-4 py-3 text-white border-0">Couple Names</th>
                                        <th class="py-3 text-white border-0">Application Date</th>
                                        <th class="py-3 text-white border-0">Status</th>
                                        <th class="py-3 text-center text-white border-0">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="app in paginatedApplications" :key="app.id" class="glass-row transition">
                                        <td class="px-4 fw-bold text-white border-0 rounded-start-4">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-heart-fill text-danger me-2 small"></i>
                                                {{ app.groomName }} & {{ app.brideName }}
                                            </div>
                                        </td>
                                        <td class="text-white opacity-75 border-0">{{ app.dateApplied }}</td>
                                        <td class="border-0">
                                            <span :class="getStatusClass(app.status)">{{ app.status }}</span>
                                        </td>
                                        <td class="text-center border-0 rounded-end-4 px-4">
                                            <div class="d-flex justify-content-center gap-2">
                                                <button @click="openViewApplicants(app)" class="btn btn-action-glass text-info">
                                                    <i class="bi bi-eye-fill me-1"></i> View
                                                </button>
                                                <button v-if="app.status === 'pending'" @click="approveApplication(app.id)" class="btn btn-action-glass text-success">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Approve
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-md-none px-2">
                            <div v-for="app in paginatedApplications" :key="'mob-' + app.id" class="mobile-staff-card glass-row rounded-4 p-4 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="text-white fw-bold mb-0 pe-2">{{ app.groomName }} & {{ app.brideName }}</h6>
                                    <span :class="getStatusClass(app.status)">{{ app.status }}</span>
                                </div>
                                <p class="small text-white opacity-50 mb-4">Applied: {{ app.dateApplied }}</p>
                                <div class="d-flex gap-2">
                                    
                                    <button @click="openViewApplicants(app)" class="btn btn-action-glass text-info">
                                        <i class="bi bi-eye-fill me-1"></i> View
                                    </button>
                                    <button v-if="app.status === 'pending'" @click="" class="btn btn-action-glass text-success flex-grow-1">
                                        <i class="bi bi-check-circle-fill"></i> Approve
                                    </button>
                                </div>
                            </div>
                        </div>

                        <nav v-if="totalPages > 1" class="d-flex justify-content-center mt-4">
                            <ul class="pagination glass-pagination">
                                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                                    <button class="page-link" @click="currentPage--"><i class="bi bi-chevron-left"></i></button>
                                </li>
                                <li v-for="page in totalPages" :key="page" class="page-item" :class="{ active: currentPage === page }">
                                    <button class="page-link" @click="currentPage = page">{{ page }}</button>
                                </li>
                                <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                                    <button class="page-link" @click="currentPage++"><i class="bi bi-chevron-right"></i></button>
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
                        <button class="btn btn-action-glass text-info px-4" @click="printApplication">
                            <i class="bi bi-printer-fill me-1"></i> Generate License
                        </button>
                        <button class="btn btn-action-glass text-secondary" @click="closeViewApplicants">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import Swal from 'sweetalert2';
import { getApplicants, viewApplicants } from '../../controller/MarriageLicense';

export default {
    data() {
        return {
            applications: [], // Start with empty array
            currentPage: 1,
            itemsPerPage: 5, // Change this to set how many rows per page
            isLoading: false,
            showApplicantsModal: false,
            control_number: "",
            applicant: [],
            groomDocuments: [],
            brideDocuments: [],
        };
    },
    computed: {
        totalPages() {
            return Math.ceil(this.applications.length / this.itemsPerPage);
        },
        paginatedApplications() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.applications.slice(start, end);
        },
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
        
        async fetchApplications() {
            this.isLoading = true;
            try {
                const response = await getApplicants();
                const rawData = response.data.data.data || [];

                const grouped = rawData.reduce((acc, current) => {
                    const key = current.control_number;
                    if (!acc[key]) {
                        acc[key] = {
                            control_number: key,
                            status: current.status,
                            dateApplied: current.submitted_at,
                            // Store the full objects here
                            groom: null,
                            bride: null
                        };
                    }

                    // First one found is groom, second is bride
                    if (!acc[key].groom) {
                        acc[key].groom = current;
                    } else {
                        acc[key].bride = current;
                    }
                    return acc;
                }, {});

                // Map it to a format the table and modal can use
                this.applications = Object.values(grouped).map(couple => ({
                    ...couple,
                    id: couple.groom.id, // for table keys
                    groomName: `${couple.groom.first_name} ${couple.groom.last_name}`,
                    brideName: couple.bride ? `${couple.bride.first_name} ${couple.bride.last_name}` : 'N/A'
                }));
            } catch (error) {
                console.error("Fetch error:", error);
            } finally {
                this.isLoading = false;
            }
        },

        // async viewApplicants(){

        // },

        getStatusClass(status) {
            const base = "badge glass-pill px-3 py-2 ";
            const s = status ? status.toLowerCase() : '';
            return s === 'approved' ? base + "status-approved" : base + "status-pending";
        },
    },
    mounted() {
        this.fetchApplications();
    }
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


</style>