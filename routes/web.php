<?php

use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KeuanganController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthControler;
use App\Http\Controllers\Pelanggan\BerandaController;
use App\Http\Controllers\Pelanggan\PeminjamanController as PelangganPeminjamanController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\Petugas\PeminjamanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BerandaController::class, 'index'])->name('index');
Route::get('/show/{alat}', [BerandaController::class, 'show'])->name('show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthControler::class, 'login'])->name('login');
    Route::post('/login', [AuthControler::class, 'login_proses'])->name('login.proses');
    Route::get('/register', [AuthControler::class, 'register'])->name('register');
    Route::post('/register', [AuthControler::class, 'register_proses'])->name('register.proses');
    // Alias untuk kompatibilitas jika ada view lama
    Route::get('/regsiter', [AuthControler::class, 'register'])->name('regsiter');
    Route::post('/regsiter', [AuthControler::class, 'register_proses'])->name('regsiter.proses');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthControler::class, 'logout'])->name('logout');
    Route::get('/peminjaman/petugas/all', [PeminjamanController::class, 'peminjaman'])->name('peminjaman.all');
    Route::get('/laporan/peminjaman/pdf', [PeminjamanController::class, 'exportPdf'])->name('peminjaman.laporan.pdf');
    Route::get('/laporan/peminjaman/excel', [PeminjamanController::class, 'exportExcel'])->name('peminjaman.laporan.excel');
    Route::get('/invoice/{peminjaman}', [PelangganPeminjamanController::class, 'invoice'])->name('invoice.peminjaman');
    Route::get('/invoice/{peminjaman}/all', [PelangganPeminjamanController::class, 'invoiceAll'])->name('invoice.all');
    Route::get('/invoice/{peminjaman}/pdf', [PelangganPeminjamanController::class, 'invoicePdf'])->name('invoice.pdf');
});

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/dashbord/admin', [DashboardController::class, 'index'])->name('admin.dashboard.index');
    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('admin.keuangan.index');
    Route::get('/keuangan/pdf', [KeuanganController::class, 'exportPdf'])->name('admin.keuangan.pdf');
    Route::get('/keuangan/excel', [KeuanganController::class, 'exportExcel'])->name('admin.keuangan.excel');
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    Route::get('/alat', [AlatController::class, 'index'])->name('alat.index');
    Route::post('/alat', [AlatController::class, 'store'])->name('alat.store');
    Route::put('/alat/{alat}', [AlatController::class, 'update'])->name('alat.update');
    Route::delete('/alat/{alat}', [AlatController::class, 'destroy'])->name('alat.destroy');
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/log', [LogController::class, 'index'])->name('index.log');
});

Route::middleware(['auth', 'role:Petugas'])->group(function () {
    Route::get('/dashbord/petugas', [PetugasDashboardController::class, 'index'])->name('petugas.dashboard.index');
    Route::get('/log/user', [LogController::class, 'user'])->name('user.log');
    Route::get('/peminjaman/petugas', [PeminjamanController::class, 'index'])->name('peminjaman');
    Route::match(['get', 'post'], '/peminjaman/approve/{peminjaman}', [PeminjamanController::class, 'diaprove'])->name('approve');
    Route::get('/peminjaman/batal/{peminjaman}', [PeminjamanController::class, 'dibatalkan'])->name('batal');
    Route::put('/peminjaman/pinjam/{peminjaman}', [PeminjamanController::class, 'dipinjam'])->name('pinjam');
    Route::get('/peminjaman/kembali/{peminjaman}', [PeminjamanController::class, 'dikembalikan'])->name('kembalikan');
});

Route::middleware(['auth', 'role:Pelanggan'])->group(function () {
    Route::post('/peminjaman/multi', [PelangganPeminjamanController::class, 'storeMulti'])->name('ajukan.peminjaman.multi');
    Route::post('/peminjaman/{alat}', [PelangganPeminjamanController::class, 'store'])->name('ajukan.peminjaman');
    Route::get('/riwayat', [PelangganPeminjamanController::class, 'index'])->name('riwayat.peminjaman');
    Route::get('/log/saya', [PelangganPeminjamanController::class, 'logSaya'])->name('pelanggan.log');
});
