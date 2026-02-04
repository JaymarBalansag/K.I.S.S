import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/', redirect: '/home',
    },
    {
      path: '/isCohabiting', name: 'isCohabiting', component: () => import("../pages/main/MarriageLicense/isCohabiting.vue")  
    },
    {
      path: '/isForeigner/Cohab/:isCohabiting', name: 'IsForeigner', component: () => import("../pages/main/MarriageLicense/isForeigner.vue")
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
        path: '/about', name: 'About', component: () => import('../pages/main/About.vue') 
    },
    { 
        path: '/contact', name: 'Contact', component: () => import('../pages/main/ContactUs.vue') 
    },
    { 
        path: '/faq', name: 'FAQ', component: () => import('../pages/main/Faq.vue') 
    },
    { 
        path: '/wedding-appointment', name: 'Wedding Appointment', component: () => import('../pages/main/WeddingAppointment.vue') 
    },

];

const router = createRouter({
  history: createWebHistory(),
  routes
})



export default router;
