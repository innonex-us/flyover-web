<?php

namespace App\Notifications;

use App\Models\TransferBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewTransferBookingNotification extends Notification
{
    use Queueable;

    public function __construct(public TransferBooking $booking) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $customerName = $this->booking->user
            ? $this->booking->user->name
            : $this->booking->guest_name;

        $route = $this->booking->pickup_location . ' → ' . $this->booking->drop_location;

        return [
            'type'       => 'transfer',
            'icon'       => 'car',
            'color'      => 'purple',
            'title'      => 'New Transfer Booking',
            'message'    => "{$customerName} booked a transfer: {$route}",
            'amount'     => $this->booking->total_amount,
            'url'        => route('admin.transfer-bookings.show', $this->booking),
            'booking_id' => $this->booking->id,
        ];
    }
}
