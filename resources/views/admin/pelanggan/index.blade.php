@extends('admin.layouts.app')

@section('header_title', 'Manajemen Pelanggan')
@section('header_description', 'Daftar Toko & Status Langganan Tenant')

@section('content')
<div class="space-y-6">

    {{-- FILTER BAR --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <div class="flex-1 flex flex-col md:flex-row gap-4">

            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" placeholder="Cari nama toko atau pemilik..."
                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none">
            </div>

            <div class="flex gap-3">
                <div class="relative">
                    <i class="fas fa-layer-group absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <select id="filterPaket"
                        class="appearance-none pl-10 pr-10 py-2.5 bg-gray-50 rounded-xl text-sm font-semibold text-gray-600 focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="">Semua Paket</option>
                        <option value="Starter">Starter</option>
                        <option value="Pro Plan">Pro</option>
                        <option value="Enterprise">Enterprise</option>
                    </select>
                </div>

                <div class="relative">
                    <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <select id="filterStatus"
                        class="appearance-none pl-10 pr-10 py-2.5 bg-gray-50 rounded-xl text-sm font-semibold text-gray-600 focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="trial">Masa Trial</option>
                        <option value="expired">Kedaluwarsa</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 border-t md:border-t-0 pt-4 md:pt-0">
            <button id="deleteSelected"
                class="hidden flex items-center gap-2 px-4 py-2.5 bg-rose-50 text-rose-600 rounded-xl text-sm font-bold hover:bg-rose-100 transition-all">
                <i class="fas fa-trash"></i>
                <span class="hidden md:block">Hapus</span>
            </button>

            <button title="Print Laporan"
                class="p-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 shadow-sm">
                <i class="fas fa-print"></i>
            </button>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm flex flex-col min-h-[520px]">
        <div class="overflow-x-auto flex-grow">
                <table id="barangTable" class="w-full text-sm text-center">
                <thead>
                    <tr class="border-b border-gray-100 text-gray-400 text-[11px] font-bold uppercase tracking-widest">
                        <th class="px-6 py-4 w-12 text-center">
                            <input type="checkbox" id="selectAll"
                                class="w-4 h-4 rounded border-gray-300 text-amber-500">
                        </th>
                        <th class="px-4 py-4">Nama Toko & Pemilik</th>
                        <th class="px-6 py-4">Paket</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Sisa Langganan</th>
                        <th class="px-8 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="customer-table-body" class="divide-y divide-gray-100"></tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="px-8 py-6 bg-white border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <p id="pagination-info" class="text-xs text-gray-500 font-medium"></p>
            <div id="pagination-controls" class="flex items-center gap-2"></div>
        </div>
    </div>
</div>

<script>
/* =======================
   DATA DUMMY
======================= */
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
const btnDelete = document.getElementById('deleteSelected');
const selectAll = document.getElementById('selectAll');

/* =======================
   RENDER TABLE
======================= */
function renderTable() {
    const tbody = document.getElementById('customer-table-body');
    const start = (currentPage - 1) * itemsPerPage;
    const pageData = rawData.slice(start, start + itemsPerPage);

    tbody.innerHTML = pageData.map(item => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-3 text-center">
                <input type="checkbox" value="${item.id}" class="row-checkbox w-4 h-4 text-amber-500">
            </td>
            <td class="px-4 py-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-${item.color}-50 text-${item.color}-600 flex items-center justify-center text-xs font-bold">
                        ${item.initial}
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 text-sm">${item.toko}</p>
                        <p class="text-[10px] text-gray-400">Pemilik: ${item.owner}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-3 text-sm font-semibold text-gray-600">${item.paket}</td>
            <td class="px-6 py-3">
                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase
                    ${item.status === 'active' ? 'bg-emerald-50 text-emerald-600' :
                      item.status === 'trial' ? 'bg-amber-50 text-amber-600' :
                      'bg-rose-50 text-rose-600'}">
                    ${item.status}
                </span>
            </td>
            <td class="px-6 py-3 text-xs ${item.sisa === '0 Hari' ? 'text-rose-500' : 'text-gray-600'}">
                ${item.sisa}
            </td>
            <td class="px-8 py-3 text-right flex justify-end gap-2">
                <button class="text-gray-400 hover:text-amber-500"><i class="fas fa-eye"></i></button>
                <button class="text-gray-400 hover:text-blue-500"><i class="fas fa-pen"></i></button>
            </td>
        </tr>
    `).join('');

    initCheckboxLogic();
    updatePagination();
}

/* =======================
   CHECKBOX
======================= */
function initCheckboxLogic() {
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    btnDelete.classList.add('hidden');
    selectAll.checked = false;

    selectAll.onchange = () => {
        rowCheckboxes.forEach(cb => cb.checked = selectAll.checked);
        toggleDeleteButton();
    };

    rowCheckboxes.forEach(cb => {
        cb.onchange = () => {
            toggleDeleteButton();
            selectAll.checked = [...rowCheckboxes].every(c => c.checked);
        };
    });
}

function toggleDeleteButton() {
    btnDelete.classList.toggle('hidden', document.querySelectorAll('.row-checkbox:checked').length === 0);
}

/* =======================
   DELETE
======================= */
btnDelete.onclick = () => {
    const ids = [...document.querySelectorAll('.row-checkbox:checked')].map(cb => +cb.value);
    if (confirm(`Hapus ${ids.length} pelanggan terpilih?`)) {
        for (let i = rawData.length - 1; i >= 0; i--) {
            if (ids.includes(rawData[i].id)) rawData.splice(i, 1);
        }
        renderTable();
    }
};

/* =======================
   PAGINATION
======================= */
function updatePagination() {
    const totalPages = Math.ceil(rawData.length / itemsPerPage);
    document.getElementById('pagination-info').innerHTML =
        `Menampilkan <b>${(currentPage-1)*itemsPerPage+1} - ${Math.min(currentPage*itemsPerPage, rawData.length)}</b> dari <b>${rawData.length}</b> pelanggan`;

    document.getElementById('pagination-controls').innerHTML = `
        <button onclick="changePage(${currentPage-1})" ${currentPage===1?'disabled':''}
            class="p-2 border border-gray-200 rounded-xl disabled:opacity-30">
            <i class="fas fa-chevron-left"></i>
        </button>
        ${Array.from({length: totalPages}, (_,i)=>`
            <button onclick="changePage(${i+1})"
                class="w-9 h-9 rounded-xl text-xs font-bold
                ${currentPage===i+1?'bg-amber-500 text-white':'border border-gray-200 text-gray-500'}">
                ${i+1}
            </button>`).join('')}
        <button onclick="changePage(${currentPage+1})" ${currentPage===totalPages?'disabled':''}
            class="p-2 border border-gray-200 rounded-xl disabled:opacity-30">
            <i class="fas fa-chevron-right"></i>
        </button>
    `;
}

window.changePage = page => {
    const totalPages = Math.ceil(rawData.length / itemsPerPage);
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    renderTable();
};

renderTable();
</script>
@endsection