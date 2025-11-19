<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConcessionOrder extends Model
{
    protected $fillable = [
        'user_id',
        'items',
        'total',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    /**
     * Concession order belongs to a user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
