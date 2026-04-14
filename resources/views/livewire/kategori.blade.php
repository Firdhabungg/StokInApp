<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kategori Barang</h1>
            <p class="text-sm text-gray-500">Kelola kategori barang untuk toko Anda</p>
        </div>

        <button onclick="document.getElementById('modal-kategori').classList.remove('hidden')"
            class="bg-amber-500 hover:bg-amber-600 text-white font-semibold px-3 py-2 rounded-xl transition-colors">
            <i class="fa-solid fa-plus"></i> Tambah Kategori
        </button>
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
