<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';
    protected $primaryKey = 'PresensiID';
    public $timestamps = false;
    protected $fillable = [
        'Pertemuan',
        'Tanggal',
        'JamMulai',
        'JamSelesai',
        'Catatan',
        'LoginEdit',
        'TanggalEdit',
    ];
}
