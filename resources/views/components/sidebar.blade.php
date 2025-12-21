<aside class="w-64 bg-white border-r border-gray-200 fixed h-full z-20">
    <!-- Logo -->
    <div class="p-4 border-b border-gray-200">
        <a href="/" class="flex items-center gap-2">
            <span class="text-xl font-bold text-gray-800">Stok<span class="text-amber-600">In</span></span>
        </a>
    </div>

    <!-- Navigation Menu -->
    <nav class="p-4">
        <ul class="space-y-2">
            <x-sidebar.links title="Dashboard" icon="fa-solid fa-gauge" route="dashboard" />
            <x-sidebar.links title="Data Barang" icon="fa-solid fa-box" route="barang.index" />
            <x-sidebar.links title="Penjualan" icon="fas fa-cash-register" route="penjualan.index" />
            @if(auth()->user()->isOwner())
                <x-sidebar.links title="Manajemen Staff" icon="fas fa-users" route="staff.index" />
            @endif
            {{-- <x-sidebar.links title="Pembelian" icon="fas fa-shopping-cart" route="#" /> --}}
            {{-- <x-sidebar.links title="Supplier" icon="fas fa-truck" route="#" /> --}}
            {{-- <x-sidebar.links title="Pelanggan" icon="fas fa-users" route="#" /> --}}
            {{-- <x-sidebar.links title="Laporan" icon="fas fa-chart-bar" route="#" /> --}}
            {{-- <x-sidebar.links title="Pengguna" icon="fas fa-user-cog" route="#" /> --}}
            {{-- <x-sidebar.links title="Pengaturan" icon="fas fa-cog" route="#" /> --}}
        </ul>
    </nav>
</aside>
