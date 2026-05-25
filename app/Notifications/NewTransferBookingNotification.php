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
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        $customerName = $this->booking->user
            ? $this->booking->user->name
            : $this->booking->guest_name;

        $route = $this->booking->pickup_location . ' → ' . $this->booking->drop_location;

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject("New Transfer Booking: " . config('app.name'))
            ->greeting("Hello Admin,")
            ->line("A new transfer booking has been received from **{$customerName}**.")
            ->line("Route: **{$route}**")
            ->line("Total Amount: ৳" . number_format($this->booking->total_amount))
            ->action('View Booking Details', route('admin.transfer-bookings.show', $this->booking))
            ->line('Please review and process the booking.');
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
