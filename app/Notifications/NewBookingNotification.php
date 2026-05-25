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
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        $payable = $this->booking->payable;
        $serviceName = $payable
            ? ($payable->title ?? ($payable->country . ' Visa'))
            : 'Unknown';

        $customerName = $this->booking->user
            ? $this->booking->user->name
            : $this->booking->guest_name;

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject("New Booking: {$serviceName} - " . config('app.name'))
            ->greeting("Hello Admin,")
            ->line("A new booking has been received from **{$customerName}**.")
            ->line("Service: **{$serviceName}**")
            ->line("Total Amount: ৳" . number_format($this->booking->total_amount))
            ->action('View Booking Details', route('admin.bookings.show', $this->booking))
            ->line('Please review and process the booking.');
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
