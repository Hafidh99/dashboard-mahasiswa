<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\KrsController; 
use App\Http\Controllers\KhsController;
use App\Http\Controllers\Auth\DosenLoginController;
use App\Http\Controllers\Dosen\PaController;
use App\Http\Controllers\Dosen\JadwalController;
use App\Http\Controllers\Dosen\LaporanController;
use App\Http\Controllers\Dosen\NilaiMahasiswaController;
use App\Http\Controllers\Dosen\PresensiController;
use App\Http\Controllers\Auth\KaryawanLoginController; 
use App\Http\Controllers\Karyawan\JadwalKaryawanController; 
use App\Http\Controllers\Karyawan\JadwalCetakController;

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

    Route::middleware('guest')->group(function () {
        Route::get('login', [DosenLoginController::class, 'create'])->name('login');
        Route::post('login', [DosenLoginController::class, 'store'])->name('login.store');
    });
    
    Route::middleware('auth:dosen')->group(function() {
        Route::get('dashboard', function() {return view('dosen.dashboard');})->name('dashboard');
        Route::get('pa/proses/{khsId}', [PaController::class, 'showKrsMahasiswa'])->name('pa.proses');
        Route::post('pa/proses/{khsId}', [PaController::class, 'processKrs'])->name('pa.process.store');
        Route::get('pa/list', [PaController::class, 'index'])->name('pa.list');
        Route::post('logout', [DosenLoginController::class, 'destroy'])->name('logout');
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/{jadwal}/presensi', [\App\Http\Controllers\Dosen\PresensiController::class, 'index'])->name('jadwal.presensi.index');
        Route::post('/jadwal/{jadwal}/presensi', [\App\Http\Controllers\Dosen\PresensiController::class, 'store'])->name('jadwal.presensi.store');
        Route::put('/presensi/{presensi}', [\App\Http\Controllers\Dosen\PresensiController::class, 'update'])->name('presensi.update');
        Route::delete('/presensi/{presensi}', [\App\Http\Controllers\Dosen\PresensiController::class, 'destroy'])->name('presensi.destroy');
        Route::get('/presensi/{presensi}/absen', [\App\Http\Controllers\Dosen\PresensiController::class, 'editAbsen'])->name('presensi.absen.edit');
        Route::post('/presensi/{presensi}/absen', [\App\Http\Controllers\Dosen\PresensiController::class, 'updateAbsen'])->name('presensi.absen.update');
        Route::get('/jadwal/{jadwal}/rekap-absen', [\App\Http\Controllers\Dosen\LaporanController::class, 'rekapAbsenMahasiswa'])->name('jadwal.rekap.absen');
        Route::get('/jadwal/{jadwal}/rekap-presensi-dosen', [\App\Http\Controllers\Dosen\LaporanController::class, 'rekapPresensiDosen'])->name('jadwal.rekap.presensi.dosen');
        Route::get('/jadwal/{jadwal}/cetak-nilai', [\App\Http\Controllers\Dosen\LaporanController::class, 'cetakNilai'])->name('jadwal.cetak.nilai');
        Route::get('/jadwal/{jadwal}/cetak-detail-nilai', [\App\Http\Controllers\Dosen\LaporanController::class, 'cetakDetailNilai'])->name('jadwal.cetak.detail_nilai');
        Route::get('/jadwal/{jadwal}/bobot-nilai', [\App\Http\Controllers\Dosen\BobotNilaiController::class, 'edit'])->name('jadwal.bobot.edit');
        Route::put('/jadwal/{jadwal}/bobot-nilai', [\App\Http\Controllers\Dosen\BobotNilaiController::class, 'update'])->name('jadwal.bobot.update');
        Route::put('/jadwal/{jadwal}/input-nilai', [\App\Http\Controllers\Dosen\NilaiMahasiswaController::class, 'update'])->name('jadwal.nilai.update');
        Route::get('/jadwal/{jadwal}/input-nilai', [\App\Http\Controllers\Dosen\NilaiMahasiswaController::class, 'edit'])->name('jadwal.nilai.edit');
    });
});

Route::prefix('karyawan')->name('karyawan.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [KaryawanLoginController::class, 'create'])->name('login');
        Route::post('login', [KaryawanLoginController::class, 'store'])->name('login.store');
    });
    
    Route::middleware('auth:karyawan')->group(function() {
        Route::get('dashboard', [JadwalKaryawanController::class, 'dashboard'])->name('dashboard');
        Route::get('jadwal', [JadwalKaryawanController::class, 'index'])->name('jadwal.index');
        Route::post('jadwal', [JadwalKaryawanController::class, 'storeJadwal'])->name('jadwal.store');
        Route::post('kelas', [JadwalKaryawanController::class, 'storeKelas'])->name('kelas.store');
        Route::put('jadwal/{id}', [JadwalKaryawanController::class, 'update'])->name('jadwal.update');
        Route::delete('jadwal/{id}', [JadwalKaryawanController::class, 'destroy'])->name('jadwal.destroy');
        Route::get('jadwal/{id}/json', [JadwalKaryawanController::class, 'getJadwalJson'])->name('jadwal.json');
        Route::get('jadwal/{id}/edit-dosen', [JadwalKaryawanController::class, 'editDosen'])->name('jadwal.editDosen');
        Route::post('jadwal/{id}/update-dosen', [JadwalKaryawanController::class, 'updateDosen'])->name('jadwal.updateDosen');
        Route::get('search/ruang', [JadwalKaryawanController::class, 'searchRuang'])->name('ruang.search');
        Route::get('search/matakuliah', [JadwalKaryawanController::class, 'searchMatakuliah'])->name('matakuliah.search');
        Route::get('search/dosen', [JadwalKaryawanController::class, 'searchDosen'])->name('dosen.search');
        Route::post('logout', [KaryawanLoginController::class, 'destroy'])->name('logout');
    });
    Route::prefix('cetak')->name('cetak.')->group(function() {
        Route::get('jadwal-keseluruhan', [JadwalCetakController::class, 'jadwalKeseluruhan'])->name('jadwalKeseluruhan');
        Route::get('frs', [JadwalCetakController::class, 'frs'])->name('frs');
        Route::get('jadwal-dosen', [JadwalCetakController::class, 'jadwalDosen'])->name('jadwalDosen');
        Route::get('jadwal-per-ruang', [JadwalCetakController::class, 'jadwalPerRuang'])->name('jadwalPerRuang');
        Route::get('daftar-hadir/{jadwal}', [JadwalCetakController::class, 'daftarHadir'])->name('daftarHadir');
        Route::get('kursi-uas/{jadwal}', [JadwalCetakController::class, 'kursiUAS'])->name('kursiUAS');
    });
});

require __DIR__.'/auth.php';
