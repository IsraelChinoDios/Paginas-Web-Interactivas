<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'funcion_id',
        'cantidad',
        'total',
    ];

    /**
     * TicketPurchase belongs to a funcion.
     */
    public function funcion(): BelongsTo
    {
        return $this->belongsTo(funcion::class);
    }

    /**
     * TicketPurchase belongs to a user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
