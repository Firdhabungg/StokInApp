<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationLogs extends Model
{
    protected $table = 'notification_logs';

    protected $fillable = [
        'toko_id',
        'barang_id',
        'type',
        'channel',
        'status',
        'recipient',
        'payload',
        'error',
        'sent_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'sent_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeForToko($query, int $tokoId)
    {
        return $query->where('toko_id', $tokoId);
    }
}
