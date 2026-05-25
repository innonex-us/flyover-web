<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\HotelBooking;
use App\Models\TransferBooking;
use App\Models\CustomizationRequest;
use App\Models\ContactMessage;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Bookings - Tour & Visa
        $tourBookings = Booking::where('user_id', $user->id)
            ->with('payable')
            ->latest()
            ->take(5)
            ->get();

        // Hotel Bookings
        $hotelBookings = HotelBooking::where('user_id', $user->id)
            ->with(['hotel', 'room'])
            ->latest()
            ->take(5)
            ->get();

        // Transfer/Pick & Drop Bookings
        $transferBookings = TransferBooking::where('user_id', $user->id)
            ->with('route')
            ->latest()
            ->take(5)
            ->get();

        // Customization Requests
        $customizationRequests = CustomizationRequest::where('email', $user->email)
            ->latest()
            ->take(5)
            ->get();

        // Contact Messages
        $contactMessages = ContactMessage::where('email', $user->email)
            ->latest()
            ->take(5)
            ->get();

        // Payment History
        $payments = Payment::whereHasMorph('payable', [
            Booking::class,
            HotelBooking::class,
            TransferBooking::class,
        ], function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->latest()
            ->take(5)
            ->get();

        // Stats
        $stats = [
            'total_tour_bookings' => Booking::where('user_id', $user->id)->count(),
            'total_hotel_bookings' => HotelBooking::where('user_id', $user->id)->count(),
            'total_transfer_bookings' => TransferBooking::where('user_id', $user->id)->count(),
            'total_spent' => Payment::whereHasMorph('payable', [
                Booking::class,
                HotelBooking::class,
                TransferBooking::class,
            ], function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_bookings' => Booking::where('user_id', $user->id)
                ->where('payment_status', '!=', 'paid')
                ->count()
                + HotelBooking::where('user_id', $user->id)
                    ->where('payment_status', '!=', 'paid')
                    ->count()
                + TransferBooking::where('user_id', $user->id)
                    ->where('payment_status', '!=', 'paid')
                    ->count(),
        ];

        // Notifications
        $notifications = $user->notifications()->latest()->take(5)->get();
        $unreadCount = $user->unreadNotifications()->count();

        return view('dashboard', compact(
            'user',
            'tourBookings',
            'hotelBookings',
            'transferBookings',
            'customizationRequests',
            'contactMessages',
            'payments',
            'stats',
            'notifications',
            'unreadCount'
        ));
    }
}
