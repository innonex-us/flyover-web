<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomizationRequest;
use Illuminate\Http\Request;

class CustomizationController extends Controller
{
    public function index()
    {
        $requests = CustomizationRequest::with('package')->latest()->paginate(10);
        return view('admin.customizations.index', compact('requests'));
    }

    public function update(Request $request, CustomizationRequest $customization)
    {
        $customization->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Request status updated successfully.');
    }
}
