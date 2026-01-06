<?php

namespace Database\Seeders;

use App\Models\Toko;
use App\Models\KategoriBarang;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $toko = Toko::first();
        $toko = Toko::find(4);

        $kategoris = [
            ['nama_kategori' => 'Sembako', 'deskripsi_kategori' => 'Barang kebutuhan pokok sehari-hari'],
            ['nama_kategori' => 'Makanan Ringan', 'deskripsi_kategori' => 'Snack dan camilan'],
            ['nama_kategori' => 'Minuman', 'deskripsi_kategori' => 'Minuman kemasan'],
            ['nama_kategori' => 'Bahan Masak', 'deskripsi_kategori' => 'Bumbu dan bahan masakan'],
            ['nama_kategori' => 'Produk Susu', 'deskripsi_kategori' => 'Susu dan produk olahan susu'],
            ['nama_kategori' => 'Alat Kebersihan', 'deskripsi_kategori' => 'Produk kebersihan rumah'],
            ['nama_kategori' => 'Perlengkapan Mandi', 'deskripsi_kategori' => 'Sabun, shampo, dan perlengkapan mandi'],
        ];

        foreach ($kategoris as $kategori) {
            KategoriBarang::create([
                'toko_id' => $toko->id,
                'nama_kategori' => $kategori['nama_kategori'],
                'deskripsi_kategori' => $kategori['deskripsi_kategori'],
            ]);
        }
    }
}
