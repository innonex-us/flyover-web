<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Post;
use App\Models\Visa;
use App\Models\Visitor;
use App\Models\VisitorSession;
use App\Models\VisitorPageView;
use App\Models\ContactMessage;
use App\Models\CustomizationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30d');
        $dateRange = $this->getDateRange($period);
        [$startDate, $endDate] = $dateRange;

        // Basic Counts
        $totalPackages = Package::count();
        $activeVisas = Visa::count();
        $totalBookings = Booking::count();
        
        // Revenue Analytics
        $totalRevenue = Booking::whereIn('status', ['confirmed', 'completed'])->sum('total_amount');
        $monthlyRevenue = $this->getMonthlyRevenue($dateRange);
        $revenueByService = $this->getRevenueByService($dateRange);
        
        // Booking Analytics
        $bookingStats = $this->getBookingStats($dateRange);
        $bookingTrends = $this->getBookingTrends($dateRange);
        $recentBookings = Booking::with(['user', 'payable'])
            ->latest()
            ->take(5)
            ->get();

        // Visitor Analytics
        $visitorStats = $this->getVisitorStats($dateRange);
        $visitorTrends = $this->getVisitorTrends($dateRange);
        $topPages = $this->getTopPages($dateRange);
        $visitorGeography = $this->getVisitorGeography($dateRange);

        // Content Analytics
        $blogStats = $this->getBlogStats();
        $recentPosts = Post::latest()->take(3)->get();
        
        // Communication Analytics
        $contactStats = $this->getContactStats($dateRange);
        $customizationStats = $this->getCustomizationStats($dateRange);

        // Performance Metrics
        $performanceMetrics = $this->getPerformanceMetrics($dateRange);

        return view('admin.dashboard', compact(
            'totalPackages',
            'activeVisas',
            'totalBookings',
            'totalRevenue',
            'monthlyRevenue',
            'revenueByService',
            'bookingStats',
            'bookingTrends',
            'recentBookings',
            'visitorStats',
            'visitorTrends',
            'topPages',
            'visitorGeography',
            'blogStats',
            'recentPosts',
            'contactStats',
            'customizationStats',
            'performanceMetrics',
            'period'
        ));
    }

    private function getDateRange(string $period): array
    {
        $now = now();
        
        return match($period) {
            '7d' => [$now->copy()->subDays(7), $now],
            '30d' => [$now->copy()->subDays(30), $now],
            '90d' => [$now->copy()->subDays(90), $now],
            '1y' => [$now->copy()->subYear(), $now],
            default => [$now->copy()->subDays(30), $now]
        };
    }

    private function getMonthlyRevenue(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        return Booking::whereIn('status', ['confirmed', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->toArray();
    }

    private function getRevenueByService(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        return Booking::whereIn('status', ['confirmed', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                CASE 
                    WHEN payable_type LIKE "%Package%" THEN "Packages"
                    WHEN payable_type LIKE "%Visa%" THEN "Visas"
                    ELSE "Other"
                END as service_type,
                SUM(total_amount) as revenue,
                COUNT(*) as count
            ')
            ->groupBy('service_type')
            ->get()
            ->toArray();
    }

    private function getBookingStats(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        $total = Booking::whereBetween('created_at', [$startDate, $endDate])->count();
        $pending = Booking::whereBetween('created_at', [$startDate, $endDate])->where('status', 'pending')->count();
        $confirmed = Booking::whereBetween('created_at', [$startDate, $endDate])->where('status', 'confirmed')->count();
        $completed = Booking::whereBetween('created_at', [$startDate, $endDate])->where('status', 'completed')->count();
        $cancelled = Booking::whereBetween('created_at', [$startDate, $endDate])->where('status', 'cancelled')->count();
        
        return compact('total', 'pending', 'confirmed', 'completed', 'cancelled');
    }

    private function getBookingTrends(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        return Booking::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as bookings')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    private function getVisitorStats(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        $totalVisitors = Visitor::whereBetween('first_visit_at', [$startDate, $endDate])->count();
        $returningVisitors = Visitor::whereBetween('first_visit_at', [$startDate, $endDate])
            ->where('total_visits', '>', 1)
            ->count();
        $totalPageViews = VisitorPageView::whereBetween('viewed_at', [$startDate, $endDate])->count();
        $totalSessions = VisitorSession::whereBetween('started_at', [$startDate, $endDate])->count();
        
        $avgSessionDuration = (float) (VisitorSession::whereBetween('started_at', [$startDate, $endDate])
            ->where('duration', '>', 0)
            ->avg('duration') ?? 0);
        
        $bounceRate = $this->calculateBounceRate($dateRange);
        
        return compact(
            'totalVisitors',
            'returningVisitors', 
            'totalPageViews',
            'totalSessions',
            'avgSessionDuration',
            'bounceRate'
        );
    }

    private function getVisitorTrends(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        return Visitor::whereBetween('first_visit_at', [$startDate, $endDate])
            ->selectRaw('DATE(first_visit_at) as date, COUNT(*) as visitors')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    private function getTopPages(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        return VisitorPageView::whereBetween('viewed_at', [$startDate, $endDate])
            ->select('path', 'title', DB::raw('COUNT(*) as views'))
            ->groupBy('path', 'title')
            ->orderByDesc('views')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function getVisitorGeography(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        return Visitor::whereBetween('first_visit_at', [$startDate, $endDate])
            ->whereNotNull('country_code')
            ->select('country_code', 'country', DB::raw('COUNT(*) as visitors'))
            ->groupBy('country_code', 'country')
            ->orderByDesc('visitors')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function getBlogStats(): array
    {
        $published = Post::where('is_published', true)->count();
        $draft = Post::where('is_published', false)->count();
        $totalViews = 0; // Views tracking not implemented yet
        
        return compact('published', 'draft', 'totalViews');
    }

    private function getContactStats(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        $total = ContactMessage::whereBetween('created_at', [$startDate, $endDate])->count();
        $unread = ContactMessage::whereBetween('created_at', [$startDate, $endDate])
            ->where('is_read', false)
            ->count();
        
        return compact('total', 'unread');
    }

    private function getCustomizationStats(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        $total = CustomizationRequest::whereBetween('created_at', [$startDate, $endDate])->count();
        $pending = CustomizationRequest::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'pending')
            ->count();
        
        return compact('total', 'pending');
    }

    private function getPerformanceMetrics(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        // Conversion rate (bookings / unique visitors, capped at 100%)
        $totalVisitors = Visitor::whereBetween('first_visit_at', [$startDate, $endDate])->count();
        $totalBookings = Booking::whereBetween('created_at', [$startDate, $endDate])->count();
        $conversionRate = $totalVisitors > 0 ? min(round(($totalBookings / $totalVisitors) * 100, 2), 100) : 0;
        
        // Average booking value
        $avgBookingValue = (float) (Booking::whereIn('status', ['confirmed', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->avg('total_amount') ?? 0);
        
        // Revenue per visitor
        $totalRevenue = Booking::whereIn('status', ['confirmed', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
        $revenuePerVisitor = $totalVisitors > 0 ? round($totalRevenue / $totalVisitors, 2) : 0;
        
        return compact('conversionRate', 'avgBookingValue', 'revenuePerVisitor');
    }

    private function calculateBounceRate(array $dateRange): float
    {
        [$startDate, $endDate] = $dateRange;
        
        $totalSessions = VisitorSession::whereBetween('started_at', [$startDate, $endDate])->count();
        if ($totalSessions === 0) return 0;
        
        $bounceSessions = VisitorSession::whereBetween('started_at', [$startDate, $endDate])
            ->where('is_bounce', true)
            ->count();
        
        return round(($bounceSessions / $totalSessions) * 100, 1);
    }
}
