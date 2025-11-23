<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangRusakController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\PenitipanController;
use App\Http\Controllers\PenitipanDetailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TransaksiDetailController;
use App\Http\Controllers\TransaksiDetailPenitipanController;
use Illuminate\Support\Facades\Route;

// Halaman welcome
Route::get('/', function () {
    return redirect()->route('login');
});

// =======================================
// Rute Kasir (karyawan / admin)
// =======================================
Route::middleware(['auth', 'role:karyawan|admin'])->group(function () {
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir');
    Route::post('/kasir/store', [KasirController::class, 'store'])->name('kasir.store');
    Route::get('/kasir/check-stok/{id_barang}', [KasirController::class, 'checkStok'])->name('kasir.check-stok');
    Route::get('/kasir/print/{id_transaksi}', [KasirController::class, 'printStruk'])->name('kasir.print');
    Route::get('/kasir/today', [KasirController::class, 'todayTransactions'])->name('kasir.today');
});

// =======================================
// Dashboard & Analytics (semua user login)
// =======================================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard/kpi', [DashboardController::class, 'kpiData'])->name('dashboard.kpi');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])->name('dashboard.chart-data');
    Route::get('/dashboard/payment-method', [DashboardController::class, 'paymentMethodData'])->name('dashboard.payment-method');
    Route::get('/dashboard/cashier-performance', [DashboardController::class, 'cashierPerformance'])->name('dashboard.cashier-performance');
    Route::get('/dashboard/category-analytics', [DashboardController::class, 'categoryAnalytics'])->name('dashboard.category-analytics');
    Route::get('/dashboard/peak-hours', [DashboardController::class, 'peakHours'])->name('dashboard.peak-hours');
    Route::get('/dashboard/stok-alerts', [DashboardController::class, 'stokAlerts'])->name('dashboard.stok-alerts');
    Route::get('/dashboard/slow-moving', [DashboardController::class, 'slowMovingItems'])->name('dashboard.slow-moving');
    Route::get('/dashboard/stok-data', [DashboardController::class, 'stokChartData'])->name('dashboard.stok-data');
    Route::get('/dashboard/stok-detail/{id_barang}', [DashboardController::class, 'detailStok'])->name('dashboard.stok-detail');
    Route::get('/dashboard/export', [DashboardController::class, 'exportReport'])->name('dashboard.export');
});

// =======================================
// Admin only
// =======================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Profil admin
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
});

require __DIR__ . '/auth.php';
