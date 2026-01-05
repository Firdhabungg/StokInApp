<?php

namespace Database\Seeders;

use App\Models\Toko;
use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $toko = Toko::first();

        $barangs = [
            // Sembako
            ['nama' => 'Beras Premium 5kg', 'kategori' => 'Sembako', 'harga' => 75000],
            ['nama' => 'Minyak Goreng 2L', 'kategori' => 'Sembako', 'harga' => 35000],
            ['nama' => 'Gula Pasir 1kg', 'kategori' => 'Sembako', 'harga' => 15000],
            ['nama' => 'Telur Ayam 1kg', 'kategori' => 'Sembako', 'harga' => 28000],

            // Makanan Ringan
            ['nama' => 'Chitato 68g', 'kategori' => 'Makanan Ringan', 'harga' => 12000],
            ['nama' => 'Oreo 137g', 'kategori' => 'Makanan Ringan', 'harga' => 15000],
            ['nama' => 'Taro 65g', 'kategori' => 'Makanan Ringan', 'harga' => 8000],

            // Minuman
            ['nama' => 'Aqua 600ml', 'kategori' => 'Minuman', 'harga' => 4000],
            ['nama' => 'Teh Pucuk 350ml', 'kategori' => 'Minuman', 'harga' => 5000],
            ['nama' => 'Coca Cola 390ml', 'kategori' => 'Minuman', 'harga' => 7000],
            ['nama' => 'Kopi ABC Sachet', 'kategori' => 'Minuman', 'harga' => 2500],

            // Bahan Masak
            ['nama' => 'Indomie Goreng', 'kategori' => 'Bahan Masak', 'harga' => 3500],
            ['nama' => 'Kecap Manis ABC 275ml', 'kategori' => 'Bahan Masak', 'harga' => 18000],
            ['nama' => 'Saori Saus Tiram 135ml', 'kategori' => 'Bahan Masak', 'harga' => 12000],

            // Produk Susu
            ['nama' => 'Susu Ultra 1L', 'kategori' => 'Produk Susu', 'harga' => 18000],
            ['nama' => 'Dancow Fortigro 400g', 'kategori' => 'Produk Susu', 'harga' => 45000],

            // Alat Kebersihan
            ['nama' => 'Sabun Cuci Piring Sunlight 755ml', 'kategori' => 'Alat Kebersihan', 'harga' => 22000],
            ['nama' => 'Rinso Deterjen 800g', 'kategori' => 'Alat Kebersihan', 'harga' => 25000],

            // Perlengkapan Mandi
            ['nama' => 'Shampo Pantene 135ml', 'kategori' => 'Perlengkapan Mandi', 'harga' => 28000],
            ['nama' => 'Sabun Lifebuoy 75g', 'kategori' => 'Perlengkapan Mandi', 'harga' => 5000],
        ];

        $counter = 1;
        foreach ($barangs as $item) {
            $kategori = KategoriBarang::where('nama_kategori', $item['kategori'])->first();

            // Hitung harga jual dengan margin 20%
            $hargaJual = $item['harga'] * 1.2;
            
            // Generate kode barang dengan format BRG-XXXXX
            $kodeBarang = 'BRG-' . str_pad($counter, 5, '0', STR_PAD_LEFT);

            Barang::create([
                'toko_id' => $toko->id,
                'kategori_id' => $kategori->kategori_id,
                'nama_barang' => $item['nama'],
                'kode_barang' => $kodeBarang,
                'harga' => $item['harga'],
                'harga_jual' => $hargaJual,
                'stok' => 0,
                'status' => 'habis',
            ]);
            
            $counter++;
        }
    }
}
