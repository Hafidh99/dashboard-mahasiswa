<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\KrsController; 
use App\Http\Controllers\KhsController;

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
    Route::get('/cetak-transkrip-wisuda', [KhsController::class, 'cetakTranskripWisuda'])->name('cetak-transkrip-wisuda');
});



require __DIR__.'/auth.php';
