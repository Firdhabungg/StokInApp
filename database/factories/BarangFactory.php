<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $namaBarang = [
            'Indomie Goreng',
            'Mie Sedaap',
            'Beras Premium',
            'Minyak Goreng',
            'Gula Pasir',
            'Teh Botol',
            'Aqua 600ml',
            'Kopi Kapal Api',
            'Sabun Mandi',
            'Shampo',
            'Pasta Gigi',
            'Deterjen'
        ];

        return [
            'nama_barang' => fake()->randomElement($namaBarang),
            'kode_barang' => 'BRG-' . str_pad(fake()->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'stok' => fake()->numberBetween(10, 100),
            'harga' => fake()->numberBetween(5000, 50000),
            'tgl_kadaluwarsa' => fake()->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
            'status' => fake()->randomElement(['tersedia', 'menipis', 'habis']),
            'kategori_id' => fake()->numberBetween(1, 5),
        ];
    }
}
