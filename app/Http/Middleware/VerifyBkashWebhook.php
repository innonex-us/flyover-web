<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerifyBkashWebhook
{
    public function handle(Request $request, Closure $next)
    {
        // Check allowed IPs if configured
        $allowed = config('services.bkash.allowed_ips', []);

        if (!empty($allowed)) {
            $ip = $request->ip();

            if (!in_array($ip, $allowed, true)) {
                // log and reject
                report(new \RuntimeException("bkash webhook rejected from IP: {$ip}"));
                return response('Forbidden', 403);
            }
        }

        // Verify signature if secret configured
        $secret = config('services.bkash.webhook_secret');

        if (!empty($secret)) {
            $signature = $request->header('X-Bkash-Signature') ?: $request->header('X-BKASH-SIGNATURE') ?: $request->header('X-Signature');

            $payload = $request->getContent();

            if (empty($signature) || !hash_equals(hash_hmac('sha256', $payload, (string) $secret), (string) $signature)) {
                report(new \RuntimeException('bkash webhook signature verification failed.'));
                return response('Forbidden', 403);
            }
        }

        return $next($request);
    }
}
