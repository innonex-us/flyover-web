<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VisitorPageView extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'session_id',
        'url',
        'title',
        'path',
        'query_params',
        'hash',
        'viewed_at',
        'time_on_page',
        'scroll_depth',
        'max_scroll_depth',
        'is_exit_page',
        'interactions',
        'performance_metrics'
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
        'is_exit_page' => 'boolean',
        'interactions' => 'array',
        'performance_metrics' => 'array'
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(VisitorSession::class);
    }

    public function addInteraction(string $type, array $data = []): void
    {
        $interactions = $this->interactions ?? [];
        $interactions[] = [
            'type' => $type,
            'data' => $data,
            'timestamp' => now()->toISOString()
        ];
        $this->interactions = $interactions;
        $this->save();
    }

    public function updateScrollDepth(int $depth): void
    {
        if ($depth > $this->max_scroll_depth) {
            $this->max_scroll_depth = $depth;
            $this->save();
        }
    }

    public function updateTimeOnPage(): void
    {
        if ($this->viewed_at) {
            $this->time_on_page = $this->viewed_at->diffInSeconds(now());
            $this->save();
        }
    }

    public function setPerformanceMetrics(array $metrics): void
    {
        $this->performance_metrics = $metrics;
        $this->save();
    }

    public function getEngagementScore(): int
    {
        $score = 0;
        
        // Time on page (max 40 points)
        $score += min(40, $this->time_on_page / 3);
        
        // Scroll depth (max 30 points)
        $score += ($this->max_scroll_depth / 100) * 30;
        
        // Interactions (max 30 points)
        $interactionCount = count($this->interactions ?? []);
        $score += min(30, $interactionCount * 10);
        
        return (int) round($score);
    }

    public function isHighEngagement(): bool
    {
        return $this->getEngagementScore() >= 70;
    }

    public function getPageCategory(): string
    {
        $path = $this->path;
        
        if (str_starts_with($path, '/admin')) {
            return 'admin';
        }
        
        if (str_starts_with($path, '/blog')) {
            return 'blog';
        }
        
        if (str_starts_with($path, '/packages')) {
            return 'packages';
        }
        
        if (str_starts_with($path, '/visa')) {
            return 'visa';
        }
        
        if (str_starts_with($path, '/contact')) {
            return 'contact';
        }
        
        if ($path === '/' || $path === '/home') {
            return 'homepage';
        }
        
        return 'other';
    }
}
