<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransferRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransferRouteController extends Controller
{
    public function index()
    {
        $routes = TransferRoute::latest()->paginate(15);
        return view('admin.transfer-routes.index', compact('routes'));
    }

    public function create()
    {
        return view('admin.transfer-routes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'pickup_location'  => 'required|string|max:255',
            'drop_location'    => 'required|string|max:255',
            'price_per_person' => 'required|numeric|min:0',
            'description'      => 'nullable|string',
            'is_active'        => 'nullable|boolean',
            'thumbnail'        => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'pickup_location', 'drop_location', 'price_per_person', 'description']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('transfers', 'public');
        }

        TransferRoute::create($data);

        return redirect()->route('admin.transfer-routes.index')->with('success', 'Transfer route created successfully.');
    }

    public function edit(TransferRoute $transferRoute)
    {
        return view('admin.transfer-routes.edit', compact('transferRoute'));
    }

    public function update(Request $request, TransferRoute $transferRoute)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'pickup_location'  => 'required|string|max:255',
            'drop_location'    => 'required|string|max:255',
            'price_per_person' => 'required|numeric|min:0',
            'description'      => 'nullable|string',
            'is_active'        => 'nullable|boolean',
            'thumbnail'        => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'pickup_location', 'drop_location', 'price_per_person', 'description']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('thumbnail')) {
            if ($transferRoute->thumbnail) {
                Storage::disk('public')->delete($transferRoute->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('transfers', 'public');
        }

        $transferRoute->update($data);

        return redirect()->route('admin.transfer-routes.index')->with('success', 'Transfer route updated successfully.');
    }

    public function destroy(TransferRoute $transferRoute)
    {
        if ($transferRoute->thumbnail) {
            Storage::disk('public')->delete($transferRoute->thumbnail);
        }
        $transferRoute->delete();
        return redirect()->route('admin.transfer-routes.index')->with('success', 'Transfer route deleted successfully.');
    }
}
