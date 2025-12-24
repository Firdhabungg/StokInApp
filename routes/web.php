<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController as UserDashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\KeuanganController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\ProfilController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('keuangan')->name('keuangan.')->group(function () {
        Route::get('/', [KeuanganController::class, 'index'])->name('index');
        Route::get('/billing', [KeuanganController::class, 'billing'])->name('billing');
        Route::get('/invoice/{id}', [KeuanganController::class, 'invoice'])->name('invoice');
    });

    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::get('/', [PengaturanController::class, 'index'])->name('index');
        Route::post('/update', [PengaturanController::class, 'update'])->name('update');
    });


    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/', function () {
            return view('admin.pelanggan.index');
        })->name('index');

        Route::get('/{id}', function ($id) {
            return view('admin.pelanggan.show', ['id' => $id]);
        })->name('show');
    });

    Route::prefix('kelola-paket')->name('kelola-paket.')->group(function () {
        Route::get('/', function () {
            return view('admin.kelola-paket.index');
        })->name('index');
    });
});


// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Barang
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

    // Penjualan
    Route::get('/penjualan', function () {
        return view('penjualan.index');
    })->name('penjualan.index');

    // Profil
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');

    // Owner only routes
    Route::middleware('owner')->group(function () {
        Route::resource('staff', StaffController::class)->except(['show', 'edit', 'update']);
    });
});
