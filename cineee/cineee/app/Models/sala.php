<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class sala extends Model
{
    Use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'capacidad',
        'sucursal_id',
    ];

    /**
     * Sala belongs to a sucursal.
     */
    public function sucursal()
    {
        return $this->belongsTo(sucursal::class);
    }
}
