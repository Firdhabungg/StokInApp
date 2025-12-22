<?php

namespace Database\Seeders;

use App\Models\Toko;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $toko = Toko::create([
            'name' => 'Toko Stokin',
            'email' => 'toko@stokinapp.com',
            'address' => 'Jl. Contoh No. 123, Jakarta',
            'phone' => '021-12345678',
        ]);
        User::create([
            'name' => 'Owner',
            'email' => 'admin@stokinapp.com',
            'password' => Hash::make('owner123'),
            'role' => 'owner',
            'toko_id' => $toko->id
        ]);

        User::create([
            'name' => 'Staff',
            'email' => 'staff@stokinapp.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
            'toko_id' => $toko->id
        ]);
    }
}
