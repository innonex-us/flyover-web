<?php

namespace App\Notifications;

use App\Models\HotelBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewHotelBookingNotification extends Notification
{
    use Queueable;

    public function __construct(public HotelBooking $booking) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $customerName = $this->booking->user
            ? $this->booking->user->name
            : $this->booking->guest_name;

        $hotelName = $this->booking->room?->hotel?->name ?? 'Hotel';

        return [
            'type'       => 'hotel',
            'icon'       => 'building',
            'color'      => 'green',
            'title'      => 'New Hotel Booking',
            'message'    => "{$customerName} booked {$hotelName} ({$this->booking->nights} nights)",
            'amount'     => $this->booking->total_amount,
            'url'        => route('admin.hotel-bookings.show', $this->booking),
            'booking_id' => $this->booking->id,
        ];
    }
}
