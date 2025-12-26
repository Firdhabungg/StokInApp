<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'toko_id',
        'plan_id',
        'status',
        'starts_at',
        'expires_at',
        'auto_renew',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'auto_renew' => 'boolean',
    ];

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function isActive()
    {
        return in_array($this->status, ['trial', 'active']) && $this->expires_at->isFuture();
    }

    public function isExpired()
    {
        return $this->expires_at->isPast() || $this->status === 'expired';
    }

    public function isTrial()
    {
        return $this->status === 'trial';
    }

    public function daysRemaining()
    {
        if ($this->expires_at->isPast()) {
            return 0;
        }
        return now()->diffInDays($this->expires_at);
    }
}
