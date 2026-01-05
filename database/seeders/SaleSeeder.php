<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Barang;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $stockService = app(StockService::class);

        // Ambil user kasir / owner
        $user = User::whereIn('role', ['kasir', 'owner'])->first();

        if (!$user) {
            $this->command->warn('❌ Tidak ada user kasir / owner');
            return;
        }

        $tokoId = $user->toko_id;

        // Ambil barang yang BENAR-BENAR punya batch stok aktif (FIFO safe)
        $barangs = Barang::where('toko_id', $tokoId)
            ->whereHas('stockBatches', function ($q) use ($tokoId) {
                $q->where('toko_id', $tokoId)
                  ->where('jumlah_sisa', '>', 0)
                  ->where('status', '!=', 'kadaluarsa');
            })
            ->with(['stockBatches' => function ($q) {
                $q->where('jumlah_sisa', '>', 0)
                  ->where('status', '!=', 'kadaluarsa');
            }])
            ->get();

        if ($barangs->isEmpty()) {
            $this->command->warn('❌ Tidak ada barang dengan stok batch aktif');
            return;
        }

        $jumlahTransaksi = 20;
        $berhasil = 0;

        for ($i = 1; $i <= $jumlahTransaksi; $i++) {

            DB::beginTransaction();

            try {
                // Filter ulang barang yang masih punya stok batch
                $barangValid = $barangs->filter(function ($barang) {
                    return $barang->stockBatches->sum('jumlah_sisa') > 0;
                });

                if ($barangValid->isEmpty()) {
                    DB::rollBack();
                    break;
                }

                $tanggal = Carbon::now()->subDays(rand(0, 30));

                $sale = Sale::create([
                    'toko_id' => $tokoId,
                    'user_id' => $user->id,
                    'kode_transaksi' => Sale::generateKodeTransaksi($tokoId),
                    'tanggal' => $tanggal,
                    'total' => 0,
                    'status' => 'selesai',
                    'metode_pembayaran' => collect(['cash', 'qris', 'transfer'])->random(),
                ]);

                $total = 0;

                // Ambil 1–3 barang aman
                $items = $barangValid->random(
                    rand(1, min(3, $barangValid->count()))
                );

                foreach ($items as $barang) {
                    $stokTersedia = $barang->stockBatches->sum('jumlah_sisa');

                    if ($stokTersedia <= 0) {
                        continue;
                    }

                    $jumlah = rand(1, min(3, $stokTersedia));
                    $harga = $barang->harga_jual ?? $barang->harga;
                    $subtotal = $jumlah * $harga;

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'barang_id' => $barang->id,
                        'jumlah' => $jumlah,
                        'harga_satuan' => $harga,
                        'subtotal' => $subtotal,
                    ]);

                    // FIFO stock out (AMAN)
                    $stockService->processStockOut([
                        'barang_id' => $barang->id,
                        'toko_id' => $tokoId,
                        'user_id' => $user->id,
                        'jumlah' => $jumlah,
                        'tgl_keluar' => $tanggal->toDateString(),
                        'alasan' => 'penjualan',
                        'keterangan' => 'Seeder penjualan awal',
                    ]);

                    $total += $subtotal;
                }

                if ($total <= 0) {
                    DB::rollBack();
                    continue;
                }

                // Hitung pembayaran
                if ($sale->metode_pembayaran === 'cash') {
                    $uangDibayar = $total + rand(0, 5000);
                    $kembalian = $uangDibayar - $total;
                } else {
                    $uangDibayar = $total;
                    $kembalian = 0;
                }

                $sale->update([
                    'total' => $total,
                    'uang_dibayar' => $uangDibayar,
                    'kembalian' => $kembalian,
                ]);

                DB::commit();
                $berhasil++;

            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error("❌ Transaksi gagal: " . $e->getMessage());
            }
        }

        $this->command->info("✅ Seeder selesai: {$berhasil} transaksi berhasil dibuat");
    }
}
