import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/', redirect: '/home',
    },
    {
        path: '/staff-portal', name: 'Login', component: () => import('../pages/Auth/Login.vue')
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
      path: '/isForeigner/Cohab/:isCohabiting', redirect: '/isCohabiting'
    },
    {
        path: '/isCohabiting', name: 'isCohabiting', component: () => import("../pages/main/MarriageLicense/isCohabiting.vue")
    },
    {
      path: '/ageRange/isForeigner/:isForeigner/:type/Cohab/:isCohabiting', redirect: '/isCohabiting'
    },
    {
      path: '/Marriage-Form/requirements/groom/:groomRequirement/bride/:brideRequirement/isForeigner/:isForeigner/:type/Cohab/:isCohabiting', name: 'MarriageForm', component: () => import("../pages/main/MarriageLicense/MarriageForm.vue")
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
        path: "/download", name: "Download", component: () => import("../pages/main/sample.vue"),
    },
    {
        path: "/Staff",
        component: () => import("../pages/Staff/Layout.vue"), // The Wrapper
        meta: { requiresAuth: true, roleIsStaff: true },
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
        path: "/Admin", component: () => import("../pages/admin/Layout.vue"), // The Wrapper
        meta: { requiresAuth: true, roleIsAdmin: true },
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
                path: "Appointments", name: "AdminAppointments", component: () => import("../pages/admin/Appointments.vue")
            },
            {
                path: "Applications", name: "AdminApplications", component: () => import("../pages/admin/Applications.vue")
            },
            {
                path: "Trash", name: "AdminTrash", component: () => import("../pages/admin/Trash.vue")
            },
            {
                path: "SmsRequests", name: "AdminSmsRequests", component: () => import("../pages/admin/SmsRequests.vue")
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

router.beforeEach((to, from, next) => {
    const isLoggedIn = !!localStorage.getItem("access_token");
    
    // 1. Get the userInfo string and parse it back to an object
    const userInfoString = localStorage.getItem("userInfo");
    const user = userInfoString ? JSON.parse(userInfoString) : null;
    
    // 2. Safely get the role
    const role = user ? user.role : "guest";

    // Debugging: Check your console to see if role is now 'staff' or 'admin'
    // console.log("Current User Role:", role);

    // Redirect logged-in users away from Login/Register
    if ((to.path === "/login" || to.path === "/register") && isLoggedIn) {
        return next("/home");
    }
    
    // Check for Staff permissions
    if (to.meta.roleIsStaff && role !== 'staff' && role !== 'admin') {
        return next("/home"); 
    }
    
    // Check for Admin permissions
    if (to.meta.roleIsAdmin && role !== 'admin') {
        return next("/home"); 
    }

    // Check if route requires auth but user is logged out
    if (to.meta.requiresAuth && !isLoggedIn) {
        localStorage.removeItem("userInfo");
        return next("/login");
    }
    
    next();
});



export default router;
