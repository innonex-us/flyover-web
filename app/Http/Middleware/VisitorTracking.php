<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Visitor;
use App\Models\VisitorSession;
use App\Models\VisitorPageView;
use App\Services\GeolocationService;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class VisitorTracking
{
    private GeolocationService $geolocationService;

    public function __construct(GeolocationService $geolocationService)
    {
        $this->geolocationService = $geolocationService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        // Skip tracking for admin routes, assets, and API calls
        if ($this->shouldSkipTracking($request)) {
            return $next($request);
        }

        // Check if user has consented to tracking
        if (!$this->hasTrackingConsent($request)) {
            return $next($request);
        }

        try {
            $this->trackVisitor($request);
        } catch (\Exception $e) {
            \Log::warning('Visitor tracking failed: ' . $e->getMessage());
        }

        return $next($request);
    }

    private function shouldSkipTracking(Request $request): bool
    {
        $skipPatterns = [
            'admin/*',
            'api/*',
            'telescope/*',
            'horizon/*',
            'sanctum/*',
            '_debugbar/*',
            'telescope-api/*'
        ];

        $path = $request->path();
        
        foreach ($skipPatterns as $pattern) {
            if (Str::is($pattern, $path)) {
                return true;
            }
        }

        // Skip if it's an asset file
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $assetExtensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'pdf', 'zip'];
        
        return in_array(strtolower($extension), $assetExtensions);
    }

    private function hasTrackingConsent(Request $request): bool
    {
        // Check if consent is required (GDPR)
        $requiresConsent = $this->geolocationService->requiresGDPRCompliance();
        
        if (!$requiresConsent) {
            return true; // Consent not required for non-EU visitors
        }

        // Check if user has given consent
        $consent = $request->cookie('tracking_consent');
        
        return $consent === 'accepted';
    }

    private function trackVisitor(Request $request): void
    {
        $agent = new Agent();
        $agent->setUserAgent($request->userAgent());
        
        // Get or create visitor
        $fingerprint = $this->generateFingerprint($request, $agent);
        $visitor = $this->getOrCreateVisitor($request, $agent, $fingerprint);
        
        // Get or create session
        $session = $this->getOrCreateSession($request, $visitor);
        
        // Track page view
        $this->trackPageView($request, $visitor, $session);
        
        // Store visitor and session IDs in cookies for client-side tracking
        Cookie::queue('visitor_id', $visitor->id, 60 * 24 * 365); // 1 year
        Cookie::queue('session_id', $session->id, 60 * 24 * 2); // 2 days
    }

    private function generateFingerprint(Request $request, Agent $agent): string
    {
        $components = [
            $request->ip(),
            $request->userAgent(),
            $request->header('Accept-Language'),
            $request->header('Accept-Encoding'),
            $agent->platform(),
            $agent->browser()
        ];

        return hash('sha256', implode('|', $components));
    }

    private function getOrCreateVisitor(Request $request, Agent $agent, string $fingerprint): Visitor
    {
        // Try to find existing visitor by fingerprint
        $visitor = Visitor::where('fingerprint', $fingerprint)->first();
        
        if (!$visitor) {
            // Try to find by IP and user agent combination
            $visitor = Visitor::where('ip_address', $request->ip())
                ->where('user_agent', $request->userAgent())
                ->first();
        }

        if (!$visitor) {
            // Create new visitor
            $location = $this->geolocationService->getLocationByIp($request->ip());
            
            $visitor = Visitor::create([
                'fingerprint' => $fingerprint,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'browser' => $agent->browser(),
                'browser_version' => $agent->version($agent->browser()),
                'platform' => $agent->platform(),
                'device_type' => $this->getDeviceType($agent),
                'is_mobile' => $agent->isMobile(),
                'is_tablet' => $agent->isTablet(),
                'is_desktop' => $agent->isDesktop(),
                'language' => $request->getPreferredLanguage(),
                'timezone' => $location['timezone'],
                'country' => $location['country'],
                'country_code' => $location['country_code'],
                'city' => $location['city'],
                'region' => $location['region'],
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude'],
                'isp' => $location['isp'],
                'organization' => $location['organization'],
                'connection_type' => $location['connection_type'],
                'cookies_enabled' => $request->hasCookie('test_cookie'),
                'javascript_enabled' => false, // Will be updated by client-side script
                'do_not_track' => $request->header('DNT') === '1',
                'consent_level' => $this->geolocationService->requiresGDPRCompliance() ? 'pending' : 'full',
                'first_visit_at' => now(),
                'last_visit_at' => now(),
                'total_visits' => 1,
                'total_page_views' => 0,
                'total_duration' => 0,
                'is_bot' => $agent->isRobot()
            ]);
        } else {
            // Update existing visitor
            $visitor->updateVisitStats();
        }

        return $visitor;
    }

    private function getOrCreateSession(Request $request, Visitor $visitor): VisitorSession
    {
        $sessionId = $request->cookie('session_id') ?? session()->getId();
        
        $session = VisitorSession::where('session_id', $sessionId)->first();
        
        if (!$session) {
            $session = VisitorSession::create([
                'visitor_id' => $visitor->id,
                'session_id' => $sessionId,
                'referrer' => $request->header('Referer'),
                'utm_source' => $request->get('utm_source'),
                'utm_medium' => $request->get('utm_medium'),
                'utm_campaign' => $request->get('utm_campaign'),
                'utm_term' => $request->get('utm_term'),
                'utm_content' => $request->get('utm_content'),
                'landing_page' => $request->fullUrl(),
                'started_at' => now(),
                'last_activity_at' => now(),
                'duration' => 0,
                'page_views' => 0,
                'interactions' => 0,
                'is_bounce' => true,
                'conversion_rate' => 0
            ]);
        } else {
            $session->updateActivity();
        }

        return $session;
    }

    private function trackPageView(Request $request, Visitor $visitor, VisitorSession $session): void
    {
        VisitorPageView::create([
            'visitor_id' => $visitor->id,
            'session_id' => $session->id,
            'url' => $request->fullUrl(),
            'title' => null, // Will be updated by client-side script
            'path' => $request->path(),
            'query_params' => $request->getQueryString(),
            'hash' => $request->getFragment(),
            'viewed_at' => now(),
            'time_on_page' => 0,
            'scroll_depth' => 0,
            'max_scroll_depth' => 0,
            'is_exit_page' => false,
            'interactions' => [],
            'performance_metrics' => []
        ]);

        $session->addPageView();
        $visitor->increment('total_page_views');
    }

    private function getDeviceType(Agent $agent): string
    {
        if ($agent->isMobile()) {
            return 'mobile';
        }
        
        if ($agent->isTablet()) {
            return 'tablet';
        }
        
        if ($agent->isRobot()) {
            return 'bot';
        }
        
        return 'desktop';
    }
}
