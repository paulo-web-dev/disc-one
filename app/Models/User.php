<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Atalho de papel — útil no middleware de admin (Etapa 6).
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isRespondent(): bool
    {
        return $this->role === 'respondent';
    }

    /**
     * Testes respondidos por este usuário.
     */
    public function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }

    /**
     * Compras (liberação de relatório) deste usuário.
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}
