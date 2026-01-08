{{-- Banner yang muncul saat Super Admin mengakses toko --}}
@if(auth()->user()->isAksesToko())
    <div class="bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-md">
        <div class="px-4 py-3 flex items-center justify-center gap-4 flex-wrap">
            <div class="flex items-center gap-2">
                <i class="fas fa-eye"></i>
                <span class="font-medium">
                    Mode Akses Toko: <strong>{{ auth()->user()->akses_toko_name }}</strong>
                </span>
                <span class="text-amber-200 text-sm hidden sm:inline">
                    (Anda melihat sebagai owner toko ini)
                </span>
            </div>
            
            <form action="{{ route('admin.akses-toko.stop') }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                    class="flex items-center gap-2 px-4 py-1.5 bg-white text-amber-600 rounded-lg font-semibold hover:bg-amber-50 transition-colors text-sm">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Admin</span>
                </button>
            </form>
        </div>
    </div>
@endif


