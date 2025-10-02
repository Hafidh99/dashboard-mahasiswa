<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Matakuliah;

class Jadwal extends Model
{
    use HasFactory;
    protected $table = 'jadwal';
    protected $primaryKey = 'JadwalID';
    public $timestamps = false;
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'ProdiID', 'ProdiID');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'NamaKelas', 'KelasID');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'DosenID', 'Login');
    }
    public function mk()
    {
        // Lebih bersih dan standar, tanpa full namespace
        return $this->belongsTo(Matakuliah::class, 'MKID', 'MKID');
    }
}
