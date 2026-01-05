<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Toko extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'address',
        'phone',
    ];

    /**
     * Get all users belonging to this toko.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the owner of the toko.
     */
    public function owner()
    {
        return $this->hasOne(User::class)->where('role', 'owner');
    }

    /**
     * Get all barangs for this toko.
     */
    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class);
    }

    /**
     * Get all kategoris for this toko.
     */
    public function kategoris(): HasMany
    {
        return $this->hasMany(KategoriBarang::class);
    }

    /**
     * Get all stock batches for this toko.
     */
    public function stockBatches(): HasMany
    {
        return $this->hasMany(StockBatch::class);
    }

    /**
     * Get all stock in records for this toko.
     */
    public function stockIn(): HasMany
    {
        return $this->hasMany(StockIn::class);
    }

    /**
     * Get all stock out records for this toko.
     */
    public function stockOut(): HasMany
    {
        return $this->hasMany(StockOut::class);
    }

    /**
     * Get all subscriptions for this toko.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the active subscription for this toko.
     */
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->whereIn('status', ['trial', 'active'])
            ->where('expires_at', '>', now())
            ->latest();
    }

    /**
     * Check if toko has an active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }

    /**
     * Check if toko subscription is expired.
     */
    public function isSubscriptionExpired(): bool
    {
        $subscription = $this->subscriptions()->latest()->first();

        if (!$subscription) {
            return true;
        }

        return $subscription->isExpired();
    }

    /**
     * Check if toko has ever used a free trial.
     */
    public function hasUsedFreeTrial(): bool
    {
        return $this->subscriptions()
            ->whereHas('plan', function ($query) {
                $query->where('slug', 'free');
            })
            ->exists();
    }

    /**
     * Get the current plan name.
     */
    public function getCurrentPlanName(): string
    {
        $subscription = $this->activeSubscription;

        if (!$subscription) {
            return 'Tidak Ada';
        }

        return $subscription->plan->name;
    }

    /**
     * Get a specific feature value from active subscription.
     */
    public function getFeature(string $key, $default = null)
    {
        $subscription = $this->activeSubscription;

        if (!$subscription || !$subscription->plan) {
            return $default;
        }

        return $subscription->plan->features[$key] ?? $default;
    }

    /**
     * Check if toko can add more kasir based on subscription limit.
     */
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

    /**
     * Get remaining kasir slots.
     */
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

    /**
     * Check if toko can export reports based on subscription.
     */
    public function canExportReport(): bool
    {
        return (bool) $this->getFeature('export_report', false);
    }
}
