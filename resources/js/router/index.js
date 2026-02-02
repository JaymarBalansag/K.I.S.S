import { createRouter, createWebHistory } from 'vue-router'
import MasterLayout from '../layouts/master.vue';
import Sample from '../pages/sample/sample.vue';
const routes = [
    {
        path: '/', redirect: '/sample',
    },
    {
        path: '/sample', name: 'sample', component: () => import('../pages/sample/sample.vue')
    },
    {
        path: '/about', name: 'about', component: () => import('../pages/sample/about.vue')
    }

];

const router = createRouter({
  history: createWebHistory(),
  routes
})



export default router;