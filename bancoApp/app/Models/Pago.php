<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'cuenta_id',
        'referencia',
        'tipo_servicio', // <-- snake_case
        'monto',
        'metodo_pago',   // <-- snake_case
        'fecha',
    ];

    public function cuenta()
    {
        return $this->belongsTo(\App\Models\Cuenta::class, 'cuenta_id');
    }
}
