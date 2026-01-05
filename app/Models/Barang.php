<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';

    protected $fillable = [
        'toko_id',
        'kategori_id',
        'nama_barang',
        'kode_barang',
        'stok',
        'harga',
        'harga_jual',
        'tgl_kadaluwarsa',
        'status',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'tgl_kadaluwarsa' => 'date',
    ];

    /**
     * Get the toko that owns the barang.
     */
    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }

    /**
     * Get the kategori of the barang.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id', 'kategori_id');
    }

    /**
     * Get all stock batches for this barang.
     */
    public function stockBatches(): HasMany
    {
        return $this->hasMany(StockBatch::class);
    }

    /**
     * Get all stock in records for this barang.
     */
    public function stockIn(): HasMany
    {
        return $this->hasMany(StockIn::class);
    }

    /**
     * Get all stock out records for this barang.
     */
    public function stockOut(): HasMany
    {
        return $this->hasMany(StockOut::class);
    }

    /**
     * Calculate total stock from all batches.
     */
    public function getTotalStockAttribute(): int
    {
        return $this->stockBatches()->sum('jumlah_sisa');
    }
}
