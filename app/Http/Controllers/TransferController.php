<?php

namespace App\Http\Controllers;

use App\Models\TransferBooking;
use App\Models\TransferRoute;
use App\Models\User;
use App\Notifications\NewTransferBookingNotification;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Transfer | Pick & Drop Transfer Service';

        $routes = TransferRoute::active()
            ->when($request->filled('pickup') || $request->filled('drop'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    if ($request->filled('pickup')) {
                        $sub->orWhere('pickup_location', 'like', '%' . $request->pickup . '%');
                    }
                    if ($request->filled('drop')) {
                        $sub->orWhere('drop_location', 'like', '%' . $request->drop . '%');
                    }
                });
            })
            ->get();
        return view('transfers.index', compact('routes', 'title'));
    }

    public function store(Request $request)
    {
        $rules = [
            'passenger_count' => 'required|integer|min:1',
            'travel_date'     => 'required|date|after:today',
            'pickup_time'     => 'nullable|string',
            'special_request' => 'nullable|string',
        ];

        if (!auth()->check()) {
            $rules['guest_name']  = 'required|string|max:255';
            $rules['guest_email'] = 'required|email|max:255';
            $rules['guest_phone'] = 'required|string|max:20';
        }

        $isCustom = !$request->filled('route_id');

        if ($isCustom) {
            $rules['pickup_location'] = 'required|string|max:255';
            $rules['drop_location']   = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        $data = [
            'passenger_count' => $request->passenger_count,
            'travel_date'     => $request->travel_date,
            'pickup_time'     => $request->pickup_time,
            'special_request' => $request->special_request,
            'user_id'         => auth()->id(),
            'guest_name'      => auth()->check() ? null : $request->guest_name,
            'guest_email'     => auth()->check() ? null : $request->guest_email,
            'guest_phone'     => auth()->check() ? null : $request->guest_phone,
            'status'          => 'pending',
            'payment_status'  => 'unpaid',
            'payment_method'  => $isCustom ? null : 'bkash',
        ];

        if (!$isCustom) {
            $route = TransferRoute::findOrFail($request->route_id);
            $data['route_id']        = $route->id;
            $data['pickup_location'] = $route->pickup_location;
            $data['drop_location']   = $route->drop_location;
            $data['total_amount']    = $route->price_per_person * $request->passenger_count;
            $data['is_custom']       = false;
        } else {
            $data['route_id']        = null;
            $data['pickup_location'] = $request->pickup_location;
            $data['drop_location']   = $request->drop_location;
            $data['total_amount']    = 0;
            $data['is_custom']       = true;
        }

        $booking = TransferBooking::create($data);
        $booking->load('user');

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewTransferBookingNotification($booking));
        }

        if ((float) $booking->total_amount > 0) {
            $payment = $booking->payments()->create([
                'gateway' => 'bkash',
                'amount' => $booking->total_amount,
                'currency' => config('services.bkash.currency', 'BDT'),
                'status' => 'pending',
            ]);

            return redirect()->route('payments.show', $payment)->with('success', 'Booking submitted! Complete your payment below.');
        }

        return redirect()->route('transfers.confirmation', $booking)->with('success', 'Transfer booking submitted successfully!');
    }

    public function confirmation(TransferBooking $booking)
    {
        $booking->load('route');
        return view('transfers.confirmation', compact('booking'));
    }
}
