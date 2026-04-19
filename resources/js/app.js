import './bootstrap';
import 'flowbite'
import AOS from 'aos';
import 'aos/dist/aos.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
import Swal from 'sweetalert2';
import DataTable from 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.min.css';
import Chart from 'chart.js/auto';
import { initFlowbite } from 'flowbite';

initFlowbite();

document.addEventListener('livewire:navigated', () => initFlowbite());

window.Chart = Chart;

window.Swal = Swal;
window.DataTable = DataTable;

// Initialize AOS
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true,
    mirror: false
});
