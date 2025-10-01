<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Karyawan extends Authenticatable
{
    use Notifiable;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'karyawan';

    /**
     * Primary key dari tabel.
     *
     * @var string
     */
    protected $primaryKey = 'Login';

    /**
     * Tipe data dari primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Menandakan apakah primary key auto-increment.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Menandakan apakah model memiliki timestamps (created_at, updated_at).
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'Nama',
        'Email',
        'Password', // Nama kolom password di database
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'Password',
    ];

    /**
     * Mengambil nama kolom password dari model ini.
     * Diperlukan karena nama kolom kita 'Password' bukan 'password'.
     */
    public function getAuthPassword()
    {
        return $this->Password;
    }

    /**
     * Mengambil nama identifier (pengenal unik) untuk otentikasi.
     * Kita tetap menggunakan 'Login' sebagai pengenal.
     */
    public function getAuthIdentifierName()
    {
        return 'Login';
    }

    /**
     * Mengambil nilai dari identifier otentikasi.
     * PENTING: Untuk session, kita akan mengembalikan null agar Laravel
     * tidak mencoba menyimpan string ke kolom user_id yang berupa integer.
     * Ini akan membuat karyawan bisa login, tetapi sesi mereka tidak akan
     * secara spesifik terikat ke user_id di tabel sessions.
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }
}

