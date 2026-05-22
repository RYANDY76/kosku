<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PemilikPublicController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\KosController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Halaman publik: pengguna dapat melihat home, mencari kos, membuka detail kos, profil pemilik, dan kontak.
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kos', [KosController::class, 'index'])->name('kos.index');
Route::get('/kos/{kos}', [KosController::class, 'show'])->name('kos.show');
Route::get('/profil-pemilik/{user}', [PemilikPublicController::class, 'show'])->name('pemilik.public.show');
Route::view('/kontak', 'kontak')->name('kontak');

// Route guest: hanya bisa dibuka sebelum login, berisi register dan login.
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    // Lupa password
    Route::get('/lupa-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/lupa-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Logout hanya untuk user yang sudah login.
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Route user login: profil, booking saya, dan pembayaran saya.
Route::middleware('auth')->group(function () {
    Route::get('/booking-saya', [DashboardController::class, 'myBookings'])->name('user.bookings');
    Route::get('/pembayaran-saya', [DashboardController::class, 'myPayments'])->name('user.payments');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Route dashboard utama: booking, pembayaran, review, serta kelola kos/kamar sesuai role.
Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::post('/booking/{kos}', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/booking/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    // User dapat membatalkan booking yang masih menunggu konfirmasi.
    Route::patch('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/payments/{payment}/upload', [PaymentController::class, 'upload'])->name('payments.upload');
    Route::patch('/payments/{payment}/status', [PaymentController::class, 'updateStatus'])->name('payments.updateStatus');
    Route::post('/review/{kos}', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/review/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Admin dan pemilik bisa mengelola kos dan kamar.
    Route::middleware('role:admin,pemilik')->group(function () {
        Route::resource('kelola-kos', KosController::class)->except(['index', 'show'])->parameters(['kelola-kos' => 'kos']);
        Route::resource('kelola-kos.kamar', KamarController::class)->except(['index', 'show'])->parameters(['kelola-kos' => 'kos']);
    });

    // Fitur khusus admin: fasilitas, pengguna, dan verifikasi kos.
    Route::middleware('role:admin')->group(function () {
        Route::resource('fasilitas', FasilitasController::class)->except(['show']);
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::patch('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('kelola-kos/{kos}/verifikasi', [KosController::class, 'verify'])->name('kelola-kos.verify');
    });
});

// Panel admin: admin memantau seluruh data sistem.
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/{section}/{page}', [DashboardController::class, 'adminPage'])->name('page');
    Route::get('/kos', fn () => redirect()->route('admin.page', ['section' => 'master-data', 'page' => 'properti']))->name('kos.index');
    Route::get('/users', fn () => redirect()->route('admin.page', ['section' => 'master-data', 'page' => 'penyewa']))->name('users.index');
    Route::get('/fasilitas', fn () => redirect()->route('admin.page', ['section' => 'master-data', 'page' => 'properti']))->name('fasilitas.index');
});

// Panel pemilik: pemilik mengelola kos, kamar, booking, dan pembayaran miliknya.
Route::middleware(['auth', 'role:pemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kos', fn () => redirect()->route('pemilik.page', ['section' => 'master-data', 'page' => 'properti']))->name('kos.index');
    Route::get('/kos/create', fn () => redirect()->route('dashboard.kelola-kos.create'))->name('kos.create');
    Route::get('/kamar', fn () => redirect()->route('pemilik.page', ['section' => 'master-data', 'page' => 'properti']))->name('kamar.index');
    Route::get('/transaksi/booking', fn () => redirect()->route('pemilik.page', ['section' => 'transaksi', 'page' => 'kontrak-sewa']))->name('bookings.index');
    Route::get('/transaksi/tagihan', fn () => redirect()->route('pemilik.page', ['section' => 'transaksi', 'page' => 'pembayaran']))->name('payments.index');
    Route::get('/{section}/{page}', [DashboardController::class, 'ownerPage'])->name('page');
});
