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
                'publicKey'  => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ]);
    }

    public function index()
    {
        $total = PushSubscription::count();
        return view('admin.push-notifications.index', compact('total'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:80',
            'body'    => 'required|string|max:200',
            'url'     => 'nullable|url|max:500',
            'icon'    => 'nullable|url|max:500',
        ]);

        $payload = json_encode([
            'title' => $validated['title'],
            'body'  => $validated['body'],
            'url'   => $validated['url'] ?? config('app.url'),
            'icon'  => $validated['icon'] ?? asset('images/logo.png'),
        ]);

        $subscriptions = PushSubscription::all();
        if ($subscriptions->isEmpty()) {
            return back()->with('error', 'No push subscribers found.');
        }

        $push    = $this->webPush();
        $sent    = 0;
        $failed  = 0;
        $expired = [];

        foreach ($subscriptions as $sub) {
            $push->queueNotification(
                Subscription::create([
                    'endpoint'        => $sub->endpoint,
                    'keys'            => [
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
            } else {
                $failed++;
                // Remove expired/invalid subscriptions
                if ($report->isSubscriptionExpired()) {
                    $endpoint = $report->getRequest()->getUri()->__toString();
                    $expired[] = hash('sha256', $endpoint);
                }
            }
        }

        if (!empty($expired)) {
            PushSubscription::whereIn('endpoint_hash', $expired)->delete();
        }

        return back()->with('success', "Sent to {$sent} subscriber(s). Failed: {$failed}.");
    }
}
