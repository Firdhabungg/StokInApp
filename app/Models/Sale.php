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
        'tanggal' => 'date',
        'total' => 'decimal:2',
        'uang_dibayar' => 'decimal:2',
        'kembalian' => 'decimal:2',
    ];

    /**
     * Get the toko that owns the sale.
     */
    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }

    /**
     * Get the user (kasir) who made the sale.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all items in this sale.
     */
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Generate unique transaction code.
     */
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

    /**
     * Calculate and update total from items.
     */
    public function calculateTotal(): void
    {
        $this->total = $this->items()->sum('subtotal');
        $this->save();
    }
}
