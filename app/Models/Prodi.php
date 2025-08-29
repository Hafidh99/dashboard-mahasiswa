<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;
    
    protected $table = 'prodi';
    protected $primaryKey = 'ProdiID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
}