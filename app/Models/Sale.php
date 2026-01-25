<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'toko_id',
        'user_id',
        'kode_transaksi',
        'tanggal',
        'total',
        'uang_dibayar',
        'kembalian',
        'status',
        'keterangan',
        'metode_pembayaran',
        'bukti_pembayaran',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'total' => 'decimal:2',
        'uang_dibayar' => 'decimal:2',
        'kembalian' => 'decimal:2',
    ];

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public static function generateKodeTransaksi(int $tokoId): string
    {
        $date = now()->format('Ymd');
        $prefix = "POS-{$date}-";

        $lastSale = self::where('toko_id', $tokoId)
            ->where('kode_transaksi', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSale) {
            $lastNumber = (int) substr($lastSale->kode_transaksi, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function calculateTotal(): void
    {
        $this->total = $this->items()->sum('subtotal');
        $this->save();
    }
}
