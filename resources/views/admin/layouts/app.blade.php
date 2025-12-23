<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StokIn - Admin Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        #sidebar, #mainWrapper {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-mini { width: 5rem !important; }

        .sidebar-mini #logo-text,
        .sidebar-mini #nav-label,
        .sidebar-mini .nav-text,
        .sidebar-mini .logout-text,
        .sidebar-mini .nav-text-saas {
            display: none !important;
        }

        .sidebar-mini .nav-link,
        .sidebar-mini .logout-btn {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin: 0 0.75rem;
        }

        .active-link {
            background-color: #FFF7ED;
            color: #D97706 !important;
            box-shadow: 0 1px 2px rgba(0,0,0,.05);
        }

        .notification-dot {
            position: absolute;
            top: 0;
            right: 0;
            width: 10px;
            height: 10px;
            background: #f43f5e;
            border-radius: 9999px;
            border: 2px solid white;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 overflow-x-hidden">

<div id="sidebarOverlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 hidden"></div>

<!-- SIDEBAR -->
<aside id="sidebar" class="fixed top-0 left-0 z-50 w-64 h-screen bg-white border-r border-gray-100 flex flex-col -translate-x-full md:translate-x-0">
    <div class="h-20 flex items-center px-6">
        <div class="flex items-center gap-3">
            <div class="bg-amber-500 p-2 rounded-xl shadow-sm">
                <i class="fas fa-boxes-stacked text-white"></i>
            </div>
            <span id="logo-text" class="text-xl font-bold text-gray-800">
                Stok<span class="text-amber-500">In</span>
            </span>
        </div>
    </div>

    <div class="flex-1 px-3 py-4 space-y-8 overflow-y-auto no-scrollbar">
        <nav class="space-y-1.5">

            <p id="nav-label" class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">
                Master Overview
            </p>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl font-semibold
               {{ request()->routeIs('admin.dashboard') ? 'active-link' : 'text-gray-500 hover:bg-amber-50 hover:text-amber-600' }}">
                <i class="fas fa-gauge-high"></i>
                <span class="nav-text text-sm">Dashboard Utama</span>
            </a>

            <p class="nav-text-saas px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-6 mb-4">
                SaaS Management
            </p>

            <a href="{{ route('admin.pelanggan.index') }}"
               class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl font-semibold
               {{ request()->routeIs('admin.pelanggan.*') ? 'active-link' : 'text-gray-500 hover:bg-amber-50 hover:text-amber-600' }}">
                <i class="fas fa-users"></i>
                <span class="nav-text text-sm">Pelanggan (Toko)</span>
            </a>

            <a href="{{ route('admin.kelola-paket.index') }}"
               class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl font-semibold
               {{ request()->routeIs('admin.kelola-paket.*') ? 'active-link' : 'text-gray-500 hover:bg-amber-50 hover:text-amber-600' }}">
                <i class="fas fa-layer-group"></i>
                <span class="nav-text text-sm">Paket Berlangganan</span>
            </a>

            <p class="nav-text-saas px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-6 mb-4">
                Financial
            </p>

            <a href="{{ route('admin.keuangan.index') }}" 
                class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-amber-50 hover:text-amber-600 font-semibold">
                <i class="fas fa-file-invoice"></i>
                <span class="nav-text text-sm">Billing & Invoice</span>
            </a>

            <a href="#" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-amber-50 hover:text-amber-600 font-semibold">
                <i class="fas fa-gear"></i>
                <span class="nav-text text-sm">Setting Aplikasi</span>
            </a>

        </nav>
    </div>

    <div class="p-4 border-t border-gray-100">
        <button class="logout-btn w-full flex items-center gap-3 px-4 py-3 bg-gray-900 text-white rounded-xl font-bold hover:bg-rose-600 transition">
            <i class="fas fa-right-from-bracket"></i>
            <span class="logout-text">Keluar Sesi</span>
        </button>
    </div>
</aside>

<!-- MAIN -->
<div id="mainWrapper" class="md:ml-64 min-h-screen">

<header class="sticky top-0 z-30 h-20 bg-white/80 backdrop-blur border-b border-gray-100 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <button id="toggleBtn" class="p-2.5 rounded-xl border border-gray-200 bg-white shadow-sm">
            <i class="fas fa-bars"></i>
        </button>
        <div>
            <h2 class="text-sm font-bold">@yield('header_title')</h2>
            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">
                @yield('header_description')
            </p>
        </div>
    </div>

    <div class="flex items-center gap-6">
        <button class="relative p-2.5 rounded-xl hover:bg-amber-50 text-gray-400 hover:text-amber-500">
            <i class="fas fa-bell"></i>
            <span class="notification-dot"></span>
        </button>

        <div class="flex items-center gap-3">
            <div class="text-right hidden md:block">
                <p class="text-sm font-bold">Jen Ratri Prabarini</p>
                <p class="text-[10px] text-amber-500 font-bold uppercase">Administrator</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-amber-500 text-white flex items-center justify-center font-bold">
                J
            </div>
        </div>
    </div>
</header>

<main class="p-4">
    <div class="max-w-7xl mx-auto">
        @yield('content')
    </div>
</main>

</div>

<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');
const toggleBtn = document.getElementById('toggleBtn');
const mainWrapper = document.getElementById('mainWrapper');

function updateMargin(mini){
    if(window.innerWidth >= 768){
        mainWrapper.style.marginLeft = mini ? '5rem' : '16rem';
    }
}

toggleBtn.onclick = () => {
    if(window.innerWidth < 768){
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    } else {
        sidebar.classList.toggle('sidebar-mini');
        updateMargin(sidebar.classList.contains('sidebar-mini'));
    }
};

overlay.onclick = () => toggleBtn.click();
</script>

</body>
</html>