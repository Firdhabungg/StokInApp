<header x-data
    class="sticky top-0 z-30 h-20 bg-white/80 backdrop-blur border-b border-gray-100 flex items-center justify-between px-6 transition-all duration-300">
    <div class="flex items-center gap-4">

        {{-- Mobile Toggle --}}
        <button @click="$dispatch('toggle-mobile-sidebar')"
            class="md:hidden p-2.5 rounded-xl border border-gray-200 bg-white shadow-sm text-gray-600 hover:bg-gray-50">
            <i class="fas fa-bars"></i>
        </button>

        <div>
            <h2 class="text-sm font-bold">@yield('header_title', 'Dashboard')</h2>
            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">
                @yield('header_description', 'Admin Panel')
            </p>
        </div>
    </div>

    <div class="flex items-center gap-6">
        {{-- Notification --}}
        <button class="relative p-2.5 rounded-xl hover:bg-amber-50 text-gray-400 hover:text-amber-500 transition">
            <i class="fas fa-bell"></i>
            <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white"></span>
        </button>

        {{-- Profile --}}
        <div class="flex items-center gap-3">
            <div class="text-right hidden md:block">
                <p class="text-sm font-bold">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-amber-500 font-bold uppercase">Super Admin</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-amber-500 text-white flex items-center justify-center font-bold shadow-amber-200 shadow-md">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>
</header>
