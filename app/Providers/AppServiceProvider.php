<?php

namespace App\Providers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Membuat driver hashing kustom bernama 'mysql_legacy'
        Hash::extend('mysql_legacy', function ($app) {
            return new class {
                public function make($value, array $options = [])
                {
                    // Logika untuk membuat hash yang dipotong (jika diperlukan)
                    $fullHash = '*' . strtoupper(sha1(sha1($value, true)));
                    return substr($fullHash, 0, 10);
                }

                public function check($value, $hashedValue, array $options = [])
                {
                    if (empty($value) || empty($hashedValue)) {
                        return false;
                    }
                    
                    // 1. Hitung hash versi LENGKAP seperti biasa
                    $fullCalculatedHash = '*' . strtoupper(sha1(sha1($value, true)));

                    // 2. POTONG hash hasil kalkulasi menjadi 10 karakter
                    $truncatedCalculatedHash = substr($fullCalculatedHash, 0, 10);

                    // 3. Bandingkan hash dari DB (yang sudah 10 karakter) 
                    //    dengan hash kalkulasi (yang juga sudah kita potong 10 karakter)
                    return hash_equals($hashedValue, $truncatedCalculatedHash);
                }

                public function needsRehash($hashedValue, array $options = [])
                {
                    // Tidak perlu rehash untuk metode lama ini, 
                    // kecuali Anda menerapkan strategi upgrade.
                    // Saya juga memperbaiki typo 'falase' menjadi 'false'.
                    return false;
                }
            };
        });
    }
}