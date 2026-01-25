<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_OWNER = 'owner';
    const ROLE_KASIR = 'kasir';

    protected $fillable = [
        'name',
        'email',
        'password',
        'toko_id',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }

    public function getEffectiveTokoAttribute(): ?Toko
    {
        // Jika Super Admin sedang dalam mode Akses Toko
        if ($this->isSuperAdmin() && session()->has('akses_toko_id')) {
            return Toko::find(session('akses_toko_id'));
        }

        return $this->toko;
    }

    public function getEffectiveTokoIdAttribute(): ?int
    {
        if ($this->isSuperAdmin() && session()->has('akses_toko_id')) {
            return session('akses_toko_id');
        }

        return $this->toko_id;
    }

    public function isAksesToko(): bool
    {
        return $this->isSuperAdmin() && session()->has('akses_toko_id');
    }

    public function getAksesTokoNameAttribute(): ?string
    {
        return session('akses_toko_name');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isOwner(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }

    public function isKasir(): bool
    {
        return $this->role === self::ROLE_KASIR;
    }

    public function hasRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    public function canAccessAdmin(): bool
    {
        return $this->isSuperAdmin();
    }

    public function canManageToko(): bool
    {
        return $this->isSuperAdmin() || $this->isOwner();
    }

    public function stockIn(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StockIn::class);
    }

    public function stockOut(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StockOut::class);
    }


    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_OWNER => 'Owner',
            self::ROLE_KASIR => 'Kasir',
            default => 'User',
        };
    }

    public function getTokoNameAttribute(): string
    {
        return $this->toko?->name ?? '';
    }
}
