<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'endpoint'   => 'required|string',
            'public_key' => 'required|string',
            'auth_token' => 'required|string',
        ]);

        PushSubscription::findOrCreateByEndpoint(
            $validated['endpoint'],
            $validated['public_key'],
            $validated['auth_token'],
            $request->userAgent()
        );

        return response()->json(['ok' => true]);
    }

    public function destroy(Request $request)
    {
        $endpoint = $request->input('endpoint');
        if ($endpoint) {
            PushSubscription::where('endpoint_hash', hash('sha256', $endpoint))->delete();
        }
        return response()->json(['ok' => true]);
    }
}
