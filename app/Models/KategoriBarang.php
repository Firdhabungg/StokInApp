<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriBarang extends Model
{
    use HasFactory;

    protected $table = 'kategoris';
    protected $primaryKey = 'kategori_id';

    protected $fillable = [
        'toko_id',
        'nama_kategori',
        'deskripsi_kategori',
    ];

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }

    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class, 'kategori_id', 'kategori_id');
    }
} 
