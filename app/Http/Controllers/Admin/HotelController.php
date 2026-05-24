<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::withCount('rooms')->latest()->paginate(15);
        return view('admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        return view('admin.hotels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'location'    => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail'   => 'nullable|image|max:2048',
            'star_rating' => 'required|numeric|min:1|max:5',
            'amenities'   => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        $data = $request->only(['name', 'location', 'description', 'star_rating']);
        $data['slug']      = Str::slug($request->name) . '-' . Str::random(4);
        $data['is_active'] = $request->boolean('is_active');
        $data['amenities'] = $request->filled('amenities')
            ? array_filter(array_map('trim', explode(',', $request->amenities)))
            : null;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('hotels', 'public');
        }

        Hotel::create($data);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel created successfully.');
    }

    public function edit(Hotel $hotel)
    {
        return view('admin.hotels.edit', compact('hotel'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'location'    => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail'   => 'nullable|image|max:2048',
            'star_rating' => 'required|numeric|min:1|max:5',
            'amenities'   => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        $data = $request->only(['name', 'location', 'description', 'star_rating']);
        $data['is_active'] = $request->boolean('is_active');
        $data['amenities'] = $request->filled('amenities')
            ? array_filter(array_map('trim', explode(',', $request->amenities)))
            : null;

        if ($request->hasFile('thumbnail')) {
            if ($hotel->thumbnail) {
                Storage::disk('public')->delete($hotel->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('hotels', 'public');
        }

        $hotel->update($data);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel updated successfully.');
    }

    public function destroy(Hotel $hotel)
    {
        if ($hotel->thumbnail) {
            Storage::disk('public')->delete($hotel->thumbnail);
        }
        $hotel->delete();
        return redirect()->route('admin.hotels.index')->with('success', 'Hotel deleted successfully.');
    }
}
