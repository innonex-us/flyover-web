<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\HotelRoom;
use App\Models\User;
use App\Notifications\NewHotelBookingNotification;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $query = Hotel::active()->with(['rooms' => fn($q) => $q->where('is_active', true)]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $hotels = $query->latest()->paginate(12)->withQueryString();
        return view('hotels.index', compact('hotels'));
    }

    public function show(Hotel $hotel)
    {
        $hotel->load(['rooms' => function ($q) {
            $q->where('is_active', true);
        }]);
        return view('hotels.show', compact('hotel'));
    }

    public function book(Request $request)
    {
        $rules = [
            'room_id'         => 'required|exists:hotel_rooms,id',
            'check_in'        => 'required|date|after:today',
            'check_out'       => 'required|date|after:check_in',
            'guests'          => 'required|integer|min:1',
            'special_request' => 'nullable|string',
        ];

        if (!auth()->check()) {
            $rules['guest_name']  = 'required|string|max:255';
            $rules['guest_email'] = 'required|email|max:255';
            $rules['guest_phone'] = 'required|string|max:20';
        }

        $request->validate($rules);

        $room   = HotelRoom::findOrFail($request->room_id);
        $checkIn  = \Carbon\Carbon::parse($request->check_in);
        $checkOut = \Carbon\Carbon::parse($request->check_out);
        $nights   = $checkIn->diffInDays($checkOut);
        $total    = $room->price_per_night * $nights;

        $booking = HotelBooking::create([
            'room_id'         => $room->id,
            'check_in'        => $request->check_in,
            'check_out'       => $request->check_out,
            'nights'          => $nights,
            'guests'          => $request->guests,
            'total_amount'    => $total,
            'user_id'         => auth()->id(),
            'guest_name'      => auth()->check() ? null : $request->guest_name,
            'guest_email'     => auth()->check() ? null : $request->guest_email,
            'guest_phone'     => auth()->check() ? null : $request->guest_phone,
            'special_request' => $request->special_request,
            'status'          => 'pending',
            'payment_status'  => 'unpaid',
            'payment_method'  => 'bkash',
        ]);

        $booking->load('room.hotel', 'user');

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewHotelBookingNotification($booking));
        }

        $payment = $booking->payments()->create([
            'gateway' => 'bkash',
            'amount' => $booking->total_amount,
            'currency' => config('services.bkash.currency', 'BDT'),
            'status' => 'pending',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Booking confirmed! Redirecting to payment...',
                'redirect_url' => route('payments.bkash.start', $payment),
            ]);
        }

        return redirect()->route('payments.bkash.start', $payment)->with('success', 'Booking submitted successfully!');
    }

    public function confirmation(HotelBooking $booking)
    {
        $booking->load(['room.hotel', 'user']);
        return view('hotels.confirmation', compact('booking'));
    }
}
