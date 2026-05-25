<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Package;
use App\Models\TransferRoute;
use App\Models\Visa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function suggestions(Request $request)
    {
        $request->validate([
            'type' => 'required|in:tours,visas,hotels,transfers',
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
                ->get(['title', 'slug', 'location', 'thumbnail', 'price', 'duration_days'])
                ->map(function ($package) {
                    return [
                        'text' => $package->title,
                        'subtext' => $package->location . ($package->duration_days ? ' · ' . $package->duration_days . ' days' : ''),
                        'url' => route('packages.show', $package->slug),
                        'image' => $package->thumbnail ? (Str::startsWith($package->thumbnail, 'http') ? $package->thumbnail : Storage::disk('public')->url($package->thumbnail)) : 'https://via.placeholder.com/100x100?text=Tour',
                        'price' => $package->price,
                    ];
                });
        } elseif ($type === 'visas') {
            $suggestions = Visa::where('is_active', true)
                ->when($query, function ($q) use ($query) {
                    $q->where('country', 'like', "%{$query}%");
                })
                ->latest()
                ->limit(5)
                ->get(['country', 'slug', 'thumbnail', 'validity', 'maximum_stay', 'price'])
                ->map(function ($visa) {
                    return [
                        'text' => $visa->country,
                        'subtext' => 'Visa Service' . ($visa->validity ? ' · Valid ' . $visa->validity : ''),
                        'url' => route('visas.show', $visa->slug),
                        'image' => $visa->thumbnail ? (Str::startsWith($visa->thumbnail, 'http') ? $visa->thumbnail : Storage::disk('public')->url($visa->thumbnail)) : 'https://via.placeholder.com/100x100?text=Visa',
                        'price' => $visa->price,
                    ];
                });
        } elseif ($type === 'transfers') {
            $suggestions = TransferRoute::where('is_active', true)
                ->when($query, function ($q) use ($query) {
                    $q->where(function ($sub) use ($query) {
                        $sub->where('name', 'like', "%{$query}%")
                            ->orWhere('pickup_location', 'like', "%{$query}%")
                            ->orWhere('drop_location', 'like', "%{$query}%");
                    });
                })
                ->latest()
                ->limit(5)
                ->get(['id', 'name', 'pickup_location', 'drop_location', 'thumbnail', 'base_price'])
                ->map(function ($route) {
                    return [
                        'text'    => $route->name,
                        'subtext' => $route->pickup_location . ' → ' . $route->drop_location,
                        'url'     => route('transfers.index'),
                        'image'   => $route->thumbnail ? (Str::startsWith($route->thumbnail, 'http') ? $route->thumbnail : Storage::disk('public')->url($route->thumbnail)) : null,
                        'price'   => $route->base_price,
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
                ->get(['id', 'name', 'slug', 'location', 'thumbnail', 'price_per_night'])
                ->map(function ($hotel) {
                    return [
                        'text'    => $hotel->name,
                        'subtext' => $hotel->location,
                        'url'     => route('hotels.show', $hotel->slug),
                        'image'   => $hotel->thumbnail ? (Str::startsWith($hotel->thumbnail, 'http') ? $hotel->thumbnail : Storage::disk('public')->url($hotel->thumbnail)) : null,
                        'price'   => $hotel->price_per_night,
                    ];
                });
        }

        return response()->json($suggestions);
    }
}
