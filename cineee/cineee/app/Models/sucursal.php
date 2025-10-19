<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class sucursal extends Model
{
    Use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'direccion',
        'director',
        'telefono',
    ];

    /**
     * A sucursal has many salas.
     */
    public function salas(): HasMany
    {
        return $this->hasMany(sala::class);
    }
}
