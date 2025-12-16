<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Toko extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'address',
        'phone',
    ];

    /**
     * Get all users belonging to this toko.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the owner of the toko.
     */
    public function owner()
    {
        return $this->hasOne(User::class)->where('role', 'owner');
    }
}
