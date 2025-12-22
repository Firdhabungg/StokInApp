<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

        Route::get('/{id}', function ($id) {
            return view('admin.kelola-paket', ['id' => $id]);
        })->name('show');
        
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

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');
    Route::get('/barang', function () {
        return view('barang.index');
    })->name('barang.index');
    Route::get('/penjualan', function () {
        return view('penjualan.index');
    })->name('penjualan.index');

    // Owner only routes
    Route::middleware('owner')->group(function () {
        Route::resource('staff', StaffController::class)->except(['show', 'edit', 'update']);
    });
});
