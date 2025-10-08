<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = ['nombre','correo','password','rfc','rol'];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function cuentas()
    {
        return $this->hasMany(\App\Models\Cuenta::class, 'usuario_id');
    }
}
