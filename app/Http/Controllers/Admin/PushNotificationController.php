<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushNotificationController extends Controller
{
    private function webPush(): WebPush
    {
        return new WebPush([
            'VAPID' => [
                'subject'    => config('app.url'),
                'publicKey'  => config('services.vapid.public_key'),
                'privateKey' => config('services.vapid.private_key'),
            ],
        ]);
    }

    private function buildPayload(string $title, string $body, ?string $url = null, ?string $icon = null): string
    {
        return json_encode([
            'title' => $title,
            'body'  => $body,
            'url'   => $url ?? config('app.url'),
            'icon'  => $icon ?? asset('logo.png'),
        ]);
    }

    private function deliverToSubscriptions($subscriptions, string $payload): array
    {
        $push    = $this->webPush();
        $sent    = 0;
        $failed  = 0;
        $expired = [];

        foreach ($subscriptions as $sub) {
            $push->queueNotification(
                Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'keys' => [
                        'p256dh' => $sub->public_key,
                        'auth'   => $sub->auth_token,
                    ],
                ]),
                $payload
            );
        }

        foreach ($push->flush() as $report) {
            if ($report->isSuccess()) {
                $sent++;
                continue;
            }

            $failed++;

            if ($report->isSubscriptionExpired()) {
                $endpoint = $report->getRequest()->getUri()->__toString();
                $expired[] = hash('sha256', $endpoint);
            }
        }

        if (!empty($expired)) {
            foreach (PushSubscription::all()->filter(function ($subscription) use ($expired) {
                return in_array($subscription->endpoint_hash, $expired, true);
            }) as $subscription) {
                PushSubscription::destroy($subscription->id);
            }
        }

        return compact('sent', 'failed');
    }

    public function index()
    {
        $subscriptions = PushSubscription::all()->sortByDesc('created_at')->values();

        return view('admin.push-notifications.index', [
            'total' => $subscriptions->count(),
            'subscriptions' => $subscriptions,
        ]);
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:80',
            'body'    => 'required|string|max:200',
            'url'     => 'nullable|url|max:500',
            'icon'    => 'nullable|url|max:500',
        ]);

        $payload = $this->buildPayload(
            $validated['title'],
            $validated['body'],
            $validated['url'] ?? null,
            $validated['icon'] ?? null,
        );

        $subscriptions = PushSubscription::all();
        if ($subscriptions->isEmpty()) {
            return back()->with('error', 'No push subscribers found.');
        }

        ['sent' => $sent, 'failed' => $failed] = $this->deliverToSubscriptions($subscriptions, $payload);

        return back()->with('success', "Sent to {$sent} subscriber(s). Failed: {$failed}.");
    }

    public function test(Request $request, string $endpointHash)
    {
        $subscription = PushSubscription::all()->firstWhere('endpoint_hash', $endpointHash);

        if (! $subscription) {
            abort(404);
        }

        $payload = $this->buildPayload(
            'Test Push Notification',
            'This is a test notification from the admin panel.',
            route('home'),
            asset('logo.png')
        );

        ['sent' => $sent, 'failed' => $failed] = $this->deliverToSubscriptions(collect([$subscription]), $payload);

        if ($sent > 0) {
            return back()->with('success', 'Test push sent successfully to ' . ($subscription->user_agent ?? 'the selected subscriber') . '.');
        }

        return back()->with('error', 'Test push failed for the selected subscriber.');
    }
}
