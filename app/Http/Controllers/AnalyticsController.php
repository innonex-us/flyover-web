<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\VisitorSession;
use App\Models\VisitorPageView;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AnalyticsController extends Controller
{
    public function pageView(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'visitor_id' => 'required|integer|exists:visitors,id',
            'session_id' => 'required|integer|exists:visitor_sessions,id',
            'url' => 'required|string',
            'title' => 'nullable|string',
            'path' => 'required|string',
            'query_params' => 'nullable|string',
            'hash' => 'nullable|string',
            'screen_resolution' => 'nullable|array',
            'viewport_size' => 'nullable|array',
            'timezone' => 'nullable|string',
            'language' => 'nullable|string',
            'referrer' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $pageView = VisitorPageView::create([
                'visitor_id' => $request->visitor_id,
                'session_id' => $request->session_id,
                'url' => $request->url,
                'title' => $request->title,
                'path' => $request->path,
                'query_params' => $request->query_params,
                'hash' => $request->hash,
                'viewed_at' => now(),
                'time_on_page' => 0,
                'scroll_depth' => 0,
                'max_scroll_depth' => 0,
                'is_exit_page' => false,
                'interactions' => [],
                'performance_metrics' => []
            ]);

            // Update visitor with client-side data
            $visitor = Visitor::find($request->visitor_id);
            if ($visitor) {
                $visitor->update([
                    'screen_resolution' => $request->screen_resolution,
                    'viewport_size' => $request->viewport_size,
                    'timezone' => $request->timezone,
                    'language' => $request->language,
                    'javascript_enabled' => true
                ]);
            }

            // Update session activity
            $session = VisitorSession::find($request->session_id);
            if ($session) {
                $session->updateActivity();
            }

            return response()->json([
                'success' => true,
                'page_view_id' => $pageView->id
            ]);
        } catch (\Exception $e) {
            \Log::error('Analytics page view tracking failed: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    public function event(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'visitor_id' => 'required|integer|exists:visitors,id',
            'session_id' => 'required|integer|exists:visitor_sessions,id',
            'page_view_id' => 'nullable|integer|exists:visitor_page_views,id',
            'event' => 'required|string|max:255',
            'data' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $session = VisitorSession::find($request->session_id);
            if ($session) {
                $session->addEvent($request->event, $request->data ?? []);
                $session->addInteraction();
            }

            // Handle specific event types
            switch ($request->event) {
                case 'conversion':
                    $this->handleConversion($request);
                    break;
                case 'form_submission':
                    $this->handleFormSubmission($request);
                    break;
                case 'download':
                    $this->handleDownload($request);
                    break;
                case 'video_play':
                    $this->handleVideoPlay($request);
                    break;
                case 'search':
                    $this->handleSearch($request);
                    break;
                case 'javascript_error':
                    $this->handleError($request);
                    break;
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Analytics event tracking failed: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    public function pageViewUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'page_view_id' => 'required|integer|exists:visitor_page_views,id',
            'time_on_page' => 'nullable|integer',
            'max_scroll_depth' => 'nullable|integer|min:0|max:100',
            'is_exit_page' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $pageView = VisitorPageView::find($request->page_view_id);
            if ($pageView) {
                $pageView->update([
                    'time_on_page' => $request->time_on_page ?? $pageView->time_on_page,
                    'max_scroll_depth' => $request->max_scroll_depth ?? $pageView->max_scroll_depth,
                    'is_exit_page' => $request->is_exit_page ?? $pageView->is_exit_page
                ]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Analytics page view update failed: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    public function visitorUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'visitor_id' => 'required|integer|exists:visitors,id',
            'screen_resolution' => 'nullable|array',
            'viewport_size' => 'nullable|array',
            'cookies_enabled' => 'nullable|boolean',
            'javascript_enabled' => 'nullable|boolean',
            'timezone' => 'nullable|string',
            'language' => 'nullable|string',
            'online' => 'nullable|boolean',
            'connection' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $visitor = Visitor::find($request->visitor_id);
            if ($visitor) {
                $updateData = $request->only([
                    'screen_resolution',
                    'viewport_size',
                    'cookies_enabled',
                    'javascript_enabled',
                    'timezone',
                    'language'
                ]);

                if ($request->has('connection')) {
                    $updateData['connection_type'] = $request->connection['effective_type'] ?? null;
                }

                $visitor->update($updateData);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Analytics visitor update failed: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    public function sessionActivity(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|integer|exists:visitor_sessions,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $session = VisitorSession::find($request->session_id);
            if ($session) {
                $session->updateActivity();
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Analytics session activity update failed: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    public function consent(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'visitor_id' => 'nullable|integer|exists:visitors,id',
            'consent_level' => 'required|in:none,essential,full'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            if ($request->visitor_id) {
                $visitor = Visitor::find($request->visitor_id);
                if ($visitor) {
                    $visitor->update(['consent_level' => $request->consent_level]);
                }
            }

            // Set consent cookie
            $cookieValue = $request->consent_level === 'full' ? 'accepted' : 'rejected';
            cookie()->queue('tracking_consent', $cookieValue, 60 * 24 * 365);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Analytics consent update failed: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    private function handleConversion(Request $request): void
    {
        $data = $request->data ?? [];
        $session = VisitorSession::find($request->session_id);
        
        if ($session) {
            // Update conversion rate
            $session->update(['conversion_rate' => 100]);
            
            // Add specific conversion event
            $session->addEvent('conversion', $data);
        }
    }

    private function handleFormSubmission(Request $request): void
    {
        $data = $request->data ?? [];
        $formName = $data['form_name'] ?? 'unknown';
        
        \Log::info('Form submission tracked', [
            'form' => $formName,
            'visitor_id' => $request->visitor_id,
            'session_id' => $request->session_id
        ]);
    }

    private function handleDownload(Request $request): void
    {
        $data = $request->data ?? [];
        $url = $data['url'] ?? 'unknown';
        
        \Log::info('Download tracked', [
            'url' => $url,
            'visitor_id' => $request->visitor_id,
            'session_id' => $request->session_id
        ]);
    }

    private function handleVideoPlay(Request $request): void
    {
        $data = $request->data ?? [];
        $title = $data['title'] ?? 'unknown';
        
        \Log::info('Video play tracked', [
            'title' => $title,
            'visitor_id' => $request->visitor_id,
            'session_id' => $request->session_id
        ]);
    }

    private function handleSearch(Request $request): void
    {
        $data = $request->data ?? [];
        $query = $data['query'] ?? 'unknown';
        
        \Log::info('Search tracked', [
            'query' => $query,
            'visitor_id' => $request->visitor_id,
            'session_id' => $request->session_id
        ]);
    }

    private function handleError(Request $request): void
    {
        $data = $request->data ?? [];
        
        \Log::warning('JavaScript error tracked', [
            'message' => $data['message'] ?? 'unknown',
            'filename' => $data['filename'] ?? 'unknown',
            'line' => $data['lineno'] ?? 'unknown',
            'visitor_id' => $request->visitor_id,
            'session_id' => $request->session_id
        ]);
    }
}
