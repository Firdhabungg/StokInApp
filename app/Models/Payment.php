<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'order_id',
        'amount',
        'status',
        'midtrans_response',
        'paid_at',
    ];

    protected $casts = [
        'midtrans_response' => 'array',
        'paid_at' => 'datetime',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function isSuccess()
    {
        return $this->status === 'success';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getInvoiceNumberAttribute()
    {
        return 'INV-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    // Cek apakah pembayaran sudah jatuh tempo (pending lebih dari 3 hari)
    public function isOverdue()
    {
        if ($this->status !== 'pending') {
            return false;
        }
        return $this->created_at->diffInDays(now()) > 3;
    }

    public function getStatusLabelAttribute()
    {
        if ($this->status === 'success') {
            return 'Lunas';
        }
        if ($this->status === 'pending') {
            return $this->isOverdue() ? 'Jatuh Tempo' : 'Menunggu';
        }
        if ($this->status === 'failed') {
            return 'Gagal';
        }
        if ($this->status === 'expired') {
            return 'Kedaluwarsa';
        }
        return ucfirst($this->status);
    }

    public function getStatusColorAttribute()
    {
        if ($this->status === 'success') {
            return 'emerald';
        }
        if ($this->status === 'pending') {
            return $this->isOverdue() ? 'rose' : 'amber';
        }
        return 'gray';
    }
}
