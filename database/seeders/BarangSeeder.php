<?php

namespace Database\Seeders;

use App\Models\Toko;
use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $toko = Toko::first();

        $barangs = [
            // Sembako
            ['nama' => 'Beras Premium 5kg', 'kode' => 'SMB-001', 'kategori' => 'Sembako', 'harga' => 75000],
            ['nama' => 'Minyak Goreng 2L', 'kode' => 'SMB-002', 'kategori' => 'Sembako', 'harga' => 35000],
            ['nama' => 'Gula Pasir 1kg', 'kode' => 'SMB-003', 'kategori' => 'Sembako', 'harga' => 15000],
            ['nama' => 'Telur Ayam 1kg', 'kode' => 'SMB-004', 'kategori' => 'Sembako', 'harga' => 28000],
            
            // Makanan Ringan
            ['nama' => 'Chitato 68g', 'kode' => 'SNK-001', 'kategori' => 'Makanan Ringan', 'harga' => 12000],
            ['nama' => 'Oreo 137g', 'kode' => 'SNK-002', 'kategori' => 'Makanan Ringan', 'harga' => 15000],
            ['nama' => 'Taro 65g', 'kode' => 'SNK-003', 'kategori' => 'Makanan Ringan', 'harga' => 8000],
            
            // Minuman
            ['nama' => 'Aqua 600ml', 'kode' => 'MNM-001', 'kategori' => 'Minuman', 'harga' => 4000],
            ['nama' => 'Teh Pucuk 350ml', 'kode' => 'MNM-002', 'kategori' => 'Minuman', 'harga' => 5000],
            ['nama' => 'Coca Cola 390ml', 'kode' => 'MNM-003', 'kategori' => 'Minuman', 'harga' => 7000],
            ['nama' => 'Kopi ABC Sachet', 'kode' => 'MNM-004', 'kategori' => 'Minuman', 'harga' => 2500],
            
            // Bahan Masak
            ['nama' => 'Indomie Goreng', 'kode' => 'MSK-001', 'kategori' => 'Bahan Masak', 'harga' => 3500],
            ['nama' => 'Kecap Manis ABC 275ml', 'kode' => 'MSK-002', 'kategori' => 'Bahan Masak', 'harga' => 18000],
            ['nama' => 'Saori Saus Tiram 135ml', 'kode' => 'MSK-003', 'kategori' => 'Bahan Masak', 'harga' => 12000],
            
            // Produk Susu
            ['nama' => 'Susu Ultra 1L', 'kode' => 'SSU-001', 'kategori' => 'Produk Susu', 'harga' => 18000],
            ['nama' => 'Dancow Fortigro 400g', 'kode' => 'SSU-002', 'kategori' => 'Produk Susu', 'harga' => 45000],
            
            // Alat Kebersihan
            ['nama' => 'Sabun Cuci Piring Sunlight 755ml', 'kode' => 'KBR-001', 'kategori' => 'Alat Kebersihan', 'harga' => 22000],
            ['nama' => 'Rinso Deterjen 800g', 'kode' => 'KBR-002', 'kategori' => 'Alat Kebersihan', 'harga' => 25000],
            
            // Perlengkapan Mandi
            ['nama' => 'Shampo Pantene 135ml', 'kode' => 'MND-001', 'kategori' => 'Perlengkapan Mandi', 'harga' => 28000],
            ['nama' => 'Sabun Lifebuoy 75g', 'kode' => 'MND-002', 'kategori' => 'Perlengkapan Mandi', 'harga' => 5000],
        ];

        foreach ($barangs as $item) {
            $kategori = KategoriBarang::where('nama_kategori', $item['kategori'])->first();
            
            Barang::create([
                'toko_id' => $toko->id,
                'kategori_id' => $kategori->kategori_id,
                'nama_barang' => $item['nama'],
                'kode_barang' => $item['kode'],
                'harga' => $item['harga'],
                'stok' => 0, // Stok akan diisi via StockSeeder
                'status' => 'habis',
            ]);
        }
    }
}
