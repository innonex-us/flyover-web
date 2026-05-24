<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VisitorSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'session_id',
        'referrer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'landing_page',
        'exit_page',
        'started_at',
        'last_activity_at',
        'duration',
        'page_views',
        'interactions',
        'is_bounce',
        'conversion_rate',
        'events'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'is_bounce' => 'boolean',
        'conversion_rate' => 'decimal:2',
        'events' => 'array'
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function pageViews(): HasMany
    {
        return $this->hasMany(VisitorPageView::class);
    }

    public function updateActivity(): void
    {
        $this->last_activity_at = now();
        $this->duration = $this->started_at->diffInSeconds(now());
        $this->save();
    }

    public function addPageView(): void
    {
        $this->increment('page_views');
        $this->updateActivity();
        
        if ($this->page_views > 1) {
            $this->is_bounce = false;
            $this->save();
        }
    }

    public function addInteraction(): void
    {
        $this->increment('interactions');
        $this->updateActivity();
    }

    public function addEvent(string $event, array $data = []): void
    {
        $events = $this->events ?? [];
        $events[] = [
            'event' => $event,
            'data' => $data,
            'timestamp' => now()->toISOString()
        ];
        $this->events = $events;
        $this->save();
    }

    public function getUtmParameters(): array
    {
        return [
            'utm_source' => $this->utm_source,
            'utm_medium' => $this->utm_medium,
            'utm_campaign' => $this->utm_campaign,
            'utm_term' => $this->utm_term,
            'utm_content' => $this->utm_content
        ];
    }

    public function isFromSearchEngine(): bool
    {
        $searchEngines = ['google', 'bing', 'yahoo', 'duckduckgo', 'baidu'];
        $referrer = strtolower($this->referrer ?? '');
        
        foreach ($searchEngines as $engine) {
            if (str_contains($referrer, $engine)) {
                return true;
            }
        }
        
        return false;
    }

    public function isFromSocialMedia(): bool
    {
        $socialPlatforms = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'tiktok'];
        $referrer = strtolower($this->referrer ?? '');
        
        foreach ($socialPlatforms as $platform) {
            if (str_contains($referrer, $platform)) {
                return true;
            }
        }
        
        return false;
    }
}
