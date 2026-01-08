@extends('admin.layouts.app')

@section('title', 'Manajemen Pelanggan')
@section('header_description', 'Daftar Toko & Status Langganan Tenant') 

@section('content')
<div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 space-y-6">

    {{-- JUDUL TABEL --}}
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-900">Daftar Pelanggan</h2>
    </div>

    {{-- FILTER BAR --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex-1 flex flex-col md:flex-row gap-4">

            {{-- Custom Search --}}
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" placeholder="Cari nama toko atau pemilik..."
                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none transition-all">
            </div>

            <div class="flex gap-3">
                {{-- Filter Paket --}}
                <div class="relative">
                    <i class="fas fa-box absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <select id="filterPaket"
                        class="appearance-none pl-10 pr-10 py-2.5 bg-gray-50 rounded-xl text-sm font-semibold text-gray-600 focus:ring-2 focus:ring-amber-500 outline-none cursor-pointer">
                        <option value="">Semua Paket</option>
                        <option value="Free Trial">Free Trial</option>
                        <option value="Pro">Pro</option>
                    </select>
                </div>

                {{-- Filter Status --}}
                <div class="relative">
                    <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <select id="filterStatus"
                        class="appearance-none pl-10 pr-10 py-2.5 bg-gray-50 rounded-xl text-sm font-semibold text-gray-600 focus:ring-2 focus:ring-amber-500 outline-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="trial">Masa Trial</option>
                        <option value="expired">Kedaluwarsa</option>
                    </select>
                </div>
            </div>
        </div>

        <a href="#"
            class="bg-amber-500 text-white px-4 py-2.5 rounded-xl hover:bg-amber-600 shadow-sm transition-all text-sm font-semibold">
            <i class="fas fa-plus mr-2"></i>Tambah Pelanggan
        </a>
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table id="pelangganTable" class="w-full text-left border-collapse text-sm text-gray-600">
            <thead>
                <tr class="border-b border-gray-100 text-gray-400 text-[12px] font-bold uppercase tracking-widest">
                    <th class="px-4 py-4 text-center">No.</th>
                    <th class="px-4 py-4">Nama Toko & Pemilik</th>
                    <th class="px-4 py-4">Paket Langganan</th>
                    <th class="px-4 py-4 text-center">Status</th>
                    <th class="px-4 py-4 text-center">Sisa Waktu</th>
                    <th class="px-4 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100"></tbody>
        </table>
    </div>

</div>

    <style>
        .dataTables_filter { display: none; }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5em 1em;
            margin-left: 0.25rem;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            background: white;
            color: #6b7280 !important;
            font-size: 0.875rem;
            cursor: pointer;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f9fafb !important;
            color: #111827 !important;
            border: 1px solid #d1d5db;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #f59e0b !important; 
            color: white !important;
            border: 1px solid #f59e0b;
        }
        .dataTables_wrapper .dataTables_length select {
            padding-right: 2rem;
            border-radius: 0.5rem;
            border-color: #e5e7eb;
            font-size: 0.875rem;
        }
        .dataTables_wrapper .dataTables_info {
            padding-top: 20px;
            color: #718096;
            font-size: 14px;
            font-style: italic;
        }
        .dataTables_paginate {
            margin-top: 1rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() { 
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
 
            let table = new DataTable('#pelangganTable', {
                data: rawData,
                responsive: true,
                pageLength: 10,
                dom: 'rtip', 
                columns: [ 
                    { 
                        data: 'id',
                        orderable: false,
                        className: 'text-center align-middle font-semibold text-gray-900',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { 
                        data: 'toko',
                        className: 'align-middle',
                        render: function(data, type, row) {
                            return `
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-${row.color}-50 text-${row.color}-600 flex items-center justify-center text-xs font-bold shrink-0">
                                        ${row.initial}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">${data}</p>
                                        <p class="text-[10px] text-gray-400">Pemilik: ${row.owner}</p>
                                    </div>
                                </div>
                            `;
                        }
                    },
                    { 
                        data: 'paket',
                        className: 'align-middle font-semibold text-gray-600'
                    },
                    { 
                        data: 'status',
                        className: 'align-middle',
                        render: function(data) {
                            let badgeClass = '';
                            let label = '';
                            
                            if(data === 'active') {
                                badgeClass = 'bg-emerald-50 text-emerald-600';
                                label = 'Aktif';
                            } else if (data === 'trial') {
                                badgeClass = 'bg-amber-50 text-amber-600';
                                label = 'Masa Trial';
                            } else {
                                badgeClass = 'bg-rose-50 text-rose-600';
                                label = 'Kedaluwarsa';
                            }

                            return `<span class="px-2 py-0.5 rounded-full text-[11px] font-bold uppercase ${badgeClass}">${label}</span>`;
                        }
                    },
                    { 
                        data: 'sisa',
                        className: 'align-middle',
                        render: function(data) {
                            return `<span class="${data === '0 Hari' ? 'text-rose-500 font-bold' : 'text-gray-600'}">${data}</span>`;
                        }
                    },
                    { 
                        data: null,
                        orderable: false,
                        className: 'text-right align-middle',
                        render: function(data, type, row) {
                            return `
                                <div class="flex justify-end gap-2">
                                    <button class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button onclick="deletePelanggan(${row.id}, '${row.toko}')" class="p-2 text-gray-400 hover:text-rose-500 transition-colors" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                language: {
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ pelanggan",
                    paginate: {
                        first: '<i class="fas fa-angle-double-left"></i>',
                        last: '<i class="fas fa-angle-double-right"></i>',
                        next: '<i class="fas fa-chevron-right"></i>',
                        previous: '<i class="fas fa-chevron-left"></i>'
                    },
                    emptyTable: "Tidak ada data pelanggan"
                }
            });

            document.getElementById('searchInput').addEventListener('keyup', function() {
                table.search(this.value).draw();
            });

            // Variables untuk menyimpan filter values
            let filterPaketVal = '';
            let filterStatusVal = '';

            // Custom filter function yang menangani kedua filter
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const rowData = table.row(dataIndex).data();
                
                // Filter paket
                if (filterPaketVal && rowData.paket !== filterPaketVal) {
                    return false;
                }
                
                // Filter status
                if (filterStatusVal && rowData.status !== filterStatusVal) {
                    return false;
                }
                
                return true;
            });

            // Event listener untuk filter paket
            document.getElementById('filterPaket').addEventListener('change', function() {
                filterPaketVal = this.value;
                table.draw();
            });

            // Event listener untuk filter status
            document.getElementById('filterStatus').addEventListener('change', function() {
                filterStatusVal = this.value;
                table.draw();
            });
        });

        function deletePelanggan(id, namaToko) {
            Swal.fire({
                title: "Hapus Pelanggan?",
                text: `Data toko "${namaToko}" akan dihapus permanen!`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#bf0603",
                cancelButtonColor: "#38b000",
                confirmButtonText: "Yes, hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire("Dihapus!", "Data pelanggan berhasil dihapus.", "success");
                }
            });
        }

    </script>
@endsection 
