<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;
    
    // Memberitahu Laravel nama tabel yang benar
    protected $table = 'prodi';
    
    // Memberitahu Laravel primary key yang benar
    protected $primaryKey = 'ProdiID';
    
    // Memberitahu Laravel bahwa primary key bukan angka (string)
    public $incrementing = false;
    protected $keyType = 'string';

    // Menonaktifkan timestamps (created_at, updated_at)
    public $timestamps = false;
}