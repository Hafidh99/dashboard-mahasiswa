<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Dosen extends Authenticatable
{
    use Notifiable;

    /**
     * Tentukan guard yang akan digunakan model ini.
     */
    protected $guard = 'dosen';

    /**
     * Menghubungkan model ke tabel 'dosen'.
     */
    protected $table = 'dosen';

    /**
     * Menentukan primary key tabel.
     */
    protected $primaryKey = 'Login';

    /**
     * Memberitahu Laravel bahwa primary key bukan auto-incrementing.
     */
    public $incrementing = false;

    /**
     * Memberitahu Laravel bahwa tipe primary key adalah string.
     */
    protected $keyType = 'string';

    /**
     * Menonaktifkan timestamps (created_at dan updated_at).
     */
    public $timestamps = false;

    /**
     * Kolom yang disembunyikan saat di-serialize ke JSON.
     */
    protected $hidden = [
        'Password',
    ];

    /**
     * Memberitahu Laravel nama kolom untuk password.
     */
    public function getAuthPassword()
    {
        return $this->Password;
    }
}
