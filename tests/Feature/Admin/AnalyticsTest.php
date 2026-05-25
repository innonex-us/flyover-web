<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Visitor;
use App\Models\VisitorSession;
use App\Models\VisitorPageView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_analytics_dashboard()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create some sample data to trigger resolvePageTitle
        $visitor = Visitor::create([
            'fingerprint' => 'test-fingerprint',
            'ip_address' => '127.0.0.1',
        ]);

        $session = VisitorSession::create([
            'visitor_id' => $visitor->id,
            'session_id' => 'test-session',
            'started_at' => now(),
            'last_activity_at' => now(),
        ]);

        VisitorPageView::create([
            'visitor_id' => $visitor->id,
            'session_id' => $session->id,
            'path' => '/tours/test-package',
            'url' => 'http://localhost/tours/test-package',
            'viewed_at' => now(),
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.analytics.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.analytics.index');
    }

    public function test_resolve_page_title_handles_special_paths()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // This will trigger the preg_match logic in resolvePageTitle
        $visitor = Visitor::create([
            'fingerprint' => 'test-fingerprint-2',
            'ip_address' => '127.0.0.1',
        ]);

        $session = VisitorSession::create([
            'visitor_id' => $visitor->id,
            'session_id' => 'test-session-2',
            'started_at' => now(),
            'last_activity_at' => now(),
        ]);

        // Create a page view with a path that matches the tours regex
        VisitorPageView::create([
            'visitor_id' => $visitor->id,
            'session_id' => $session->id,
            'path' => '/tours/test-package',
            'url' => 'http://localhost/tours/test-package',
            'viewed_at' => now(),
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.analytics.index'));

        $response->assertStatus(200);
    }
}
