<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',  // ← diffForHumans() blade mein use ho raha hai
            'updated_at' => 'datetime',
        ];
    }

    // ─── RELATIONSHIPS ───────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── SCOPES ──────────────────────────────────────────────────
    public function scopeAlerts($query)         // ← AdminController mein ActivityLog::alerts()->count()
    {
        return $query->whereIn('action', [
            'suspicious_login_attempt',
            'ssrf_attempt_blocked',
            'multiple_failed_logins',
        ]);
    }

    public function scopeForUser($query, $userId)   // ← showUser() mein kaam aayega
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query, $limit = 15)
    {
        return $query->latest()->take($limit);
    }
}