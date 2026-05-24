<?php

namespace App\Http\Controllers;

use App\Models\TransferBooking;
use App\Models\TransferRoute;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index()
    {
        $routes = TransferRoute::active()->get();
        return view('transfers.index', compact('routes'));
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

        return redirect()->route('transfers.confirmation', $booking)->with('success', 'Transfer booking submitted successfully!');
    }

    public function confirmation(TransferBooking $booking)
    {
        $booking->load('route');
        return view('transfers.confirmation', compact('booking'));
    }
}
