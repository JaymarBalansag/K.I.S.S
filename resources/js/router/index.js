import { createRouter, createWebHistory } from 'vue-router'
const routes = [
    {
        path: '/', redirect: '/home',
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
