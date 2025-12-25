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

    /**
     * Available roles
     */
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_OWNER = 'owner';
    const ROLE_KASIR = 'kasir';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'toko_id',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the toko that the user belongs to.
     */
    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }

    /**
     * Check if user is super admin (developer/app owner).
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if user is owner of toko.
     */
    public function isOwner(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }

    /**
     * Check if user is kasir.
     */
    public function isKasir(): bool
    {
        return $this->role === self::ROLE_KASIR;
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Check if user can access admin features.
     */
    public function canAccessAdmin(): bool
    {
        return $this->isSuperAdmin();
    }

    /**
     * Check if user can manage toko (owner or super admin).
     */
    public function canManageToko(): bool
    {
        return $this->isSuperAdmin() || $this->isOwner();
    }

    /**
     * Get all stock in records created by this user.
     */
    public function stockIn(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StockIn::class);
    }

    /**
     * Get all stock out records created by this user.
     */
    public function stockOut(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StockOut::class);
    }
}
