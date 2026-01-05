<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SubscriptionPlanSeeder::class, // 0. Buat Paket Langganan
            UserSeeder::class,      // 1. Buat Toko + User (Owner, Kasir, Staff)
            KategoriSeeder::class,  // 2. Buat Kategori Barang
            BarangSeeder::class,    // 3. Buat Data Barang
            StockSeeder::class,     // 4. Buat Stok Masuk & Keluar (Batch)
            SaleSeeder::class,
        ]);
    }
}
