<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategoris')->insert([
            [
                'nama_kategori' => 'Sembako',
                'deskripsi_kategori' => 'Barang kebutuhan pokok yang dikonsumsi sehari-hari',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Makanan Ringan',
                'deskripsi_kategori' => 'Produk makanan siap konsumsi yang bersifat camilan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Minuman',
                'deskripsi_kategori' => 'Produk cair atau serbuk yang dikonsumsi untuk diminum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Bahan Masak',
                'deskripsi_kategori' => 'Bahan yang digunakan dalam proses memasak makanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Frozen Food',
                'deskripsi_kategori' => 'Produk makanan yang disimpan dalam kondisi beku',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Produk Susu dan Olahan',
                'deskripsi_kategori' => 'Produk berbahan dasar susu atau hasil olahan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Roti dan Kue',
                'deskripsi_kategori' => 'Produk makanan berbahan dasar tepung yang siap konsumsi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Alat Kebersihan',
                'deskripsi_kategori' => 'Produk yang digunakan untuk menjaga kebersihan rumah atau lingkungan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Perlengkapan dapur',
                'deskripsi_kategori' => 'Alat atau barang penunjang aktivitas memasak dan makan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Perlengkapan Mandi',
                'deskripsi_kategori' => 'Produk yang digunakan untuk keperluan kebersihan dan perawatan tubuh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Perlengkapan Bayi',
                'deskripsi_kategori' => 'Produk khusus untuk perawatan dan kebutuhan bayi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Kecantikan',
                'deskripsi_kategori' => 'Produk Perawatan wajah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Produk Kesehatan',
                'deskripsi_kategori' => 'Produk yang digunakan untuk mengatasi kondisi kesehatan ringan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
