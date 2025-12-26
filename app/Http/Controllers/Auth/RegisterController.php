<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
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

            // Get selected plan from query param
            $selectedPlan = $request->query('plan', 'free');
            
            // Create trial subscription for free plan
            $freePlan = SubscriptionPlan::where('slug', 'free')->first();
            
            if ($freePlan) {
                Subscription::create([
                    'toko_id' => $toko->id,
                    'plan_id' => $freePlan->id,
                    'status' => 'trial',
                    'starts_at' => now(),
                    'expires_at' => now()->addDays($freePlan->duration_days),
                ]);
            }

            DB::commit();
            Auth::login($user);

            // If user selected Pro plan, redirect to checkout
            if ($selectedPlan === 'pro') {
                return redirect()->route('subscription.checkout', 'pro')
                    ->with('success', 'Registrasi berhasil! Silakan selesaikan pembayaran.');
            }

            return redirect()->intended('/dashboard')
                ->with('success', 'Registrasi berhasil! Trial 14 hari Anda sudah aktif.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }
}
