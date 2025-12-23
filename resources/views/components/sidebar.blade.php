<div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden"></div>

<aside id="sidebar" class="fixed top-0 left-0 z-50 w-64 h-screen bg-white border-r border-slate-100 flex flex-col md:translate-x-0 -translate-x-full">
    <div class="h-20 flex items-center px-6 flex-shrink-0 border-b border-slate-50">
        <div class="flex items-center gap-3">
            <div class="bg-amber-500 p-2 rounded-xl shadow-lg shadow-amber-200 flex-shrink-0">
                <i class="fa-solid fa-box text-white w-5 h-5 flex items-center justify-center"></i>
            </div>
            <span id="logo-text" class="text-xl font-bold tracking-tight text-slate-800 whitespace-nowrap">
                Stok<span class="text-amber-500">In</span>
            </span>
        </div>
    </div>

    <div class="flex-1 px-3 py-4 overflow-y-auto no-scrollbar">
        <nav class="space-y-1.5">
            <p id="nav-label" class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Main Menu</p>
            
            <ul class="space-y-1.5">
                <li>
                    <x-sidebar.links title="Dashboard" icon="fa-solid fa-gauge" route="dashboard" 
                        class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold" />
                </li>
                <li>
                    <x-sidebar.links title="Data Barang" icon="fa-solid fa-box" route="barang.index" 
                        class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold" />
                </li>
                <li>
                    <x-sidebar.links title="Penjualan" icon="fas fa-cash-register" route="penjualan.index" 
                        class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold" />
                </li>
                
                @auth
                @if(auth()->user()->isOwner())
                    <p id="nav-label" class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-6 mb-4">
                        Administrator
                    </p>
                    <li>
                        <x-sidebar.links title="Manajemen Staff" icon="fas fa-users" route="staff.index" 
                            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold" />
                    </li>
                @endif
                @endauth
            </ul>
        </nav>
    </div>

    <div class="p-4 border-t border-slate-50">
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <button type="button" onclick="confirmLogout()" 
                class="logout-btn w-full flex items-center gap-3 px-4 py-3 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-rose-600 transition-all active:scale-95 shadow-lg shadow-slate-200">
                <i class="fas fa-sign-out-alt w-5 h-5 flex-shrink-0"></i>
                <span class="logout-text whitespace-nowrap">Keluar Sesi</span>
            </button>
        </form>
    </div>
</aside> 