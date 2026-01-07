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
        return view('packages.show', compact('package'));
    }

    public function customize(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        // In a real app, we would send an email or save this to the database.
        // For now, just flash a success message.

        return back()->with('success', 'Your customization request has been sent! We will contact you shortly.');
    }
}
