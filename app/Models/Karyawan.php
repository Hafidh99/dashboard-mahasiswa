<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Karyawan extends Authenticatable
{
    use Notifiable;
    protected $table = 'karyawan';

    protected $primaryKey = 'Login';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'Nama',
        'Email',
        'Password', 
    ];    
    protected $hidden = [
        'Password',
    ];

    public function getAuthPassword()
    {
        return $this->Password;
    }

    public function getAuthIdentifierName()
    {
        return 'Login';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }
}

