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
            'thumbnail'   => 'nullable|image|max:4096',
            'gallery.*'   => 'nullable|image|max:4096',
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

        if ($request->hasFile('gallery')) {
            $data['gallery'] = collect($request->file('gallery'))
                ->map(fn ($file) => $file->store('hotels/gallery', 'public'))
                ->values()
                ->all();
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
            'name'           => 'required|string|max:255',
            'location'       => 'required|string|max:255',
            'description'    => 'nullable|string',
            'thumbnail'      => 'nullable|image|max:4096',
            'gallery.*'      => 'nullable|image|max:4096',
            'remove_gallery' => 'nullable|array',
            'remove_gallery.*' => 'nullable|string',
            'star_rating'    => 'required|numeric|min:1|max:5',
            'amenities'      => 'nullable|string',
            'is_active'      => 'nullable|boolean',
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

        $existingGallery = $hotel->gallery ?? [];

        if ($request->filled('remove_gallery')) {
            foreach ($request->remove_gallery as $path) {
                Storage::disk('public')->delete($path);
            }
            $existingGallery = array_values(array_diff($existingGallery, $request->remove_gallery));
        }

        if ($request->hasFile('gallery')) {
            $newImages = collect($request->file('gallery'))
                ->map(fn ($file) => $file->store('hotels/gallery', 'public'))
                ->values()
                ->all();
            $existingGallery = array_merge($existingGallery, $newImages);
        }

        $data['gallery'] = $existingGallery ?: null;

        $hotel->update($data);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel updated successfully.');
    }

    public function destroy(Hotel $hotel)
    {
        if ($hotel->thumbnail) {
            Storage::disk('public')->delete($hotel->thumbnail);
        }
        if ($hotel->gallery) {
            Storage::disk('public')->delete($hotel->gallery);
        }
        $hotel->delete();
        return redirect()->route('admin.hotels.index')->with('success', 'Hotel deleted successfully.');
    }
}
