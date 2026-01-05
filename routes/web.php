<?php

use App\Http\Controllers\Admin\AdminPaketController;
use App\Http\Controllers\Admin\AdminPelangganController;
use App\Http\Controllers\Admin\AdminTokoController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;
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
use App\Http\Controllers\KategoriController;

// Landing page
Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Management Toko
    Route::get('/toko', [AdminTokoController::class, 'index'])->name('toko.index');
    Route::get('/toko/{toko}', [AdminTokoController::class, 'show'])->name('toko.show');

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
        Route::get('/', [AdminPelangganController::class, 'index'])->name('index');
        Route::get('/{toko}', [AdminPelangganController::class, 'show'])->name('show');
    });

    Route::prefix('kelola-paket')->name('kelola-paket.')->group(function () {
        Route::get('/', [AdminPaketController::class, 'index'])->name('index');
        Route::get('/{plan}/edit', [AdminPaketController::class, 'edit'])->name('edit');
        Route::put('/{plan}', [AdminPaketController::class, 'update'])->name('update');
        Route::patch('/{plan}/toggle', [AdminPaketController::class, 'toggleStatus'])->name('toggle');
    });

    // Profil Super Admin
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [ProfilController::class, 'adminIndex'])->name('index');
        Route::put('/update', [ProfilController::class, 'update'])->name('update');
        Route::put('/password', [ProfilController::class, 'updatePassword'])->name('password');
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
    // Profil (Update toko) - Owner
    Route::put('/profil/toko', [ProfilController::class, 'updateToko'])
        ->name('profil.toko.update');

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

        // Kategori Barang
        Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
        Route::post('/kategori', action: [KategoriController::class, 'store'])->name('kategori.store');
        Route::get('/kategori/{id}', [KategoriController::class, 'show'])->name('kategori.show');
        Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
        Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

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
            
            // Export Routes
            Route::get('/stok/export/excel', [LaporanController::class, 'exportStokExcel'])->name('stok.export.excel');
            Route::get('/stok/export/pdf', [LaporanController::class, 'exportStokPdf'])->name('stok.export.pdf');
            Route::get('/penjualan/export/excel', [LaporanController::class, 'exportPenjualanExcel'])->name('penjualan.export.excel');
            Route::get('/penjualan/export/pdf', [LaporanController::class, 'exportPenjualanPdf'])->name('penjualan.export.pdf');
            Route::get('/barang-masuk/export/excel', [LaporanController::class, 'exportBarangMasukExcel'])->name('barang-masuk.export.excel');
            Route::get('/barang-masuk/export/pdf', [LaporanController::class, 'exportBarangMasukPdf'])->name('barang-masuk.export.pdf');
            Route::get('/barang-keluar/export/excel', [LaporanController::class, 'exportBarangKeluarExcel'])->name('barang-keluar.export.excel');
            Route::get('/barang-keluar/export/pdf', [LaporanController::class, 'exportBarangKeluarPdf'])->name('barang-keluar.export.pdf');
        });

        // Manajemen Kasir - Owner only
        Route::resource('kasir', KasirController::class)->except(['show', 'edit', 'update']);
    });

    // Subscription Management
    Route::prefix('subscription')->name('subscription.')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('index');
        Route::get('/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('checkout');
        Route::get('/callback', [SubscriptionController::class, 'callback'])->name('callback');
        Route::get('/expired', [SubscriptionController::class, 'expired'])->name('expired');
    });
});

// Public pricing page
Route::get('/pricing', [SubscriptionController::class, 'plans'])->name('pricing');

// Midtrans Webhook (no auth, verified by Midtrans signature)
Route::post('/midtrans/webhook', [SubscriptionController::class, 'webhook'])->name('midtrans.webhook');
