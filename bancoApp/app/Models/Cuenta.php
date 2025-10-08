<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $table = 'cuentas';

    protected $fillable = [
        'tipo',       // 'nomina' | 'credito'
        'usuario_id',  // FK -> usuarios.id
        'saldo',
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
