import './bootstrap';
import 'flowbite'
import AOS from 'aos';
import 'aos/dist/aos.css';
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import DataTable from 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.min.css';

// Make libraries globally available
window.Swal = Swal;
window.DataTable = DataTable;

window.Alpine = Alpine
Alpine.start()

// Initialize AOS
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true,
    mirror: false
});
