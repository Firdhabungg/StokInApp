<header x-data
    class="sticky top-0 z-30 h-20 bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-6 md:px-10"
    x-data="{ open: false, notifOpen: false, notifications: [], count: 0 }" x-init="fetch('/notifications/get')
        .then(r => r.json())
        .then(data => {
            notifications = data.notifications;
            count = data.count;
        })
        .catch(e => console.log('No notifications'));">
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
        <button class="relative p-2 rounded-full hover:bg-gray-100 text-gray-400 transition">
            <i class="fas fa-bell text-gray-500 text-lg"></i>
            <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white"></span>
        </button>

        <a href="{{ route('admin.profil.index') }}"
            class="flex items-center gap-3 p-1.5 pr-4 rounded-full hover:bg-amber-50 transition-colors group">
            <div
                class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center font-bold shadow-md group-hover:shadow-lg transition-shadow">
                <i class="fas fa-user text-sm"></i>
            </div>
            <div class="text-right hidden md:block">
                <p class="text-sm font-bold group-hover:text-amber-600 transition-colors">{{ auth()->user()->name }}</p>
            </div>
        </a>
    </div>
</header>
