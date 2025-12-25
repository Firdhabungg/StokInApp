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
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\StockBatchController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Toko Management
    Route::get('/toko', [\App\Http\Controllers\Admin\AdminTokoController::class, 'index'])->name('toko.index');
    Route::get('/toko/{toko}', [\App\Http\Controllers\Admin\AdminTokoController::class, 'show'])->name('toko.show');

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
        Route::get('/', [\App\Http\Controllers\Admin\AdminPelangganController::class, 'index'])->name('index');
        Route::get('/{toko}', [\App\Http\Controllers\Admin\AdminPelangganController::class, 'show'])->name('show');
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

    // Dashboard - Semua role
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Notifikasi - Semua role
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/get', [NotificationController::class, 'getNotifications'])->name('notifications.get');

    // Profil - Semua role
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');

    // Penjualan (POS) - Kasir & Owner
    Route::resource('penjualan', PenjualanController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/penjualan/get-barang/{id}', [PenjualanController::class, 'getBarang'])->name('penjualan.getBarang');

    // Routes untuk Owner & Super Admin only
    Route::middleware('role:owner,super_admin')->group(function () {
        // Barang
        Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
        Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
        Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
        Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
        Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
        Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

        // Stock Management
        Route::prefix('stock')->name('stock.')->group(function () {
            // Barang Masuk
            Route::get('/in', [StockInController::class, 'index'])->name('in.index');
            Route::get('/in/create', [StockInController::class, 'create'])->name('in.create');
            Route::post('/in', [StockInController::class, 'store'])->name('in.store');
            Route::get('/in/{stockIn}', [StockInController::class, 'show'])->name('in.show');

            // Barang Keluar
            Route::get('/out', [StockOutController::class, 'index'])->name('out.index');
            Route::get('/out/create', [StockOutController::class, 'create'])->name('out.create');
            Route::post('/out', [StockOutController::class, 'store'])->name('out.store');
            Route::get('/out/available-stock/{barangId}', [StockOutController::class, 'getAvailableStock'])->name('out.available');
            Route::get('/out/{stockOut}', [StockOutController::class, 'show'])->name('out.show');

            // Batch
            Route::get('/batch', [StockBatchController::class, 'index'])->name('batch.index');
            Route::get('/batch/barang/{barang}', [StockBatchController::class, 'showByBarang'])->name('batch.byBarang');
            Route::get('/batch/{batch}', [StockBatchController::class, 'show'])->name('batch.show');
        });

        // Laporan
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('index');
            Route::get('/stok', [LaporanController::class, 'stok'])->name('stok');
            Route::get('/penjualan', [LaporanController::class, 'penjualan'])->name('penjualan');
            Route::get('/barang-masuk', [LaporanController::class, 'barangMasuk'])->name('barang-masuk');
            Route::get('/barang-keluar', [LaporanController::class, 'barangKeluar'])->name('barang-keluar');
        });

        // Manajemen Kasir - Owner only
        Route::resource('staff', StaffController::class)->except(['show', 'edit', 'update']);
    });
});
