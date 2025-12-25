<?php

namespace Database\Seeders;

use App\Models\Toko;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin (Developer/App Owner) - tidak terikat toko
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@stokinapp.com',
            'password' => Hash::make('super123'),
            'role' => 'super_admin',
            'toko_id' => null,
        ]);

        // Buat Toko pertama
        $toko = Toko::create([
            'name' => 'Toko Makmur Jaya',
            'email' => 'toko@stokinapp.com',
            'address' => 'Jl. Contoh No. 123, Jakarta',
            'phone' => '021-12345678',
        ]);

        // Owner (Pemilik Toko)
        User::create([
            'name' => 'Jen Ratri',
            'email' => 'owner@stokinapp.com',
            'password' => Hash::make('owner123'),
            'role' => 'owner',
            'toko_id' => $toko->id
        ]);

        // Kasir
        User::create([
            'name' => 'Destiyana',
            'email' => 'kasir@stokinapp.com',
            'password' => Hash::make('kasir123'),
            'role' => 'kasir',
            'toko_id' => $toko->id
        ]);
    }
}
