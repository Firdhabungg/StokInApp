<div>
    @if (session()->has('success'))
        <div data-flash class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
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

    <livewire:kategori-list />

    <div id="modal-kategori" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"
            onclick="document.getElementById('modal-kategori').classList.add('hidden')">
        </div>

        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6 z-10">
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
@script
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

        window.addEventListener('open-modal', () => {
            document.getElementById('modal-kategori').classList.remove('hidden');
        });

        window.addEventListener('close-modal', () => {
            document.getElementById('modal-kategori').classList.add('hidden');
        });
    </script>
@endscript
