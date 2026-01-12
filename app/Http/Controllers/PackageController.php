<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::where('is_active', true);

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
        }

        $packages = $query->paginate(12);
        return view('packages.index', compact('packages'));
    }

    public function show($slug)
    {
        $package = Package::where('slug', $slug)->where('is_active', true)->firstOrFail();

        // SEO Data
        $title = $package->title . ' - Tour Package | FlyoverBD';
        $meta_description = \Illuminate\Support\Str::limit(strip_tags($package->description), 155);
        $meta_image = $package->thumbnail 
            ? (\Illuminate\Support\Str::startsWith($package->thumbnail, 'http') ? $package->thumbnail : \Illuminate\Support\Facades\Storage::url($package->thumbnail))
            : asset('logo.png');

        $relatedPackages = Package::where('is_active', true)
            ->where('id', '!=', $package->id)
            ->where('location', 'like', '%' . $package->location . '%')
            ->limit(4)
            ->get();

        // If not enough related packages from same location, get some others
        if ($relatedPackages->count() < 4) {
            $extraPackages = Package::where('is_active', true)
                ->where('id', '!=', $package->id)
                ->whereNotIn('id', $relatedPackages->pluck('id'))
                ->limit(4 - $relatedPackages->count())
                ->get();
            $relatedPackages = $relatedPackages->concat($extraPackages);
        }

        return view('packages.show', compact('package', 'title', 'meta_description', 'meta_image', 'relatedPackages'));
    }

    public function customize(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'nullable|string|max:2000',
            'adults' => 'nullable|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'hotel_type' => 'nullable|string',
            'travel_date' => 'nullable|date',
            'destinations' => 'nullable|array',
        ]);

        \App\Models\CustomizationRequest::create([
            'package_id' => $package->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message ?? 'Customized Package Request',
            'details' => [
                'adults' => $request->adults,
                'children' => $request->children,
                'infants' => $request->infants,
                'hotel_type' => $request->hotel_type,
                'travel_date' => $request->travel_date,
                'destinations' => $request->destinations,
            ],
        ]);

        return back()->with('success', 'Your customization request has been sent! We will contact you shortly.');
    }
}
