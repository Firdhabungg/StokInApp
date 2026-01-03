<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - StokIn Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js Store for Sidebar --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('adminSidebar', {
                mini: localStorage.getItem('adminSidebarMini') === 'true',
                toggle() {
                    this.mini = !this.mini;
                    localStorage.setItem('adminSidebarMini', this.mini);
                }
            });
        });
    </script>
</head>

<body class="bg-gray-50 text-gray-900 overflow-x-hidden" x-data>

    {{-- Sidebar Component --}}
    <div x-data="{ mobileOpen: false }" @toggle-mobile-sidebar.window="mobileOpen = !mobileOpen">
        {{-- Mobile Overlay --}}
        <div x-show="mobileOpen" 
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileOpen = false"
             class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 md:hidden">
        </div>

        {{-- Sidebar --}}
        <aside :class="{ '-translate-x-full': !mobileOpen, 'translate-x-0': mobileOpen }"
               class="fixed top-0 left-0 z-50 w-64 h-screen bg-white border-r border-slate-100 flex flex-col transition-transform duration-300 md:translate-x-0"
               x-bind:class="$store.adminSidebar.mini ? 'sidebar-mini' : ''">

            {{-- Header with Toggle --}}
            <div class="h-20 flex items-center justify-between px-4 flex-shrink-0 border-b border-slate-50">
                <div class="flex items-center gap-3">
                    <div class="bg-amber-500 p-2 rounded-xl shadow-lg shadow-amber-200 flex-shrink-0">
                        <i class="fa-solid fa-box text-white w-5 h-5 flex items-center justify-center"></i>
                    </div>
                    <span class="nav-text text-xl font-bold tracking-tight text-slate-800 whitespace-nowrap">
                        Stok<span class="text-amber-500">In</span>
                    </span>
                </div>
                {{-- Desktop Toggle Button --}}
                <button @click="$store.adminSidebar.toggle()" class="hidden md:flex p-2 rounded-lg hover:bg-gray-100 transition-colors text-gray-400 hover:text-gray-600">
                    <i class="fas fa-chevron-left text-sm transition-transform duration-300" :class="$store.adminSidebar.mini ? 'rotate-180' : ''"></i>
                </button>
                {{-- Mobile Close Button --}}
                <button @click="mobileOpen = false" class="md:hidden p-2 text-gray-500 hover:text-amber-500 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- Navigation --}}
            <div class="flex-1 px-3 py-4 overflow-y-auto no-scrollbar">
                <nav class="space-y-1.5">
                    <p class="nav-label px-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">
                        Main Menu
                    </p>

                    <ul class="space-y-1.5">
                        <li>
                            <x-sidebar.links title="Dashboard" icon="fas fa-gauge-high" route="admin.dashboard" />
                        </li>

                        <p class="nav-label px-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-6 mb-4">
                            Pengelolaan SaaS
                        </p>

                        <li>
                            <x-sidebar.links title="Semua Toko" icon="fas fa-store" route="admin.toko.index" />
                        </li>
                        <li>
                            <x-sidebar.links title="Paket Berlangganan" icon="fas fa-layer-group" route="admin.kelola-paket.index" />
                        </li>

                        <p class="nav-label px-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-6 mb-4">
                            Keuangan
                        </p>

                        <li>
                            <x-sidebar.links title="Tagihan & Faktur" icon="fas fa-file-invoice" route="admin.keuangan.index" />
                        </li>

                        <p class="nav-label px-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-6 mb-4">
                            Sistem
                        </p>

                        <li>
                            <x-sidebar.links title="Pengaturan" icon="fas fa-gear" route="admin.pengaturan.index" />
                        </li>
                    </ul>
                </nav>
            </div>

            {{-- Footer: User Info + Logout --}}
            <div class="p-4 border-t border-slate-50">
                <div class="mb-2 px-2 py-1 bg-slate-100 rounded-lg text-center">
                    <p class="nav-text text-xs text-slate-500">Login sebagai</p>
                    <p class="nav-text text-sm font-semibold text-slate-700">Super Admin</p>
                </div>
                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="button" onclick="confirmLogout()"
                        class="logout-btn w-full px-4 py-3 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-rose-600 transition-all active:scale-95 shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="nav-text whitespace-nowrap">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>
    </div>

    {{-- Main Content --}}
    <div class="md:ml-64 min-h-screen transition-all duration-300" :class="$store.adminSidebar.mini ? 'md:ml-20' : 'md:ml-64'">

        {{-- Header Component --}}
        <x-admin-header />

        {{-- Page Content --}}
        <main class="p-4">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

    </div>

    @stack('scripts')

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('stokInTable') && !$.fn.DataTable.isDataTable('#stokInTable')) {
            new DataTable('#stokInTable', {
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    paginate: { first: "Pertama", last: "Terakhir", next: "Selanjutnya", previous: "Sebelumnya" }
                },
                pageLength: 10
            });
        }
    });

    function confirmLogout() {
        Swal.fire({
            title: 'Keluar dari Akun?',
            text: 'Anda akan keluar dari dashboard admin.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
    </script>

</body>

</html>
