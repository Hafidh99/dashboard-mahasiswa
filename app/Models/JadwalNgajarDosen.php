<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalNgajarDosen extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jadwal'; 
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'JadwalID';
    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
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
