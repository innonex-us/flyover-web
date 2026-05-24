<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelBooking;
use Illuminate\Http\Request;

class HotelBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = HotelBooking::with(['room.hotel', 'user'])->latest();

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
        return view('admin.hotel-bookings.index', compact('bookings'));
    }

    public function show(HotelBooking $hotelBooking)
    {
        $hotelBooking->load(['room.hotel', 'user']);
        return view('admin.hotel-bookings.show', compact('hotelBooking'));
    }

    public function update(Request $request, HotelBooking $hotelBooking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $hotelBooking->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Booking status updated successfully.');
    }

    public function destroy(HotelBooking $hotelBooking)
    {
        $hotelBooking->delete();
        return redirect()->route('admin.hotel-bookings.index')->with('success', 'Booking deleted successfully.');
    }
}
