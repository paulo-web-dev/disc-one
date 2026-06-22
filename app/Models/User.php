<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'referral_code',
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

    public function isConsultant(): bool
    {
        return $this->role === 'consultant';
    }

    /** Testes trazidos por este consultor (via link de referral). */
    public function referredTests(): HasMany
    {
        return $this->hasMany(Test::class, 'consultant_id');
    }

    /** Link de referral pronto para compartilhar. */
    public function referralUrl(): string
    {
        return url('/r/'.$this->referral_code);
    }

    /** Gera um código de referral único. */
    public static function generateReferralCode(): string
    {
        do {
            $code = Str::lower(Str::random(8));
        } while (static::where('referral_code', $code)->exists());

        return $code;
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
