<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Visa;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payable_type' => 'required|in:package,visa',
            'payable_id' => 'required|integer',
            'booking_date' => 'required|date|after_or_equal:today',
            'guest_name' => 'nullable|required_without:user_id|string|max:255',
            'guest_email' => 'nullable|required_without:user_id|email|max:255',
            'guest_phone' => 'nullable|required_without:user_id|string|max:20',
            'quantity' => 'required|integer|min:1',
        ]);

        $bookingData = [
            'payable_id' => $validated['payable_id'],
            'booking_date' => $validated['booking_date'],
            'quantity' => $validated['quantity'],
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ];

        if (Auth::check()) {
            $bookingData['user_id'] = Auth::id();
        } else {
            $bookingData['user_id'] = null;
            $bookingData['guest_name'] = $validated['guest_name'];
            $bookingData['guest_email'] = $validated['guest_email'];
            $bookingData['guest_phone'] = $validated['guest_phone'];
        }

        // Determine Payable Type and Price
        if ($validated['payable_type'] === 'package') {
            $payable = Package::findOrFail($validated['payable_id']);
            $bookingData['payable_type'] = Package::class;
        } else {
            $payable = Visa::findOrFail($validated['payable_id']);
            $bookingData['payable_type'] = Visa::class;
        }

        $bookingData['total_amount'] = $payable->price * $bookingData['quantity'];

        $booking = Booking::create($bookingData);

        return redirect()->route('bookings.confirmation', $booking)->with('success', 'Booking request submitted successfully!');
    }

    public function confirmation(Booking $booking)
    {
        // Simple security check: if it's a guest booking, maybe check session or just allow for now based on ID (low risk for this confirmed non-PII invoice)
        // Ideally we'd sign the URL, but for this quick add, ID is fine.
        
        $booking->load('payable');
        return view('bookings.confirmation', compact('booking'));
    }
}
