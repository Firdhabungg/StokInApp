<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-3">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Daftar Kasir</h2>
            <p class="text-gray-500 mt-1 text-sm sm:text-base">Manajemen akun kasir untuk operasional penjualan</p>
            @if ($maxKasir != -1)
                <p class="text-sm text-amber-600 mt-1">
                    <i class="fas fa-users mr-1"></i>
                    Kuota kasir: {{ $kasirs->count() }}/{{ $maxKasir }}
                    ({{ $remainingSlots }} slot tersisa)
                </p>
            @endif
        </div>
        @if ($canAddUser)
            <a href="{{ route('kasir.create') }}"
                class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white px-3 sm:px-5 py-2.5 rounded-xl font-medium transition-all duration-300 shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 flex items-center justify-center gap-2 text-sm sm:text-base w-full sm:w-auto">
                <i class="fas fa-plus"></i>
                <span>Tambah Kasir</span>
            </a>
        @else
            <div class="text-center sm:text-right w-full sm:w-auto">
                <button disabled
                    class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg font-medium cursor-not-allowed w-full sm:w-auto">
                    <i class="fas fa-lock mr-2"></i>Batas Tercapai
                </button>
                <a href="{{ route('subscription.index') }}" class="block text-sm text-amber-600 hover:underline mt-1">
                    Upgrade paket →
                </a>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-3 mb-3">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-amber-500 text-lg"></i>
            </div>
            <input type="text" wire:model.debounce.300ms.live="search" placeholder="Cari kasir...."
                class="w-full pl-12 pr-4 py-2 bg-white border-2 border-amber-200/50 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-100 transition-all duration-300 text-base shadow-sm">
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-sm rounded-xl border border-gray-200">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 font-semibold text-gray-700">Nama</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Email</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Role</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kasirs as $kasir)
                    <tr class="bg-white border-b border-gray-100 hover:bg-green-50 transition-colors duration-150">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $kasir->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $kasir->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-4 py-1.5 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">
                                Kasir
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <form id="delete-form-{{ $kasir->id }}" action="{{ route('kasir.destroy', $kasir) }}"
                                method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="text-red-600 hover:text-red-800"
                                    onclick="deleteKasir({{ $kasir->id }}, '{{ $kasir->name }}')">
                                    delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            Tidak ada kasir yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $kasirs->links() }}
        </div>
    </div>
</div>
