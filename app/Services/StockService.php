<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\StockBatch;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Proses barang masuk - membuat batch baru dan record stock_in
     */
    public function processStockIn(array $data): StockIn
    {
        return DB::transaction(function () use ($data) {
            // Generate batch code
            $batchCode = 'BTH-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // Create new batch
            $batch = StockBatch::create([
                'barang_id' => $data['barang_id'],
                'toko_id' => $data['toko_id'],
                'batch_code' => $batchCode,
                'jumlah_awal' => $data['jumlah'],
                'jumlah_sisa' => $data['jumlah'],
                'tgl_masuk' => $data['tgl_masuk'],
                'tgl_kadaluarsa' => $data['tgl_kadaluarsa'] ?? null,
                'status' => 'aman',
            ]);

            // Create stock in record
            $stockIn = StockIn::create([
                'barang_id' => $data['barang_id'],
                'batch_id' => $batch->id,
                'toko_id' => $data['toko_id'],
                'user_id' => $data['user_id'],
                'jumlah' => $data['jumlah'],
                'tgl_masuk' => $data['tgl_masuk'],
                'tgl_kadaluarsa' => $data['tgl_kadaluarsa'] ?? null,
                'supplier' => $data['supplier'] ?? null,
                'harga_beli' => $data['harga_beli'] ?? null,
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            // Update barang total stok
            $this->updateBarangTotalStock($data['barang_id']);

            return $stockIn;
        });
    }

    /**
     * Proses barang keluar dengan FIFO
     */
    public function processStockOut(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $barangId = $data['barang_id'];
            $tokoId = $data['toko_id'];
            $jumlahKeluar = $data['jumlah'];
            $stockOutRecords = [];

            // Get available batches ordered by FIFO (tgl_masuk ASC)
            $batches = StockBatch::where('barang_id', $barangId)
                ->where('toko_id', $tokoId)
                ->where('jumlah_sisa', '>', 0)
                ->where('status', '!=', 'kadaluarsa')
                ->orderBy('tgl_masuk', 'asc')
                ->get();

            $totalAvailable = $batches->sum('jumlah_sisa');

            if ($totalAvailable < $jumlahKeluar) {
                throw new \Exception("Stok tidak mencukupi. Tersedia: {$totalAvailable}, Diminta: {$jumlahKeluar}");
            }

            $remaining = $jumlahKeluar;

            foreach ($batches as $batch) {
                if ($remaining <= 0) break;

                $takeFromBatch = min($batch->jumlah_sisa, $remaining);

                // Create stock out record
                $stockOut = StockOut::create([
                    'barang_id' => $barangId,
                    'batch_id' => $batch->id,
                    'toko_id' => $tokoId,
                    'user_id' => $data['user_id'],
                    'jumlah' => $takeFromBatch,
                    'tgl_keluar' => $data['tgl_keluar'],
                    'alasan' => $data['alasan'] ?? 'penjualan',
                    'keterangan' => $data['keterangan'] ?? null,
                ]);

                $stockOutRecords[] = $stockOut;

                // Update batch remaining stock
                $batch->jumlah_sisa -= $takeFromBatch;
                $batch->save();

                $remaining -= $takeFromBatch;
            }

            // Update barang total stok
            $this->updateBarangTotalStock($barangId);

            return $stockOutRecords;
        });
    }

    /**
     * Update total stok di tabel barang berdasarkan jumlah batch
     */
    public function updateBarangTotalStock(int $barangId): void
    {
        $barang = Barang::find($barangId);
        if ($barang) {
            $totalStock = StockBatch::where('barang_id', $barangId)
                ->sum('jumlah_sisa');

            $barang->stok = $totalStock;

            // Update status berdasarkan stok
            if ($totalStock <= 0) {
                $barang->status = 'habis';
            } elseif ($totalStock <= 10) { // Threshold bisa diubah
                $barang->status = 'menipis';
            } else {
                $barang->status = 'tersedia';
            }

            $barang->save();
        }
    }

    /**
     * Update status batch berdasarkan tanggal kadaluarsa
     */
    public function updateBatchExpiryStatus(): int
    {
        $today = now()->toDateString();
        $nearExpiryDate = now()->addDays(7)->toDateString();

        // Update batch yang sudah kadaluarsa
        $expiredCount = StockBatch::where('tgl_kadaluarsa', '<=', $today)
            ->where('status', '!=', 'kadaluarsa')
            ->update(['status' => 'kadaluarsa']);

        // Update batch yang hampir kadaluarsa
        StockBatch::where('tgl_kadaluarsa', '>', $today)
            ->where('tgl_kadaluarsa', '<=', $nearExpiryDate)
            ->where('status', '=', 'aman')
            ->update(['status' => 'hampir_kadaluarsa']);

        return $expiredCount;
    }

    /**
     * Get batches untuk barang tertentu
     */
    public function getBatchesByBarang(int $barangId, int $tokoId)
    {
        return StockBatch::where('barang_id', $barangId)
            ->where('toko_id', $tokoId)
            ->orderBy('tgl_masuk', 'asc')
            ->get();
    }
}
