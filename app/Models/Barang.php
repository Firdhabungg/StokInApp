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
        'stok_minimum',
        'low_stock_notified_at',
        'harga',
        'harga_jual',
        'tgl_kadaluwarsa',
        'status',
    ];

    protected $casts = [
        'harga'                 => 'decimal:2',
        'tgl_kadaluwarsa'       => 'date',
        'low_stock_notified_at' => 'datetime',
        'stok_minimum'          => 'integer',
    ];

    // ─── Helper Methods untuk Notifikasi ─────────────────────────

    /**
     * Apakah stok saat ini di bawah atau sama dengan ambang batas minimum?
     */
    public function isLowStock(): bool
    {
        return $this->stok > 0 && $this->stok <= $this->stok_minimum;
    }

    /**
     * Apakah stok sudah benar-benar habis?
     */
    public function isOutOfStock(): bool
    {
        return $this->stok <= 0;
    }

    /**
     * Apakah barang ini perlu dikirim notifikasi?
     * Cooldown 24 jam untuk mencegah spam.
     */
    public function needsNotification(): bool
    {
        if (is_null($this->low_stock_notified_at)) {
            return true;
        }

        return $this->low_stock_notified_at->lt(now()->subHours(24));
    }

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
}
