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

    /**
     * Get all barangs for this toko.
     */
    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class);
    }

    /**
     * Get all kategoris for this toko.
     */
    public function kategoris(): HasMany
    {
        return $this->hasMany(KategoriBarang::class);
    }

    /**
     * Get all stock batches for this toko.
     */
    public function stockBatches(): HasMany
    {
        return $this->hasMany(StockBatch::class);
    }

    /**
     * Get all stock in records for this toko.
     */
    public function stockIn(): HasMany
    {
        return $this->hasMany(StockIn::class);
    }

    /**
     * Get all stock out records for this toko.
     */
    public function stockOut(): HasMany
    {
        return $this->hasMany(StockOut::class);
    }
}
