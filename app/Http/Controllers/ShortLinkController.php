<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortLinkController extends Controller
{
    public function redirect(string $code)
    {
        $link = ShortLink::where('code', $code)->firstOrFail();
        $link->increment('clicks');
        return redirect()->away($link->url);
    }

    /**
     * Generate or retrieve short link for a URL (API endpoint)
     */
    public function generate(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $url = $request->url;

        // Check if short link already exists for this URL
        $existing = ShortLink::where('url', $url)->first();
        if ($existing) {
            return response()->json([
                'short_url' => config('app.url') . '/s/' . $existing->code,
                'code' => $existing->code,
            ]);
        }

        // Generate unique code
        do {
            $code = Str::random(6);
        } while (ShortLink::where('code', $code)->exists());

        $link = ShortLink::create([
            'code' => $code,
            'url' => $url,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'short_url' => config('app.url') . '/s/' . $link->code,
            'code' => $link->code,
        ]);
    }
}
