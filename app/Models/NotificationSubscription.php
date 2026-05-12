<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSubscription extends Model
{
    protected $fillable = [
        'endpoint',
        'p256dh',
        'auth',
        'user_agent',
        'ip_address',
        'is_active',
        'last_notified_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_notified_at' => 'datetime',
    ];

    /**
     * Scope for active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Mark as notified
     */
    public function markAsNotified()
    {
        $this->update(['last_notified_at' => now()]);
    }
}
