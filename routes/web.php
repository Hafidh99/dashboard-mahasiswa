<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\KrsController; 
use App\Http\Controllers\KhsController;
use App\Http\Controllers\Auth\DosenLoginController;
use App\Http\Controllers\Dosen\PaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/set-password', [ProfileController::class, 'setPassword'])->name('profile.password.set');
    Route::patch('/profile/pa', [ProfileController::class, 'updatePa'])->name('profile.pa.update');
});

Route::get('/krs', function () {
    return redirect()->route('dashboard'); 
})->middleware(['auth', 'verified'])->name('krs.index');

Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->middleware('auth')->name('profile.photo.update');

Route::prefix('krs')->name('krs.')->group(function () {
    Route::get('/lihat', [KrsController::class, 'lihat'])->name('lihat');
    Route::get('/isi', [KrsController::class, 'isi'])->name('isi');
    Route::get('/cetak', [KrsController::class, 'cetak'])->name('cetak');
    Route::get('/ambil', [KrsController::class, 'ambil'])->name('ambil');
    Route::post('/simpan', [KrsController::class, 'simpan'])->name('simpan');
    Route::post('/hapus/{krsId}', [KrsController::class, 'hapus'])->name('hapus');
});

Route::prefix('khs')->name('khs.')->group(function () {
    Route::get('/cetak', [KhsController::class, 'cetak'])->name('cetak');
    Route::get('/transkrip', [KhsController::class, 'transkrip'])->name('transkrip');
    Route::get('/cetak-transkrip-wisuda', [KhsController::class, 'transkripWisuda'])->name('cetak-transkrip-wisuda');
});

Route::prefix('dosen')->name('dosen.')->group(function () {
    
    // TAMBAHKAN GRUP INI
    Route::middleware('guest')->group(function () {
        Route::get('login', [DosenLoginController::class, 'create'])->name('login');
        Route::post('login', [DosenLoginController::class, 'store'])->name('login.store');
    });

    // Grup ini untuk route yang hanya bisa diakses setelah login
    Route::middleware('auth:dosen')->group(function() {
        Route::get('dashboard', function() {
            return view('dosen.dashboard');
        })->name('dashboard');

        Route::get('pa/proses/{khsId}', [PaController::class, 'showKrsMahasiswa'])->name('pa.proses');
        Route::post('pa/proses/{khsId}', [PaController::class, 'processKrs'])->name('pa.process.store');
        Route::get('pa/list', [PaController::class, 'index'])->name('pa.list');
        Route::post('logout', [DosenLoginController::class, 'destroy'])->name('logout');
    });
});

require __DIR__.'/auth.php';
