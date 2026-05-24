<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $payable = $this->booking->payable;
        $serviceName = $payable
            ? ($payable->title ?? ($payable->country . ' Visa'))
            : 'Unknown';

        $customerName = $this->booking->user
            ? $this->booking->user->name
            : $this->booking->guest_name;

        return [
            'type'     => 'booking',
            'icon'     => 'calendar',
            'color'    => 'blue',
            'title'    => 'New Booking',
            'message'  => "{$customerName} booked {$serviceName}",
            'amount'   => $this->booking->total_amount,
            'url'      => route('admin.bookings.show', $this->booking),
            'booking_id' => $this->booking->id,
        ];
    }
}
