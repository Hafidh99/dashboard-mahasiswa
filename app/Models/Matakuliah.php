<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    use HasFactory;

    protected $table = 'mk'; 
    protected $primaryKey = 'MKID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
}