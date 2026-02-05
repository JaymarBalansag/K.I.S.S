import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/', redirect: '/home',
    },
    {
        path: '/login', name: 'Login', component: () => import('../pages/Auth/Login.vue')
    },
    {
        path: '/home', name: 'Home', component: () => import('../pages/main/LandingPage.vue')
    },
    {
        path: '/about', name: 'About', component: () => import('../pages/main/About.vue')
    },
    {
        path: '/contact', name: 'Contact', component: () => import('../pages/main/ContactUs.vue')
    },
    {
        path: '/faq', name: 'FAQ', component: () => import('../pages/main/Faq.vue')
    },
    {
        path: '/wedding-appointment', name: 'Wedding Appointment', component: () => import('../pages/main/WeddingAppointment/Services.vue')
    },
    {
      path: '/isForeigner/Cohab/:isCohabiting', name: 'IsForeigner', component: () => import("../pages/main/MarriageLicense/isForeigner.vue")
    },
    {
        path: '/isCohabiting', name: 'isCohabiting', component: () => import("../pages/main/MarriageLicense/isCohabiting.vue")
    },
    {
      path: '/ageRange/isForeigner/:isForeigner/Cohab/:isCohabiting', name: 'AgeRange', component: () => import("../pages/main/MarriageLicense/AgeRange.vue")
    },
    {
      path: '/Marriage-Form/ageRange/requirements/:requirements/isForeigner/:isForeigner/Cohab/:isCohabiting', name: 'MarriageForm', component: () => import("../pages/main/MarriageLicense/MarriageForm.vue")
    },
    {
        path: '/home', name: 'Home', component: () => import('../pages/main/LandingPage.vue')
    },
    {
        path: '/Civil-Wedding-Appointment', name: 'Marriage License', component: () => import('../pages/main/WeddingAppointment/CivilWedding.vue')
    },
    {
        path: '/Pre-Marriage-Counseling', name: 'Pre-Marriage Counseling', component: () => import('../pages/main/WeddingAppointment/PreMarriageCounseling.vue')
    },
    {
        path: "/Service-Status", name: "ServiceDashboard", component: () => import("../pages/User/Dashboard.vue"),
    },
    {
        path: "/Civil-Wedding-Appointment-Form", name: "CivilWeddingAppointmentForm", component: () => import("../pages/main/WeddingAppointment/CivilWeddingAppointmentForm.vue"),
    },
    {
        path: "/Pre-Marriage-Counseling-Form", name: "PreMarriageCounselingAppointmentForm", component: () => import("../pages/main/WeddingAppointment/PreMarriageAppointmentForm.vue"),
    },
    {
        path: "/Staff",
        component: () => import("../pages/Staff/Layout.vue"), // The Wrapper
        children: [
            {
                path: "", // This handles the base /Staff URL
                redirect: { name: 'StaffDashboard' }
            },
            {
                path: "Dashboard",
                name: "StaffDashboard",
                component: () => import("../pages/Staff/Dashboard.vue")
            },
            {
                path: "Appointments", name: "StaffAppointments", component: () => import("../pages/Staff/Appointments.vue")
            },
            {
                path: "Applications", name: "StaffApplications", component: () => import("../pages/Staff/Applications.vue")
            },
        ]

    },
    {
        path: "/admin", component: () => import("../pages/admin/Layout.vue"), // The Wrapper
        children: [
            {
                path: "", // This handles the base /admin URL
                redirect: { name: 'AdminDashboard' }
            },
            {
                path: "Dashboard", name: "AdminDashboard", component: () => import("../pages/admin/Dashboard.vue")
            },
            {
                path: "Staffs", name: "AdminStaffs", component: () => import("../pages/admin/Staffs.vue")
            },
            {
                path: "Staffs/Add", name: "AddStaff", component: () => import("../pages/admin/AddStaffForm.vue")
            },
            {
                path: "Staffs/Edit/:id", name: "EditStaff", component: () => import("../pages/admin/EditStaffForm.vue")
            },
            {
                path: "Position", name: "AdminPosition", component: () => import("../pages/admin/Position.vue")
            },
            {
                path: "Position/Add", name: "AddPosition", component: () => import("../pages/admin/AddPositionForm.vue")
            },
            {
                path: "Position/Edit/:id", name: "EditPosition", component: () => import("../pages/admin/EditPositionForm.vue")
            },
        ]
    }
];

const router = createRouter({
  history: createWebHistory(),
  routes
})



export default router;
