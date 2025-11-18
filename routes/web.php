<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangRusakController;
use App\Http\Controllers\PenitipanController;
use App\Http\Controllers\PenitipanDetailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TransaksiDetailController;
use App\Http\Controllers\TransaksiDetailPenitipanController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view(view: 'dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// Barang
    Route::resource('/barang', BarangController::class);
    Route::resource('/barangMasuk', BarangMasukController::class);
    Route::resource('/barangRusak', BarangRusakController::class);

// Penitipan
    Route::resource('/penitipan', PenitipanController::class);
    Route::resource('/penitipanDetail', PenitipanDetailController::class);

// Transaksi
    Route::resource('/transaksi', TransaksiController::class);
    Route::resource('/transaksiDetail', TransaksiDetailController::class);
    Route::resource('/transaksiDetailPenitipan', TransaksiDetailPenitipanController::class);

//Barang Masuk & Rusak
    Route::resource('barang-masuks', App\Http\Controllers\BarangMasukController::class);
    Route::resource('barang-rusaks', App\Http\Controllers\BarangRusakController::class);
});

require __DIR__ . '/auth.php';
