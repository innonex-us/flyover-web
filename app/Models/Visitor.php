<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'fingerprint',
        'ip_address',
        'user_agent',
        'browser',
        'browser_version',
        'platform',
        'device_type',
        'is_mobile',
        'is_tablet',
        'is_desktop',
        'language',
        'timezone',
        'country',
        'country_code',
        'city',
        'region',
        'latitude',
        'longitude',
        'isp',
        'organization',
        'connection_type',
        'screen_resolution',
        'viewport_size',
        'cookies_enabled',
        'javascript_enabled',
        'do_not_track',
        'consent_level',
        'first_visit_at',
        'last_visit_at',
        'total_visits',
        'total_page_views',
        'total_duration',
        'is_bot'
    ];

    protected $casts = [
        'screen_resolution' => 'array',
        'viewport_size' => 'array',
        'cookies_enabled' => 'boolean',
        'javascript_enabled' => 'boolean',
        'do_not_track' => 'boolean',
        'is_mobile' => 'boolean',
        'is_tablet' => 'boolean',
        'is_desktop' => 'boolean',
        'is_bot' => 'boolean',
        'first_visit_at' => 'datetime',
        'last_visit_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    public function sessions(): HasMany
    {
        return $this->hasMany(VisitorSession::class);
    }

    public function pageViews(): HasMany
    {
        return $this->hasMany(VisitorPageView::class);
    }

    public function updateVisitStats(): void
    {
        $this->increment('total_visits');
        $this->touch('last_visit_at');
        
        if (!$this->first_visit_at) {
            $this->first_visit_at = now();
            $this->save();
        }
    }

    public function isReturningVisitor(): bool
    {
        return $this->total_visits > 1;
    }

    public function getAverageSessionDuration(): int
    {
        return $this->sessions()->avg('duration') ?? 0;
    }

    public function getBounceRate(): float
    {
        $totalSessions = $this->sessions()->count();
        if ($totalSessions === 0) return 0;
        
        $bounceSessions = $this->sessions()->where('is_bounce', true)->count();
        return ($bounceSessions / $totalSessions) * 100;
    }
}
