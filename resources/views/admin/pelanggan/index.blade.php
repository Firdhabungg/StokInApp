@extends('admin.layouts.app')

@section('header_title', 'Manajemen Pelanggan')
@section('header_description', 'Daftar Toko & Status Langganan Tenant')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
        <div class="flex-1 flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <i data-lucide="search" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="searchInput" placeholder="Cari nama toko atau pemilik..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-amber-500 transition-all outline-none">
            </div>
            
            <div class="flex gap-3">
                <div class="relative">
                    <i data-lucide="layers" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <select id="filterPaket" 
                        class="appearance-none pl-10 pr-10 py-2.5 bg-slate-50 border-none rounded-xl text-sm font-semibold text-slate-600 focus:ring-2 focus:ring-amber-500 cursor-pointer outline-none">
                        <option value="">Semua Paket</option>
                        <option value="Starter">Starter</option>
                        <option value="Pro Plan">Pro</option>
                        <option value="Enterprise">Enterprise</option>
                    </select>
                </div>

                <div class="relative">
                    <i data-lucide="filter" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <select id="filterStatus" 
                        class="appearance-none pl-10 pr-10 py-2.5 bg-slate-50 border-none rounded-xl text-sm font-semibold text-slate-600 focus:ring-2 focus:ring-amber-500 cursor-pointer outline-none">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="trial">Masa Trial</option>
                        <option value="expired">Kedaluwarsa</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 border-t md:border-t-0 pt-4 md:pt-0">
            <button id="deleteSelected" class="hidden flex items-center gap-2 px-4 py-2.5 bg-rose-50 text-rose-600 rounded-xl text-sm font-bold hover:bg-rose-100 transition-all">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
                <span class="hidden md:block">Hapus</span>
            </button>
            <button title="Print Laporan" class="p-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-all shadow-sm">
                <i data-lucide="printer" class="w-5 h-5"></i>
            </button>
        </div>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-sm flex flex-col min-h-[520px]">
        <div class="overflow-x-auto flex-grow">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-50 text-slate-400 text-[11px] font-bold uppercase tracking-[0.1em]">
                        <th class="px-6 py-4 w-12 text-center">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-slate-300 text-amber-500 cursor-pointer">
                        </th>
                        <th class="px-4 py-4">Nama Toko & Pemilik</th>
                        <th class="px-6 py-4">Paket</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Sisa Langganan</th>
                        <th class="px-8 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="customer-table-body" class="divide-y divide-slate-50">
                    </tbody>
            </table>
        </div>
        
        <div class="px-8 py-8 bg-white border-t border-slate-50 flex flex-col md:flex-row items-center justify-between gap-4">
            <p id="pagination-info" class="text-xs text-slate-500 font-medium"></p>
            <div id="pagination-controls" class="flex items-center gap-2">
                </div>
        </div>
    </div>
</div>

<script>
    // 1. Data Dummy (10 Data)
    const rawData = [
        { id: 1, toko: "Coffee Shop Senja", owner: "Aris Setiawan", paket: "Pro Plan", status: "active", sisa: "142 Hari", initial: "CS", color: "amber" },
        { id: 2, toko: "Berkah Jaya Mart", owner: "Ibu Siti", paket: "Starter", status: "trial", sisa: "3 Hari", initial: "BJ", color: "slate" },
        { id: 3, toko: "Global Store Indo", owner: "PT. Maju Bersama", paket: "Enterprise", status: "active", sisa: "Unlimited", initial: "GS", color: "purple" },
        { id: 4, toko: "Warung Fitri", owner: "Fitriani", paket: "Starter", status: "expired", sisa: "0 Hari", initial: "WF", color: "rose" },
        { id: 5, toko: "Barber Pro", owner: "Budi Pomade", paket: "Pro Plan", status: "active", sisa: "60 Hari", initial: "BP", color: "blue" },
        { id: 6, toko: "Laundry Wangi", owner: "Siska", paket: "Starter", status: "active", sisa: "15 Hari", initial: "LW", color: "emerald" },
        { id: 7, toko: "Bakery Lezat", owner: "Chef Juna", paket: "Pro Plan", status: "trial", sisa: "5 Hari", initial: "BL", color: "orange" },
        { id: 8, toko: "Pet Shop Meow", owner: "Dr. Kevin", paket: "Enterprise", status: "active", sisa: "200 Hari", initial: "PS", color: "indigo" },
        { id: 9, toko: "Gadget Corner", owner: "Rian", paket: "Pro Plan", status: "active", sisa: "30 Hari", initial: "GC", color: "cyan" },
        { id: 10, toko: "Apotek Sehat", owner: "Apoteker Andi", paket: "Starter", status: "expired", sisa: "0 Hari", initial: "AS", color: "rose" }
    ];

    let currentPage = 1;
const itemsPerPage = 8;

// Tambahkan inisialisasi element global
const btnDelete = document.getElementById('deleteSelected');
const selectAll = document.getElementById('selectAll');

function renderTable() {
    const tbody = document.getElementById('customer-table-body');
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = rawData.slice(start, end);

    // Jika halaman saat ini kosong (karena data dihapus), mundur ke halaman sebelumnya
    if (pageData.length === 0 && currentPage > 1) {
        currentPage--;
        renderTable();
        return;
    }

    tbody.innerHTML = pageData.map(item => `
        <tr class="hover:bg-slate-50/80 transition-all border-b border-slate-50">
            <td class="px-6 py-3 text-center">
                <input type="checkbox" value="${item.id}" class="row-checkbox w-4 h-4 rounded border-slate-300 text-amber-500 cursor-pointer">
            </td>
            <td class="px-4 py-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-${item.color}-50 flex items-center justify-center font-bold text-${item.color}-600 text-xs">${item.initial}</div>
                    <div>
                        <p class="font-bold text-slate-800 text-sm leading-tight">${item.toko}</p>
                        <p class="text-[10px] text-slate-400 font-medium">Pemilik: ${item.owner}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-3"><span class="text-xs font-semibold text-slate-600">${item.paket}</span></td>
            <td class="px-6 py-3">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-${item.status === 'active' ? 'emerald' : (item.status === 'trial' ? 'amber' : 'rose')}-50 text-${item.status === 'active' ? 'emerald' : (item.status === 'trial' ? 'amber' : 'rose')}-600 text-[9px] font-bold uppercase">
                    ${item.status}
                </span>
            </td>
            <td class="px-6 py-3 text-xs font-medium ${item.sisa === '0 Hari' ? 'text-rose-500' : 'text-slate-600'}">${item.sisa}</td>
            <td class="px-8 py-3 text-right">
                <div class="flex justify-end gap-1">
                    <button class="p-1.5 text-slate-400 hover:text-amber-500"><i data-lucide="eye" class="w-4 h-4"></i></button>
                    <button class="p-1.5 text-slate-400 hover:text-blue-500"><i data-lucide="square-pen" class="w-4 h-4"></i></button>
                </div>
            </td>
        </tr>
    `).join('');

    updatePagination();
    initCheckboxLogic(); // Panggil logika checkbox setiap kali tabel di-render
    if (window.lucide) lucide.createIcons();
}

function initCheckboxLogic() {
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    
    // Reset tombol hapus & selectAll setiap render
    btnDelete.classList.add('hidden');
    selectAll.checked = false;

    // Event untuk Checkbox "Pilih Semua"
    selectAll.onchange = function() {
        rowCheckboxes.forEach(cb => cb.checked = selectAll.checked);
        toggleDeleteButton();
    };

    // Event untuk setiap Checkbox Baris
    rowCheckboxes.forEach(cb => {
        cb.onchange = function() {
            toggleDeleteButton();
            // Jika satu saja tidak dicentang, matikan "Pilih Semua"
            const allChecked = Array.from(rowCheckboxes).every(c => c.checked);
            selectAll.checked = allChecked;
        };
    });
}

function toggleDeleteButton() {
    const anyChecked = document.querySelectorAll('.row-checkbox:checked').length > 0;
    btnDelete.classList.toggle('hidden', !anyChecked);
}

// Fungsi Hapus Data
btnDelete.onclick = function() {
    const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
    const idsToDelete = Array.from(checkedBoxes).map(cb => parseInt(cb.value));

    if (confirm(`Hapus ${idsToDelete.length} pelanggan terpilih?`)) {
        // Filter array rawData (Hapus data secara permanen di memory)
        for (let i = rawData.length - 1; i >= 0; i--) {
            if (idsToDelete.includes(rawData[i].id)) {
                rawData.splice(i, 1);
            }
        }
        
        renderTable(); // Render ulang tabel
    }
};

    function updatePagination() {
        const totalPages = Math.ceil(rawData.length / itemsPerPage);
        document.getElementById('pagination-info').innerHTML = `Menampilkan <span class="text-slate-900 font-bold">${((currentPage-1)*itemsPerPage)+1} - ${Math.min(currentPage*itemsPerPage, rawData.length)}</span> dari <span class="text-slate-900 font-bold">${rawData.length}</span> pelanggan`;

        let controls = `
            <button onclick="changePage(${currentPage-1})" ${currentPage === 1 ? 'disabled' : ''} class="p-2 border border-slate-200 rounded-xl disabled:opacity-30">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </button>
        `;

        for (let i = 1; i <= totalPages; i++) {
            controls += `
                <button onclick="changePage(${i})" class="w-9 h-9 flex items-center justify-center rounded-xl text-xs font-bold transition-all ${currentPage === i ? 'bg-amber-500 text-white shadow-lg shadow-amber-200' : 'bg-white border border-transparent text-slate-500 hover:border-slate-200'}">
                    ${i}
                </button>
            `;
        }

        controls += `
            <button onclick="changePage(${currentPage+1})" ${currentPage === totalPages ? 'disabled' : ''} class="p-2 border border-slate-200 rounded-xl disabled:opacity-30">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
        `;

        document.getElementById('pagination-controls').innerHTML = controls;
        if (window.lucide) lucide.createIcons();
    }

    window.changePage = function(page) {
        const totalPages = Math.ceil(rawData.length / itemsPerPage);
        if (page < 1 || page > totalPages) return;
        currentPage = page;
        renderTable();
    }

    // Inisialisasi awal
    renderTable();
</script>
@endsection