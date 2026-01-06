<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use App\Models\User;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Default categories for new stores.
     */
    protected $defaultCategories = [
        ['nama_kategori' => 'Makanan', 'deskripsi_kategori' => 'Produk makanan dan snack'],
        ['nama_kategori' => 'Minuman', 'deskripsi_kategori' => 'Produk minuman'],
        ['nama_kategori' => 'Kebutuhan Rumah Tangga', 'deskripsi_kategori' => 'Produk kebutuhan sehari-hari'],
        ['nama_kategori' => 'Elektronik', 'deskripsi_kategori' => 'Produk elektronik dan aksesoris'],
        ['nama_kategori' => 'Alat Tulis', 'deskripsi_kategori' => 'Alat tulis dan perlengkapan kantor'],
        ['nama_kategori' => 'Lainnya', 'deskripsi_kategori' => 'Kategori lainnya'],
    ];

    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'toko_name' => ['required', 'string', 'max:255'],
            'toko_email' => ['required', 'string', 'email', 'max:255', 'unique:tokos,email'],
            'toko_address' => ['required', 'string'],
            'toko_phone' => ['required', 'string', 'max:20'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password minimal 8 karakter.',
            'toko_name.required' => 'Nama toko wajib diisi.',
            'toko_email.required' => 'Email toko wajib diisi.',
            'toko_email.email' => 'Format email toko tidak valid.',
            'toko_email.unique' => 'Email toko sudah terdaftar.',
            'toko_address.required' => 'Alamat toko wajib diisi.',
            'toko_phone.required' => 'Nomor telepon toko wajib diisi.',
        ]);

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

            // Membuat kategori default untuk toko baru
            foreach ($this->defaultCategories as $category) {
                KategoriBarang::create([
                    'toko_id' => $toko->id,
                    'nama_kategori' => $category['nama_kategori'],
                    'deskripsi_kategori' => $category['deskripsi_kategori'],
                ]);
            }

            DB::commit();
            Auth::login($user);

            // Redirect to plan selection page instead of dashboard
            return redirect()->route('subscription.index')
                ->with('info', 'Registrasi berhasil! Silakan pilih paket langganan untuk memulai.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Registration error: ' . $e->getMessage() . ' - ' . $e->getFile() . ':' . $e->getLine());
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}

