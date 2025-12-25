<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockIn extends Model
{
    use HasFactory;

    protected $table = 'stock_in';

    protected $fillable = [
        'barang_id',
        'batch_id',
        'toko_id',
        'user_id',
        'jumlah',
        'tgl_masuk',
        'tgl_kadaluarsa',
        'supplier',
        'harga_beli',
        'keterangan',
    ];

    protected $casts = [
        'tgl_masuk' => 'date',
        'tgl_kadaluarsa' => 'date',
        'harga_beli' => 'decimal:2',
    ];

    /**
     * Get the barang for this stock in record.
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    /**
     * Get the batch for this stock in record.
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(StockBatch::class, 'batch_id');
    }

    /**
     * Get the toko for this stock in record.
     */
    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }

    /**
     * Get the user who created this stock in record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
