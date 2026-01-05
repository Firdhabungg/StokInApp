<div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden"></div>

<aside id="sidebar"
    class="fixed top-0 left-0 z-50 w-64 h-screen bg-white border-r border-slate-100 flex flex-col md:translate-x-0 -translate-x-full transition-all duration-300">

    {{-- Header with Toggle --}}
    <div class="h-20 flex items-center justify-between px-4 flex-shrink-0 border-b border-slate-50">
        <div id="logo-desktop" class="flex items-center gap-3">
            <div class="bg-amber-500 p-2 rounded-xl shadow-lg shadow-amber-200 flex-shrink-0">
                <i class="fa-solid fa-box text-white w-5 h-5 flex items-center justify-center"></i>
            </div>
            <span id="logo-text" class="nav-text text-xl font-bold tracking-tight text-slate-800 whitespace-nowrap">
                Stok<span class="text-amber-500">In</span>
            </span>
        </div>
        {{-- Desktop Toggle Button --}}
        <button id="desktopSidebarToggle"
            class="hidden md:flex p-2 rounded-lg hover:bg-gray-100 transition-colors text-gray-400 hover:text-gray-600">
            <i class="fas fa-chevron-left text-sm transition-transform duration-300"></i>
        </button>
    </div>

    <div class="flex-1 px-3 py-4 overflow-y-auto no-scrollbar">
        <nav class="space-y-1.5">
            <p id="nav-label" class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Main
                Menu</p>

            <ul class="space-y-1.5">
                {{-- Dashboard - Semua role --}}
                <li>
                    <x-sidebar.links title="Dashboard" icon="fa-solid fa-gauge" route="dashboard"
                        class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold" />
                </li>

                {{-- Data Barang - Owner & Super Admin only --}}
                @if (auth()->user()->canManageToko())
                    <li>
                        <x-sidebar.links title="Data Barang" icon="fa-solid fa-box" route="barang.index"
                            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold" />
                    </li>
                    <li>
                        <x-sidebar.links title="Kategori" icon="fa-solid fa-folder" route="kategori.index"
                            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold" />
                    </li>
                @endif

                {{-- Penjualan - Kasir & Owner --}}
                <li>
                    <x-sidebar.links title="Penjualan" icon="fas fa-cash-register" route="penjualan.index"
                        class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold" />
                </li>

                {{-- Stock Management Section - Owner & Super Admin --}}
                @if (auth()->user()->canManageToko())
                    <p id="nav-label"
                        class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-6 mb-4">
                        Stock Management
                    </p>
                    <li>
                        <x-sidebar.links title="Barang Masuk" icon="fas fa-arrow-down" route="stock.in.index"
                            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold" />
                    </li>
                    <li>
                        <x-sidebar.links title="Barang Keluar" icon="fas fa-arrow-up" route="stock.out.index"
                            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold" />
                    </li>
                    <li>
                        <x-sidebar.links title="Daftar Batch" icon="fas fa-layer-group" route="stock.batch.index"
                            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold" />
                    </li>
                    <li>
                        <x-sidebar.links title="Laporan" icon="fas fa-chart-bar" route="laporan.index"
                            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold" />
                    </li>
                @endif

                {{-- Administrator - Owner only --}}
                @if (auth()->user()->isOwner() || auth()->user()->isSuperAdmin())
                    <p id="nav-label"
                        class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-6 mb-4">
                        Administrator
                    </p>
                    <li>
                        <x-sidebar.links title="Manajemen Kasir" icon="fas fa-users" route="kasir.index"
                            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold" />
                    </li>
                    <li>
                        <x-sidebar.links title="Langganan" icon="fas fa-crown" route="subscription.index"
                            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold" />
                    </li>
                @endif
            </ul>
        </nav>
    </div>

    <div class="p-4 border-slate-50">
        <div id="userInfo" class="mb-2 px-2 py-1 bg-slate-100 rounded-lg text-center">
            <p class="nav-text text-xs text-slate-500">Login sebagai</p>
            <p class="nav-text text-sm font-semibold text-slate-700">
                {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
        </div>
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <button type="button" onclick="confirmLogout()"
                class="logout-btn w-full px-4 py-3 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-rose-600 transition-all active:scale-95 shadow-lg flex items-center justify-center gap-2">
                <i class="fas fa-sign-out-alt"></i>
                <span class="nav-text whitespace-nowrap">Keluar</span>
            </button>
        </form>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        const desktopToggle = document.getElementById('desktopSidebarToggle');
        const overlay = document.getElementById('sidebarOverlay');

        // Check saved state
        const isMini = localStorage.getItem('sidebarMini') === 'true';
        if (isMini) {
            sidebar.classList.add('sidebar-mini');
            if (mainWrapper) mainWrapper.classList.remove('md:ml-64');
            if (mainWrapper) mainWrapper.classList.add('md:ml-20');
        }

        // Desktop toggle
        if (desktopToggle) {
            desktopToggle.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-mini');

                if (sidebar.classList.contains('sidebar-mini')) {
                    if (mainWrapper) {
                        mainWrapper.classList.remove('md:ml-64');
                        mainWrapper.classList.add('md:ml-20');
                    }
                    localStorage.setItem('sidebarMini', 'true');
                } else {
                    if (mainWrapper) {
                        mainWrapper.classList.remove('md:ml-20');
                        mainWrapper.classList.add('md:ml-64');
                    }
                    localStorage.setItem('sidebarMini', 'false');
                }
            });
        }

        // Mobile toggle (from header hamburger if exists)
        window.toggleMobileSidebar = function() {
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('translate-x-0');
            overlay.classList.toggle('hidden');
        };

        // Close on overlay click
        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                overlay.classList.add('hidden');
            });
        }
    });
</script>
