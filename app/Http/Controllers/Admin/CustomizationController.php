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

    public function show(CustomizationRequest $customization)
    {
        $customization->load('package');
        return view('admin.customizations.show', compact('customization'));
    }

    public function update(Request $request, CustomizationRequest $customization)
    {
        $oldStatus = $customization->status;
        $customization->update([
            'status' => $request->status,
        ]);

        if ($oldStatus !== $customization->status && $customization->email) {
            $serviceName = $customization->package?->title ?? 'Custom Trip Request';
            
            \Illuminate\Support\Facades\Notification::route('mail', $customization->email)
                ->notify(new \App\Notifications\BookingStatusUpdatedNotification(
                    bookingType: 'customization',
                    bookingId:   $customization->id,
                    serviceName: $serviceName,
                    status:      $customization->status,
                    url:         url('/'), // No public confirmation page for customization yet
                ));
        }

        return back()->with('success', 'Request status updated successfully.');
    }
}
