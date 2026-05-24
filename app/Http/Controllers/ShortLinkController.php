<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;

class ShortLinkController extends Controller
{
    public function redirect(string $code)
    {
        $link = ShortLink::where('code', $code)->firstOrFail();
        $link->increment('clicks');
        return redirect()->away($link->url);
    }
}
