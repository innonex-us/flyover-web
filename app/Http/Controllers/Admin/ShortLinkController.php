<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Post;
use App\Models\ShortLink;
use App\Models\Visa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortLinkController extends Controller
{
    public function index()
    {
        $links = ShortLink::with('user')->latest()->paginate(15);
        return view('admin.short-links.index', compact('links'));
    }

    public function create()
    {
        $packages = Package::where('is_active', true)->get(['id', 'title', 'slug']);
        $visas = Visa::where('is_active', true)->get(['id', 'country', 'type', 'slug']);
        $posts = Post::where('is_published', true)->get(['id', 'title', 'slug']);
        return view('admin.short-links.create', compact('packages', 'visas', 'posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'url'   => 'required|url',
            'label' => 'nullable|string|max:255',
        ]);

        do {
            $code = Str::random(6);
        } while (ShortLink::where('code', $code)->exists());

        ShortLink::create([
            'code'    => $code,
            'url'     => $request->url,
            'label'   => $request->label,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('admin.short-links.index')->with('success', 'Short link created successfully.');
    }

    public function destroy(ShortLink $shortLink)
    {
        $shortLink->delete();
        return redirect()->back()->with('success', 'Short link deleted successfully.');
    }
}
