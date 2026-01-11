<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = Package::latest()->paginate(10);
        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'start_date' => 'nullable|date',
            'is_active' => 'boolean',
            'inclusions' => 'nullable|array',
            'exclusions' => 'nullable|array',
            'requirements' => 'nullable|string',
            'policy' => 'nullable|string',
            'itinerary' => 'nullable|array',
            'itinerary.*.day' => 'nullable|integer',
            'itinerary.*.title' => 'nullable|string',
            'itinerary.*.activities' => 'nullable|array',
            'itinerary.*.activities.*' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('packages/thumbnails', 'public');
        }

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('packages/gallery', 'public');
            }
        }
        $validated['images'] = $images;

        // Clean up array inputs which might contain empty strings if dynamically added
        $validated['inclusions'] = array_filter($validated['inclusions'] ?? [], fn($value) => !is_null($value) && $value !== '');
        $validated['exclusions'] = array_filter($validated['exclusions'] ?? [], fn($value) => !is_null($value) && $value !== '');

        // Sort itinerary by day and filter activities
        if (isset($validated['itinerary']) && is_array($validated['itinerary'])) {
            $validated['itinerary'] = array_values($validated['itinerary']); // Reindex
            foreach ($validated['itinerary'] as &$day) {
                $day['activities'] = array_filter($day['activities'] ?? [], fn($value) => !is_null($value) && $value !== '');
                $day['activities'] = array_values($day['activities']); // Reindex
            }
            usort($validated['itinerary'], fn($a, $b) => ($a['day'] ?? 0) <=> ($b['day'] ?? 0));
        }

        Package::create($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Package created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'start_date' => 'nullable|date',
            'is_active' => 'boolean',
            'inclusions' => 'nullable|array',
            'exclusions' => 'nullable|array',
            'requirements' => 'nullable|string',
            'policy' => 'nullable|string',
            'itinerary' => 'nullable|array',
            'itinerary.*.day' => 'nullable|integer',
            'itinerary.*.title' => 'nullable|string',
            'itinerary.*.activities' => 'nullable|array',
            'itinerary.*.activities.*' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($package->thumbnail) {
                Storage::disk('public')->delete($package->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('packages/thumbnails', 'public');
        }

        if ($request->hasFile('images')) {
             // Append new images to existing ones
            $currentImages = $package->images ?? [];
            $newImages = [];
            foreach ($request->file('images') as $image) {
                $newImages[] = $image->store('packages/gallery', 'public');
            }
            $validated['images'] = array_merge($currentImages, $newImages);
        }

        // Clean up array inputs
        $validated['inclusions'] = array_filter($validated['inclusions'] ?? [], fn($value) => !is_null($value) && $value !== '');
        $validated['exclusions'] = array_filter($validated['exclusions'] ?? [], fn($value) => !is_null($value) && $value !== '');

        // Sort itinerary by day
        if (isset($validated['itinerary']) && is_array($validated['itinerary'])) {
            $validated['itinerary'] = array_values($validated['itinerary']); // Reindex
            usort($validated['itinerary'], fn($a, $b) => $a['day'] <=> $b['day']);
        }
        
        // Handle checkbox logic for is_active (if unchecked, it won't be in request)
        $validated['is_active'] = $request->boolean('is_active');

        $package->update($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        if ($package->thumbnail) {
            Storage::disk('public')->delete($package->thumbnail);
        }
        
        if ($package->images) {
            foreach ($package->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $package->delete();

        return redirect()->route('admin.packages.index')->with('success', 'Package deleted successfully.');
    }
}
