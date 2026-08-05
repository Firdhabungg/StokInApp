 @section('page-title', 'Form Stok Keluar')
 @section('page-description', 'Kelola data stok toko Anda')
 <div>
     @if (session()->has('success'))
         <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
             {{ session('success') }}
         </div>
     @endif

     @if ($errors->has('error'))
         <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg border border-red-200 flex items-start gap-2">
             <i class="fas fa-exclamation-circle mt-1"></i>
             <div>{{ $errors->first('error') }}</div>
         </div>
     @endif

     <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
         <form wire:submit.prevent="save">
             {{-- Mode Selector --}}
             <div class="mb-6 flex gap-3">
                 <button type="button" wire:click="setMode('fifo')"
                     class="flex items-center gap-2 px-4 py-2 rounded-lg border-2 font-medium text-sm transition-all {{ $mode === 'fifo' ? 'border-red-500 bg-red-50 text-red-700' : 'border-gray-200 text-gray-500 hover:border-red-400 hover:text-red-600' }}">
                     <i class="fas fa-sort-amount-down"></i> Otomatis (FIFO)
                 </button>
                 <button type="button" wire:click="setMode('manual')"
                     class="flex items-center gap-2 px-4 py-2 rounded-lg border-2 font-medium text-sm transition-all {{ $mode === 'manual' ? 'border-orange-500 bg-orange-50 text-orange-700' : 'border-gray-200 text-gray-500 hover:border-orange-400 hover:text-orange-600' }}">
                     <i class="fas fa-hand-pointer"></i> Pilih Batch Manual
                 </button>
             </div>

             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <div>
                     <label for="barang_id" class="block text-sm font-medium text-gray-700 mb-2">
                         Pilih Barang <span class="text-red-500">*</span>
                     </label>
                     <select wire:model.live="barang_id" id="barang_id"
                         class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                         <option value="">-- Pilih Barang --</option>
                         @foreach ($barangs as $barang)
                             <option value="{{ $barang->id }}">
                                 {{ $barang->kode_barang }} - {{ $barang->nama_barang }}
                             </option>
                         @endforeach
                     </select>
                     @error('barang_id')
                         <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                     @enderror
                 </div>

                 <div>
                     <label class="block text-sm font-medium text-gray-700 mb-2">Stok Tersedia</label>
                     <div
                         class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-gray-600 flex items-center justify-between">
                         @if ($barang_id)
                             <span wire:loading.remove wire:target="barang_id"><span
                                     class="font-bold text-gray-900">{{ $total_stock }}</span> unit tersedia</span>
                             <span wire:loading wire:target="barang_id" class="text-gray-400"><i
                                     class="fas fa-spinner fa-spin"></i> Memuat data...</span>
                         @else
                             Pilih barang terlebih dahulu
                         @endif
                     </div>
                 </div>

                 @if ($mode === 'fifo')
                     <div>
                         <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                             Jumlah Keluar <span class="text-red-500">*</span>
                         </label>
                         <input type="number" wire:model.live.debounce.300ms="jumlah" id="jumlah" min="1"
                             max="{{ $total_stock > 0 ? $total_stock : 1 }}"
                             class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                         @error('jumlah')
                             <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                         @enderror
                     </div>
                 @endif

                 <div>
                     <label for="tgl_keluar" class="block text-sm font-medium text-gray-700 mb-2">
                         Tanggal Keluar <span class="text-red-500">*</span>
                     </label>
                     <input type="date" wire:model="tgl_keluar" id="tgl_keluar"
                         class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                     @error('tgl_keluar')
                         <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                     @enderror
                 </div>

                 <div>
                     <label for="alasan" class="block text-sm font-medium text-gray-700 mb-2">
                         Alasan <span class="text-red-500">*</span>
                     </label>
                     <select wire:model="alasan" id="alasan"
                         class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                         <option value="">-- Pilih Alasan --</option>
                         <option value="rusak">Rusak</option>
                         <option value="kadaluarsa">Kadaluarsa</option>
                         <option value="retur">Retur ke Supplier</option>
                         <option value="hilang">Hilang/Selisih Stock</option>
                         <option value="sample">Sample/Promosi</option>
                         <option value="lainnya">Lainnya</option>
                     </select>
                     @error('alasan')
                         <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                     @enderror
                 </div>

                 <div class="md:col-span-2">
                     <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                     <textarea wire:model="keterangan" id="keterangan" rows="3" placeholder="Catatan tambahan (opsional)"
                         class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500"></textarea>
                     @error('keterangan')
                         <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                     @enderror
                 </div>
             </div>

             @if ($mode === 'fifo' && count($available_batches) > 0)
                 <div class="mt-6">
                     <h3 class="text-sm font-medium text-gray-700 mb-2">
                         <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                         Batch yang akan diambil (FIFO):
                     </h3>
                     <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-2">
                         @if ($jumlah > 0 && $jumlah <= $total_stock)
                             @php $remaining = $jumlah; @endphp
                             @foreach ($available_batches as $batch)
                                 @if ($remaining > 0)
                                     @php
                                         $take = min($remaining, $batch['jumlah_sisa']);
                                         $remaining -= $take;
                                     @endphp
                                     <div
                                         class="flex justify-between items-center bg-white p-3 border border-gray-200 rounded-md shadow-sm">
                                         <div>
                                             <span class="font-medium text-gray-800">{{ $batch['batch_code'] }}</span>
                                             <span class="text-xs text-gray-500 ml-2">Sisa:
                                                 {{ $batch['jumlah_sisa'] }}</span>
                                         </div>
                                         <div class="text-red-600 font-semibold">
                                             - {{ $take }} unit
                                         </div>
                                     </div>
                                 @endif
                             @endforeach
                         @elseif($jumlah > $total_stock)
                             <p class="text-sm text-red-500"><i class="fas fa-exclamation-triangle"></i> Jumlah melebihi
                                 total stok tersedia ({{ $total_stock }} unit).</p>
                         @else
                             <p class="text-sm text-gray-500">Masukkan jumlah keluar untuk melihat preview batch.</p>
                         @endif
                     </div>
                 </div>
             @endif

             @if ($mode === 'manual')
                 <div class="mt-6">
                     <div class="flex items-center justify-between mb-3">
                         <h3 class="text-sm font-medium text-gray-700">
                             <i class="fas fa-hand-pointer text-orange-500 mr-1"></i>
                             Pilih Batch yang Akan Dikeluarkan:
                         </h3>
                         @php
                             $manualTotal = 0;
                             foreach ($selected_batches as $qty) {
                                 $manualTotal += (int) $qty;
                             }
                         @endphp
                         <span class="text-xs font-semibold px-3 py-1 rounded-full bg-orange-100 text-orange-700">
                             Total dipilih: <span>{{ $manualTotal }}</span> unit
                         </span>
                     </div>
                     @if (count($available_batches) > 0)
                         <div class="space-y-3">
                             @foreach ($available_batches as $batch)
                                 <div
                                     class="flex items-center gap-4 bg-white p-3 border border-gray-200 rounded-lg hover:border-gray-300 transition-colors">
                                     <div class="flex-grow">
                                         <div>
                                             <p class="font-medium text-gray-800">{{ $batch['batch_code'] }}</p>
                                             <p class="text-xs text-gray-500">Sisa Stok: <span
                                                     class="font-bold">{{ $batch['jumlah_sisa'] }}</span> | Exp:
                                                 {{ $batch['tgl_kadaluarsa'] ?? '-' }}</p>
                                         </div>
                                     </div>
                                     <div class="w-32">
                                         <div
                                             class="flex items-center bg-gray-50 border border-gray-300 rounded-md overflow-hidden">
                                             <input type="number"
                                                 wire:model.live="selected_batches.{{ $batch['id'] }}" min="0"
                                                 max="{{ $batch['jumlah_sisa'] }}" placeholder="0"
                                                 class="w-full text-center border-none py-1 focus:ring-0 text-sm">
                                             <span
                                                 class="px-2 bg-gray-100 text-xs text-gray-500 border-l border-gray-300">unit</span>
                                         </div>
                                     </div>
                                 </div>
                             @endforeach
                         </div>
                         @error('selected_batches')
                             <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                         @enderror
                         @foreach ($available_batches as $batch)
                             @error('selected_batches.' . $batch['id'])
                                 <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                             @enderror
                         @endforeach
                     @else
                         <p class="text-sm text-gray-400">Pilih barang terlebih dahulu atau batch tidak tersedia.</p>
                     @endif
                 </div>
             @endif

             <div class="mt-6 flex items-center justify-end gap-4">
                 <a href="{{ route('stock.out.index') }}"
                     class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-300 transition-colors">
                     Batal
                 </a>
                 <button type="submit"
                     class="flex items-center gap-2 px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                     <svg wire:loading wire:target="save" aria-hidden="true" class="w-4 h-4 text-white animate-spin"
                         viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                         <path
                             d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                             fill="currentColor" opacity="0.3" />
                         <path
                             d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                             fill="currentColor" />
                     </svg>
                     <span wire:loading.remove wire:target="save">Simpan Data</span>
                     <span wire:loading wire:target="save">Menyimpan...</span>
                 </button>
             </div>
         </form>
     </div>
 </div>
