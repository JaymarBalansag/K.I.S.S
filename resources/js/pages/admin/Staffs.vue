<template>
    <div class="staff-content animate__animated animate__fadeIn px-2 px-md-3">

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
            <h4 class="text-white fw-bold mb-0">Staff Management</h4>
            <router-link to="/Admin/Staffs/Add"
                class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm border-0 transition-all">
                <i class="bi bi-plus-lg me-1"></i> Add Staff
            </router-link>
        </div>

        <div class="table-responsive d-none d-md-block">
            <table class="table glass-table align-middle">
                <thead>
                    <tr class="text-uppercase small opacity-75 ls-1">
                        <th class="px-4 py-3 text-white border-0">Staff Name</th>
                        <th class="py-3 text-white border-0">Position</th>
                        <th class="py-3 text-white border-0">Email Address</th>
                        <th class="py-3 text-center text-white border-0">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(staff, index) in staffList" :key="staff.id" class="glass-row transition">
                        <td class="px-4 fw-bold text-white border-0 rounded-start-4">
                            <div class="d-flex align-items-center">
                                <div
                                    class="avatar-sm bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center shadow-sm">
                                    {{ staff.name.charAt(0) }}
                                </div>
                                {{ staff.name }}
                            </div>
                        </td>
                        <td class="border-0">
                            <span class="badge glass-pill text-info border border-info border-opacity-25 px-3 py-2">
                                {{ staff.role }}
                            </span>
                        </td>
                        <td class="text-white opacity-75 border-0">{{ staff.email }}</td>
                        <td class="text-center border-0 rounded-end-4 px-4">
                            <div class="d-flex justify-content-center gap-2">
                                <router-link :to="`/Admin/Staffs/Edit/${staff.id}`"
                                    class="btn btn-action-glass text-info" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </router-link>
                                <button @click="deleteStaff(index)" class="btn btn-action-glass text-danger"
                                    title="Delete">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-md-none">
            <div v-for="(staff, index) in staffList" :key="'mob-' + staff.id"
                class="mobile-staff-card glass-row rounded-4 p-3 mb-3 animate__animated animate__fadeInUp border-white border-opacity-10">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center shadow-lg"
                            style="width: 48px; height: 48px;">
                            {{ staff.name.charAt(0) }}
                        </div>
                        <div>
                            <h6 class="text-white fw-bold mb-0">{{ staff.name }}</h6>
                            <small class="text-info opacity-75 fw-medium">{{ staff.role }}</small>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-link text-white opacity-50 p-0" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical fs-5"></i>
                        </button>
                        <ul
                            class="dropdown-menu dropdown-menu-end glass-dropdown border-white border-opacity-10 shadow-lg">
                            <li><button @click="openModal(staff, index)" class="dropdown-item text-white py-2"><i
                                        class="bi bi-pencil me-2 text-info"></i>Edit</button></li>
                            <li><button @click="deleteStaff(index)" class="dropdown-item text-danger py-2"><i
                                        class="bi bi-trash me-2"></i>Delete</button></li>
                        </ul>
                    </div>
                </div>
                <div class="pt-2 border-top border-white border-opacity-10 d-flex align-items-center">
                    <span class="small text-white opacity-50 text-truncate">
                        <i class="bi bi-envelope me-1"></i> {{ staff.email }}
                    </span>
                </div>
            </div>
        </div>

        <div v-if="staffList.length === 0"
            class="text-center py-5 text-white opacity-50 animate__animated animate__fadeIn">
            <i class="bi bi-people fs-1 d-block mb-3"></i>
            <p>No staff records found.</p>
        </div>
    </div>
</template>

<script>
export default {
    name: 'StaffManagement',
    data() {
        return {
            staffList: [
                { id: 1, name: "Maria Clara", role: "Head Registrar", email: "m.clara@lcro.gov" },
                { id: 2, name: "Juan Dela Cruz", role: "Wedding Coordinator", email: "j.delacruz@lcro.gov" },
                { id: 3, name: "Crisostomo Ibarra", role: "Records Officer", email: "c.ibarra@lcro.gov" },
                { id: 4, name: "Leonor Rivera", role: "Clerk II", email: "l.rivera@lcro.gov" }
            ]
        };
    },
    methods: {
        openModal(staff = null, index = null) {
            // Trigger your modal logic here
            console.log(staff ? "Editing: " + staff.name : "Adding New Staff");
        },
        deleteStaff(index) {
            if (confirm("Are you sure you want to remove this staff member?")) {
                this.staffList.splice(index, 1);
            }
        }
    }
};
</script>

<style scoped>
/* TABLE CONFIGURATION - ESSENTIAL FOR GLASS LOOK */
.glass-table {
    --bs-table-bg: transparent !important;
    --bs-table-hover-bg: transparent !important;
    border-collapse: separate !important;
    border-spacing: 0 12px !important;
    /* Creates the space between floating rows */
}

/* FLOATING GLASS ROW */
.glass-row {
    background: rgba(255, 255, 255, 0.05) !important;
    backdrop-filter: blur(15px) saturate(160%);
    -webkit-backdrop-filter: blur(15px) saturate(160%);
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.glass-row:hover {
    background: rgba(255, 255, 255, 0.1) !important;
    transform: translateY(-3px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
}

/* TABLE CELL PADDING REFINEMENT */
.glass-row td {
    padding-top: 1.2rem;
    padding-bottom: 1.2rem;
}

/* GLASS PILL BADGE */
.glass-pill {
    background: rgba(13, 202, 240, 0.1) !important;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(13, 202, 240, 0.2) !important;
    font-size: 0.75rem;
    font-weight: 500;
}

/* ACTION BUTTONS (Frosty style) */
.btn-action-glass {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    padding: 6px 12px;
    transition: all 0.2s ease;
}

.btn-action-glass:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.4);
    transform: scale(1.1);
}

/* AVATAR STYLE (Gradient match to LCRO theme) */
.avatar-sm {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
    font-size: 0.9rem;
    font-weight: bold;
}

/* DROPDOWN REFINEMENT (Mobile) */
.glass-dropdown {
    background: rgba(15, 23, 42, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 12px;
    padding: 0.5rem;
}

.dropdown-item:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white !important;
    border-radius: 8px;
}

/* UTILITIES */
.ls-1 {
    letter-spacing: 1px;
}

.transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.transition {
    transition: all 0.3s ease;
}

/* FIX FOR IOS/SAFARI BACKDROP BLUR */
@supports not (backdrop-filter: blur(15px)) {
    .glass-row {
        background: rgba(30, 41, 59, 0.9) !important;
    }
}
</style>