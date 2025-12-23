<header
    class="sticky top-0 z-30 h-20 bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-6 md:px-10">
    <div class="flex items-center gap-4">
        <div>
            <h1 class="text-sm font-bold text-slate-800">@yield('page-title', 'Dashboard')</h1>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">@yield('page-description')</p>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2 p-2 rounded-lg hover:bg-slate-100">
                <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
                <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                <i class="fas fa-chevron-down text-xs"></i>
            </button>

            <!-- Dropdown -->
            <div x-show="open" @click.away="open = false" x-transition
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200">
                <a href="{{ route('profil.index') }}"
                    class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-slate-700">
                    <i class="fas fa-user w-4"></i> Profil
                </a>
            </div>
        </div>
    </div>
</header>
