<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockBatch extends Model
{
    use HasFactory;

    protected $table = 'stock_batches';

    protected $fillable = [
        'barang_id',
        'toko_id',
        'batch_code',
        'jumlah_awal',
        'jumlah_sisa',
        'tgl_masuk',
        'tgl_kadaluarsa',
        'status',
    ];

    protected $casts = [
        'tgl_masuk' => 'date',
        'tgl_kadaluarsa' => 'date',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }

    public function stockIn(): HasMany
    {
        return $this->hasMany(StockIn::class, 'batch_id');
    }

    public function stockOut(): HasMany
    {
        return $this->hasMany(StockOut::class, 'batch_id');
    }

    public function isExpired(): bool
    {
        if (!$this->tgl_kadaluarsa) {
            return false;
        }
        return $this->tgl_kadaluarsa->isPast();
    }

    public function isNearExpiry(): bool
    {
        if (!$this->tgl_kadaluarsa) {
            return false;
        }
        return $this->tgl_kadaluarsa->diffInDays(now()) <= 7 && !$this->isExpired();
    }

    public function scopeAvailable($query)
    {
        return $query->where('jumlah_sisa', '>', 0)
            ->where('status', '!=', 'kadaluarsa')
            ->orderBy('tgl_masuk', 'asc');
    }
}
