<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'mhsw';

    protected $primaryKey = 'MhswID';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

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

    protected $hidden = [
        'Password',
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'ProdiID', 'ProdiID');
    }

    public function khs()
    {
        return $this->hasMany(Khs::class, 'MhswID', 'MhswID');
    }

    public function pembimbingAkademik()
    {
        return $this->belongsTo(Dosen::class, 'PenasehatAkademik', 'Login');
    }

    public function getAuthPassword()
    {
        return $this->Password;
    }
    
}