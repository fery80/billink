<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\RegisterControllersementara;

Route::get('/register/sementara', [RegisterControllersementara::class, 'showForm'])->name('register.form');
Route::post('/register/sementara', [RegisterControllersementara::class, 'register'])->name('register.submit');
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\LaporanController;

// Route login
// Route login
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard dengan middleware
Route::middleware(['web', 'auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/map', [MapController::class, 'index'])->name('map.show');
    Route::get('/paket', [PaketController::class, 'index'])->name('paket.index');
    Route::post('/paket', [PaketController::class, 'store'])->name('paket.store');
    Route::get('/paket/{id}/edit', [PaketController::class, 'edit'])->name('paket.edit');
    Route::put('/paket/{id}', [PaketController::class, 'update'])->name('paket.update');
    Route::delete('/paket/{id}', [PaketController::class, 'destroy'])->name('paket.destroy');

     Route::get('/admin/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::get('/admin/pelanggan/create', [PelangganController::class, 'create'])->name('pelanggan.create');
    Route::post('/admin/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
    Route::get('/admin/pelanggan/{id}/edit', [PelangganController::class, 'edit'])->name('pelanggan.edit');
    Route::put('/admin/pelanggan/{id}', [PelangganController::class, 'update'])->name('pelanggan.update');
    Route::delete('/admin/pelanggan/{id}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');
    Route::get('/admin/daftar_pelanggan', [PelangganController::class, 'daftar'])->name('admin.daftar_pelanggan');

Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::delete('/pembayaran/{id}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');

Route::get('/admin/laporan', [LaporanController::class, 'index'])
    ->name('laporan.index');
    
});

Route::middleware(['web', 'auth', 'role:teknisi'])->group(function () {
    Route::get('/teknisi/dashboard', [TeknisiController::class, 'index'])->name('teknisi.dashboard');
});

