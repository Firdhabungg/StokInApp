<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Admin Stokin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine Store: Sidebar --}}
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

    <!-- ================= SIDEBAR ================= -->
    <div x-data="{ mobileOpen: false }" @toggle-mobile-sidebar.window="mobileOpen = !mobileOpen">

        <!-- Overlay Mobile -->
        <div x-show="mobileOpen" @click="mobileOpen = false"
            class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 md:hidden" x-transition.opacity>
        </div>

        <!-- Sidebar -->
        <aside
            class="fixed top-0 left-0 z-50 h-screen bg-white border-r border-slate-100 flex flex-col transition-all duration-300
               md:translate-x-0"
            :class="[
                mobileOpen ? 'translate-x-0' : '-translate-x-full',
                $store.adminSidebar.mini ? 'w-20 sidebar-mini' : 'w-64'
            ]">

            <!-- Header -->
            <div class="h-20 flex items-center justify-between px-4 border-b border-slate-50">
                <div class="flex items-center gap-3">
                    <div class="bg-amber-500 p-2 rounded-xl shadow-lg shadow-amber-200">
                        <i class="fa-solid fa-box text-white"></i>
                    </div>
                    <span class="nav-text text-xl font-bold text-slate-800">
                        Stok<span class="text-amber-500">In</span>
                    </span>
                </div>

                <!-- Toggle Desktop -->
                <button @click="$store.adminSidebar.toggle()"
                    class="hidden md:flex p-2 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-chevron-left transition-transform"
                        :class="$store.adminSidebar.mini ? 'rotate-180' : ''"></i>
                </button>

                <!-- Close Mobile -->
                <button @click="mobileOpen = false" class="md:hidden p-2 text-gray-500 hover:text-amber-500">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-4 overflow-y-auto no-scrollbar space-y-4">
                <ul>
                    <p class="nav-label px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        Main Menu
                    </p>

                    <x-sidebar.links title="Dashboard" icon="fas fa-gauge-high" route="admin.dashboard" />

                    <p class="nav-label px-4 mt-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        Kelola Layanan
                    </p>

                    <x-sidebar.links title="Semua Toko" icon="fas fa-store" route="admin.toko.index" />
                    <x-sidebar.links title="Manajemen Paket" icon="fas fa-layer-group"
                        route="admin.kelola-paket.index" />

                    <p class="nav-label px-4 mt-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        Keuangan
                    </p>

                    <x-sidebar.links title="Tagihan & Faktur" icon="fas fa-file-invoice" route="admin.keuangan.index" />

                    <p class="nav-label px-4 mt-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        Sistem
                    </p>

                    <x-sidebar.links title="Pengaturan" icon="fas fa-gear" route="admin.pengaturan.index" />
                </ul>

            </nav>

            <!-- Footer -->
            <div class="p-4 border-t border-slate-50">
                <div class="mb-2 px-2 py-1 bg-slate-100 rounded-lg text-center">
                    <p class="nav-text text-xs text-slate-500">Login sebagai</p>
                    <p class="nav-text text-sm font-semibold text-slate-700">Super Admin</p>
                </div>

                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="button" onclick="confirmLogout()"
                        class="logout-btn w-full py-2 bg-slate-900 text-white rounded-xl font-bold hover:bg-rose-600 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="nav-text">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>
    </div>

    <!-- ================= MAIN CONTENT ================= -->
    <div class="min-h-screen transition-all duration-300" :class="$store.adminSidebar.mini ? 'md:ml-20' : 'md:ml-64'">

        <x-admin-header />

        <main class="p-4">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')

    <!-- ================= SCRIPTS ================= -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('stokInTable') && !$.fn.DataTable.isDataTable('#stokInTable')) {
                new DataTable('#stokInTable', {
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        zeroRecords: "Tidak ada data ditemukan",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        }
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
                confirmButtonText: 'Logout',
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
