<div>
    @if (session()->has('success'))
        <div data-flash class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div data-flash class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kategori Barang</h1>
            <p class="text-sm text-gray-500">Kelola kategori barang untuk toko Anda</p>
        </div>

        <button onclick="document.getElementById('modal-kategori').classList.remove('hidden')"
            class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white p-2 sm:px-5 rounded-xl font-medium transition-all duration-300 shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 flex items-center justify-center gap-2 text-sm sm:text-base w-full sm:w-auto">
            <i class="fa-solid fa-plus"></i> Tambah Kategori
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-3 mb-3">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-amber-500 text-lg"></i>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kategori..."
                class="w-full pl-12 pr-12 py-2 bg-white border-2 border-amber-200/50 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-100 transition-all duration-300 text-base shadow-sm" />
        </div>
    </div>

    <livewire:kategori-list />
    {{-- Modal Form --}}
    <div id="modal-kategori" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"
            onclick="document.getElementById('modal-kategori').classList.add('hidden')">
        </div>

        {{-- Modal Content --}}
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6 z-10">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Tambah Kategori</h2>
                <button onclick="document.getElementById('modal-kategori').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <livewire:kategori-form />

        </div>
    </div>
</div>
@push('scripts')
    <script>
        window.addEventListener('close-modal', () => {
            document.getElementById('modal-kategori').classList.add('hidden');
        });

        window.addEventListener('close-modal', () => {
            document.getElementById('modal-kategori').classList.add('hidden');
        });

        // Auto hide flash message setelah 3 detik
        document.addEventListener('livewire:navigated', autoHideFlash);
        document.addEventListener('DOMContentLoaded', autoHideFlash);
        document.addEventListener('livewire:updated', autoHideFlash);

        function autoHideFlash() {
            const flash = document.querySelectorAll('[data-flash]');
            flash.forEach(el => {
                setTimeout(() => {
                    el.style.transition = 'opacity 0.5s ease';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                }, 3000);
            });
        }
    </script>
@endpush
