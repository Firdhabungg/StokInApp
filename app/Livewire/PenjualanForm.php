<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockBatch;
use App\Services\StockService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Transaksi Baru')]
#[Layout('layouts.dashboard')]
class PenjualanForm extends Component
{
    use WithFileUploads;

    // Search & product selection
    public string $search = '';
    public string $selectedKategori = '';

    // Cart items: array of ['barang_id', 'nama', 'kode', 'harga', 'jumlah', 'stok']
    public array $items = [];

    // Checkout fields
    public string $metode_pembayaran = '';
    public $uang_dibayar = null;
    public string $keterangan = '';
    public $bukti_pembayaran = null;

    public function addItem(int $barangId): void
    {
        \Illuminate\Support\Facades\Log::info('addItem hit', ['barangId' => $barangId]);
        $tokoId = Auth::user()->effective_toko_id;

        $barang = Barang::where('id', $barangId)
            ->where('toko_id', $tokoId)
            ->first();

        if (!$barang) return;

        $stokTersedia = StockBatch::where('barang_id', $barang->id)
            ->where('toko_id', $tokoId)
            ->where('jumlah_sisa', '>', 0)
            ->where('status', '!=', 'kadaluarsa')
            ->sum('jumlah_sisa');

        if ($stokTersedia < 1) {
            $this->dispatch('show-toast', type: 'warning', message: 'Stok produk ini habis!');
            return;
        }

        // Check if already in cart
        $existingIndex = null;
        foreach ($this->items as $index => $item) {
            if ($item['barang_id'] == $barangId) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            $newQty = $this->items[$existingIndex]['jumlah'] + 1;
            if ($newQty > $stokTersedia) {
                $this->dispatch('show-toast', type: 'warning', message: "Stok maksimal: {$stokTersedia}");
                return;
            }
            $this->items[$existingIndex]['jumlah'] = $newQty;
        } else {
            $this->items[] = [
                'barang_id' => $barang->id,
                'nama' => $barang->nama_barang,
                'kode' => $barang->kode_barang,
                'harga' => (int) $barang->harga_jual,
                'jumlah' => 1,
                'stok' => (int) $stokTersedia,
            ];
        }
    }

    public function updateQty(int $index, int $qty): void
    {
        if (!isset($this->items[$index])) return;

        if ($qty < 1) {
            $this->removeItem($index);
            return;
        }

        if ($qty > $this->items[$index]['stok']) {
            $this->dispatch('show-toast', type: 'warning', message: "Stok maksimal: {$this->items[$index]['stok']}");
            return;
        }

        $this->items[$index]['jumlah'] = $qty;
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // re-index
    }

    public function getGrandTotalProperty(): int
    {
        return collect($this->items)->sum(fn($item) => $item['harga'] * $item['jumlah']);
    }

    public function getTotalItemsProperty(): int
    {
        return collect($this->items)->sum('jumlah');
    }

    public function getKembalianProperty(): int
    {
        if ($this->metode_pembayaran !== 'cash' || !$this->uang_dibayar) {
            return 0;
        }
        return max(0, (int) $this->uang_dibayar - $this->grandTotal);
    }

    public function getItemInCart(int $barangId): ?int
    {
        foreach ($this->items as $item) {
            if ($item['barang_id'] == $barangId) {
                return $item['jumlah'];
            }
        }
        return null;
    }

    public function save(): void
    {
        $this->validate([
            'items' => 'required|array|min:1',
            'metode_pembayaran' => 'required|in:cash,transfer,qris',
            'keterangan' => 'nullable|string|max:500',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'items.required' => 'Tambahkan minimal 1 produk ke keranjang.',
            'items.min' => 'Tambahkan minimal 1 produk ke keranjang.',
            'metode_pembayaran.required' => 'Pilih metode pembayaran.',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid.',
        ]);

        $tokoId = Auth::user()->effective_toko_id;

        // Validate stock for all items before transaction
        foreach ($this->items as $item) {
            $stok = StockBatch::where('barang_id', $item['barang_id'])
                ->where('toko_id', $tokoId)
                ->where('jumlah_sisa', '>', 0)
                ->where('status', '!=', 'kadaluarsa')
                ->sum('jumlah_sisa');

            if ($stok < $item['jumlah']) {
                $this->addError('items', "Stok {$item['nama']} tidak mencukupi. Tersedia: {$stok}, Diminta: {$item['jumlah']}");
                return;
            }
        }

        // Validate cash payment
        if ($this->metode_pembayaran === 'cash') {
            if (!$this->uang_dibayar || (int) $this->uang_dibayar < $this->grandTotal) {
                $this->addError('uang_dibayar', 'Uang dibayar kurang dari total transaksi.');
                return;
            }
        }

        $buktiPembayaranPath = null;
        if ($this->bukti_pembayaran) {
            $buktiPembayaranPath = $this->bukti_pembayaran->store('bukti-pembayaran', 'public');
        }

        try {
            DB::beginTransaction();

            $total = $this->grandTotal;
            $uangDibayar = $this->metode_pembayaran === 'cash'
                ? (int) $this->uang_dibayar
                : $total;

            $sale = Sale::create([
                'toko_id'           => $tokoId,
                'user_id'           => Auth::id(),
                'kode_transaksi'    => Sale::generateKodeTransaksi($tokoId),
                'tanggal'           => now(),
                'total'             => $total,
                'uang_dibayar'      => $uangDibayar,
                'kembalian'         => max(0, $uangDibayar - $total),
                'status'            => 'selesai',
                'keterangan'        => $this->keterangan ?: null,
                'metode_pembayaran' => $this->metode_pembayaran,
                'bukti_pembayaran'  => $buktiPembayaranPath,
            ]);

            $stockService = app(StockService::class);

            foreach ($this->items as $item) {
                $subtotal = $item['jumlah'] * $item['harga'];

                SaleItem::create([
                    'sale_id'      => $sale->id,
                    'barang_id'    => $item['barang_id'],
                    'jumlah'       => $item['jumlah'],
                    'harga_satuan' => $item['harga'],
                    'subtotal'     => $subtotal,
                ]);

                $stockService->processStockOut([
                    'barang_id'  => $item['barang_id'],
                    'toko_id'    => $tokoId,
                    'user_id'    => Auth::id(),
                    'jumlah'     => $item['jumlah'],
                    'tgl_keluar' => now()->toDateString(),
                    'alasan'     => 'penjualan',
                    'keterangan' => 'Transaksi: ' . $sale->kode_transaksi,
                ]);
            }

            DB::commit();

            session()->flash('success', 'Transaksi berhasil disimpan!');
            $this->redirectRoute('penjualan.show', ['sale' => $sale->id], navigate: true);
        } catch (Exception $e) {
            DB::rollBack();
            $this->addError('error', $e->getMessage());
        }
    }

    public function render()
    {
        $tokoId = Auth::user()->effective_toko_id;

        $barangsQuery = Barang::where('toko_id', $tokoId)
            ->whereHas('stockBatches', function ($q) use ($tokoId) {
                $q->where('toko_id', $tokoId)
                    ->where('jumlah_sisa', '>', 0)
                    ->where('status', '!=', 'kadaluarsa');
            });

        if ($this->search) {
            $barangsQuery->where(function ($q) {
                $q->where('nama_barang', 'like', "%{$this->search}%")
                    ->orWhere('kode_barang', 'like', "%{$this->search}%");
            });
        }

        if ($this->selectedKategori !== '') {
            $barangsQuery->where('kategori_id', $this->selectedKategori);
        }

        $barangs = $barangsQuery->orderBy('nama_barang')->get()->map(function ($barang) use ($tokoId) {
            $barang->stok_tersedia = StockBatch::where('barang_id', $barang->id)
                ->where('toko_id', $tokoId)
                ->where('jumlah_sisa', '>', 0)
                ->where('status', '!=', 'kadaluarsa')
                ->sum('jumlah_sisa');
            return $barang;
        });

        $kodeTransaksi = Sale::generateKodeTransaksi($tokoId);

        $kategoris = KategoriBarang::where('toko_id', $tokoId)->orderBy('nama_kategori')->get();

        return view('penjualan.create', [
            'barangs' => $barangs,
            'kodeTransaksi' => $kodeTransaksi,
            'kategoris' => $kategoris,
            'selectedKategori' => $this->selectedKategori,
        ]);
    }
}
