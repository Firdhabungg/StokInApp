<header
    class="sticky top-0 z-30 h-20 bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-6 md:px-10"
    x-data="{ open: false }">
    <div class="flex items-center gap-4">
        <div>
            <h1 class="text-sm font-bold text-slate-800">@yield('page-title', 'Dashboard')</h1>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">@yield('page-description')</p>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <a href="{{ route('profil.index') }}" class="flex items-center gap-2 p-2 rounded-full transition-colors">
            <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center hover:bg-gray-200">
                <i class="fas fa-user text-white text-sm hover:text-amber-500"></i>
            </div>
        </a>
    </div>

</header>
