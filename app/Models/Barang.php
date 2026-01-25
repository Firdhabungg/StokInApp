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

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id', 'kategori_id');
    }

    public function stockBatches(): HasMany
    {
        return $this->hasMany(StockBatch::class);
    }

    public function stockIn(): HasMany
    {
        return $this->hasMany(StockIn::class);
    }

    public function stockOut(): HasMany
    {
        return $this->hasMany(StockOut::class);
    }

    // hitung total stok dari stock batches
    public function getTotalStockAttribute(): int
    {
        return $this->stockBatches()->sum('jumlah_sisa');
    }
}
