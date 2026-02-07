import './bootstrap';
import { createApp } from 'vue';
import App from '../App.vue';
import router from './router';

// Bootstrap & Icons
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.css';

// SweetAlert2 Setup
import Swal from 'sweetalert2';

// 1. Configure Global Toast (Small notifications)
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

// 2. Assign to window so they are accessible in any .vue file without importing
window.Swal = Swal;
window.Toast = Toast;

const app = createApp(App);
app.use(router);
app.mount('#app');