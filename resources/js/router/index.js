import { createRouter, createWebHistory } from 'vue-router'
import MasterLayout from '../layouts/master.vue';
const routes = [
    {
        path: '/', redirect: '/sample', component: MasterLayout,
    },
    {
        path: '/sample', name: 'sample', component: () => import('../pages/sample/sample.vue')
    },
    {
        path: '/about', name: 'about', component: () => import('../pages/sample/about.vue')
    },

    { path: '/Home', name: 'Home', component: () => import('../pages/main/LandingPage.vue') },

];

const router = createRouter({
  history: createWebHistory(),
  routes
})



export default router;
