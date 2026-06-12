<?php

use App\Http\Controllers\{SubscriptionController, ProfilController, StockInController, StockOutController, StockBatchController, PenjualanController, LaporanController, NotificationController, LaporanExportController, DashboardController as UserDashboardController, ForgotPasswordController};
use App\Http\Controllers\Admin\{AdminPaketController, AdminPelangganController, AdminTokoController, KeuanganController, DashboardController as AdminDashboardController, PengaturanController};
use App\Http\Controllers\Admin\AksesTokoController;
use App\Http\Controllers\Auth\{AuthController, RegisterController};
use App\Livewire\BarangForm;
use App\Livewire\Barangs;
use App\Livewire\Kasir;
use App\Livewire\KasirForm;
use App\Livewire\KategoriDetail;
use App\Livewire\Kategoris;
use App\Livewire\Laporan\LaporanBarangKeluar;
use App\Livewire\Laporan\LaporanBarangMasuk;
use App\Livewire\Laporan\LaporanPenjualan;
use App\Livewire\Laporan\Laporans;
use App\Livewire\Laporan\LaporanStok;
use App\Livewire\StockInForm;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    return view('welcome');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    // Jika sudah terverifikasi sebelumnya, langsung redirect
    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->route('subscription.index')->with('info', 'Email Anda sudah terverifikasi.');
    }

    // Jalankan verifikasi dan simpan ke database
    $request->fulfill();

    // Refresh user di session agar middleware 'verified' mengenali status terbaru
    Auth::setUser($request->user()->fresh());

    return redirect()->route('subscription.index')->with('success', 'Email berhasil diverifikasi! Silakan pilih paket langganan.');
})->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});
// Admin routes
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Management Toko
    Route::get('/toko', [AdminTokoController::class, 'index'])->name('toko.index');
    Route::get('/toko/{toko}', [AdminTokoController::class, 'show'])->name('toko.show');

    // Akses Toko (Super Admin masuk sebagai toko)
    // Route stop harus di atas agar tidak tertangkap oleh {toko}
    Route::post('/akses-toko/stop', [AksesTokoController::class, 'stop'])->name('akses-toko.stop');
    Route::post('/akses-toko/{toko}', [AksesTokoController::class, 'start'])->name('akses-toko.start');

    Route::prefix('keuangan')->name('keuangan.')->group(function () {
        Route::get('/', [KeuanganController::class, 'index'])->name('index');
        Route::get('/billing', [KeuanganController::class, 'billing'])->name('billing');
        Route::get('/invoice/{payment}', [KeuanganController::class, 'show'])->name('show');
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

    // Notifikasi Super Admin
    Route::get('/notifications', [NotificationController::class, 'adminIndex'])->name('notifications.index');
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard - Semua role
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Notifikasi - Semua role
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/get', [NotificationController::class, 'getNotifications'])->name('notifications.get');

    // Profil - Semua role
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password.update');
    // Profil (Update toko) - Owner
    Route::put('/profil/toko', [ProfilController::class, 'updateToko'])
        ->name('profil.toko.update');

    // Penjualan (POS) - Kasir & Owner
    Route::resource('penjualan', PenjualanController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/penjualan/get-barang/{id}', [PenjualanController::class, 'getBarang'])->name('penjualan.getBarang');

    // Data Barang - Read-only untuk semua (termasuk kasir)
    Route::get('/barang', Barangs::class)->name('barang.index');

    // Routes untuk Owner & Super Admin only
    Route::middleware('role:owner,super_admin')->group(function () {
        Route::get('/barang/create', BarangForm::class)->name('barang.create');
        Route::get('/barang/{barangId}/edit', BarangForm::class)->name('barang.edit');

        Route::get('/kategori', Kategoris::class)->name('kategori.index');
        Route::get('/kategori/{kategori}', KategoriDetail::class)->name('kategori.detail');

        // Stock Management
        Route::prefix('stock')->name('stock.')->group(function () {
            Route::get('/in', [StockInController::class, 'index'])->name('in.index');
            Route::get('/stock/in/create', StockInForm::class)->name('in.create');
            Route::get('/in/{stockIn}', [StockInController::class, 'show'])->name('in.show');

            Route::get('/out', [StockOutController::class, 'index'])->name('out.index');
            Route::get('/out/create', [StockOutController::class, 'create'])->name('out.create');
            Route::post('/out', [StockOutController::class, 'store'])->name('out.store');
            Route::get('/out/available-stock/{barangId}', [StockOutController::class, 'getAvailableStock'])->name('out.available');
            Route::get('/out/{stockOut}', [StockOutController::class, 'show'])->name('out.show');

            Route::get('/batch', [StockBatchController::class, 'index'])->name('batch.index');
            Route::get('/batch/barang/{barang}', [StockBatchController::class, 'showByBarang'])->name('batch.byBarang');
            Route::get('/batch/{batch}', [StockBatchController::class, 'show'])->name('batch.show');
        });

        // Laporan
        Route::middleware(['auth'])->prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', Laporans::class)->name('index');
            Route::get('/stok', LaporanStok::class)->name('stok');
            Route::get('/penjualan', LaporanPenjualan::class)->name('penjualan');
            Route::get('/barang-masuk', LaporanBarangMasuk::class)->name('barang-masuk');
            Route::get('/barang-keluar', LaporanBarangKeluar::class)->name('barang-keluar');

            Route::prefix('stok/export')->name('stok.export.')->group(function () {
                Route::get('excel', [LaporanExportController::class, 'stokExcel'])->name('excel');
                Route::get('pdf',   [LaporanExportController::class, 'stokPdf'])->name('pdf');
            });

            Route::prefix('penjualan/export')->name('penjualan.export.')->group(function () {
                Route::get('excel', [LaporanExportController::class, 'penjualanExcel'])->name('excel');
                Route::get('pdf',   [LaporanExportController::class, 'penjualanPdf'])->name('pdf');
            });

            Route::prefix('barang-masuk/export')->name('barang-masuk.export.')->group(function () {
                Route::get('excel', [LaporanExportController::class, 'barangMasukExcel'])->name('excel');
                Route::get('pdf',   [LaporanExportController::class, 'barangMasukPdf'])->name('pdf');
            });

            Route::prefix('barang-keluar/export')->name('barang-keluar.export.')->group(function () {
                Route::get('excel', [LaporanExportController::class, 'barangKeluarExcel'])->name('excel');
                Route::get('pdf',   [LaporanExportController::class, 'barangKeluarPdf'])->name('pdf');
            });
        });

        // Manajemen Kasir - Owner only
        Route::get('/kasir', Kasir::class)->name('kasir.index');
        Route::get('/kasir/create', KasirForm::class)->name('kasir.create');
    });

    // Subscription Management - Owner only (except expired page)
    Route::prefix('subscription')->name('subscription.')->group(function () {
        Route::get('/expired', [SubscriptionController::class, 'expired'])->name('expired');
    });

    Route::prefix('subscription')->name('subscription.')->middleware('role:owner,super_admin')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('index');
        Route::get('/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('checkout');
        Route::get('/callback', [SubscriptionController::class, 'callback'])->name('callback');
    });
});

Route::get('/pricing', [SubscriptionController::class, 'plans'])->name('pricing');

// Midtrans Webhook (no auth, verified by Midtrans signature)
Route::post('/midtrans/webhook', [SubscriptionController::class, 'webhook'])->name('midtrans.webhook');
