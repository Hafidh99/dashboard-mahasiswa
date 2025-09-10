<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'presensi';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'PresensiID';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
