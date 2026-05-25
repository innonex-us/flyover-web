<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransferBooking;
use App\Notifications\BookingStatusUpdatedNotification;
use Illuminate\Http\Request;

class TransferBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = TransferBooking::with(['route', 'user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('guest_name', 'like', "%{$search}%")
                  ->orWhere('guest_email', 'like', "%{$search}%")
                  ->orWhere('guest_phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->paginate(15)->withQueryString();
        return view('admin.transfer-bookings.index', compact('bookings'));
    }

    public function show(TransferBooking $transferBooking)
    {
        $transferBooking->load(['route', 'user']);
        return view('admin.transfer-bookings.show', compact('transferBooking'));
    }

    public function update(Request $request, TransferBooking $transferBooking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $oldStatus = $transferBooking->status;
        $transferBooking->update(['status' => $request->status]);

        if ($oldStatus !== $transferBooking->status) {
            $transferBooking->load('user');
            $route = $transferBooking->pickup_location . ' → ' . $transferBooking->drop_location;
            $notification = new BookingStatusUpdatedNotification(
                bookingType: 'transfer',
                bookingId:   $transferBooking->id,
                serviceName: $route,
                status:      $transferBooking->status,
                url:         route('transfers.confirmation', $transferBooking),
            );

            if ($transferBooking->user) {
                $transferBooking->user->notify($notification);
            } elseif ($transferBooking->guest_email) {
                \Illuminate\Support\Facades\Notification::route('mail', $transferBooking->guest_email)
                    ->notify($notification);
            }
        }

        return redirect()->back()->with('success', 'Booking status updated successfully.');
    }

    public function destroy(TransferBooking $transferBooking)
    {
        $transferBooking->delete();
        return redirect()->route('admin.transfer-bookings.index')->with('success', 'Booking deleted successfully.');
    }
}
