<?php

namespace App\Console\Commands;

use App\Models\Visitor;
use App\Models\VisitorSession;
use App\Models\VisitorPageView;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanupAnalyticsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:cleanup 
                            {--dry-run : Show what would be deleted without actually deleting}
                            {--force : Force cleanup without confirmation}
                            {--retention-days=365 : Number of days to retain data (default: 365)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old analytics data based on retention policies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $retentionDays = $this->option('retention-days');
        $isDryRun = $this->option('dry-run');
        $force = $this->option('force');
        
        $this->info("Analytics Data Cleanup");
        $this->info("====================");
        $this->info("Retention Period: {$retentionDays} days");
        $this->info("Dry Run: " . ($isDryRun ? 'Yes' : 'No'));
        $this->line("");

        $cutoffDate = Carbon::now()->subDays($retentionDays);
        
        if (!$isDryRun && !$force) {
            $this->warn("This will permanently delete analytics data older than {$cutoffDate->toDateString()}");
            if (!$this->confirm('Do you want to continue?')) {
                $this->info('Cleanup cancelled.');
                return 0;
            }
        }

        $this->performCleanup($cutoffDate, $isDryRun);
        
        $this->info('Analytics cleanup completed successfully!');
        return 0;
    }

    private function performCleanup(Carbon $cutoffDate, bool $isDryRun): void
    {
        // Clean up old page views
        $this->cleanupPageViews($cutoffDate, $isDryRun);
        
        // Clean up old sessions
        $this->cleanupSessions($cutoffDate, $isDryRun);
        
        // Clean up old visitors (keep basic info for GDPR compliance)
        $this->cleanupVisitors($cutoffDate, $isDryRun);
        
        // Clean up bot traffic
        $this->cleanupBotTraffic($isDryRun);
        
        // Optimize tables
        if (!$isDryRun) {
            $this->optimizeTables();
        }
    }

    private function cleanupPageViews(Carbon $cutoffDate, bool $isDryRun): void
    {
        $this->line("Cleaning up page views older than {$cutoffDate->toDateString()}...");
        
        $query = VisitorPageView::where('viewed_at', '<', $cutoffDate);
        $count = $query->count();
        
        if ($count > 0) {
            $this->info("Found {$count} old page views to delete");
            
            if ($isDryRun) {
                $this->line("Would delete {$count} page views");
            } else {
                $deleted = $query->delete();
                $this->info("Deleted {$deleted} page views");
            }
        } else {
            $this->info("No old page views found");
        }
        
        $this->line("");
    }

    private function cleanupSessions(Carbon $cutoffDate, bool $isDryRun): void
    {
        $this->line("Cleaning up sessions older than {$cutoffDate->toDateString()}...");
        
        $query = VisitorSession::where('started_at', '<', $cutoffDate);
        $count = $query->count();
        
        if ($count > 0) {
            $this->info("Found {$count} old sessions to delete");
            
            if ($isDryRun) {
                $this->line("Would delete {$count} sessions");
            } else {
                $deleted = $query->delete();
                $this->info("Deleted {$deleted} sessions");
            }
        } else {
            $this->info("No old sessions found");
        }
        
        $this->line("");
    }

    private function cleanupVisitors(Carbon $cutoffDate, bool $isDryRun): void
    {
        $this->line("Cleaning up visitor data older than {$cutoffDate->toDateString()}...");
        
        // For GDPR compliance, we keep basic visitor info but anonymize detailed data
        $query = Visitor::where('last_visit_at', '<', $cutoffDate)
            ->where('total_visits', '<=', 5); // Only clean up low-frequency visitors
        
        $count = $query->count();
        
        if ($count > 0) {
            $this->info("Found {$count} old visitor records to anonymize");
            
            if ($isDryRun) {
                $this->line("Would anonymize {$count} visitor records");
            } else {
                $anonymized = $query->update([
                    'ip_address' => null,
                    'user_agent' => null,
                    'browser' => null,
                    'browser_version' => null,
                    'platform' => null,
                    'screen_resolution' => null,
                    'viewport_size' => null,
                    'cookies_enabled' => false,
                    'javascript_enabled' => false,
                    'timezone' => null,
                    'city' => null,
                    'region' => null,
                    'latitude' => null,
                    'longitude' => null,
                    'isp' => null,
                    'organization' => null,
                    'connection_type' => null,
                ]);
                $this->info("Anonymized {$anonymized} visitor records");
            }
        } else {
            $this->info("No old visitor records found");
        }
        
        // Delete completely inactive visitors (no visits in 2 years)
        $twoYearsAgo = Carbon::now()->subYears(2);
        $deleteQuery = Visitor::where('last_visit_at', '<', $twoYearsAgo);
        $deleteCount = $deleteQuery->count();
        
        if ($deleteCount > 0) {
            $this->info("Found {$deleteCount} completely inactive visitors to delete");
            
            if ($isDryRun) {
                $this->line("Would delete {$deleteCount} inactive visitors");
            } else {
                $deleted = $deleteQuery->delete();
                $this->info("Deleted {$deleted} inactive visitors");
            }
        }
        
        $this->line("");
    }

    private function cleanupBotTraffic(bool $isDryRun): void
    {
        $this->line("Cleaning up bot traffic...");
        
        $pageViewsQuery = VisitorPageView::whereHas('visitor', function($query) {
            $query->where('is_bot', true);
        });
        
        $pageViewsCount = $pageViewsQuery->count();
        
        if ($pageViewsCount > 0) {
            $this->info("Found {$pageViewsCount} bot page views to delete");
            
            if ($isDryRun) {
                $this->line("Would delete {$pageViewsCount} bot page views");
            } else {
                $deleted = $pageViewsQuery->delete();
                $this->info("Deleted {$deleted} bot page views");
            }
        }
        
        $sessionsQuery = VisitorSession::whereHas('visitor', function($query) {
            $query->where('is_bot', true);
        });
        
        $sessionsCount = $sessionsQuery->count();
        
        if ($sessionsCount > 0) {
            $this->info("Found {$sessionsCount} bot sessions to delete");
            
            if ($isDryRun) {
                $this->line("Would delete {$sessionsCount} bot sessions");
            } else {
                $deleted = $sessionsQuery->delete();
                $this->info("Deleted {$deleted} bot sessions");
            }
        }
        
        $visitorsQuery = Visitor::where('is_bot', true);
        $visitorsCount = $visitorsQuery->count();
        
        if ($visitorsCount > 0) {
            $this->info("Found {$visitorsCount} bot visitors to delete");
            
            if ($isDryRun) {
                $this->line("Would delete {$visitorsCount} bot visitors");
            } else {
                $deleted = $visitorsQuery->delete();
                $this->info("Deleted {$deleted} bot visitors");
            }
        }
        
        $this->line("");
    }

    private function optimizeTables(): void
    {
        $this->line("Optimizing analytics tables...");
        
        try {
            DB::statement('OPTIMIZE TABLE visitors');
            DB::statement('OPTIMIZE TABLE visitor_sessions');
            DB::statement('OPTIMIZE TABLE visitor_page_views');
            
            $this->info("Table optimization completed");
        } catch (\Exception $e) {
            $this->warn("Table optimization failed: " . $e->getMessage());
        }
        
        $this->line("");
    }
}
