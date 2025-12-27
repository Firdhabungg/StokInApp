<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;

class HargaJualSeeder extends Seeder
{
    public function run(): void
    {
        $margin = 0.3; 

        Barang::where(function ($query) {
                $query->whereNull('harga_jual')
                      ->orWhere('harga_jual', 0);
            })
            ->get()
            ->each(function ($barang) use ($margin) {
 
                $harga = $barang->harga;

                if (!$harga || $harga <= 0) {
                    return;
                }

                $hargaJual = (int) round($harga + ($harga * $margin));

                $barang->update([
                    'harga_jual' => $hargaJual,
                ]);
            });
    }
}