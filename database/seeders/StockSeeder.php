<?php

namespace Database\Seeders;

use App\Models\Toko;
use App\Models\User;
use App\Models\Barang;
use App\Services\StockService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stockService = app(StockService::class);
        $toko = Toko::first();
        $owner = User::where('role', 'owner')->first();

        $barangs = Barang::where('toko_id', $toko->id)->get();

        foreach ($barangs as $barang) {
            // Buat 2-3 batch per barang dengan tanggal kadaluarsa berbeda
            $batchCount = rand(2, 3);

            for ($i = 0; $i < $batchCount; $i++) {
                $jumlah = rand(10, 50);
                $daysAgo = rand(1, 30);
                $expiryDays = rand(7, 90); // Beberapa akan hampir kadaluarsa

                $stockService->processStockIn([
                    'barang_id' => $barang->id,
                    'toko_id' => $toko->id,
                    'user_id' => $owner->id,
                    'jumlah' => $jumlah,
                    'tgl_masuk' => Carbon::now()->subDays($daysAgo)->toDateString(),
                    'tgl_kadaluarsa' => Carbon::now()->addDays($expiryDays)->toDateString(),
                    'supplier' => $this->getRandomSupplier(),
                    'harga_beli' => $barang->harga * 0.8, // Harga beli 80% dari harga jual
                ]);
            }
        }

        // Tambahkan beberapa barang keluar untuk simulasi
        $this->simulateStockOut($stockService, $toko, $owner);
    }

    private function getRandomSupplier(): string
    {
        $suppliers = [
            'PT Indofood',
            'PT Unilever Indonesia',
            'PT Nestle Indonesia',
            'CV Sumber Makmur',
            'UD Jaya Abadi',
            'PT Wings Surya',
        ];

        return $suppliers[array_rand($suppliers)];
    }

    private function simulateStockOut(StockService $stockService, Toko $toko, User $user): void
    {
        $barangs = Barang::where('toko_id', $toko->id)
            ->whereHas('stockBatches', function ($q) {
                $q->where('jumlah_sisa', '>', 0);
            })
            ->inRandomOrder()
            ->limit(10)
            ->get();

        foreach ($barangs as $barang) {
            $jumlah = rand(5, 15);

            try {
                $stockService->processStockOut([
                    'barang_id' => $barang->id,
                    'toko_id' => $toko->id,
                    'user_id' => $user->id,
                    'jumlah' => $jumlah,
                    'tgl_keluar' => Carbon::now()->subDays(rand(0, 5))->toDateString(),
                    'alasan' => $this->getRandomAlasan(),
                ]);
            } catch (\Exception $e) {
                // Skip jika stok tidak cukup
                continue;
            }
        }
    }

    private function getRandomAlasan(): string
    {
        $alasan = ['penjualan', 'penjualan', 'penjualan', 'rusak', 'retur'];
        return $alasan[array_rand($alasan)];
    }
}
