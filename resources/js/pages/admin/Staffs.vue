<template>
    <div class="management-page" :style="{ backgroundImage: 'url(/background.jpg)' }">
        <main class="content-overlay">
            <div class="container py-5 mt-5">
                <div class="row justify-content-center text-center mb-5 mt-4">
                    <div class="col-lg-9 col-xl-8">
                        <span
                            class="badge bg-primary bg-opacity-75 rounded-pill px-4 py-2 mb-3 shadow-sm text-uppercase fw-bold animate__animated animate__fadeInDown"
                            style="letter-spacing: 1px;">
                            Personnel & Staff Management
                        </span>
                        <div class="d-flex justify-content-center align-items-center gap-3 mt-3">
                            <h2 class="text-white fw-bold mb-0 text-shadow-heavy">Administrative Records</h2>
                            <router-link to="/Admin/Staffs/Add"
                                class="btn btn-action-glass text-info animate__animated animate__fadeInRight">
                                <i class="bi bi-person-plus-fill me-2"></i> Add Staff
                            </router-link>
                        </div>
                    </div>
                </div>

                <div class="staff-content animate__animated animate__fadeInUp">
                    <div v-if="loading" class="text-center py-5 text-white">
                        <div class="spinner-border text-info" style="width: 3rem; height: 3rem;" role="status"></div>
                        <p class="mt-3 opacity-75 fw-bold">Synchronizing records...</p>
                    </div>

                    <template v-else-if="staffList && staffList.length > 0">
                        <div class="table-responsive d-none d-md-block">
                            <table class="table glass-table align-middle">
                                <thead>
                                    <tr class="text-uppercase small opacity-75 ls-1">
                                        <th class="px-4 py-3 text-white border-0">Staff Member</th>
                                        <th class="py-3 text-white border-0">Position</th>
                                        <th class="py-3 text-white border-0">Email Address</th>
                                        <th class="py-3 text-center text-white border-0">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="staff in staffList" :key="staff.id" class="glass-row transition">
                                        <td class="px-4 fw-bold text-white border-0 rounded-start-4">
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="avatar-sm bg-info bg-opacity-25 text-info rounded-circle me-3 d-flex align-items-center justify-content-center shadow-sm border border-info border-opacity-25">
                                                    {{ staff.first_name ? staff.first_name.charAt(0) : '?' }}
                                                </div>
                                                <div class="text-shadow-medium">
                                                    {{ staff.first_name }} {{ staff.last_name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="border-0">
                                            <span
                                                class="badge glass-pill text-info border border-info border-opacity-25 px-3 py-2 text-capitalize">
                                                {{ staff.role }}
                                            </span>
                                        </td>
                                        <td class="text-white opacity-75 border-0">
                                            {{ staff.email }}
                                        </td>
                                        <td class="text-center border-0 rounded-end-4 px-4">
                                            <div class="d-flex justify-content-center gap-2">
                                                <router-link :to="`/Admin/Staffs/Edit/${staff.id}`"
                                                    class="btn btn-action-glass text-info" title="Edit Profile">
                                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                                </router-link>
                                                <button @click="deleteStaff(staff.id)"
                                                    class="btn btn-action-glass text-danger" title="Remove Staff">
                                                    <i class="bi bi-trash3 me-1"></i> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-md-none px-2">
                            <div v-for="staff in staffList" :key="'mob-' + staff.id"
                                class="mobile-staff-card glass-row rounded-4 p-4 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-md bg-info bg-opacity-25 text-info rounded-circle me-3 d-flex align-items-center justify-content-center shadow-lg border border-info border-opacity-25"
                                            style="width: 45px; height: 45px; font-weight: bold;">
                                            {{ staff.first_name ? staff.first_name.charAt(0) : '?' }}
                                        </div>
                                        <div>
                                            <h6 class="text-white fw-bold mb-0 text-capitalize">{{ staff.first_name }}
                                                {{ staff.last_name }}</h6>
                                            <small class="text-info opacity-75 fw-medium text-uppercase ls-1">{{
                                                staff.role }}</small>
                                        </div>
                                    </div>
                                </div>
                                <p class="small text-white opacity-50 mb-4">
                                    <i class="bi bi-envelope-at me-2"></i>{{ staff.email }}
                                </p>
                                <div class="d-flex gap-2">
                                    <router-link :to="`/Admin/Staffs/Edit/${staff.id}`"
                                        class="btn btn-action-glass text-info flex-grow-1">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </router-link>
                                    <button @click="deleteStaff(staff.id)"
                                        class="btn btn-action-glass text-danger flex-grow-1">
                                        <i class="bi bi-trash3"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div v-else class="text-center py-5 animate__animated animate__fadeIn">
                        <div class="py-5 glass-row rounded-4 border border-white border-opacity-10 mx-auto"
                            style="max-width: 600px;">
                            <i class="bi bi-people text-info opacity-25 display-1 d-block mb-3"></i>
                            <h4 class="text-white fw-bold">No Staff Found</h4>
                            <p class="text-white opacity-50 px-4">The registry database is currently empty of staff
                                personnel.</p>
                            <router-link to="/Admin/Staffs/Add" class="btn btn-action-glass text-info mt-2">
                                <i class="bi bi-plus-lg me-2"></i>Register First Staff
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import { DeleteStaff, ReadStaff } from '../../controller/Staffs';

export default {
    name: 'StaffManagement',
    data() {
        return {
            staffList: [],
            loading: true
        };
    },
    created() {
        this.fetchStaffs();
    },
    methods: {
        async fetchStaffs() {
            try {
                this.loading = true;
                const response = await ReadStaff('/Staffs');
                const data = response.data.data || response.data;
                this.staffList = Array.isArray(data) ? data : [];
            } catch (error) {
                console.error("Fetch error:", error);
                this.staffList = [];
            } finally {
                this.loading = false;
            }
        },
        async deleteStaff(id) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently revoke staff access.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                background: '#0f172a',
                color: '#fff',
                backdrop: `rgba(15, 23, 42, 0.8)`
            });

            if (result.isConfirmed) {
                try {
                    await DeleteStaff(id);
                    this.staffList = this.staffList.filter(s => s.id !== id);
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Account has been removed.',
                        icon: 'success',
                        background: '#0f172a',
                        color: '#fff'
                    });
                } catch (e) {
                    Swal.fire('Error', 'Failed to remove staff record.', 'error');
                }
            }
        }
    }
};
</script>

<style scoped>
/* UNIFIED BACKGROUND & OVERLAY */
.management-page {
    min-height: 100vh;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    position: relative;
}

.content-overlay {
    min-height: 100vh;
    background: rgba(15, 23, 42, 0.5);
    /* Darker tint for better text contrast */
}

/* GLASS TABLE DESIGN */
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

/* BUTTONS & UI ELEMENTS */
.btn-action-glass {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: white;
    border-radius: 12px;
    padding: 8px 18px;
    font-size: 0.85rem;
    font-weight: 600;
    transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-action-glass:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
    transform: scale(1.05);
}

.glass-pill {
    background: rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(4px);
    font-weight: 600;
    letter-spacing: 0.5px;
}

.avatar-sm {
    width: 38px;
    height: 38px;
    font-weight: 800;
}

/* TYPOGRAPHY */
.text-shadow-heavy {
    text-shadow: 0 4px 15px rgba(0, 0, 0, 0.8);
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

/* MOBILE SPECIFIC */
.mobile-staff-card {
    border: 1px solid rgba(255, 255, 255, 0.1);
}
</style>