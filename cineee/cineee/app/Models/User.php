<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    public function initials(): string
    {
        $name = trim((string) $this->name);
        if ($name === '') {
            return '';
        }

        $parts = Str::of($name)->replaceMatches('/\s+/', ' ')->trim()->explode(' ');
        $letters = $parts->filter()->map(fn ($p) => Str::upper(Str::substr($p, 0, 1)));
        return $letters->take(2)->implode('');
    }
}
