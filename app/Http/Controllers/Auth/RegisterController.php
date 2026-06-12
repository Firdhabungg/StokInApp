<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\KategoriBarang;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class RegisterController extends Controller
{
    protected $defaultCategories = [
        ['nama_kategori' => 'Makanan', 'deskripsi_kategori' => 'Produk makanan dan snack'],
        ['nama_kategori' => 'Minuman', 'deskripsi_kategori' => 'Produk minuman'],
        ['nama_kategori' => 'Kebutuhan Rumah Tangga', 'deskripsi_kategori' => 'Produk kebutuhan sehari-hari'],
        ['nama_kategori' => 'Elektronik', 'deskripsi_kategori' => 'Produk elektronik dan aksesoris'],
        ['nama_kategori' => 'Alat Tulis', 'deskripsi_kategori' => 'Alat tulis dan perlengkapan kantor'],
        ['nama_kategori' => 'Lainnya', 'deskripsi_kategori' => 'Kategori lainnya'],
    ];

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $request->validated();

        try {
            DB::beginTransaction();
            $toko = Toko::create([
                'name' => $request->toko_name,
                'email' => $request->toko_email,
                'address' => $request->toko_address,
                'phone' => $request->toko_phone,
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'toko_id' => $toko->id,
                'role' => 'owner',
            ]);

            foreach ($this->defaultCategories as $category) {
                KategoriBarang::create([
                    'toko_id' => $toko->id,
                    'nama_kategori' => $category['nama_kategori'],
                    'deskripsi_kategori' => $category['deskripsi_kategori'],
                ]);
            }

            DB::commit();

            // Kirim email verifikasi
            $user->sendEmailVerificationNotification();

            // Login lalu arahkan ke halaman verifikasi
            Auth::login($user);

            return redirect()->route('verification.notice')
                ->with('info', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi akun sebelum melanjutkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Registration error: ' . $e->getMessage() . ' - ' . $e->getFile() . ':' . $e->getLine());
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
