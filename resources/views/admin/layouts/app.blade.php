<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StokIn - Pro Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        /* Transition smooth untuk lebar sidebar dan margin konten */
        #sidebar, #mainWrapper {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* State Sidebar Mini (Desktop) */
        .sidebar-mini { 
            width: 5rem !important; 
        }

        /* Sembunyikan elemen teks secara total saat mini */
        .sidebar-mini #logo-text, 
        .sidebar-mini #nav-label,
        .sidebar-mini .nav-text, 
        .sidebar-mini .logout-text,
        .sidebar-mini .nav-text-saas { 
            display: none !important;
        }

        /* Penyesuaian Navigasi saat Mini */
        .sidebar-mini .nav-link, 
        .sidebar-mini .logout-btn { 
            justify-content: center !important; 
            padding-left: 0 !important; 
            padding-right: 0 !important;
            margin: 0 0.75rem;
        }

        /* Logic Active Link */
        .active-link {
            background-color: #FFF7ED !important; /* amber-50 */
            color: #D97706 !important; /* amber-600 */
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .sidebar-mini .active-link {
            border-right: none !important;
        }

        .notification-dot {
            @apply absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-rose-500 ring-2 ring-white;
        }

        /* Transisi teks */
        .nav-text, #logo-text, #nav-label, .logout-text {
            transition: opacity 0.2s ease;
        }
    </style>
</head>
<body class="bg-gray-50 text-slate-900 overflow-x-hidden">

    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden"></div>

    <aside id="sidebar" class="fixed top-0 left-0 z-50 w-64 h-screen bg-white border-r border-slate-100 flex flex-col md:translate-x-0 -translate-x-full">
        <div class="h-20 flex items-center px-6 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="bg-amber-500 p-2 rounded-xl shadow-lg shadow-amber-200 flex-shrink-0">
                    <i data-lucide="package-check" class="w-6 h-6 text-white"></i>
                </div>
                <span id="logo-text" class="text-xl font-bold tracking-tight text-slate-800 whitespace-nowrap">
                    Stok<span class="text-amber-500">In</span>
                </span>
            </div>
        </div>

        <div class="flex-1 px-3 py-4 space-y-8 overflow-y-auto no-scrollbar">
            <nav class="space-y-1.5">
                <p id="nav-label" class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Master Overview</p>
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold {{ request()->routeIs('admin.dashboard') ? 'active-link' : 'text-slate-500 hover:bg-amber-50 hover:text-amber-600' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="nav-text text-sm whitespace-nowrap">Dashboard Utama</span>
                </a>

                <p id="nav-label" class="nav-text-saas px-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-6 mb-4">SaaS Management</p>

                <a href="{{ route('admin.pelanggan.index') }}" 
                   class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold border-r-4 border-transparent {{ request()->routeIs('admin.pelanggan.*') ? 'active-link' : 'text-slate-500 hover:bg-amber-50 hover:text-amber-600' }}">
                    <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="nav-text text-sm whitespace-nowrap">Pelanggan (Toko)</span>
                </a>

                <a href="{{ route('admin.kelola-paket.index') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold border-r-4 border-transparent {{ request()->routeIs('admin.kelola-paket.*') ? 'active-link' : 'text-slate-500 hover:bg-amber-50 hover:text-amber-600' }}">
                    <i data-lucide="layers" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="nav-text text-sm whitespace-nowrap">Paket Berlangganan</span>
                </a>

                <p id="nav-label" class="nav-text-saas px-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-6 mb-4">Financial</p>

                <a href="#" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold border-r-4 border-transparent">
                    <i data-lucide="receipt" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="nav-text text-sm whitespace-nowrap">Billing & Invoice</span>
                </a>

                <a href="#" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 hover:bg-amber-50 hover:text-amber-600 transition-all font-semibold border-r-4 border-transparent">
                    <i data-lucide="settings-2" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="nav-text text-sm whitespace-nowrap">Setting Aplikasi</span>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-slate-50">
            <form method="POST" action="#">
                <button type="submit" class="logout-btn w-full flex items-center gap-3 px-4 py-3 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-rose-600 transition-all active:scale-95 shadow-lg shadow-slate-200">
                    <i data-lucide="log-out" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="logout-text whitespace-nowrap">Keluar Sesi</span>
                </button>
            </form>
        </div>
    </aside>

    <div id="mainWrapper" class="md:ml-64 min-h-screen">
        
        <header class="sticky top-0 z-30 h-20 bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-6 md:px-10">
            <div class="flex items-center gap-4">
                <button id="toggleBtn" class="p-2.5 rounded-xl bg-white text-slate-600 hover:bg-slate-50 border border-slate-200 shadow-sm transition-all active:scale-90">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                <div class="hidden sm:block">
                    <h2 class="text-sm font-bold text-slate-800 tracking-tight">
                        @yield('header_title', 'Dashboard')
                    </h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                        @yield('header_description', 'Ringkasan Sistem StokIn')
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <button class="relative p-2.5 text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-xl transition-all group">
                    <i data-lucide="bell" class="w-5 h-5 transition-transform group-hover:rotate-12"></i>
                    <span class="notification-dot"></span>
                </button>

                <div class="h-8 w-[1px] bg-slate-100 hidden md:block"></div>

                <div class="flex items-center gap-3.5">
                    <div class="hidden md:flex flex-col text-right">
                        <span class="text-sm font-bold text-slate-800 leading-none">Jen Ratri P.</span>
                        <span class="text-[10px] text-amber-500 font-bold uppercase tracking-tighter mt-1">Administrator</span>
                    </div>
                    <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white font-bold shadow-lg shadow-amber-100 ring-2 ring-amber-50">
                        J
                    </div>
                </div>
            </div>
        </header>

        <main class="p-4 md:p-4">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
   <script>
    lucide.createIcons();

    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('toggleBtn');
    const mainWrapper = document.getElementById('mainWrapper');

    // 1. Fungsi penyesuaian margin konten secara dinamis
    function updateWrapperMargin(isMini) {
        if (mainWrapper) {
            if (window.innerWidth >= 768) {
                mainWrapper.style.marginLeft = isMini ? "5rem" : "16rem";
            } else {
                mainWrapper.style.marginLeft = "0";
            }
        }
    }

    // 2. Terapkan state saat halaman dimuat (Persistence)
    function initSidebar() {
        const isMini = localStorage.getItem('sidebar-mini') === 'true';
        if (window.innerWidth >= 768 && isMini) {
            sidebar.classList.add('sidebar-mini');
            updateWrapperMargin(true);
        } else {
            sidebar.classList.remove('sidebar-mini');
            updateWrapperMargin(false);
        }
    }

    // Panggil saat pertama kali load
    initSidebar();

    // 3. Logika Buka/Tutup (Toggle)
    function handleToggle() {
        const isMobile = window.innerWidth < 768;

        if (isMobile) {
            // Mode Mobile: Geser keluar/masuk
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        } else {
            // Mode Desktop: Kecilkan (Mini) / Besarkan
            sidebar.classList.toggle('sidebar-mini');
            const isNowMini = sidebar.classList.contains('sidebar-mini');
            localStorage.setItem('sidebar-mini', isNowMini);
            updateWrapperMargin(isNowMini);
        }
    }

    // Daftarkan Event Listener (dengan pengecekan elemen)
    if (toggleBtn) {
        toggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            handleToggle();
        });
    }

    if (overlay) {
        overlay.addEventListener('click', handleToggle);
    }

    // 4. Handle Resize Layar (Responsif)
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            overlay.classList.add('hidden');
            sidebar.classList.remove('-translate-x-full');
            initSidebar();
        } else {
            // Reset margin saat di mobile
            if (mainWrapper) mainWrapper.style.marginLeft = "0";
        }
    });
</script>
</body>
</html>