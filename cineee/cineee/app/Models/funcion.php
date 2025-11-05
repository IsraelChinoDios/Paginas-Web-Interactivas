<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class funcion extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fecha',
        'pelicula_id',
        'sala_id',
        'tipo',
        'costo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha' => 'datetime',
        'costo' => 'decimal:2',
    ];

    /**
     * Funcion belongs to a pelicula.
     */
    public function pelicula(): BelongsTo
    {
        return $this->belongsTo(pelicula::class);
    }

    /**
     * Funcion belongs to a sala.
     */
    public function sala(): BelongsTo
    {
        return $this->belongsTo(sala::class);
    }
}
