<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOut extends Model
{
    use HasFactory;

    protected $table = 'stock_out';

    protected $fillable = [
        'barang_id',
        'batch_id',
        'toko_id',
        'user_id',
        'jumlah',
        'tgl_keluar',
        'alasan',
        'keterangan',
    ];

    protected $casts = [
        'tgl_keluar' => 'date',
    ];

    /**
     * Get the barang for this stock out record.
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    /**
     * Get the batch for this stock out record.
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(StockBatch::class, 'batch_id');
    }

    /**
     * Get the toko for this stock out record.
     */
    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }

    /**
     * Get the user who created this stock out record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the alasan label.
     */
    public function getAlasanLabelAttribute(): string
    {
        return match($this->alasan) {
            'penjualan' => 'Penjualan',
            'rusak' => 'Rusak',
            'kadaluarsa' => 'Kadaluarsa',
            'retur' => 'Retur',
            'lainnya' => 'Lainnya',
            default => $this->alasan,
        };
    }
}
