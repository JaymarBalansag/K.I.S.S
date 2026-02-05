<template>
    <div class="position-content animate__animated animate__fadeIn px-2 px-md-3">

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
            <h4 class="text-white fw-bold mb-0">Position Management</h4>
            <router-link to="/Admin/Position/Add"
                class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm border-0 transition-all">
                <i class="bi bi-plus-lg me-1"></i> Add Staff
            </router-link>
        </div>

        <div class="table-responsive d-none d-md-block">
            <table class="table glass-table align-middle">
                <thead>
                    <tr class="text-uppercase small opacity-75 ls-1">
                        <th class="px-4 py-3 text-white border-0">Position Title</th>
                        <th class="py-3 text-white border-0">Department/Category</th>
                        <th class="py-3 text-white border-0 text-center">Staff Count</th>
                        <th class="py-3 text-center text-white border-0">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(pos, index) in positionList" :key="pos.id" class="glass-row transition">
                        <td class="px-4 fw-bold text-white border-0 rounded-start-4">
                            <div class="d-flex align-items-center">
                                <div
                                    class="icon-sm bg-info text-white rounded-3 me-3 d-flex align-items-center justify-content-center shadow-sm">
                                    <i class="bi bi-briefcase"></i>
                                </div>
                                {{ pos.title }}
                            </div>
                        </td>

                        <td class="border-0">
                            <span class="badge glass-pill text-info border border-info border-opacity-25 px-3 py-2">
                                {{ pos.category }}
                            </span>
                        </td>

                        <td class="text-white opacity-75 border-0 text-center">
                            <span class="badge rounded-pill bg-white bg-opacity-10 px-3">{{ pos.count }} Members</span>
                        </td>

                        <td class="text-center border-0 rounded-end-4 px-4">
                            <div class="d-flex justify-content-center gap-2">
                                <router-link :to="`/Admin/Position/Edit/${pos.id}`"
                                    class="btn btn-action-glass text-info" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </router-link>
                                <button @click="deletePosition(index)" class="btn btn-action-glass text-danger"
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
            <div v-for="(pos, index) in positionList" :key="'mob-' + pos.id"
                class="mobile-staff-card glass-row rounded-4 p-3 mb-3 animate__animated animate__fadeInUp border-white border-opacity-10">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex align-items-center">
                        <div class="icon-md bg-info rounded-3 me-3 d-flex align-items-center justify-content-center shadow-lg"
                            style="width: 42px; height: 42px;">
                            <i class="bi bi-briefcase text-white"></i>
                        </div>
                        <div>
                            <h6 class="text-white fw-bold mb-0">{{ pos.title }}</h6>
                            <span class="extra-small text-info opacity-75 fw-medium text-uppercase ls-1">{{ pos.category
                                }}</span>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-link text-white opacity-50 p-0" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical fs-5"></i>
                        </button>
                        <ul
                            class="dropdown-menu dropdown-menu-end glass-dropdown border-white border-opacity-10 shadow-lg">
                            <li><button @click="openModal(pos)" class="dropdown-item text-white py-2"><i
                                        class="bi bi-pencil me-2 text-info"></i>Edit</button></li>
                            <li><button @click="deletePosition(index)" class="dropdown-item text-danger py-2"><i
                                        class="bi bi-trash me-2"></i>Delete</button></li>
                        </ul>
                    </div>
                </div>
                <div
                    class="pt-2 border-top border-white border-opacity-10 d-flex justify-content-between align-items-center">
                    <span class="small text-white opacity-50">Current Usage:</span>
                    <span class="badge rounded-pill bg-info bg-opacity-10 text-info px-3">{{ pos.count }} Staff</span>
                </div>
            </div>
        </div>

        <div v-if="positionList.length === 0" class="text-center py-5 text-white opacity-50">
            <i class="bi bi-award fs-1 d-block mb-3"></i>
            <p>No positions defined yet.</p>
        </div>
    </div>
</template>

<script>
export default {
    name: 'PositionManagement',
    data() {
        return {
            positionList: [
                { id: 1, title: "Head Registrar", category: "Administrative", count: 1 },
                { id: 2, title: "Wedding Coordinator", category: "Events", count: 3 },
                { id: 3, title: "Records Officer", category: "Documentation", count: 2 },
                { id: 4, title: "Clerk II", category: "Administrative", count: 5 }
            ]
        };
    },
    methods: {
        openModal(position = null) {
            console.log(position ? "Editing Position: " + position.title : "Adding New Position");
        },
        deletePosition(index) {
            if (confirm("Warning: Deleting this position may affect staff records assigned to it. Proceed?")) {
                this.positionList.splice(index, 1);
            }
        }
    }
};
</script>

<style scoped>
/* TABLE CONFIGURATION */
.glass-table {
    --bs-table-bg: transparent !important;
    --bs-table-hover-bg: transparent !important;
    border-collapse: separate !important;
    border-spacing: 0 12px !important;
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

.glass-row td {
    padding-top: 1.2rem;
    padding-bottom: 1.2rem;
}

/* GLASS PILL BADGE */
.glass-pill {
    background: rgba(13, 202, 240, 0.12) !important;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(13, 202, 240, 0.25) !important;
    font-size: 0.75rem;
    font-weight: 500;
}

/* ACTION BUTTONS */
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

/* ICON BOXES */
.icon-sm {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
}

.extra-small {
    font-size: 0.65rem;
}

/* MOBILE DROPDOWN */
.glass-dropdown {
    background: rgba(15, 23, 42, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 12px;
}

.ls-1 {
    letter-spacing: 1px;
}

.transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>