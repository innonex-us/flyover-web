<?php

namespace App\Notifications;

use App\Models\CustomizationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewCustomizationRequestNotification extends Notification
{
    use Queueable;

    public function __construct(public CustomizationRequest $customization) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject("New Customization Request: " . config('app.name'))
            ->greeting("Hello Admin,")
            ->line("A new custom trip request has been received from **{$this->customization->name}**.")
            ->line("Message: {$this->customization->message}")
            ->action('View Request Details', route('admin.customizations.show', $this->customization))
            ->line('Please review and respond to the customer.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'customization',
            'icon'    => 'document',
            'color'   => 'yellow',
            'title'   => 'New Customization Request',
            'message' => "{$this->customization->name} submitted a custom trip request",
            'url'     => route('admin.customizations.show', $this->customization),
            'id'      => $this->customization->id,
        ];
    }
}
