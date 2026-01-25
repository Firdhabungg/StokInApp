<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Toko extends Model
{
    protected $fillable = [
        'name',
        'email',
        'address',
        'phone',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function owner()
    {
        return $this->hasOne(User::class)->where('role', 'owner');
    }

    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class);
    }

    public function kategoris(): HasMany
    {
        return $this->hasMany(KategoriBarang::class);
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

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->whereIn('status', ['trial', 'active'])
            ->where('expires_at', '>', now())
            ->latest();
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }

    public function isSubscriptionExpired(): bool
    {
        $subscription = $this->subscriptions()->latest()->first();

        if (!$subscription) {
            return true;
        }

        return $subscription->isExpired();
    }

    public function hasUsedFreeTrial(): bool
    {
        return $this->subscriptions()
            ->whereHas('plan', function ($query) {
                $query->where('slug', 'free');
            })
            ->exists();
    }

    public function getCurrentPlanName(): string
    {
        $subscription = $this->activeSubscription;

        if (!$subscription) {
            return 'Tidak Ada';
        }

        return $subscription->plan->name;
    }

    public function getFeature(string $key, $default = null)
    {
        $subscription = $this->activeSubscription;

        if (!$subscription || !$subscription->plan) {
            return $default;
        }

        return $subscription->plan->features[$key] ?? $default;
    }

    public function canAddUser(): bool
    {
        $maxKasir = $this->getFeature('max_kasir', 1);

        // -1 means unlimited
        if ($maxKasir == -1) {
            return true;
        }

        // Count only kasir users (not owner)
        $currentKasirCount = $this->users()->where('role', 'kasir')->count();

        return $currentKasirCount < $maxKasir;
    }

    public function remainingUserSlots(): int
    {
        $maxKasir = $this->getFeature('max_kasir', 1);

        // -1 means unlimited
        if ($maxKasir == -1) {
            return 999; // large number for unlimited
        }

        // Count only kasir users (not owner)
        $currentKasirCount = $this->users()->where('role', 'kasir')->count();

        return max(0, $maxKasir - $currentKasirCount);
    }

    public function canExportReport(): bool
    {
        return (bool) $this->getFeature('export_report', false);
    }
}
