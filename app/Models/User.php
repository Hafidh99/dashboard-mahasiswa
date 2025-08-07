<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Menghubungkan model ke tabel 'mhsw'.
     */
    protected $table = 'mhsw';

    /**
     * Menentukan primary key tabel.
     */
    protected $primaryKey = 'MhswID';

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
     * Kolom yang bisa diisi secara massal (opsional untuk login).
     */
    protected $fillable = [
        'Nama',
        'Password',
        'TempatLahir',
        'TanggalLahir',
        'Agama',
        'Handphone',
        'Email',
        'Alamat',
        'NamaAyah',
        'NamaIbu',
        'AlamatOrtu',
        'PendidikanAyah',
        'PendidikanIbu',
        'PekerjaanAyah',
        'PekerjaanIbu',
        'HandphoneOrtu',
    ];

    /**
     * Kolom yang disembunyikan saat di-serialize ke JSON.
     */
    protected $hidden = [
        'Password',
    ];

    /**
     * Relasi ke tabel prodi
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'ProdiID', 'ProdiID');
    }

    /**
     * Relasi ke tabel khs
     */
    public function khs()
    {
        return $this->hasMany(Khs::class, 'MhswID', 'MhswID');
    }

    /**
     * Relasi ke tabel dosen
     */
    public function pembimbingAkademik()
    {
        return $this->belongsTo(Dosen::class, 'PenasehatAkademik', 'Login');
    }

    /**
     * Memberitahu Laravel nama kolom untuk password.
     */
    public function getAuthPassword()
    {
        return $this->Password;
    }
    
}