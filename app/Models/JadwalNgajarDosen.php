<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalNgajarDosen extends Model
{
    use HasFactory;

    protected $table = 'jadwal'; 
    protected $primaryKey = 'JadwalID';
    public $incrementing = true;
    public $timestamps = false; 
    protected $fillable = [
        'Presensi',
        'TugasMandiri',
        'Tugas1',
        'Tugas2',
        'Tugas3',
        'Tugas4',       
        'Tugas5',
        'UTS',
        'UAS',
        'Responsi',     
        'CatatanGagal', 
    ];
}
