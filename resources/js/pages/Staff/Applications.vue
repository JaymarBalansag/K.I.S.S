<template>
    <div class="management-page" :style="{ backgroundImage: 'url(/background.jpg)' }">
        <main class="content-overlay">
            <div class="container py-5 mt-5">
                <div class="row justify-content-center text-center mb-5 mt-4">
                    <div class="col-lg-9 col-xl-8">
                        <span
                            class="badge bg-primary bg-opacity-75 rounded-pill px-4 py-2 mb-3 shadow-sm text-uppercase fw-bold animate__animated animate__fadeInDown"
                            style="letter-spacing: 1px;">
                            Marriage License Applications Management
                        </span>
                    </div>
                </div>

                <div class="staff-content animate__animated animate__fadeInUp">
                    <div class="table-responsive d-none d-md-block">
                        <table class="table glass-table align-middle">
                            <thead>
                                <tr class="text-uppercase small opacity-75 ls-1">
                                    <th class="px-4 py-3 text-white border-0">Applicant Name</th>
                                    <th class="py-3 text-white border-0">Application Date</th>
                                    <th class="py-3 text-white border-0">Status</th>
                                    <th class="py-3 text-center text-white border-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="app in applications" :key="app.id" class="glass-row transition">
                                    <td class="px-4 fw-bold text-white border-0 rounded-start-4">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-heart-fill text-danger me-2 small"></i>
                                            {{ app.groomName }} & {{ app.brideName }}
                                        </div>
                                    </td>
                                    <td class="text-white opacity-75 border-0">
                                        {{ app.dateApplied }}
                                    </td>
                                    <td class="border-0">
                                        <span :class="getStatusClass(app.status)">
                                            {{ app.status }}
                                        </span>
                                    </td>
                                    <td class="text-center border-0 rounded-end-4 px-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button @click="viewApplication(app.id)"
                                                class="btn btn-action-glass text-info">
                                                <i class="bi bi-eye-fill me-1"></i> View
                                            </button>
                                            <button v-if="app.status === 'Pending'" @click="approveApplication(app.id)"
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
                        <div v-for="app in applications" :key="'mob-' + app.id"
                            class="mobile-staff-card glass-row rounded-4 p-4 mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="text-white fw-bold mb-0 pe-2">{{ app.groomName }} & {{ app.brideName }}</h6>
                                <span :class="getStatusClass(app.status)">{{ app.status }}</span>
                            </div>
                            <p class="small text-white opacity-50 mb-4">Applied: {{ app.dateApplied }}</p>
                            <div class="d-flex gap-2">
                                <button @click="viewApplication(app.id)"
                                    class="btn btn-action-glass text-info flex-grow-1">
                                    <i class="bi bi-eye-fill"></i> View
                                </button>
                                <button v-if="app.status === 'Pending'" @click="approveApplication(app.id)"
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
export default {
    data() {
        return {
            applications: [
                { id: 101, groomName: "Ricardo Dalisay", brideName: "Alyana Arevalo", dateApplied: "2026-02-01", status: "Pending" },
                { id: 102, groomName: "Basilio Santos", brideName: "Sisa Reyes", dateApplied: "2026-01-28", status: "Approved" },
                { id: 103, groomName: "Crisostomo Ibarra", brideName: "Maria Clara", dateApplied: "2026-02-03", status: "Pending" }
            ]
        };
    },
    methods: {
        getStatusClass(status) {
            const base = "badge glass-pill px-3 py-2 ";
            return status === 'Approved' ? base + "status-approved" : base + "status-pending";
        },
        viewApplication(id) {
            console.log("Viewing Application:", id);
        },
        approveApplication(id) {
            if (confirm("Confirm approval for this marriage license application?")) {
                const app = this.applications.find(a => a.id === id);
                if (app) app.status = 'Approved';
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

/* Darken the background slightly to make the glass pop */
.content-overlay {
    min-height: 100vh;
    background: rgba(15, 23, 42, 0.4);
    /* Subtle dark tint over your image */
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
    border-color: rgba(255, 255, 255, 0.3) !important;
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
    transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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