<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Manajemen Pelanggan
        |--------------------------------------------------------------------------
        */
        Route::prefix('pelanggan')
            ->name('pelanggan.')
            ->group(function () {

                Route::get('/', function () {
                    return view('admin.pelanggan.index');
                })->name('index');

                Route::get('/{id}', function ($id) {
                    return view('admin.pelanggan.show', ['id' => $id]);
                })->name('show');
            });

        /*
        |--------------------------------------------------------------------------
        | Kelola Paket
        |--------------------------------------------------------------------------
        */
        Route::prefix('kelola-paket')
            ->name('kelola-paket.')
            ->group(function () {

                Route::get('/', function () {
                    return view('admin.kelola-paket.index');
                })->name('index');

                Route::get('/{id}', function ($id) {
                    return view('admin.kelola-paket.show', ['id' => $id]);
                })->name('show');
            });

        /*
        |--------------------------------------------------------------------------
        | Billing & Invoice (KEUANGAN) ✅
        |--------------------------------------------------------------------------
        */
        Route::prefix('keuangan')
            ->name('keuangan.')
            ->group(function () {

                // Halaman utama Billing & Invoice
                Route::get('/', function () {
                    return view('admin.keuangan.index');
                })->name('index');

                // Detail Invoice
                Route::get('/invoice/{id}', function ($id) {
                    return view('admin.keuangan.show', ['id' => $id]);
                })->name('show');
            });
    });

/*
|--------------------------------------------------------------------------
| GUEST ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/barang', function () {
        return view('barang.index');
    })->name('barang.index');

    Route::get('/penjualan', function () {
        return view('penjualan.index');
    })->name('penjualan.index');

    /*
    |--------------------------------------------------------------------------
    | OWNER ONLY
    |--------------------------------------------------------------------------
    */
    Route::middleware('owner')->group(function () {
        Route::resource('staff', StaffController::class)
            ->except(['show', 'edit', 'update']);
    });
});
