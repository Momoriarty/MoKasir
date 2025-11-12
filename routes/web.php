<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view(view: 'dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('/home', HomeController::class);
Route::get('/signin', action: [AuthController::class, 'showLogin'])->name('signin');
Route::get('/signup', action: [AuthController::class, 'showRegister'])->name('signup');
Route::post('/signupProses', [AuthController::class, 'register'])->name('register.post');
Route::get('/go/{id}', action: [HomeController::class, 'RedirectTo'])->name('go');
Route::post('/home/store', [HomeController::class, 'store'])->name('home.signup');
Route::post('/process', [AuthController::class, 'process'])->name('login.process');

Route::resource('/admin', AdminController::class);
Route::resource('/karyawan', KaryawanController::class);

// Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

require __DIR__ . '/auth.php';
