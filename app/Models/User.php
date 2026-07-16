<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'posyandu_id', 'ibu_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    const ROLE_ADMIN = 'admin_desa';
    const ROLE_KADER = 'kader';
    const ROLE_ORTU  = 'orang_tua';

    public function isAdmin(): bool    { return $this->role === self::ROLE_ADMIN; }
    public function isKader(): bool    { return $this->role === self::ROLE_KADER; }
    public function isOrangTua(): bool { return $this->role === self::ROLE_ORTU; }

    public function posyandu() { return $this->belongsTo(Posyandu::class); }
    public function ibu()      { return $this->belongsTo(Ibu::class); }

    public function canAccessPanel(Panel $panel): bool
    {
        return match($panel->getId()) {
            'admin' => $this->isAdmin(),
            'kader' => $this->isKader(),
            default => false,
        };
    }
}
