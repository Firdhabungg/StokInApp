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

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(StockBatch::class, 'batch_id');
    }

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
