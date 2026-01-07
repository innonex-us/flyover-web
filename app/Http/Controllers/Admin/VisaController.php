<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class VisaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $visas = Visa::latest()->paginate(10);
        return view('admin.visas.index', compact('visas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.visas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'country' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'processing_time' => 'required|string|max:255',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'required|string',
            'entry_type' => 'nullable|string',
            'validity_info' => 'nullable|string',
            'requirements' => 'nullable|string',
            'fees' => 'nullable|string',
            'important_notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'required_documents' => 'nullable|array',
        ]);

        $validated['slug'] = Str::slug($validated['country'] . '-' . $validated['type']);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('visas/thumbnails', 'public');
        }

        // Filter empty documents
        $validated['required_documents'] = array_filter($validated['required_documents'] ?? [], fn($value) => !is_null($value) && $value !== '');

        Visa::create($validated);

        return redirect()->route('admin.visas.index')->with('success', 'Visa created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visa $visa)
    {
        return view('admin.visas.edit', compact('visa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Visa $visa)
    {
        $validated = $request->validate([
            'country' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'processing_time' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'required|string',
             'entry_type' => 'nullable|string',
            'validity_info' => 'nullable|string',
            'requirements' => 'nullable|string',
            'fees' => 'nullable|string',
            'important_notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'required_documents' => 'nullable|array',
        ]);

        $validated['slug'] = Str::slug($validated['country'] . '-' . $validated['type']);

        if ($request->hasFile('thumbnail')) {
            if ($visa->thumbnail) {
                Storage::disk('public')->delete($visa->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('visas/thumbnails', 'public');
        }

        // Filter empty documents
        $validated['required_documents'] = array_filter($validated['required_documents'] ?? [], fn($value) => !is_null($value) && $value !== '');

        $visa->update($validated);

        return redirect()->route('admin.visas.index')->with('success', 'Visa updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visa $visa)
    {
        if ($visa->thumbnail) {
            Storage::disk('public')->delete($visa->thumbnail);
        }

        $visa->delete();

        return redirect()->route('admin.visas.index')->with('success', 'Visa deleted successfully.');
    }
}
