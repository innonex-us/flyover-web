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
        if ($notifiable instanceof \Illuminate\Notifications\AnonymousNotifiable) {
            return ['mail'];
        }

        $channels = ['database'];
        if ($notifiable->email) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        $statusLabel = match($this->status) {
            'confirmed' => 'Confirmed',
            'cancelled' => 'Cancelled',
            'completed' => 'Completed',
            'pending'   => 'Pending Review',
            'contacted' => 'Contacted',
            'closed'    => 'Closed',
            default     => ucfirst($this->status),
        };

        $subject = ($this->bookingType === 'customization' ? "Update on Your Request: " : "Booking Update: ") . "{$statusLabel}";

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject("{$subject} - " . config('app.name'))
            ->greeting("Hello " . ($notifiable->name ?? 'Guest') . ",")
            ->line("The status of your " . ($this->bookingType === 'customization' ? "request" : "booking") . " for **{$this->serviceName}** has been updated to **{$statusLabel}**.")
            ->action('View Details', $this->url)
            ->line('Thank you for choosing ' . config('app.name') . '!');
    }

    public function toArray(object $notifiable): array
    {
        $statusLabels = [
            'confirmed'  => 'Your booking has been confirmed!',
            'cancelled'  => 'Your booking was cancelled.',
            'completed'  => 'Your booking is marked as completed.',
            'pending'    => 'Your booking is pending review.',
            'contacted'  => 'We have contacted you regarding your request.',
            'closed'     => 'Your request has been closed.',
        ];

        $color = match($this->status) {
            'confirmed' => 'green',
            'contacted' => 'blue',
            'cancelled' => 'red',
            'completed' => 'blue',
            'closed'    => 'gray',
            default     => 'yellow',
        };

        return [
            'type'         => 'status_update',
            'icon'         => $this->bookingType === 'customization' ? 'document' : 'calendar',
            'color'        => $color,
            'title'        => ($this->bookingType === 'customization' ? 'Request' : 'Booking') . ' Update: ' . ucfirst($this->status),
            'message'      => ($statusLabels[$this->status] ?? 'Status changed.') . ' - ' . $this->serviceName,
            'url'          => $this->url,
            'booking_type' => $this->bookingType,
            'booking_id'   => $this->bookingId,
        ];
    }
}
