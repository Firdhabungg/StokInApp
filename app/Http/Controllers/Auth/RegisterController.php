<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
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
            // User data
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
            
            // Toko data
            'toko_name' => ['required', 'string', 'max:255'],
            'toko_email' => ['required', 'string', 'email', 'max:255', 'unique:tokos,email'],
            'toko_address' => ['required', 'string'],
            'toko_phone' => ['required', 'string', 'max:20'],
            
            // Role
            'role' => ['required', 'in:owner,admin,staff'],
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
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
        ]);

        try {
            DB::beginTransaction();

            // Create toko first
            $toko = Toko::create([
                'name' => $request->toko_name,
                'email' => $request->toko_email,
                'address' => $request->toko_address,
                'phone' => $request->toko_phone,
            ]);

            // Create user with selected role
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'toko_id' => $toko->id,
                'role' => $request->role,
            ]);

            DB::commit();

            // Login the user
            Auth::login($user);

            return redirect()->intended('/dashboard')->with('success', 'Registrasi berhasil! Selamat datang di StokIn.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }
}
