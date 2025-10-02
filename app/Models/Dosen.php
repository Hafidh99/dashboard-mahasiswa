<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Dosen extends Authenticatable
{
    use Notifiable;

    protected $guard = 'dosen';
    protected $table = 'dosen';
    protected $primaryKey = 'Login';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $hidden = ['Password',];
    public function getAuthPassword()
    {
        return $this->Password;
    }
}
