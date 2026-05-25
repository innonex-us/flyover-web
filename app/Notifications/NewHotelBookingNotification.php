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
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        $customerName = $this->booking->user
            ? $this->booking->user->name
            : $this->booking->guest_name;

        $hotelName = $this->booking->room?->hotel?->name ?? 'Hotel';

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject("New Hotel Booking: {$hotelName} - " . config('app.name'))
            ->greeting("Hello Admin,")
            ->line("A new hotel booking has been received from **{$customerName}**.")
            ->line("Hotel: **{$hotelName}**")
            ->line("Nights: **{$this->booking->nights}**")
            ->line("Total Amount: ৳" . number_format($this->booking->total_amount))
            ->action('View Booking Details', route('admin.hotel-bookings.show', $this->booking))
            ->line('Please review and process the booking.');
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
