<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnggotaControler;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\transaksiController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileAnggotaController;



Route::get('/', [BerandaController::class, 'index'])->name('beranda');

Route::middleware('auth')->group(function () {
    Route::get('/simpanan', [SimpananController::class, 'index'])->name('simpanan');
    Route::get('/pinjaman', [PinjamanController::class, 'index'])->name('pinjaman');
    Route::get('/transaksi', [transaksiController::class, 'index'])->name('transaksi');
});

Route::middleware(['auth'])->group(function () {

    // Pinjaman Routes
    Route::prefix('pinjaman')->name('pinjaman.')->group(function () {

        // Dashboard pinjaman (list semua pinjaman user)
        Route::get('/', [PinjamanController::class, 'index'])->name('index');

        // Form pengajuan pinjaman baru
        Route::get('/create', [PinjamanController::class, 'create'])->name('create');

        // Store pengajuan pinjaman baru
        Route::post('/store', [PinjamanController::class, 'store'])->name('store');

        // Detail pinjaman
        Route::get('/{id}', [PinjamanController::class, 'show'])->name('show');


        // AJAX: Calculate angsuran untuk preview
        Route::post('/calculate-angsuran', [PinjamanController::class, 'calculateAngsuran'])->name('calculate');

    });
});

        Route::get('/pinjaman/elektronik/create', [PinjamanController::class, 'createElektronik'])
        ->name('pinjaman.elektronik.create');

        Route::post('/pinjaman/elektronik/store', [PinjamanController::class, 'storeElektronik'])
        ->name('pinjaman.store-elektronik');


Route::prefix('simpanan')->name('simpanan.')->group(function () {
    Route::get('/dashboard', [SimpananController::class, 'index'])->name('dashboard');
    Route::get('/penarikan', [SimpananController::class, 'formPenarikan'])->name('penarikan');
    Route::post('/penarikan', [SimpananController::class, 'submitPenarikan'])->name('penarikan.submit');
    Route::get('/detail/{id}', [SimpananController::class, 'detail'])->name('detail');
});

Route::middleware(['auth', 'role:anggota'])->prefix('anggota')->name('anggota.')->group(function () {

    // Transaksi Routes
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/', [transaksiController::class, 'index'])->name('index');
        Route::get('/export', [transaksiController::class, 'export'])->name('export');
        Route::get('/{id}', [transaksiController::class, 'show'])->name('show');
    });
});



Route::middleware(['auth'])->group(function () {

    // Profile Routes
    Route::middleware(['auth'])->group(function () {
        // Profile routes
        Route::get('/profile', [ProfileAnggotaController::class, 'index'])->name('profile.index');
        Route::get('/profile/show', [ProfileAnggotaController::class, 'show'])->name('profile.show');
        Route::get('/profile/create', [ProfileAnggotaController::class, 'create'])->name('profile.create');
        Route::post('/profile/store', [ProfileAnggotaController::class, 'store'])->name('profile.store');
        Route::get('/profile/edit', [ProfileAnggotaController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileAnggotaController::class, 'update'])->name('profile.update');
    });

    // Anggota Routes (redirect ke profile)
    Route::prefix('anggota')->name('anggota.')->group(function () {
        Route::get('/', [ProfileAnggotaController::class, 'index'])->name('index');
        Route::get('/profile', [ProfileAnggotaController::class, 'show'])->name('profile');
    });
});




// GET untuk menampilkan form registrasi
Route::get('/register', function () {
    return view('auth.register');
})->name('register.form');

// POST untuk memproses registrasi
Route::post('/register', [AuthController::class, 'register'])->name('register');

// GET untuk menampilkan form login
Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');

// POST untuk memproses login
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Route yang dilindungi
Route::group(['middleware' => ['auth', 'check_role:anggota']], function () {
    Route::get('/anggota', [BerandaController::class, 'index']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        return redirect()->route('login');
    })->name('filament.admin.auth.login');
});

// Password Reset Routes
Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])
    ->name('password.forgot');

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetCode'])
    ->name('password.email');

Route::get('/verify-code', [PasswordResetController::class, 'showVerifyForm'])
    ->name('password.verify');

Route::post('/verify-code', [PasswordResetController::class, 'verifyCode'])
    ->name('password.verify.submit');

Route::get('/reset-password', [PasswordResetController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
    ->name('password.update');

Route::post('/resend-code', [PasswordResetController::class, 'resendCode'])
    ->name('password.resend');
