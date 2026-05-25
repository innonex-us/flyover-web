<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\VisitorSession;
use App\Models\VisitorPageView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '7d');
        $dateRange = $this->getDateRange($period);
        
        // Overview Metrics
        $metrics = $this->getOverviewMetrics($dateRange);
        
        // Visitors Data
        $visitorsData = $this->getVisitorsData($dateRange);
        
        // Pages Data
        $pagesData = $this->getPagesData($dateRange);
        
        // Geographic Data
        $geoData = $this->getGeographicData($dateRange);
        
        // Device Data
        $deviceData = $this->getDeviceData($dateRange);
        
        // Sessions Data
        $sessionsData = $this->getSessionsData($dateRange);
        
        // Real-time Data
        $realtimeData = $this->getRealtimeData();
        
        return view('admin.analytics.index', compact(
            'metrics',
            'visitorsData',
            'pagesData',
            'geoData',
            'deviceData',
            'sessionsData',
            'realtimeData',
            'period'
        ));
    }
    
    private function getDateRange(string $period): array
    {
        $now = now();
        
        return match($period) {
            '1d' => [$now->copy()->subDay(), $now],
            '7d' => [$now->copy()->subDays(7), $now],
            '30d' => [$now->copy()->subDays(30), $now],
            '90d' => [$now->copy()->subDays(90), $now],
            '1y' => [$now->copy()->subYear(), $now],
            default => [$now->copy()->subDays(7), $now]
        };
    }
    
    private function getOverviewMetrics(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        $totalVisitors = Visitor::whereBetween('first_visit_at', [$startDate, $endDate], 'and', false)->count();
        $returningVisitors = Visitor::whereBetween('first_visit_at', [$startDate, $endDate], 'and', false)
            ->where('total_visits', '>', 1)
            ->count();
        $totalPageViews = VisitorPageView::whereBetween('viewed_at', [$startDate, $endDate], 'and', false)->count();
        $totalSessions = VisitorSession::whereBetween('started_at', [$startDate, $endDate], 'and', false)->count();
        
        // Previous period for comparison
        $previousStart = $startDate->copy()->subDays($startDate->diffInDays($endDate));
        $previousEnd = $startDate->copy();
        
        $previousVisitors = Visitor::whereBetween('first_visit_at', [$previousStart, $previousEnd], 'and', false)->count();
        $previousPageViews = VisitorPageView::whereBetween('viewed_at', [$previousStart, $previousEnd], 'and', false)->count();
        $previousSessions = VisitorSession::whereBetween('started_at', [$previousStart, $previousEnd], 'and', false)->count();
        
        return [
            'total_visitors' => $totalVisitors,
            'visitors_growth' => $previousVisitors > 0 ? round((($totalVisitors - $previousVisitors) / $previousVisitors) * 100, 1) : 0,
            'returning_visitors' => $returningVisitors,
            'returning_rate' => $totalVisitors > 0 ? round(($returningVisitors / $totalVisitors) * 100, 1) : 0,
            'total_page_views' => $totalPageViews,
            'page_views_growth' => $previousPageViews > 0 ? round((($totalPageViews - $previousPageViews) / $previousPageViews) * 100, 1) : 0,
            'total_sessions' => $totalSessions,
            'sessions_growth' => $previousSessions > 0 ? round((($totalSessions - $previousSessions) / $previousSessions) * 100, 1) : 0,
            'avg_session_duration' => $this->getAverageSessionDuration($dateRange),
            'bounce_rate' => $this->getBounceRate($dateRange),
        ];
    }
    
    private function getVisitorsData(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        // Daily visitors trend
        $dailyVisitors = Visitor::selectRaw('DATE(first_visit_at) as date, COUNT(*) as count', [])
            ->whereBetween('first_visit_at', [$startDate, $endDate], 'and', false)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // New vs Returning visitors
        $newVisitors = Visitor::whereBetween('first_visit_at', [$startDate, $endDate], 'and', false)
            ->where('total_visits', 1)
            ->count();
        $returningVisitors = Visitor::whereBetween('first_visit_at', [$startDate, $endDate], 'and', false)
            ->where('total_visits', '>', 1)
            ->count();
        
        // Top countries
        $topCountries = Visitor::select('country_code', 'country', DB::raw('COUNT(*) as count'))
            ->whereBetween('first_visit_at', [$startDate, $endDate])
            ->whereNotNull('country_code')
            ->groupBy('country_code', 'country')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        return [
            'daily_trend' => $dailyVisitors,
            'new_visitors' => $newVisitors,
            'returning_visitors' => $returningVisitors,
            'top_countries' => $topCountries,
        ];
    }
    
    private function getPagesData(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        // Top pages
        $topPages = VisitorPageView::select('path', 'title', DB::raw('COUNT(*) as views'))
            ->whereBetween('viewed_at', [$startDate, $endDate])
            ->groupBy('path', 'title')
            ->orderByDesc('views')
            ->limit(10)
            ->get();
        
        // Page categories performance
        $pageCategories = VisitorPageView::selectRaw('
                CASE 
                    WHEN path LIKE "/admin%" THEN "admin"
                    WHEN path LIKE "/blog%" THEN "blog"
                    WHEN path LIKE "/packages%" THEN "packages"
                    WHEN path LIKE "/visa%" THEN "visa"
                    WHEN path LIKE "/contact%" THEN "contact"
                    WHEN path IN ("/", "/home") THEN "homepage"
                    ELSE "other"
                END as category,
                COUNT(*) as views
            ', [])
            ->whereBetween('viewed_at', [$startDate, $endDate], 'and', false)
            ->groupBy('category')
            ->orderByDesc('views')
            ->get();
        
        // Average time on page
        $avgTimeOnPage = (float) (VisitorPageView::whereBetween('viewed_at', [$startDate, $endDate], 'and', false)
            ->where('time_on_page', '>', 0)
            ->avg('time_on_page') ?? 0);
        
        return [
            'top_pages' => $topPages,
            'categories' => $pageCategories,
            'avg_time_on_page' => round($avgTimeOnPage),
        ];
    }
    
    private function getGeographicData(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        $geoData = Visitor::select([
                'country_code',
                'country',
                'city',
                DB::raw('COUNT(*) as visitors'),
                DB::raw('AVG(latitude) as avg_lat'),
                DB::raw('AVG(longitude) as avg_lng')
            ])
            ->whereBetween('first_visit_at', [$startDate, $endDate])
            ->whereNotNull('country_code')
            ->groupBy('country_code', 'country', 'city')
            ->orderByDesc('visitors')
            ->get();
        
        return [
            'data' => $geoData,
            'map_data' => $geoData->map(function($item) {
                return [
                    'country' => $item->country_code,
                    'visitors' => $item->visitors,
                    'lat' => $item->avg_lat,
                    'lng' => $item->avg_lng
                ];
            })
        ];
    }
    
    private function getDeviceData(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        $deviceTypes = Visitor::selectRaw('
                CASE
                    WHEN is_mobile = 1 THEN "Mobile"
                    WHEN is_tablet = 1 THEN "Tablet"
                    WHEN is_desktop = 1 THEN "Desktop"
                    ELSE "Unknown"
                END as device_type,
                COUNT(*) as count
            ', [])
            ->whereBetween('first_visit_at', [$startDate, $endDate], 'and', false)
            ->groupByRaw('
                CASE
                    WHEN is_mobile = 1 THEN "Mobile"
                    WHEN is_tablet = 1 THEN "Tablet"
                    WHEN is_desktop = 1 THEN "Desktop"
                    ELSE "Unknown"
                END
            ')
            ->get();
        
        $browsers = Visitor::select('browser', DB::raw('COUNT(*) as count'))
            ->whereBetween('first_visit_at', [$startDate, $endDate], 'and', false)
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        $platforms = Visitor::select('platform', DB::raw('COUNT(*) as count'))
            ->whereBetween('first_visit_at', [$startDate, $endDate], 'and', false)
            ->whereNotNull('platform')
            ->groupBy('platform')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        return [
            'device_types' => $deviceTypes,
            'browsers' => $browsers,
            'platforms' => $platforms,
        ];
    }
    
    private function getSessionsData(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        $sessions = VisitorSession::whereBetween('started_at', [$startDate, $endDate], 'and', false)
            ->with(['visitor', 'pageViews'])
            ->orderByDesc('started_at')
            ->limit(100)
            ->get();
        
        return [
            'sessions' => $sessions,
            'total' => $sessions->count(),
        ];
    }
    
    private function getRealtimeData(): array
    {
        $now = now();
        $fiveMinutesAgo = $now->copy()->subMinutes(5);
        
        $activeVisitors = VisitorSession::where('last_activity_at', '>=', $fiveMinutesAgo, 'and')
            ->distinct('visitor_id')
            ->count();
        
        $currentSessions = VisitorSession::where('last_activity_at', '>=', $fiveMinutesAgo, 'and')
            ->with(['visitor', 'pageViews' => function($query) {
                $query->orderBy('viewed_at', 'desc');
            }])
            ->orderByDesc('last_activity_at')
            ->limit(50)
            ->get();

        $sessions = $currentSessions->map(function (VisitorSession $session) {
            $visitor = $session->visitor;
            $latestPageView = $session->pageViews->first();

            $browserLabel = $visitor?->browser ?: ($visitor?->user_agent ? Str::limit($visitor->user_agent, 36) : 'Unknown browser');
            $deviceLabel = $visitor?->device_type ?: ($visitor?->is_mobile ? 'mobile' : ($visitor?->is_tablet ? 'tablet' : ($visitor?->is_desktop ? 'desktop' : 'Unknown device')));
            $locationLabel = trim(implode(', ', array_filter([
                $visitor?->city,
                $visitor?->country,
            ]))) ?: 'Unknown location';
            $pageTitle = data_get($latestPageView, 'title') ?: data_get($latestPageView, 'path') ?: 'Unknown page';
            $pagePath = data_get($latestPageView, 'path') ?: '/';

            return [
                'id' => $session->id,
                'browser' => $browserLabel,
                'device_type' => $deviceLabel,
                'location' => $locationLabel,
                'ip_address' => $visitor?->ip_address ?: 'Unknown',
                'page_title' => $pageTitle,
                'page_path' => $pagePath,
                'duration' => (int) $session->duration,
                'last_activity_at' => $session->last_activity_at,
            ];
        });
        
        return [
            'active_visitors' => $activeVisitors,
            'current_sessions' => $sessions,
        ];
    }
    
    private function getAverageSessionDuration(array $dateRange): int
    {
        [$startDate, $endDate] = $dateRange;
        
        $avgDuration = (float) (VisitorSession::whereBetween('started_at', [$startDate, $endDate], 'and', false)
            ->where('duration', '>', 0)
            ->avg('duration') ?? 0);
        
        return (int) round($avgDuration);
    }
    
    private function getBounceRate(array $dateRange): float
    {
        [$startDate, $endDate] = $dateRange;
        
        $totalSessions = VisitorSession::whereBetween('started_at', [$startDate, $endDate], 'and', false)->count();
        if ($totalSessions === 0) return 0;
        
        $bounceSessions = VisitorSession::whereBetween('started_at', [$startDate, $endDate], 'and', false)
            ->where('is_bounce', true)
            ->count();
        
        return round(($bounceSessions / $totalSessions) * 100, 1);
    }
    
    public function export(Request $request)
    {
        $period = $request->get('period', '30d');
        $dateRange = $this->getDateRange($period);
        [$startDate, $endDate] = $dateRange;
        
        $data = Visitor::with(['sessions', 'pageViews'])
            ->whereBetween('first_visit_at', [$startDate, $endDate])
            ->get();
        
        $filename = "analytics_export_{$period}_" . now()->format('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'Visitor ID',
                'First Visit',
                'Last Visit',
                'Total Visits',
                'Total Page Views',
                'Country',
                'City',
                'Browser',
                'Platform',
                'Device Type',
                'IP Address'
            ]);
            
            foreach ($data as $visitor) {
                fputcsv($file, [
                    $visitor->id,
                    $visitor->first_visit_at,
                    $visitor->last_visit_at,
                    $visitor->total_visits,
                    $visitor->total_page_views,
                    $visitor->country,
                    $visitor->city,
                    $visitor->browser,
                    $visitor->platform,
                    $visitor->device_type,
                    $visitor->ip_address
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
