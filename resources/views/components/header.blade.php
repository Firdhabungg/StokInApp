<header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
    <div>
        <h1 class="text-xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
        <p class="text-sm text-gray-500">@yield('page-description', '')</p>
    </div>
    <div class="flex items-center gap-4">
        <button class="relative p-2 text-gray-500 hover:text-amber-600 transition-colors">
            <i class="fas fa-bell text-xl"></i>
            <span
                class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
        </button>
        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">{{ Auth::user()->toko->name ?? 'No Toko' }}</p>
            </div>
            <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <form method="POST" action="{{ route('logout') }}" class="inline" id="logout-form">
                @csrf
                <button type="button" onclick="confirmLogout()"
                    class="text-gray-400 hover:text-red-600 transition-colors" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</header>
