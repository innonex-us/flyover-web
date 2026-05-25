<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingStatusUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $bookingType,
        public int    $bookingId,
        public string $serviceName,
        public string $status,
        public string $url,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusLabels = [
            'confirmed'  => 'Your booking has been confirmed!',
            'cancelled'  => 'Your booking was cancelled.',
            'completed'  => 'Your booking is marked as completed.',
            'pending'    => 'Your booking is pending review.',
        ];

        $color = match($this->status) {
            'confirmed' => 'green',
            'cancelled' => 'red',
            'completed' => 'blue',
            default     => 'yellow',
        };

        return [
            'type'         => 'status_update',
            'icon'         => 'calendar',
            'color'        => $color,
            'title'        => 'Booking Update: ' . ucfirst($this->status),
            'message'      => ($statusLabels[$this->status] ?? 'Booking status changed.') . ' - ' . $this->serviceName,
            'url'          => $this->url,
            'booking_type' => $this->bookingType,
            'booking_id'   => $this->bookingId,
        ];
    }
}
