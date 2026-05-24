<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Package;
use App\Models\Visa;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function suggestions(Request $request)
    {
        $request->validate([
            'type' => 'required|in:tours,visas,hotels',
            'query' => 'nullable|string',
        ]);

        $query = $request->input('query');
        $type = $request->input('type');
        $suggestions = [];

        // if (!$query) {
        //     return response()->json([]);
        // }

        if ($type === 'tours') {
            $suggestions = Package::where('is_active', true)
                ->when($query, function ($q) use ($query) {
                    $q->where(function ($sub) use ($query) {
                        $sub->where('title', 'like', "%{$query}%")
                            ->orWhere('location', 'like', "%{$query}%");
                    });
                })
                ->latest()
                ->limit(5)
                ->get(['title', 'slug', 'location', 'thumbnail'])
                ->map(function ($package) {
                    return [
                        'text' => $package->title,
                        'subtext' => $package->location,
                        'url' => route('packages.show', $package->slug),
                        'image' => $package->thumbnail ? \Storage::url($package->thumbnail) : 'https://via.placeholder.com/100x100?text=Tour',
                    ];
                });
        } elseif ($type === 'visas') {
            $suggestions = Visa::where('is_active', true)
                ->when($query, function ($q) use ($query) {
                    $q->where('country', 'like', "%{$query}%");
                })
                ->latest()
                ->limit(5)
                ->get(['country', 'slug', 'thumbnail'])
                ->map(function ($visa) {
                    return [
                        'text' => $visa->country,
                        'subtext' => 'Visa Service',
                        'url' => route('visas.show', $visa->slug),
                        'image' => $visa->thumbnail ? \Storage::url($visa->thumbnail) : 'https://via.placeholder.com/100x100?text=Visa',
                    ];
                });
        } elseif ($type === 'hotels') {
            $suggestions = Hotel::where('is_active', true)
                ->when($query, function ($q) use ($query) {
                    $q->where(function ($sub) use ($query) {
                        $sub->where('name', 'like', "%{$query}%")
                            ->orWhere('location', 'like', "%{$query}%");
                    });
                })
                ->latest()
                ->limit(5)
                ->get(['id', 'name', 'slug', 'location', 'thumbnail'])
                ->map(function ($hotel) {
                    return [
                        'text'    => $hotel->name,
                        'subtext' => $hotel->location,
                        'url'     => route('hotels.show', $hotel->slug),
                        'image'   => $hotel->thumbnail ? \Storage::url($hotel->thumbnail) : null,
                    ];
                });
        }

        return response()->json($suggestions);
    }
}
