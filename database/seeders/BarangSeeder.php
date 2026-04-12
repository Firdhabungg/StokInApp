<?php

namespace Database\Seeders;

use App\Models\Toko;
use App\Models\Barang;
use App\Models\StockBatch;
use App\Models\KategoriBarang;
use App\Services\StockService;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $toko = Toko::first();
        $stockService = app(StockService::class);

        // 1. Hapus data lama dulu
        StockBatch::where('toko_id', $toko->id)->delete();
        Barang::where('toko_id', $toko->id)->delete();

        $barangs = [
            // Sembako
            ['nama' => 'Beras Premium 5kg',         'kategori' => 'Sembako',             'harga' => 75000,  'stok' => 50],
            ['nama' => 'Minyak Goreng 2L',           'kategori' => 'Sembako',             'harga' => 35000,  'stok' => 40],
            ['nama' => 'Gula Pasir 1kg',             'kategori' => 'Sembako',             'harga' => 15000,  'stok' => 60],
            ['nama' => 'Telur Ayam 1kg',             'kategori' => 'Sembako',             'harga' => 28000,  'stok' => 30],

            // Makanan Ringan
            ['nama' => 'Chitato 68g',                'kategori' => 'Makanan Ringan',      'harga' => 12000,  'stok' => 100],
            ['nama' => 'Oreo 137g',                  'kategori' => 'Makanan Ringan',      'harga' => 15000,  'stok' => 80],
            ['nama' => 'Taro 65g',                   'kategori' => 'Makanan Ringan',      'harga' => 8000,   'stok' => 90],

            // Minuman
            ['nama' => 'Aqua 600ml',                 'kategori' => 'Minuman',             'harga' => 4000,   'stok' => 200],
            ['nama' => 'Teh Pucuk 350ml',            'kategori' => 'Minuman',             'harga' => 5000,   'stok' => 150],
            ['nama' => 'Coca Cola 390ml',            'kategori' => 'Minuman',             'harga' => 7000,   'stok' => 120],
            ['nama' => 'Kopi ABC Sachet',            'kategori' => 'Minuman',             'harga' => 2500,   'stok' => 200],

            // Bahan Masak
            ['nama' => 'Indomie Goreng',             'kategori' => 'Bahan Masak',         'harga' => 3500,   'stok' => 150],
            ['nama' => 'Kecap Manis ABC 275ml',      'kategori' => 'Bahan Masak',         'harga' => 18000,  'stok' => 40],
            ['nama' => 'Saori Saus Tiram 135ml',     'kategori' => 'Bahan Masak',         'harga' => 12000,  'stok' => 35],

            // Produk Susu
            ['nama' => 'Susu Ultra 1L',              'kategori' => 'Produk Susu',         'harga' => 18000,  'stok' => 60],
            ['nama' => 'Dancow Fortigro 400g',       'kategori' => 'Produk Susu',         'harga' => 45000,  'stok' => 25],

            // Alat Kebersihan
            ['nama' => 'Sabun Cuci Piring Sunlight 755ml', 'kategori' => 'Alat Kebersihan', 'harga' => 22000, 'stok' => 30],
            ['nama' => 'Rinso Deterjen 800g',        'kategori' => 'Alat Kebersihan',     'harga' => 25000,  'stok' => 25],

            // Perlengkapan Mandi
            ['nama' => 'Shampo Pantene 135ml',       'kategori' => 'Perlengkapan Mandi',  'harga' => 28000,  'stok' => 40],
            ['nama' => 'Sabun Lifebuoy 75g',         'kategori' => 'Perlengkapan Mandi',  'harga' => 5000,   'stok' => 60],
        ];

        $counter = 1;
        foreach ($barangs as $item) {
            $kategori = KategoriBarang::where('nama_kategori', $item['kategori'])->first();

            $hargaJual  = $item['harga'] * 1.2;
            $kodeBarang = 'BRG-' . str_pad($counter, 5, '0', STR_PAD_LEFT);

            // Buat barang dengan stok 0 dulu, nanti diupdate otomatis via StockService
            $barang = Barang::create([
                'toko_id'     => $toko->id,
                'kategori_id' => $kategori->kategori_id,
                'nama_barang' => $item['nama'],
                'kode_barang' => $kodeBarang,
                'harga'       => $item['harga'],
                'harga_jual'  => $hargaJual,
                'stok'        => 0,
                'status'      => 'habis',
            ]);

            // Buat stock batch awal agar barang muncul di POS
            StockBatch::create([
                'barang_id'      => $barang->id,
                'toko_id'        => $toko->id,
                'batch_code'     => 'BTH-INIT-' . str_pad($counter, 5, '0', STR_PAD_LEFT),
                'jumlah_awal'    => $item['stok'],
                'jumlah_sisa'    => $item['stok'],
                'tgl_masuk'      => now()->toDateString(),
                'tgl_kadaluarsa' => null,
                'status'         => 'aman',
            ]);

            // Sync stok & status barang dari batch
            $stockService->updateBarangTotalStock($barang->id);

            $counter++;
        }
    }
}
